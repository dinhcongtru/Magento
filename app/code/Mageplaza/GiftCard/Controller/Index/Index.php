<?php

namespace Mageplaza\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mageplaza\GiftCard\Model\CustomerFactory;
use Mageplaza\GiftCard\Helper\Data;
use Magento\Config\Model\ResourceModel\Config;
use Mageplaza\GiftCard\Model\PostGift;

class Index extends Action
{
    protected $_customerFactory;
    protected $_helperData;
    protected $_config;
    protected $_postGift;

    public function __construct(Context         $context,
                                CustomerFactory $customerFactory,
                                PostGift        $postGift,
                                Data            $helperData, Config $config)
    {
        $this->_customerFactory = $customerFactory;
        $this->_helperData = $helperData;
        $this->_postGift = $postGift;
        $this->_config = $config;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($this->_postGift->getLogger()) {
            if ($this->_helperData->getUrlConfig('enable_giftcard') == 1) {
                $this->updateGiftCardBalance();
                $this->_view->loadLayout();
                $this->_view->renderLayout();
            } else {
                $this->_redirect('customer/account');
            }
        } else {
            return $resultRedirect->setPath('');
        }
    }

    public function updateGiftCardBalance()
    {
        $Balance = 0;
        $customerId = $this->_postGift->getLogger();
        $post = $this->_customerFactory->create();
        $post->load($customerId);
        foreach ($this->_postGift->getHistoryCollection() as $item) {
            $Balance += $item->getBalance();
        }
        $data = [
            'giftcard_balance' => $Balance
        ];
        try {
            $post->addData($data)->save();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('We are currently unable to process your Balance data.');
        }
    }
}
