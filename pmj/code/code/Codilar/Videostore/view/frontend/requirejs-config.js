var config = {
    map: {
        '*': {
            catalogAddToTryOn: 'Codilar_Videostore/js/catalog-add-to-tryon',
            videocartForm: 'Codilar_Videostore/js/videocart-form'
        }
    },
    config: {
        mixins: {
            'Magento_Catalog/js/catalog-add-to-cart': {
                'Codilar_Videostore/js/catalog-add-to-cart-mixin': true
            }
        }
    }
};
