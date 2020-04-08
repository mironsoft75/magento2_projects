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
                    $(document).find('.block_data_static_container').hide();
                    $('div[data-index="block_data"]').show();
                }, 1000);
            }else{
                setTimeout(function () {
                    $('div[data-index="block_data"]').hide();
                    $(document).find('.block_data_static_container').show();
                }, 1000);
            }
        },
        /**
         * Change currently selected option
         *
         * @param {String} id
         */
        selectProductOption: function(id){
            if(($("#"+id).val() == 0)||($("#"+id).val() == undefined)) {
                $('div[data-index="block_data"]').hide();
                $(document).find('.block_data_static_container').show();
            } else if($("#"+id).val() == 1) {
                $(document).find('.block_data_static_container').hide();
                $('div[data-index="block_data"]').show();
            }
        }
    });
});