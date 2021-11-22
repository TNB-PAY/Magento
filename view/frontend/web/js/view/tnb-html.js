define(
    [
        'jquery',
        'ko',
        'uiComponent'
    ],
    function ($, ko, Component) {
        'use strict';
        var tnbAddress = window.checkoutConfig.payment.tnb.address;
        var tnbRates = window.checkoutConfig.payment.tnb.rates;
        var copyIcon = window.checkoutConfig.payment.tnb.copyIcon;
        return Component.extend({
            defaults: {
                template: 'TNB_Pay/payment/tnb_html'
            },
            tnbAddress: tnbAddress,
            tnbRates: tnbRates,
            copyIcon: copyIcon,
            copyClipboard: function () {
                const body = document.querySelector('body');
                const paragraph = document.querySelector('#address_copied');
                const area = document.createElement('textarea');
                body.appendChild(area);
                area.value = paragraph.innerText;
                area.select();
                document.execCommand('copy');
                body.removeChild(area);
            },
            copyClipboard1: function () {
                const body = document.querySelector('body');
                const paragraph = document.querySelector('#address_memo');
                const area = document.createElement('textarea');
                body.appendChild(area);
                area.value = paragraph.innerText;
                area.select();
                document.execCommand('copy');
                body.removeChild(area);
            }
        });
    }
);
