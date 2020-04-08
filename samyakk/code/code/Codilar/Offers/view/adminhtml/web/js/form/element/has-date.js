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
            if (value == 1){
                setTimeout(function () {
                    $('div[data-index="start_date"]').show();
                    $('div[data-index="end_date"]').show();
                }, 1000);
            }else {
                setTimeout(function () {
                    $('div[data-index="start_date"]').hide();
                    $('div[data-index="end_date"]').hide();
                }, 1000);
            }
        },
        /**
         * Change currently selected option
         *
         * @param {String} id
         */
        selectDateOption: function(id){
            if(($("#"+id).val() == 0)||($("#"+id).val() == undefined)) {
                $('div[data-index="start_date"]').hide();
                $('div[data-index="end_date"]').hide();
            } else if($("#"+id).val() == 1) {
                $('div[data-index="start_date"]').show();
                $('div[data-index="end_date"]').show();
            }
        },
    });
});