<?php

namespace Mageplaza\GiftCard\Model\ResourceModel\GiftHistory;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'history_id';
    protected $_eventPrefix = 'giftcard_history_collection';
    protected $_eventObject = 'giftcard_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\GiftHistory', 'Mageplaza\GiftCard\Model\ResourceModel\GiftHistory');
    }
}
