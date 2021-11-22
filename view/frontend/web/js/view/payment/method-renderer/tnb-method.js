define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'TNB_Pay/payment/tnb'
            },
            getInstructions: function () {
                return window.checkoutConfig.payment.tnb.instructions;
            },
            getPaymentAcceptanceMarkSrc: function () {
                return window.checkoutConfig.payment.tnb.paymentAcceptanceMarkSrc;
            },
            getData: function () {
                return {
                    method: this.item.method,
                    'additional_data': {
                        'tnbAddress': window.checkoutConfig.payment.tnb.address,
                        'tnbRates': window.checkoutConfig.payment.tnb.rates,
                        'memo': window.checkoutConfig.payment.tnb.memo
                    }
                };
            }
        });
    }
);
