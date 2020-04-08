define([
    "jquery",
    "jquery/ui"
], function ($) {
    "use strict";
    function main(config, element) {
        var $element = $(element);
        var AjaxUrl = config.AjaxUrl;
        $(document).on('click', '#video-button', function () {
            $.ajax({
                showLoader: true,
                url: AjaxUrl,
                type: "GET"
            }).done(function (data) {
                if (data['success']) {
                    $('#video-log').html(data['success']);
                    $('#video-log').css('color', 'red');
                } else {
                    $('#video-log').html('Please wait...');
                    $('#video-log').css('color', 'red');
                }
                return true;
            });

        });
    }

    return main;

});