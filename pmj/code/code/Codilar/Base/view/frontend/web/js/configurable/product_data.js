define([
    'jquery',
    'mage/url',
    'Magento_ConfigurableProduct/js/configurable'
], function ($,mageUrl) {
    return function(config){
        let products = config.productData;
        if(!products) return;
        jQuery(document).ready(function(){
            $(document).on('change',"input[name='selected_configurable_option']",function (event) {
                let productId = $(this).val();
                if(productId){
                    getDiamondDetails(productId);
                    getPriceBreakUpDetails(productId);
                    setProductAttributeValues(productId);
                    setMetalAttributeValues(productId);
                }
            });
            setTimeout(function(){
                let productId = $("input[name='selected_configurable_option']").val();
                if(productId){
                    getDiamondDetails(productId);
                    getPriceBreakUpDetails(productId);
                    setProductAttributeValues(productId);
                    setMetalAttributeValues(productId);
                }
            }, 2000);

        });

        function getDiamondDetails(productId) {
            let diamondDetailsUrl = config.diamondUrl;
            if(diamondDetailsUrl.indexOf('diamonddetails')){
                $.ajax(diamondDetailsUrl, {
                    method: "POST",
                    data: {
                        product_id:productId
                    },
                    success: function(response){
                        $("#product-info-diamond-details").html(response);
                    },
                    error: function (e) {

                    }
                });
            }
        }

        function getPriceBreakUpDetails(productId) {
            let priceBreakUpUrl = config.priceBreakUpUrl;
            if(priceBreakUpUrl.indexOf('pricebreakup')){
                $.ajax(priceBreakUpUrl, {
                    method: "POST",
                    data: {
                        product_id:productId
                    },
                    success: function(response){
                        $("#product-info-price-breakup").html(response);
                    },
                    error: function (e) {

                    }
                });
            }
        }

        function setProductAttributeValues(productId) {
            //for updating sku on simple product id
            jQuery("div[itemprop=sku]").text(products[productId].sku);

            //for updating product description based on simple product id
            let descPosition = ".product.attribute.overview .value";
            jQuery(descPosition).html(products[productId].description);

            //for updating product collection details based on simple product id
            if(products[productId].attributes.collection){
                jQuery(".collection-name").text(products[productId].attributes.collection.value);
            }

            //for updating product attributes based on simple product id
            let attributesPosition = ".additional-attributes-wrapper.table-wrapper";
            let attributes = products[productId].attributes;
            let attributeValues = "<table class='data table additional-attributes' id='product-attribute-specs-table'>" +
                "            <caption class='table-caption'>Product Details</caption>" +
                "            <tbody>";
            jQuery.each(attributes, function( index, value ) {
                attributeValues += '<tr><th class="col label" scope="row">'+value["label"]+'</th><td class="col data" data-th="'+value["label"]+'">'+value["value"]+'</td> </tr>';
            });
            attributeValues += "</tbody></table>";
            jQuery(attributesPosition).html(attributeValues);
        }

        function setMetalAttributeValues(productId) {



            //for updating product attributes based on simple product id
            let attributesPosition = "#product-info-metal-details";
            let attributes = products[productId].attributes;
            let attributeValues = "<table class='table'>" +
                "            <caption class='table-caption'>Metal Details</caption>" +
                "            <tbody>";
            jQuery.each(attributes, function( index, value ) {
                if(value["label"] == 'Metal Type' || value["label"] == 'Metal Color' || value["label"] == 'Net Weight' || value["label"] == 'Metal Karat') {
                    attributeValues += '<tr><th class="col label" scope="row">' + value["label"] + '</th><td class="col data" data-th="' + value["label"] + '">' + value["value"] + '</td> </tr>';
                }
            });
            attributeValues += "</tbody></table>";
            jQuery(attributesPosition).html(attributeValues);
        }
    }
});