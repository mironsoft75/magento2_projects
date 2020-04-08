define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Customer/js/customer-data',
    'ko',
    'mage/url'
], function ($, wrapper, customerData,ko, murl) {
    'use strict';
    let wishlistData = customerData.get('wishlist');
    let comaparelistData = customerData.get('compare-products');

    return function (bindFormSubmit) {

        return wrapper.wrap(bindFormSubmit, function (originalAction) {

            function inWishlist(productId) {
                if($.inArray(productId, _wishlistProductIds()) === -1){
                    return false;
                }else{
                    return true;
                }
            }
            function inComparelist(productId) {
                if($.inArray(productId, _comparelistProductIds()) === -1){
                    return false;
                }else{
                    return true;
                }
            }

            function updateWishlistIcons(){
                let wishlistLink = $('[data-action="add-to-wishlist"]');
                $.each(wishlistLink, function(){
                    let productId = $(this).data('post').data.product;
                    if(inWishlist(productId)){
                        $(this).addClass('inwishlist');
                    }else{
                        $(this).removeClass('inwishlist')
                    }
                });
            }

            function updateComparelistIcons(){
                let comparelistLink =  $("[aria-label= 'Add to Compare']");
                $.each(comparelistLink, function(){
                    let productId = $(this).data('post').data.product;
                    if(inComparelist(productId)){
                        $(this).addClass('compare');
                    }else{
                        $(this).removeClass('compare')
                    }
                });
            }

            $('body').click(function (event) {
                //for wishlist
                if($(event.target).attr('data-action') === 'add-to-wishlist'){

                    let params, action, data;

                    event.stopPropagation();
                    event.preventDefault();

                    params = $(event.target).data('post');
                    data = params.data.product;
                    action = params.action;

                    if(!inWishlist(data)){
                        action = murl.build('codilarwishlist/index/add/');
                    }else{
                        action = murl.build('codilarwishlist/index/remove/');
                    }

                    $.ajax({
                        url: action,
                        data: {product: data},
                        dataType: 'json',
                        showLoader: true,
                        success: function (data) {
                            customerData.reload();
                            if(data.redirect){
                                window.location.href = window.checkout.customerLoginUrl;
                            }
                        }
                    });
                }

                //for compare
                if($(event.target).attr('aria-label') === 'Add to Compare'){

                    let params, action, data;

                    event.stopPropagation();
                    event.preventDefault();

                    params = $(event.target).data('post');
                    data = params.data.product;
                    action = params.action;

                    if(!inComparelist(data)){
                        action = murl.build('catalog/product_compare/add/');
                    }else{
                        action = murl.build('catalog/product_compare/remove/');
                    }

                    $.ajax({
                        url: action,
                        data: {
                            product: data,
                            isAjax: true,
                        },
                        dataType: 'json',
                        showLoader: true,
                        success: function (data) {
                            customerData.reload();
                        }
                    });
                }
            });

            //check for changes in wishlist
            wishlistData.subscribe(function (data) {
                let _wishlistProductIds = ko.observableArray([]);
                window._wishlistProductIds = _wishlistProductIds;
                data.items.forEach(function (item) {
                    _wishlistProductIds.push(item['productId']);
                });
                updateWishlistIcons();
            });

            //check for comapare products
            comaparelistData.subscribe(function (data) {
                let _comparelistProductIds = ko.observableArray([]);
                window._comparelistProductIds = _comparelistProductIds;
                data.items.forEach(function (item) {
                    _comparelistProductIds.push(item['id']);
                });
                updateComparelistIcons();
            });

            //update when filter is applied
            if($('#wp_ln_shopby_items').length){
                updateWishlistIcons();
                updateComparelistIcons();
            }

            //update when filter is cleared
            if(window.filterClear){
                updateWishlistIcons();
                updateComparelistIcons();
            }

            //update when load more is clicked
            if(window.LoadMore){
                $.ias().on('rendered', function(items) {
                    updateWishlistIcons();
                    updateComparelistIcons();
                });
            }

            return originalAction(); // it is returning the flow to original action
        });

    };
});