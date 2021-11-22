define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/additional-validators',
        'TNB_Pay/js/model/validator'
    ],
    function (Component, additionalValidators, yourValidator) {
        'use strict';
        additionalValidators.registerValidator(yourValidator);
        return Component.extend({});
    }
);
