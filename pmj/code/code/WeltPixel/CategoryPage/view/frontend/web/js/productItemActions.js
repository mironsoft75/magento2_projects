define(['jquery'], function (jQuery) {
    "use strict";

    var CategoryPage = {

        displayAddToCart: function (actions) {
            var displayAddToCart = actions['displayAddToCart'],
                addtocart_position = '.addtocart_position_',
                productItem = '.products-grid .product-item',
                productImage = '.product_image',
                priceBox = '.price-box.price-final_price';

            if (displayAddToCart == 1 || displayAddToCart == 2) {
                jQuery(productItem).each(function () {
                    var el = jQuery(this),
                        btn = el.find(addtocart_position + displayAddToCart);

                    el.find(productImage).append(btn);
                });
            } else if (displayAddToCart == 3 || displayAddToCart == 4) {
                jQuery(productItem).each(function () {
                    var el = jQuery(this),
                        btn = el.find(addtocart_position + displayAddToCart);

                    el.find(priceBox).after(btn);
                });
            }
        },

        displayWishlist: function (actions) {
            var displayWishlist = actions['displayWishlist'],
                whishlist_position = '.whishlist_position_',
                productItem = '.products-grid .product-item',
                productImageAction = '.product_image .actions .actions-secondary';

            if (displayWishlist == 1 || displayWishlist == 2 || displayWishlist == 3 || displayWishlist == 4) {
                jQuery(productItem).each(function () {
                    var el = jQuery(this),
                        btn = el.find(whishlist_position + displayWishlist);

                    el.find(productImageAction).append(btn);
                });
            }
        },

        displayCompare: function (actions) {
            var displayCompare = actions['displayCompare'],
                compare_position = '.compare_position_',
                productItem = '.products-grid .product-item',
                productImageAction = '.product_image .actions .actions-secondary';

            if (displayCompare == 1 || displayCompare == 2 || displayCompare == 3 || displayCompare == 4) {
                jQuery(productItem).each(function () {
                    var el = jQuery(this),
                        btn = el.find(compare_position + displayCompare);

                    el.find(productImageAction).append(btn);
                });
            }
        },

        toCartWidth: function (actions) {
            var displayAddToCart = actions['displayAddToCart'],
                toCart = jQuery('.products-grid .product-item .product_image .addtocart_position_' + displayAddToCart + ' .tocart'),
                toCartPos = jQuery('.products-grid .product-item .product_image .addtocart_position_' + displayAddToCart),
                toCartWidth = toCart.outerWidth();
            toCartPos.css('width', toCartWidth);
        },

        buttonQuickView: function () {
            var productItem = '.products-grid .product-item',
                image = '.product-item-photo img',
                buttonQuickView_1 = '.weltpixel_quickview_button_v1',
                buttonQuickView_2 = '.weltpixel_quickview_button_v2',
                eTrue = false,
                addToCart = productItem + ' .product_image [class*="addtocart_position_"]';

            if (jQuery(addToCart)[0]) {
                eTrue = true;
            } else {
                eTrue = false;
            }

            jQuery(productItem).each(function () {
                var el = jQuery(this),
                    img = el.find(image).parent().outerHeight(),
                    quickViewH_1 = el.find(buttonQuickView_1).outerHeight(),
                    quickViewH_2 = el.find(buttonQuickView_2).outerHeight() + 10;

                if (eTrue) {
                    el.find(buttonQuickView_1).addClass('weltpixel_quickview_button_v2').removeClass('weltpixel_quickview_button_v1');
                    var quickViewH_2 = el.find(buttonQuickView_2).outerHeight() + 10;

                } else {

                    el.find(buttonQuickView_1).css('top', img - quickViewH_2 - 45);
                    el.find(buttonQuickView_2).css('top', img - quickViewH_2 - 10);
                }

            });
        },

        hoverShow: function (actions) {
            var displaySwatches = actions['displaySwatches'],
                prodItemDetails = jQuery('.product-item .product-item-details div[class*="swatch-opt-"]');

            if (displaySwatches == 2) {
                prodItemDetails.addClass('hoverShow');
            }
        },

        itemHover: function () {
            var productItem = '.products-grid .product-item',
                ItemHeight = jQuery(productItem).outerHeight();
            jQuery(productItem).mouseenter(function () {
                jQuery(this).css('height',ItemHeight);
            }).mouseleave(function () {
                jQuery(this).removeAttr('style');
            });
        },

        itemHeight: function () {
            var productItem = '.products-grid .product-item',
                productItemHeight = {};
            jQuery(productItem).each(function() {
                /** if height was already set
                 * keep this height for all the new items
                 * loaded by ajaxInfiniteScroll
                 */
                if (jQuery(this).filter('[style*=height]').length) {
                    var height = parseInt(jQuery(this).css('height'));
                    productItemHeight[height] = height;
                    return false;
                } else {
                    var height = jQuery(this).outerHeight();
                }

                productItemHeight[height] = height;
            });
            var finalHeight = productItemHeight[Object.keys(productItemHeight)[Object.keys(productItemHeight).length - 1]];
            jQuery(productItem).height(finalHeight);
        },

        actions: function () {
            var actions = window.actions;
            this.displayAddToCart(actions);
            this.displayWishlist(actions);
            this.displayCompare(actions);
            this.toCartWidth(actions);
            this.hoverShow(actions);
            this.buttonQuickView();
            this.itemHeight();

            var that = this;
            jQuery(window).resize(function() {
                that.toCartWidth(actions);
                that.hoverShow(actions);
                that.buttonQuickView();
                that.itemHover();
            });
        }

    };

    window.CategoryPage = CategoryPage;
    return CategoryPage;
});

