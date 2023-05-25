<?php

namespace Mageplaza\GiftCard\Model;

use Magento\Framework\App\ObjectManager;
use Mageplaza\GiftCard\Model\GiftHistoryFactory;

class  PostGift
{
    protected $_giftHistoryFactory;

    public function __construct(GiftHistoryFactory $giftHistoryFactory)
    {
        $this->_giftHistoryFactory = $giftHistoryFactory;
    }

    public function getLogger()
    {
        $objectManager = ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');

        if ($customerSession->isLoggedIn()) {
            return $customerSession->getCustomerId();
        } else {
            return false;
        }
    }

    public function getHistoryCollection()
    {
        $id = $this->getLogger();
        if ($id) {
            $historyConllection = $this->_giftHistoryFactory->create()->getCollection()
                ->addFieldToFilter('customer_id', $id);
            $historyConllection->getSelect()
                ->joinLeft(
                    ['giftcard_code' => $historyConllection->getTable('giftcard_code')],
                    'main_table.giftcard_id = giftcard_code.giftcard_id',
                    ['code', 'balance']
                );
            $historyConllection->getSelect()
                ->joinLeft(
                    ['customer_entity' => $historyConllection->getTable('customer_entity')],
                    'main_table.customer_id = customer_entity.entity_id',
                    ['giftcard_balance']
                );
            return $historyConllection;
        }
        return null;
    }
}
