define([
    'jquery',
    'mage/url',
    'mage/translate',
    'jquery/ui',
], function ($, murl) {

    $('.category-tryonvideo-form').submit(function (e) {
        e.preventDefault();
    });

    $('.category-tryonvideo-button .tryonvideo-button').click(function () {
        let productId = $($(this).parent()).find("input[name='product-id']").val();
        ajaxSubmit(productId);
    });

    function ajaxSubmit(productId) {
        var id = productId;
        $.ajax({
            type: "post",
            url: murl.build('videostore/cart/addnew'),
            showLoader: true,
            data:{
                'product-id': id,
            },
            success: function (data, status) {
               if(data.success == true){
                let redirectUrl = murl.build('videostore/cart/');
                window.location.replace(redirectUrl);
               }
            }
        });
    }
});
