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
                type: 'codilar_checkout_paypal',
                component: 'Codilar_CheckoutPaypal/js/view/payment/method-renderer/checkoutpaypal-method'
            }
        );
        return Component.extend({});
    }
);