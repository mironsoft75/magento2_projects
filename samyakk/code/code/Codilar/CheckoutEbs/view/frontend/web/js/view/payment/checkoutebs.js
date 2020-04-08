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
                type: 'ebs_payment_gateway',
                component: 'Codilar_CheckoutEbs/js/view/payment/method-renderer/checkoutebs-method'
            }
        );
        return Component.extend({});
    }
);