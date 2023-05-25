<?php

namespace Mageplaza\GiftCard\Controller\Index;
class Config extends \Magento\Framework\App\Action\Action
{
    protected $helperData;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mageplaza\GiftCard\Helper\Data       $helperData
    )
    {
        $this->helperData = $helperData;
        return parent::__construct($context);
    }

    public function execute()
    {
        echo $this->helperData->getGeneralConfig('display_text');
        echo $this->helperData->getUrlConfig('enable');
        exit();
    }
}
