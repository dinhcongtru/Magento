<?php

namespace Mageplaza\GiftCard\Observer;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageplaza\GiftCard\Model\GiftCardFactory;
use Mageplaza\GiftCard\Model\GiftHistoryFactory;
use Mageplaza\GiftCard\Helper\Data;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Response\Http;
use Mageplaza\GiftCard\Model\PostGift;

class BuyGifCardCustomer implements ObserverInterface
{
    protected $_helperData;
    protected $_giftCardFactory;
    protected $_giftHistoryFactory;
    protected $_postGift;
    protected $_productRepository;
    protected $_messageManager;

    protected $response;

    public function __construct(GiftCardFactory  $giftCardFactory, GiftHistoryFactory $giftHistoryFactory,
                                Data             $helperData, PostGift $postGift, ProductRepositoryInterface $productRepository,
                                ManagerInterface $messageManager, Http $response)
    {
        $this->_giftCardFactory = $giftCardFactory;
        $this->_giftHistoryFactory = $giftHistoryFactory;
        $this->_helperData = $helperData;
        $this->_postGift = $postGift;
        $this->_productRepository = $productRepository;
        $this->_messageManager = $messageManager;
        $this->response = $response;
    }

    public function execute(Observer $observer)
    {
        if ($this->_helperData->getGeneralConfig('display_text') && $this->_helperData->getUrlConfig('enable_giftcard') == 1) {
            $quote = $observer->getQuote();
            $orderId = $quote->getReservedOrderId();
            //  Kiểm tra xem khách đặt số lượng bao nhiêu và addData đúng với số lượng đó
            $cartItems = $quote->getAllItems();
            foreach ($cartItems as $item) {
                $qty = 0;
                $proId = $item->getProduct()->getId();
                $product = $this->_productRepository->getById($proId);
                $amount = $product->getCustomAttribute('gift_card_amount');
                if ($product->getTypeId() === 'virtual' && $amount && $amount->getValue() > 0) {
                    $balance = $amount->getValue();
                    $qty += $item->getQty();
                    for ($i = 0; $i < $qty; $i++) {
                        $codeLength = $this->_helperData->getGeneralConfig('display_text');
                        $giftCardId = $this->addDataTabeGiftcardCode($this->_helperData->randomCodeLength($codeLength), $balance);
                        $this->addDataTabeGiftcardHistory($giftCardId, $this->_postGift->getLogger(), $orderId);
                    }
                }
            }
        } else {
            $this->_messageManager->addErrorMessage('Error while buy one product virtual for gift card');
            $this->response->setRedirect('/');
        }
    }

    public function addDataTabeGiftcardCode($code, $balance)
    {
        $data = [
            'code' => $code,
            'balance' => $balance,
            'amount_used' => null,
            'create_from' => 'Create customer'
        ];
        $giftCard = $this->_giftCardFactory->create();
        $giftCard->addData($data);
        try {
            $giftCard->save();
        } catch (Exception $e) {
            $this->_messageManager->addExceptionMessage('Error saving gift card' . $e->getMessage());
        }
        return $giftCard->getId();
    }

    public function addDataTabeGiftcardHistory($newGiftcardId, $customId, $oder)
    {
        $data = [
            'giftcard_id' => $newGiftcardId,
            'customer_id' => $customId,
            'amount' => null,
            'action' => 'Create by customer with Order ' . $oder,
        ];
        $history = $this->_giftHistoryFactory->create();
        $history->addData($data);
        try {
            $history->save();
        } catch (\Exception $e) {
            $this->_messageManager->addExceptionMessage('Error saving gift card history' . $e->getMessage());
        }
    }
}
