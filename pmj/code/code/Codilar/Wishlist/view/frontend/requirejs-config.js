var config = {
    map: {
        '*': {
            addToWishlist:  'Magento_Wishlist/js/add-to-wishlist'
        }
    },
    config: {
        mixins: {
            'Magento_Wishlist/js/add-to-wishlist': {
                'Codilar_Wishlist/js/add-to-wishlist-mixin': true
            }
        }
    }
};
