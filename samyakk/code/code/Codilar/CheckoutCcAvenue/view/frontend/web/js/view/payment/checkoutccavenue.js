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
                type: 'ccavenue_payment_gateway',
                component: 'Codilar_CheckoutCcAvenue/js/view/payment/method-renderer/checkoutccavenue-method'
            }
        );
        return Component.extend({});
    }
);