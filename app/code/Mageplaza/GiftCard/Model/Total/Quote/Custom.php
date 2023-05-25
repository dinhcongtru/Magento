<?php

namespace Mageplaza\GiftCard\Model\Total\Quote;

use Mageplaza\GiftCard\Model\GiftCardFactory;

/**
 * Class Custom
 * @package Mageplaza\GiftCard\Model\Total\Quote
 */
class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;
    protected $_giftCardFactory;

    /**
     * Custom constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        GiftCardFactory                                   $giftCardFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    )
    {
        $this->_giftCardFactory = $giftCardFactory;
        $this->_priceCurrency = $priceCurrency;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|bool
     */
    public function collect(
        \Magento\Quote\Model\Quote                          $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total            $total
    )
    {
        $code = $quote->getGiftcardCode();
        $loadData = $this->_giftCardFactory->create()->load($code, 'code');
        $balanceAvailable = 0;
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        if ($loadData->getId()) {
            $balanceAvailable = $loadData->getBalance() - $loadData->getAmountUsed();
            $orderTotal = $quote->getBaseSubtotalWithDiscount() + $quote->getShippingAddress()->getBaseShippingAmount();
            if ($balanceAvailable > $orderTotal) {
                $balanceAvailable = $orderTotal;
            }
        }
        parent::collect($quote, $shippingAssignment, $total);
        $baseDiscount = $balanceAvailable;
        $discount = $this->_priceCurrency->convert($baseDiscount);
        $total->addTotalAmount('customdiscount', -$discount);
        $total->addBaseTotalAmount('customdiscount', -$baseDiscount);
        $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
        $quote->setCustomDiscount(-$discount);
        $total->setCustomDiscount($discount);
        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        return [
            'code' => 'custom_discount',
            'title' => '',
            'value' => $total->getCustomDiscount()
        ];
    }
}
