define(
    [
        'jquery',
        'uiComponent',
        'knockout',
        'Magento_Checkout/js/model/quote'
    ],
    function ($, Component, ko, quote) {
        'use strict';

        ko.extenders.maxOrderCommentLength = function (target, maxLength) {
            var timer;
            var result = ko.computed({
                read: target,
                write: function (val) {
                    if (maxLength > 0) {
                        clearTimeout(timer);
                        if (val.length > maxLength) {
                            var limitedVal = val.substring(0, maxLength);
                            if (target() === limitedVal) {
                                target.notifySubscribers();
                            } else {
                                target(limitedVal);
                            }
                            result.css("_error");
                            timer = setTimeout(function () { result.css(""); }, 800);
                        } else {
                            target(val);
                            result.css("");
                        }
                    } else {
                        target(val);
                    }
                }
            }).extend({ notify: 'always' });
            result.css = ko.observable();
            result(target());
            return result;
        };

        return Component.extend({
            defaults: {
                template: 'Bold_OrderComment/checkout/order-comment-block'
            },
            initialize: function() {
                this._super();
                var self = this;
                this.comment = ko.observable("").extend({maxOrderCommentLength: this.getMaxLength()});

                this.remainingCharacters = ko.computed(function(){
                    return self.getMaxLength() - self.comment().length;
                });

            },
            hasMaxLength: function() {
                return window.checkoutConfig.max_length > 0;
            },
            getMaxLength: function () {
                return window.checkoutConfig.max_length;
            },
            isPanNumberEnabled: function() {
                let orderTotal = this.getGrandTotal();
                let minOrderTotal = window.checkoutConfig.order_total_amount;
                return orderTotal > minOrderTotal;
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
        });
    }
);
