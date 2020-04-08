/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

/* global $, $H */

define([
    'jquery',
    'mage/adminhtml/grid'
], function ($) {
    'use strict';

    return function (config) {
        var selectedProducts = config.selectedProducts,
            storeProducts = $H(selectedProducts),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;


        // $('in_block_products').value = Object.toJSON(storeProducts);
        $(document).find("input[name='product_content']").val(Object.toJSON(storeProducts)).trigger("change");

        /**
         * Register Store Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerStoreProduct(grid, element, checked) {
            if (checked) {
                //if (element.positionElement) {
                //element.positionElement.disabled = false;
                storeProducts.set(element.value, 'id');
                //}
            } else {
                /*if (element.positionElement) {
                 element.positionElement.disabled = true;
                 }*/
                storeProducts.unset(element.value);
            }
            console.log(element.value);
            // $('in_block_products').value = Object.toJSON(storeProducts);
            $(document).find("input[name='product_content']").val(Object.toJSON(storeProducts)).trigger("change");
            grid.reloadParams = {
                'selected_products[]': storeProducts.keys()
            };
        }

        /**
         * Click on product row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function storeProductRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = event.target.tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        /**
         * Change product position
         *
         * @param {String} event
         */
        function positionChange(event) {
            var element = Event.element(event);

            if (element && element.checkboxElement && element.checkboxElement.checked) {
                storeProducts.set(element.checkboxElement.value, element.value);
                // $('in_block_products').value = Object.toJSON(storeProducts);
                $(document).find("input[name='product_content']").val(Object.toJSON(storeProducts)).trigger("change");
            }
        }

        /**
         * Initialize store product row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function storeProductRowInit(grid, row) {
            var checkbox = $(row).find('.checkbox')[0],
                position = $(row).find('.input-text')[0];

            if (checkbox && position) {
                checkbox.positionElement = position;
                position.checkboxElement = checkbox;
                 position.disabled = !checkbox.checked;
                 position.tabIndex = tabIndex++;
                 Event.observe(position, 'keyup', positionChange);
            }
        }

        gridJsObject.rowClickCallback = storeProductRowClick;
        gridJsObject.initRowCallback = storeProductRowInit;
        gridJsObject.checkboxCheckCallback = registerStoreProduct;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                storeProductRowInit(gridJsObject, row);
            });
        }
    };
});
