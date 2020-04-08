define([
    "jquery",
    "jquery/ui"
], function ($) {
    "use strict";
    function main(config, element) {
        var $element = $(element);
        var AjaxUrl = config.AjaxUrl;
        $(document).on('click', '#image-button', function () {
            $.ajax({
                showLoader: true,
                url: AjaxUrl,
                type: "GET"
            }).done(function (data) {
                if (data['success']) {
                    $('#image-log').html(data['success']);
                    $('#image-log').css('color', 'red');
                } else {
                    $('#image-log').html('Please wait...');
                    $('#image-log').css('color', 'red');
                }
                return true;
            });

        });
    }

    return main;

});