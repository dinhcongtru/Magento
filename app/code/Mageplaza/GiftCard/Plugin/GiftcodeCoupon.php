<?php

namespace Mageplaza\GiftCard\Plugin;

use Magento\Checkout\Block\Cart\Coupon;
use Magento\Quote\Model\QuoteFactory;

class GiftcodeCoupon
{
    private QuoteFactory $quoteFactory;

    public function __construct(
        QuoteFactory $quoteFactory
    )
    {
        $this->quoteFactory = $quoteFactory;
    }

    public function afterGetCouponCode(Coupon $subject, $result)
    {
        $quoteId = $subject->getQuote()->getId();
        if (!$result && $quoteId) {
            $quote = $this->quoteFactory->create()->load($quoteId);
            $result = $quote->getGiftcardCode();
        }
        return $result;
    }
}

