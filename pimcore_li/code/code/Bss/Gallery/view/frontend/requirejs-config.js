var config = {
	paths: {
		'bss_fancybox': 'Bss_Gallery/js/fancybox/source/jquery.fancybox',
		'bss_fancyboxButtons': 'Bss_Gallery/js/fancybox/source/helpers/jquery.fancybox-buttons',
		'bss_fancyboxThumbs': 'Bss_Gallery/js/fancybox/source/helpers/jquery.fancybox-thumbs',
		'bss_fancyboxMedia': 'Bss_Gallery/js/fancybox/source/helpers/jquery.fancybox-media',
		'bss_owlslider': 'Bss_Gallery/js/owl.carousel.2.0/owl.carousel.min',
	},
	shim: {
		'bss_fancybox': {
			deps: ['jquery']
		},
		'bss_fancyboxButtons': {
			deps: ['bss_fancybox']
		},
		'bss_fancyboxThumbs': {
			deps: ['bss_fancybox']
		},
		'bss_fancyboxMedia': {
			deps: ['bss_fancybox']
		},
		'bss_owlslider': {
			deps: ['jquery']
		}
	}
};
require.config(config);