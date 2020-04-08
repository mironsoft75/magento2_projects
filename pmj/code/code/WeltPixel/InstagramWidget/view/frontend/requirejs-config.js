var config = {
    map: {
        '*': {
            Instafeed: 'WeltPixel_InstagramWidget/js/Instafeed',
            shufflejs: 'WeltPixel_InstagramWidget/js/Shuffle',
            polyfill: 'WeltPixel_InstagramWidget/js/Polyfill',
        }
    },
    shim: {
        Instafeed: {
            deps: ['jquery']
        },
        shufflejs : {
            deps: ['polyfill']
        }
    }
};