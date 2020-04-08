require(['jquery'], function ($) {
    $(document).ready(function () {
        $('.card-homepage').on("click", function () {
            window.location = $(this).data('href');
        });
    });
});
