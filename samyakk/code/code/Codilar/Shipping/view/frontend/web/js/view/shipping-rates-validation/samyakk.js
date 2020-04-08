define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'Codilar_Shipping/js/model/shipping-rates-validator/samyakk',
        'Codilar_Shipping/js/model/shipping-rates-validation-rules/samyakk'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        samyakkShippingRatesValidator,
        samyakkShippingRatesValidationRules
    ) {
        "use strict";
        defaultShippingRatesValidator.registerValidator('samyakk', samyakkShippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('samyakk', samyakkShippingRatesValidationRules);
        return Component;
    }
);
