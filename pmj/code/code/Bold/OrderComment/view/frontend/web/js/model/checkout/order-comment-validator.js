define(
    [
        'jquery',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'mage/url',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Ui/js/model/messageList',
        'mage/translate'
    ],
    function ($, customer, quote, urlBuilder, urlFormatter, errorProcessor, messageContainer, __) {
        'use strict';

        return {

            /**
             * Make an ajax PUT request to store the order comment in the quote.
             *
             * @returns {Boolean}
             */
            validate: function () {
                let orderTotal = this.getGrandTotal();
                let minOrderTotal = window.checkoutConfig.order_total_amount;
                if(orderTotal > minOrderTotal){
                    var isCustomer = customer.isLoggedIn();
                    var form = $('.payment-method input[name="payment[method]"]:checked').parents('.payment-method').find('form.order-comment-form');
                    form.find(".pan-number-field-error").hide();
                    // Compatibility for Rubic_CleanCheckout
                    if (!form.length) {
                        form = $('form.order-comment-form');
                    }

                    var comment = form.find('.input-text.order-comment').val();
                    if(comment.length < 1){
                        messageContainer.addErrorMessage({ message: __("PAN Number is required field.") });
                        form.find(".pan-number-field-error").show();
                        return false;
                    }
                    else if (comment.length < this.getMaxLength()) {
                        messageContainer.addErrorMessage({ message: __("PAN Number should be 10 characters") });
                        form.find(".pan-number-field-error").text("PAN Number should be 10 characters");
                        form.find(".pan-number-field-error").show();
                        return false;
                    }
                    else if (this.hasMaxLength() && comment.length > this.getMaxLength()) {
                        messageContainer.addErrorMessage({ message: __("PAN Number should be 10 characters") });
                        form.find(".pan-number-field-error").text("PAN Number should be 10 characters");
                        form.find(".pan-number-field-error").show();
                        return false;
                    }

                    var quoteId = quote.getQuoteId();

                    var url;
                    if (isCustomer) {
                        url = urlBuilder.createUrl('/carts/mine/set-order-comment', {})
                    } else {
                        url = urlBuilder.createUrl('/guest-carts/:cartId/set-order-comment', {cartId: quoteId});
                    }

                    var payload = {
                        cartId: quoteId,
                        orderComment: {
                            comment: comment
                        }
                    };

                    if (!payload.orderComment.comment) {
                        return true;
                    }

                    var result = true;

                    $.ajax({
                        url: urlFormatter.build(url),
                        data: JSON.stringify(payload),
                        global: false,
                        contentType: 'application/json',
                        type: 'PUT',
                        async: false
                    }).done(
                        function (response) {
                            result = true;
                        }
                    ).fail(
                        function (response) {
                            result = false;
                            errorProcessor.process(response);
                        }
                    );

                    return result;
                }
                else{
                    return true;
                }
            },
            hasMaxLength: function() {
                return window.checkoutConfig.max_length > 0;
            },
            getMaxLength: function () {
                return window.checkoutConfig.max_length;
            },
            /**
             * Get pure value.
             */
            getGrandTotal: function () {
                var totals = quote.getTotals()();

                if (totals) {
                    return totals['base_grand_total'];
                }

                return quote['base_grand_total'];
            }
        };
    }
);
