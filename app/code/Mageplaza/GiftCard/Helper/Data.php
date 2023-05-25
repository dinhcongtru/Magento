<?php

namespace Mageplaza\GiftCard\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_HELLOWORLD = 'giftcard/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_HELLOWORLD . 'general_code/' . $code, $storeId);
    }

    public function getUrlConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_HELLOWORLD . 'general/' . $code, $storeId);
    }

    public function randomCodeLength($count)
    {
        $characters = 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789';
        $code = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $count; $i++) {
            $code .= $characters[random_int(0, $max)];
        }
        return $code;
    }


}
