define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url'
],function ($, modal, murl) {
    'use strict';
    let options = {
        type: 'popup',
        title: $.mage.__('Try on video process'),
        responsive: false,
        innerScroll: true,
        modalClass: 'tryonVideoModal-container',
        create: function (e, ui) {
            $( ".modal-footer" ).append('<div class="checkbox"><label><input class="pop-up-checkbox" type="checkbox" value="no">I Understood, do not show again.</label></div>');
        },
        buttons: [{
            text: 'Got It',
            class: 'tryon-ok',
            click: function() {
               if($('.pop-up-checkbox').prop('checked') === true){
                   $.cookie('tryon_popup', false, { expires: 7, path: '/' });
               }
                ajaxSubmit();
                this.closeModal();
            }
        },
        ],
    };
    $('.tryon-video-form').submit(function (e) {
        e.preventDefault();
    });
    $('#tryonvideo-button').click(function () {
        if($.cookie('tryon_popup')){
            ajaxSubmit();
        }
        else{
            modal(options, $('#tryonVideoModal'));
            $('#tryonVideoModal').modal('openModal');
        }
    });

    function ajaxSubmit() {

        var configProductId = $("#product_addtocart_form input[name='selected_configurable_option']").val();
        var productId = $("#product_addtocart_form input[name='product']").val();
        var id = configProductId ? configProductId : productId;
        $.ajax({
            type: "post",
            url: murl.build('videostore/cart/addnew'),
            data:{
                'product-id': id,
            },
            success: function (data, status) {
            }
        });
    }

});

