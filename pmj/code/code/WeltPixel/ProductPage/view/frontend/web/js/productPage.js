var productPage = {
	init: function () {
		jQuery('.togglet').bind('click', function() {
			setTimeout(function() {jQuery(window).trigger('resize')}, 300);
		});
	},

	load: function () {
		this.action();
		this.mageSticky();
		this.addMinHeight();
	},

	ajaxComplete: function () {
		this.mageSticky();
		this.adjustHeight();
	},

	resize: function () {
		this.action();
		this.adjustHeight();
		this.mageSticky();
	},

	adjustHeight: function() {
		// adjust left media height as well, in case it is smallers
		var media = jQuery('.product.media'),
			mediaGallery = jQuery('.product.media .gallery'),
			infoMain = jQuery('.product-info-main');

		if ( jQuery('body').hasClass('wp-device-xs') ||
			jQuery('body').hasClass('wp-device-s') ||
			jQuery('body').hasClass('wp-device-m')
		) {
			media.height('auto');
		} else {
			if ( ( mediaGallery.height() > 0 ) && ( mediaGallery.height() < infoMain.height())) {
				media.height(infoMain.height());
			}
		}
	},

	mageSticky: function () {
        var positionProductInfo = window.positionProductInfo;

        if (positionProductInfo == 1) {
        	if (jQuery('body').hasClass('product-page-v2')) {
                jQuery('.product-info-main.product_v2.cart-summary').mage('sticky', {
                    container: '.product-top-main.product_v2',
                    spacingTop: 100
                });
			}
            if (jQuery('body').hasClass('product-page-v4')) {
                jQuery('.product-info-main.product_v4.cart-summary').mage('sticky', {
                    container: '.product-top-main.product_v4',
                    spacingTop: 25
                });
            }

		} else {
            if (jQuery('body').hasClass('product-page-v2') || jQuery('body').hasClass('product-page-v4')) {
                jQuery('.product-info-main.product_v2.cart-summary, .product-info-main.product_v4.cart-summary').addClass("no-sticky-product-page");
			}
		}

	},

	action: function () {
		var media = jQuery('.product.media.product_v2'),
			media_v4 = jQuery('.product.media.product_v4'),
			swipeOff = jQuery('.swipe_desktop_off #swipeOff');

		if(jQuery(window).width() > 768) {
			media.addClass('v2');
			media_v4.addClass('v4');
		} else {
			media.removeClass('v2');
			media_v4.removeClass('v4');
		}

		if(jQuery(window).width() > 1024) {
			swipeOff.addClass('active');
		} else {
			swipeOff.removeClass('active');
		}
	},

    addMinHeight: function() {
        var media_v4 = jQuery('.product.media.product_v4');
        if (media_v4.length) {
            var mediaContainer = media_v4.find('.gallery-placeholder');
            this.waitForEl('.fotorama__loaded--img', function() {
                var prodImg = mediaContainer.find('.fotorama__loaded--img').first();
                mediaContainer.css('min-height', prodImg.outerHeight());
            });
        }
    },

    waitForEl: function(selector, callback) {
		var that = this;
        if (jQuery(selector).length) {
            callback();
        } else {
            setTimeout(function() {
                that.waitForEl(selector, callback);
            }, 500);
        }
    }
};

require(['jquery', 'productPage', 'mage/mage', 'mage/ie-class-fixer', 'mage/gallery/gallery'],
	function ($) {
		$(document).ready(function () {
			productPage.init();
		});

		$(window).load(function () {
			productPage.load();
		});

		$(document).ajaxComplete(function () {
			productPage.ajaxComplete();
		});

		var reinitTimer;
		$(window).on('resize', function () {
			clearTimeout(reinitTimer);
			reinitTimer = setTimeout(productPage.resize(), 300);
		});

        var headerSection = $('.page-wrapper div.page-header');
        var stickyElement = $('.product-info-main.cart-summary');
        /*$(window).scroll(function() {
            if (headerSection.hasClass('sticky-header')) {
				$(stickyElement.children().get(0)).css('padding-top', headerSection.height());
            } else {
                $(stickyElement.children().get(0)).css('padding-top', 0);
			}
        });*/
	}
);