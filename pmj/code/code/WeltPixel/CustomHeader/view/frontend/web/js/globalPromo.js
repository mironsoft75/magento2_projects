define(["jquery", 'mage/cookies'], function ($) {
    var globalPromoComponent = function(config, node)
    {
        $(document).ready(function() {
            function showGlobalNotification() {
                var headerSection = $('.page-wrapper div.page-header');
                if (!headerSection.hasClass('sticky-header')) {
                    $(node).show();
                }
            }

            if (!$.cookie('weltpixel_global_notification')  ) {
                showGlobalNotification();
            }

            $(node).find('.close-global-notification').bind('click', function() {
                $.cookie('weltpixel_global_notification', true);
                $(node).hide();
            });


            //rotation speed and timer
            var speed = 5000;

            var run = setInterval(rotate, speed);
            var slides = $('.slide');
            var container = $('#slides ul');


            var elm = container.find(':first-child').prop("tagName");
            var item_width = container.width();
            var previous = 'prev'; //id of previous button
            var next = 'next'; //id of next button
            slides.width(item_width); //set the slides to the correct pixel width
            container.parent().width(item_width);
            container.width(slides.length * item_width); //set the slides container to the correct total width
            container.find(elm + ':first').before(container.find(elm + ':last'));
            resetSlides();

            if ( $('#slides ul li').size() <= 1 ) {
                $('.btn-bar').addClass("no-arrows");
                $('#carousel').addClass("no-carousel");
            }

            //if user clicked on prev button

            $('#buttons a').click(function (e) {
                //slide the item

                if (container.is(':animated')) {
                    return false;
                }
                if (e.target.id == previous) {
                    container.stop().animate({
                        'left': 0
                    }, 1000, "swing", function () {
                        container.find(elm + ':first').before(container.find(elm + ':last'));
                        resetSlides();
                    });
                }

                if (e.target.id == next) {
                    container.stop().animate({
                        'left': item_width * -2
                    }, 1000, "swing", function () {
                        container.find(elm + ':last').after(container.find(elm + ':first'));
                        resetSlides();
                    });
                }

                //cancel the link behavior
                return false;

            });

            function resetSlides() {
                //and adjust the container so current is in the frame
                container.css({
                    'left': -1 * item_width
                });
            }
        });

        function rotate() {
            $('#next').click();
        }

    };

    return globalPromoComponent;
});