<?php

namespace Mageplaza\GiftCard\Model;
class GiftHistory extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'giftcard_history';
    protected $_cacheTag = 'giftcard_history';
    protected $_eventPrefix = 'giftcard_history';

    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\ResourceModel\GiftHistory');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }
}
