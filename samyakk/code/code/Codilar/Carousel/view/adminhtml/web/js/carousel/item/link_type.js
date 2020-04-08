define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'ko'
], function ($, Select, ko) {
    'use strict';

    ko.bindingHandlers.customBinding = {
        init: function (element, valueAccesor) {
            console.log('I am a custom binding.');
            console.log(element);
            console.log(valueAccesor);
        }
    };

    return Select.extend({
        defaults: {
            customName: '${ $.parentName }.${ $.index }_input'
        },

        initialize: function () {
            this._super();
            this.resetVisibility();
            return this;
        },

        resetVisibility: function () {
            var value = this.value();
            if (value == 'product'){
                setTimeout(function () {
                    $('div[data-index="category_data"]').hide();
                    $('div[data-index="cms_data"]').hide();
                    $('div[data-index="product_data"]').show();
                }, 1000);
            }else if (value == 'category') {
                setTimeout(function () {
                    $('div[data-index="product_data"]').hide();
                    $('div[data-index="cms_data"]').hide();
                    $('div[data-index="category_data"]').show();
                }, 1000);
            } else if (value == 'cms') {
                setTimeout(function () {
                    $('div[data-index="product_data"]').hide();
                    $('div[data-index="category_data"]').hide();
                    $('div[data-index="cms_data"]').show();
                }, 1000);
            }else {
                setTimeout(function () {
                    $('div[data-index="product_data"]').hide();
                    $('div[data-index="category_data"]').hide();
                    $('div[data-index="cms_data"]').hide();
                }, 1000);
            }
        },
        /**
         * Change currently selected option
         *
         * @param {String} id
         */
        selectProductOption: function(id) {
            if(($("#"+id).val() == 'product')||($("#"+id).val() == undefined)) {
                $('div[data-index="category_data"]').hide();
                $('div[data-index="cms_data"]').hide();
                $('div[data-index="product_data"]').show();
            } else if($("#"+id).val() == 'category') {
                $('div[data-index="product_data"]').hide();
                $('div[data-index="cms_data"]').hide();
                $('div[data-index="category_data"]').show();
            } else if ($("#"+id).val() == 'cms') {
                $('div[data-index="product_data"]').hide();
                $('div[data-index="category_data"]').hide();
                $('div[data-index="cms_data"]').show();
            } else {
                $('div[data-index="product_data"]').hide();
                $('div[data-index="category_data"]').hide();
                $('div[data-index="cms_data"]').hide();
            }
        }
    });
});