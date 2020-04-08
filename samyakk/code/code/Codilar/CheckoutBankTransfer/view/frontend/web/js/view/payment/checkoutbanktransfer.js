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
                type: 'bank_transfer',
                component: 'Codilar_CheckoutBankTransfer/js/view/payment/method-renderer/checkoutbanktransfer-method'
            }
        );
        return Component.extend({});
    }
);