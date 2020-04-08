define([
    'jquery','dist',
], function ($) {
                
                const players = Array.from(document.querySelectorAll('.js-player')).map(player => new Plyr(player));
                players.forEach(function(instance,index) {
                instance.on('play',function(){
                players.forEach(function(instance1,index1){
                    if(instance != instance1){
                        instance1.pause();
                    }
                });
            });
        });         jQuery(".js-pause").click(function() {
                        players.forEach(function(instance){
                                instance.pause()
                        });
                    });
          
            
        function initializePlayer(quality) {
            const players = Array.from(document.querySelectorAll('.js-player')).map(player => new Plyr(player),{

                quality: { 
                default: quality, 
                options: [4320, 2880, 2160, 1440, 1080, 720, 576, 480, 360, 240] 
            },
                storage: { enabled: false },
                 previewThumbnails: {
                 enabled: false           
                }

            });
        }


/*internet speed test start*/

function showResults() {
    var duration = (endTime - startTime) / 1000; //Math.round()
    var bitsLoaded = downloadSize * 8;
    var speedBps = (bitsLoaded / duration).toFixed(2);
    var speedKbps = (speedBps / 1024).toFixed(2);        

    if(speedKbps<500){
        //LOAD_SMALL_VIDEO
        initializePlayer(360);

    } else if(speedKbps < 1000){
        //LOAD_MEDIUM_VIDEO
        initializePlayer(480);
        
    } else {
        //LOAD_LARGE_VIDEO
        initializePlayer(720); 
    }

}

var imageAddr = window.imgpath;
var startTime, endTime;
var downloadSize = 511532;
var download = new Image();
download.onload = function () {
    endTime = (new Date()).getTime();
    showResults();
}

startTime = (new Date()).getTime();
download.src = imageAddr;

/*internet speed test end*/
});

