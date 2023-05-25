<?php

namespace Mageplaza\GiftCard\Controller\Customer;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Mageplaza\GiftCard\Model\GiftCardFactory;
use Mageplaza\GiftCard\Model\CustomerFactory;
use Mageplaza\GiftCard\Model\GiftHistoryFactory;
use Mageplaza\GiftCard\Helper\Data;
use Mageplaza\GiftCard\Model\PostGift;

class Save extends Action
{
    protected $_giftcardFactory;
    protected $_giftcardHistoryFactory;
    protected $_customerFactory;
    protected $_helperData;
    protected $_postGift;

    public function __construct(Context            $context,
                                GiftCardFactory    $giftCardFactory,
                                CustomerFactory    $customerFactory,
                                PostGift           $postGift,
                                GiftHistoryFactory $giftHistoryFactory,
                                Data               $helpreData)
    {
        $this->_giftcardFactory = $giftCardFactory;
        $this->_giftcardHistoryFactory = $giftHistoryFactory;
        $this->_customerFactory = $customerFactory;
        $this->_helperData = $helpreData;
        $this->_postGift = $postGift;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->_helperData->getUrlConfig('enable_redeem') == 0) {
            $this->messageManager->addErrorMessage('An error occurred during processing!');
        } else {
            $data = [
                'code' => $this->getRequest()->getParam('redeem')
            ];
            $post = $this->_giftcardFactory->create()->load($data['code'], 'code');
            $codes = [];
            foreach ($this->_giftcardFactory->create()->getCollection() as $blockHistory) {
                $code = $blockHistory->getCode();
                $codes[] = $code;
            }
            if (!in_array($data['code'], $codes)) {
                $this->messageManager->addErrorMessage('Invalid Gift Card.');
                return $this->_redirect('*/index/index/');
            }
            $giftCardIds = [];
            foreach ($this->_postGift->getHistoryCollection() as $block) {
                $id = $block->getGiftcardId();
                $giftCardIds[] = $id;
            }
            if (in_array($post->getId(), $giftCardIds)) {
                $this->messageManager->addErrorMessage('Gift Card Code already exists.');
                return $this->_redirect('*/index/index/');
            }
            $dataHistory = [
                'giftcard_id' => $post->getId(),
                'customer_id' => $this->_postGift->getLogger(),
                'action' => 'Redeem',
            ];
            $giftHistory = $this->_giftcardHistoryFactory->create();
            $giftHistory->addData($dataHistory)->save();
            // nếu balance trong giftcard_code chưa được redeem (có nghĩa là vẫn còn tiền) thì trừ số tiền quy đổi
            $giftCard = $this->_giftcardFactory->create();
            $dataGiftCard = [
                'amount_used' => $post->getBalance(),
            ];
            $giftCard->load($post->getId());
            $giftCard->addData($dataGiftCard)->save();
            $this->messageManager->addSuccessMessage('add redeem card code successfully');
            $this->_redirect('*/index/index/');
        }
        $this->_redirect('*/index/index/');
    }
}
