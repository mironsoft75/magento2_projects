/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * ['jquery', "jquery/ui", "prototype",
    "varien/js", "varien/form"]
 */
var getpop=function(){}, getMassPopup=function(){},togglePickup=function(){},getclose =function(){}, submitForConsignment=function(){};
 
require([
    'jquery', 
    'Magento_Ui/js/modal/modal'
     ], function (jQuery, modal) {
    
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        title: 'Configure and submit for consignment',
        buttons: [{
                text: jQuery.mage.__('Submit'),
                class: '',
                attr: {id:'shipment_creation_submit_id'},
                click: function(){
                    submitForConsignment();
                }
            },{
                text: jQuery.mage.__('Close'),
                class: '',
                click: function(){
                    this.closeModal();
                }
            }]
    };
    
    modal = jQuery.mage.modal;
    var popup = modal(options, jQuery('#ship_overlay'));
    togglePickup = function()
    {
        jQuery('#pickup_infromation').toggle('slow');
    };

    getpop =  function(itemscount)
    {
        //jQuery("#ship_overlay").css("display", "block");
        //jQuery("#shipment_creation").fadeIn(1000);
        jQuery('#ship_overlay').show();
        popup.openModal();
    };
    getMassPopup = function(){
        var selorder= [];
        jQuery("#spsOrdersGrid_table tbody input[type='checkbox']:checked").each(function(){
            selorder.push(jQuery(this).val());
        });
        if(selorder.length>0){
            jQuery('input#order_ids').val(selorder.join());
            jQuery('#ship_overlay').show();
            popup.openModal();
        }else{
            alert("Please select order to generate awb");
        }
        
    };
    getclose = function()
    {
        //jQuery("#ship_overlay").css("display", "none");
        //jQuery("#shipment_creation").fadeOut(500);
        jQuery('#ship_overlay').hide();
        popup.closeModal();
    };
    submitForConsignment = function(){
        var form  = jQuery("#blurdart_order_shipment");
        var href =  window.location.href;
        var notvalid = [];
        var i =0;
        form.find('input.required-entry').each(function(){
            if(isNaN(jQuery(this).val()) || jQuery(this).val()<=0){
                notvalid[i] = jQuery(this).attr('data-title');
                i++;
            }
        });
        if(notvalid.length==0){
            jQuery.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                showLoader: true,
            }).done(function(resp){
                try{
                    var respo = JSON.parse(resp);
                    jQuery(".error_flex").html(respo.error_flex);
                    if(respo.reload===true){
                        window.location.href = href;
                    }
                }catch(e){
                    jQuery(".error_flex").html(e);
                    console.log(e);
                }
            });
        }else{
            alert(notvalid.join(", ")+" should be numeric and greater than 0.")
        }
    };
    validateNonNegative = function(element){
        var val = jQuery(element).val();
        if(isNaN(val)){
             jQuery(element).val('');
        } else if(val<0){
           jQuery(element).val(Math.abs(val));         
        }
    };
});

