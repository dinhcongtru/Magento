<?php

namespace Mageplaza\GiftCard\Block\Index;

use Exception;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Mageplaza\GiftCard\Helper\Data;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_giftHistoryFactory;
    protected $_customerFactoty;
    protected $_giftCardFactory;
    protected $_priceHelper;

    protected $customerSession;
    protected $_timezoneInterface;
    protected $_helperData;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mageplaza\GiftCard\Model\GiftHistoryFactory     $giftHistoryFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory        $giftCardFactory,
        CustomerSession                                  $customerSession,
        PriceHelper                                      $priceHelper,
        TimezoneInterface                                $timezoneInterface,
        Data                                             $helperData,
        \Mageplaza\GiftCard\Model\CustomerFactory        $customerFactory, array $data = []
    )
    {
        $this->_giftHistoryFactory = $giftHistoryFactory;
        $this->_giftCardFactory = $giftCardFactory;
        $this->_customerFactoty = $customerFactory;
        $this->customerSession = $customerSession;
        $this->_priceHelper = $priceHelper;
        $this->_timezoneInterface = $timezoneInterface;
        $this->_helperData = $helperData;
        parent::__construct($context, $data);
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

    public function formattedPrice($price)
    {
        return $this->_priceHelper->currency($price, true, false);
    }

    /**
     * @throws Exception
     */
    public function formattedDateTime($date)
    {
        $dateTime = new \DateTime($date);
        return $this->_timezoneInterface->formatDateTime(
            $dateTime,
            \IntlDateFormatter::SHORT,
            \IntlDateFormatter::NONE,
            null,
            $this->_timezoneInterface->getConfigTimezone()
        );
    }

    public function allowRedeem()
    {
        $allowRedeem = $this->_helperData->getUrlConfig('enable_redeem');
        return $allowRedeem;
    }
}
