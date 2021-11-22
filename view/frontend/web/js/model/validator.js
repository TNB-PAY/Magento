define(
    [
        'jquery',
        'Magento_Ui/js/modal/modal',
        'mage/translate',
        'Magento_Ui/js/model/messageList',
        'Magento_Checkout/js/model/quote',
        'mage/url',
    ],
    function ($, modal, $t, messageList, quote, url) {
        'use strict';
        return {
            validate: function () {
                var isValid = false; //Put your validation logic here
                let memoSaveUrl =  url.build('tnbpay');
                var selectedPaymentMethod = $('input[name="payment[method]"]:checked').val();
                if (selectedPaymentMethod !== 'tnb') {
                    return  true;
                }
                console.log("popup started");
                if ($('#verified').val() == 0) {
                    var totals = quote.totals();
                    var tnbRates = window.checkoutConfig.payment.tnb.rates;
                    var tnbGrandTotal = (totals ? totals : quote)['base_grand_total'];
                    var tnbTotal = tnbGrandTotal/tnbRates;
                    var tnbMemo = window.checkoutConfig.payment.tnb.memo;
                    var tnbPayModalOptions = {
                        type: 'popup',
                        responsive: true,
                        modalClass: 'tnb-pay-popup',
                        innerScroll: true,
                        clickableOverlay: false,
                        title: "TnbPay",
                        buttons: [
                            {
                                text: $.mage.__('Payment Made, Next'),
                                class: 'action primary tnb-popup-btn',
                                click: function () {
                                    var memoVerifyUrl = url.build('tnbpay/verify');
                                    $.ajax({
                                        url: memoVerifyUrl,
                                        type: 'POST',
                                        data: {memo: tnbMemo, total: tnbTotal},
                                        showLoader: true,
                                        success: function (response) {
                                            if (response.flag == 0) {
                                                $(".message.error").html(response.msg);
                                                $(".messages.tnb-success").hide();
                                                $(".messages.tnb-error").show();
                                            } else if (response.flag == 1 || response.flag == 2 || response.flag == 3)
                                            {
                                                $(".messages.success").html(response.msg);
                                                $(".messages.tnb-error").hide();
                                                $(".messages.tnb-success").show();
                                                jQuery('#verified').val(1);
                                                $(".payment-method._active").find('.action.primary.checkout').trigger('click');
                                            }
                                        }
                                    });
                                }
                            }
                        ]
                    }
                    var tnbTotalWithText = tnbTotal+' TNB';
                    $(".tnb_grand_total").text(tnbTotalWithText);
                    $(".tnb_memo").text(tnbMemo);
                    var popup = modal(tnbPayModalOptions, $('.tnbpay-popup'));
                    $(".tnbpay-popup").modal("openModal");
                } else {
                    isValid = true;
                }
                return isValid;
            }
        }
    }
);
