/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Customer/js/model/authentication-popup',
    'Magento_Customer/js/customer-data',
    'mage/url'
], function ($, authenticationPopup, customerData, mageUrl) {
    'use strict';

    return function (config, element) {
        $(element).click(function (event) {
            var cart = customerData.get('cart'),
                customer = customerData.get('customer');

            event.preventDefault();

            if (!customer().firstname && cart().isGuestCheckoutAllowed === false) {
                location.href = mageUrl.build("customer/account/login");
                //authenticationPopup.showModal();

                return false;
            }
            $(element).attr('disabled', true);
            location.href = config.checkoutUrl;
        });

    };
});
