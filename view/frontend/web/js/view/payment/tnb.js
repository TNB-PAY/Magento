define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'tnb',
                component: 'TNB_Pay/js/view/payment/method-renderer/tnb-method'
            }
        );
        return Component.extend({});
    }
);