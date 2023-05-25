<?php

namespace Mageplaza\GiftCard\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageplaza\GiftCard\Model\GiftCardFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ActionFlag;
use Mageplaza\GiftCard\Model\PostGift;
use Mageplaza\GiftCard\Helper\Data;

class ApplyDiscountGiftCode implements ObserverInterface
{
    protected $_postGift;
    protected $_giftcardFactory;
    protected $_messageManager;
    protected $_redirect;
    protected $_actionFlag;
    protected $_helperData;
    protected $_checkoutSession;

    public function __construct(PostGift          $postGift, GiftCardFactory $giftCardFactory,
                                ManagerInterface  $messageManager, Session $session,
                                RedirectInterface $redirect, Data $helperData, ActionFlag $flag)
    {
        $this->_messageManager = $messageManager;
        $this->_giftcardFactory = $giftCardFactory;
        $this->_postGift = $postGift;
        $this->_checkoutSession = $session;
        $this->_helperData = $helperData;
        $this->_redirect = $redirect;
        $this->_actionFlag = $flag;
    }

    public function execute(Observer $observer)
    {
        $allowGiftCheckout = $this->_helperData->getUrlConfig('allow_gift_checkout');
        $customerId = $this->_postGift->getLogger();
        $controller = $observer->getControllerAction();
        if ($allowGiftCheckout == 1) {
            if ($customerId) {
                $couponCode = trim($controller->getRequest()->getParam('coupon_code'));
                $giftcard = $this->_giftcardFactory->create();
                $giftcard_id = $giftcard->load($couponCode, 'code')->getId();
                $amountUsed = $giftcard->load($couponCode, 'code')->getAmountUsed();
                if ($giftcard_id) {
                    $this->handleCheckGiftCode($couponCode, $giftcard_id, $amountUsed);
                }
            } else {
                $this->_messageManager->addErrorMessage('you have Login to apply a gift card');
            }
        }
        if ($controller->getRequest()->getParam('remove')) {
            $this->_checkoutSession->getQuote()->setGiftcardCode('')->save();
            $this->_messageManager->addSuccessMessage('Gift Card was cancelled successfully');
        }
        $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
        $this->_redirect->redirect($controller->getResponse(), 'checkout/cart');

    }

    public function handleCheckGiftCode($couponCode, $giftcard_id, $amountUsed)
    {
        if ($giftcard_id && $amountUsed == null) {
            $this->_messageManager->addSuccessMessage('Gift code applied successfully!');
            $quote = $this->_checkoutSession->getQuote();
            $quote->setGiftcardCode($couponCode);
            $quote->save();
        } elseif ($giftcard_id && $amountUsed !== null) {
            $this->_messageManager->addErrorMessage('The balance of this gift card is no longer available.');
        }
    }
}
