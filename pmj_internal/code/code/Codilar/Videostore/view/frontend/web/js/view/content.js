define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'ko',
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/confirm'
], function(Component, customerData, ko, $, murl, confirmation) {

    let sectionName = "videocart";
    let cartProducts = customerData.get(sectionName);

    function  showDeleteAlert(formElement) {
        confirmation({
            title: 'Delete from ShortListed Products',
            content: 'Are you sure you want to delete product from ShortListed Products?',
            actions: {
                confirm: function(){
                    continueDelete(formElement);
                },
            }
        });
    };
    function  continueDelete(formElement) {
        let  id= $(formElement).serialize();
        $.ajax({
            type: "post",
            url: murl.build('videostore/cart/delete'),
            data: id,
            success: function (data) {

            },
        });
    }

    return Component.extend({
        _products: ko.observableArray([]),
        _proceedUrl: ko.observable(),
        initialize: function () {
            let self = this;
            self._super();
            cartProducts.subscribe(function (data) {
                window._products = self._products;
                window._proceedUrl = self._proceedUrl;
                self._products([]);
                data.products.forEach(function (product) {
                    
                    function isEmpty(obj) {
                        for(var key in obj) {
                            if(obj.hasOwnProperty(key))
                                return false;
                        }
                        return true;
                    }

                    if(!isEmpty(product.image_custom_urls))
                    {
                        product.image_custom_urls = product.image_custom_urls.split(',')[0];
                        console.log("under if");
                    }
                    else if(!isEmpty(product.video_thumbnail_url ))
                    {
                        product.image_custom_urls = product.video_thumbnail_url.split(',')[0]; 
                        console.log("under else");
                    }
                    else
                    {
                        product.image_custom_urls = murl.build('pub/media/catalog/product/placeholder/default/logo_9.png');

                    }
                    self._products.push(product);
                })
            });
        },
        getProducts: function () {
            return this._products;
        },
        reloadSection: function () {
            customerData.reload([sectionName], false);
        },
        // getImage: function(imgStr) {
        //     let urls = imgStr.split(',');
        //     return urls[0];
        // },
        getCount: function () {
            return cartProducts().count ;
        },
        openDialog: function () {
            $('#videocart-dropdown').toggle();
        },
        dialogClose: function () {
            $('#videocart-dropdown').hide();
        },
        deleteProduct : function(formElement) {
            showDeleteAlert(formElement);
        },
        getProceedUrl: function () {
           return murl.build('videostore/cart/');
        },
        getViewUrl: function () {
            return cartProducts().proceedUrl;
        },
        getUrl: function () {
           return murl.build('videostore/internal/index');
        },
    });
});