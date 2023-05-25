define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils'
    ],
    function ($, Component, totals, quote, priceUtils) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Mageplaza_GiftCard/checkout/summary/customdiscount'
            },
            isDisplayedCustomdiscount: function () {
                let valueDiscount = totals.getSegment('custom_discount').value;
                if (!valueDiscount) {
                    return false;
                }
                return true;
            },
            getCustomDiscount: function () {
                let valueDiscount = totals.getSegment('custom_discount').value;
                if (valueDiscount !== null && valueDiscount !== undefined) {
                    console.log(valueDiscount)
                    if (valueDiscount !== 0) {
                        document.getElementById("customTotale").style.color = 'red';
                        return priceUtils.formatPrice(-valueDiscount);
                    } else {
                        document.getElementById("customTotale").style.display = 'none';
                    }
                }
            }
        });
    }
);
