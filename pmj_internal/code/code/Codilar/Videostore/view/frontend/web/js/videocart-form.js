require([
    "jquery",
    "mage/calendar",
    'mage/url'
], function ($, mc, murl) {
    $('.videostore-form').submit(function (e) {
        $(".otp-sent").css("display", "none");
        $(".otp-resent").css("display", "none");
        $(".otp-success").css("display", "none");
        $(".otp-failed").css("display", "none");
        e.preventDefault();

        $.ajax({
            showLoader: true,
            url: murl.build('videostore/otp/verify'),
            data: {
                'otp': $('#otp').val(),
                'mobile_number': $('#mobile_number').val()
            }
        }).done(function (data) {
            if (data.status === true) {
                $(".otp-success").css("display", "block");
                $('#videocart-submit').prop('disabled', false);
                if ($('.videostore-form').valid()) {
                    $('body').loader('show');
                    $('.videostore-form').unbind().submit();
                }
            }
            else {
                $(".otp-failed").css("display", "block");
            }
        });
    });
    var currentMobile;
    $("#tryonDate").calendar({
        buttonText: "<?php echo __('Select') ?>",
        changeYear: true,
        dateFormat: 'yy/mm/dd',
        yearRange: "2018:<?php echo date('Y') ?>",
        minDate: new Date(),
        showAnim: "blind",
        onSelect: function (dateText) {
            var seldate = $(this).datepicker('getDate');
            seldate = seldate.toDateString();
            seldate = seldate.split(' ');
            var weekday = new Array();
            weekday['Mon'] = "Monday";
            weekday['Tue'] = "Tuesday";
            weekday['Wed'] = "Wednesday";
            weekday['Thu'] = "Thursday";
            weekday['Fri'] = "Friday";
            weekday['Sat'] = "Saturday";
            weekday['Sun'] = "Sunday";
            var dayOfWeek = weekday[seldate[0]];
            $('#timeSelect option:not(:first-child)').each(function () {
                if ($(this).val()) {
                    $(this).remove();
                }
            });
            $.getJSON(window.checkout.baseUrl + 'rest/V1/getslots/day/' + dayOfWeek, {}, function (slots) {
                if (slots.length > 0) {
                    $('#slot-message').remove();
                    $.each(slots, function (key, value) {
                        $('#timeSelect').append($('<option/>').val(value).text(value));
                    });
                } else {
                    $('#slot-message').remove();
                    $($($('#tryonDate').parent()).parent().after('<span id="slot-message" style="color: #e02b27; font-size: 12px">No slots available for selected day</span>'))
                }
            });
        }
    });
    //ajax delete from the cart
    $('.cart-delete-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: murl.build('videostore/cart/delete'),
            data: $('.cart-delete-form').serialize(),
            success: function (data, status) {
                customerData.reload('videocart');
            }
        });
    });
    $('.otp-details').css('display', 'none');
    $('#mobile_number').on('keyup', $('#mobile_number'), function () {
        let value = $('#mobile_number').val();
        if (currentMobile === $('#mobile_number').val()) {
            $('.opt-input').prop('disabled', false);
        }
        if ($.isNumeric(value) && value.length === 10 && currentMobile !== value) {
            $('.generate-otp-btn').css('display', 'block');
            $('.opt-input').prop('disabled', false);
        }
        else {
            $('.generate-otp-btn').css('display', 'none');
        }
    });
    $('.generate-otp-btn').click(function () {
        currentMobile = $('#mobile_number').val();
        $(".otp-resent").css("display", "none");
        $(".otp-success").css("display", "none");
        $(".otp-failed").css("display", "none");
        $.ajax({
            url: murl.build('videostore/otp/sendotp'),
            showLoader: true,
            data: {
                'mobile_number': $('#mobile_number').val()
            }
        }).done(function (data) {
            $('.generate-otp-btn').css("display", "none");
            $('.otp-details').css('display', 'block');
            $('.opt-input').prop('disabled', false);
            $(".otp-sent").css("display", "block");
            $(".otp-resent").css("display", "none");
            $('.resend-otp-btn').hide();
            $('.resend-otp-msg').hide();
            setTimeout(function () {
                $(".otp-sent").css("display", "none");
                $('.resend-otp-msg').show();
                $('.resend-otp-btn').show();
            }, 60000);
        });
    });
    $('.resend-otp-btn').click(function () {
        $('.resend-otp-btn').hide();
        $('.resend-otp-msg').hide();
        $(".otp-sent").css("display", "none");
        $(".otp-resent").css("display", "none");
        $(".otp-success").css("display", "none");
        $(".otp-failed").css("display", "none");
        $.ajax({
            url: murl.build('videostore/otp/sendotp'),
            data: {
                'mobile_number': $('#mobile_number').val()
            }
        }).done(function (data) {
            $('.generate-otp-btn').attr('disabled', 'true');
            $(".otp-sent").css("display", "block");
            $(".otp-resent").css("display", "none");
            setTimeout(function () {
                $(".otp-sent").css("display", "none");
                $('.resend-otp-msg').show();
                $('.resend-otp-btn').show();
            }, 60000);
        });
    });
    $('.verify-otp-btn').click(function () {
        $(".otp-sent").css("display", "none");
        $(".otp-resent").css("display", "none");
        $(".otp-success").css("display", "none");
        $(".otp-failed").css("display", "none");
        $.ajax({
            url: murl.build('videostore/otp/verify'),
            data: {
                'otp': $('#otp').val(),
                'mobile_number': $('#mobile_number').val()
            }
        }).done(function (data) {
            if (data === 'Success') {
                $(".otp-success").css("display", "block");
                $('#videocart-submit').prop('disabled', false);
            }
            else {
                $(".otp-failed").css("display", "block");
                $('#videocart-submit').prop('disabled', true);
            }
        });
    });

    //dynamic select
    $('.videostore-form select').click(function () {
        if ($(this).val()) {
            $(this).addClass('select-active');
        }
        else {
            $(this).removeClass('select-active');
        }
    });

});