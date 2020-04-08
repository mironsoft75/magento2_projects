define([
    'jquery',
], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.catalogAddToCart', widget, {
            /**
             * @param {String} form
             */

            ajaxSubmit: function (form) {
                $('#product-custom-message').hide();
                var self = this;

                $(self.options.minicartSelector).trigger('contentLoading');
                self.disableAddToCartButton(form);

                $.ajax({
                    url: form.attr('action'),
                    data: form.serialize(),
                    type: 'post',
                    dataType: 'json',

                    /** @inheritdoc */
                    beforeSend: function () {
                        if (self.isLoaderEnabled()) {
                            $('body').trigger(self.options.processStart);
                        }
                    },

                    /** @inheritdoc */
                    success: function (res) {
                        var eventData, parameters;
                        $('#product-custom-message').html("Product is added to the shopping cart").show();
                        $(document).trigger('ajax:addToCart', {
                            'sku': form.data().productSku,
                            'form': form,
                            'response': res
                        });
                        if (self.isLoaderEnabled()) {
                            $('body').trigger(self.options.processStop);
                        }
                        if (res.backUrl) {
                            eventData = {
                                'form': form,
                                'redirectParameters': []
                            };
                            // trigger global event, so other modules will be able add parameters to redirect url
                            $('body').trigger('catalogCategoryAddToCartRedirect', eventData);
                          console.log(eventData);
                            if (eventData.redirectParameters.length > 0) {
                                parameters = res.backUrl.split('#');
                                parameters.push(eventData.redirectParameters.join('&'));
                                res.backUrl = parameters.join('#');
                            }
                            window.location = res.backUrl;

                            return;
                        }


                        if (res.messages) {
                            $(self.options.messagesSelector).html(res.messages);
                            console.log(res.messages);
                            console.log( $(self.options.messagesSelector).html(res.messages));

                        }

                        if (res.minicart) {
                            $(self.options.minicartSelector).replaceWith(res.minicart);
                            $(self.options.minicartSelector).trigger('contentUpdated');
                        }

                        if (res.product && res.product.statusText) {
                            $(self.options.productStatusSelector)
                                .removeClass('available')
                                .addClass('unavailable')
                                .find('span')
                                .html(res.product.statusText);
                        }
                        self.enableAddToCartButton(form);
                    },

                    failure: function () {

                        $('#product-custom-message').html("Product is not added.Out of stock").show();

                    }
                });
            }
        });
        return $.mage.catalogAddToCart;
    }
});