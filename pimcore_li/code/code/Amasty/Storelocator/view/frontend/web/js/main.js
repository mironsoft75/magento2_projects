define([
    "jquery",
    "jquery/ui"
], function ($) {

    $.widget('mage.amLocator', {
        options: {},
        url: null,
        useGeo: null,
        imageLocations: null,
        filterAttributeUrl: null,
        map: {},
        marker: {},

        _create: function () {
            this.url = this.options.ajaxCallUrl;
            this.filterAttributeUrl = this.options.filterAttributeUrl;
            this.useGeo = this.options.useGeo;
            this.imageLocations = this.options.imageLocations;
            this.Amastyload();
            var self = this;
            $('#locateNearBy').click(function(){
                self.navigateMe()
            });

            $("#" + this.options.searchButtonId).click(function(){
                self.sortByFilter()
            });

            $('#filterAttribute').click(function(){
                self.filterByAttribute()
            });

            if ( (navigator.geolocation) && (this.useGeo == 1) ) {
                navigator.geolocation.getCurrentPosition( function(position) {
                    document.getElementById("am_lat").value = position.coords.latitude;
                    document.getElementById("am_lng").value = position.coords.longitude;
                }, this.navigateFail );
            }
        },

        goHome: function(){
            window.location.href = window.location.pathname;
        },

        navigateMe: function(){

            if ( (navigator.geolocation) && (this.useGeo==1) ) {
                var self = this;
                navigator.geolocation.getCurrentPosition( function(position) {
                    self.makeAjaxCall(position);
                }, this.navigateFail );
            }else{
                this.makeAjaxCall();
            }
        },

        navigateFail: function(error){

        },

        getQueryVariable: function(variable) {
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                if (pair[0] == variable) {
                    return pair[1];
                }
            }
        },

        makeAjaxCall: function(position) {
            var mapId = this.options.mapId;
            var storeListId = "getStoresListId";
            var self = this;
            if ( (position != "") && (typeof position!=="undefined")){

                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                $.ajax({
                    url     : this.url,
                    type    : 'POST',
                    data: {
                        "lat": lat,
                        "lng": lng,
                        "product": productId,
                        "category": categoryId,
                        "mapId": mapId,
                        "storeListId": storeListId
                    },
                    showLoader: true
                }).done($.proxy(function(response) {
                    response = JSON.parse(response);
                    self.options.jsonLocations = response;
                    self.Amastyload(response);
                }));
            }else{
                $.ajax({
                    url     : this.url,
                    type    : 'POST',
                    showLoader: true,
                    data: {
                        "sort": "distance",
                        "lat": lat,
                        "lng": lng,
                        "product": productId,
                        "category": categoryId,
                        "mapId": mapId,
                        "storeListId": storeListId
                    },
                }).done($.proxy(function(response) {
                    response = JSON.parse(response);
                    self.options.jsonLocations = response;
                    self.Amastyload(response);
                }));
            }

        },

        Amastyload: function(response) {
            this.initializeMap();
            this.processLocation(this.options.jsonLocations);

            var markerCluster = new MarkerClusterer(this.map[this.options.mapId], this.marker[this.options.mapId], {imagePath: this.imageLocations+'/m'});

            this.geocoder = new google.maps.Geocoder();

            if (this.options.showSearch) {
                var address = document.getElementById(this.options.searchId);
                var autocomplete = new google.maps.places.Autocomplete(address);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    var place = autocomplete.getPlace();
                    document.getElementById("am_lat").value = place.geometry.location.lat();
                    document.getElementById("am_lng").value = place.geometry.location.lng();
                });
            }

            var mapId = this.options.mapId;
            if (response && response.storeListId) {
                $("#" + "getStoresListId").replaceWith(response.block);
            }
            var self = this;
            $('[data-mapid=' +  mapId + ']').click(function(){
                var id =  $(this).attr('data-amid');
                self.gotoPoint(id);
            });

            $("#" + this.options.storeListId + " .today_schedule" ).click(function(event) {
                $(this).next( ".all_schedule" ).toggle( "slow", function() {
                    // Animation complete.
                });
                $(this).find( ".locator_arrow" ).toggleClass("arrow_active");
                event.stopPropagation();
            });
        },

        sortByFilter: function() {
            var e = document.getElementById(this.options.searchRadiusId);
            var radius = e.options[e.selectedIndex].value;
            var lat = document.getElementById("am_lat").value;
            var lng = document.getElementById("am_lng").value;
            var mapId = this.options.mapId;
            var storeListId = this.options.storeListId;
            if (!lat || !lng) {
                alert('Please fill Current Location field');
                return false;
            }
            var self = this;

            $.ajax({
                url     : this.url,
                type    : 'POST',
                data: {
                    "lat": lat,
                    "lng": lng,
                    "radius": radius,
                    "product": productId,
                    "category": categoryId,
                    "mapId": mapId,
                    "storeListId": storeListId
                },
                showLoader: true
            }).done($.proxy(function(response) {
                response = JSON.parse(response);
                self.options.jsonLocations = response;
                self.Amastyload(response);
            }));

        },

        filterByAttribute: function(){
            var form = $("#attribute-form").serialize();
            var mapId = this.options.mapId;
            var storeListId = this.options.storeListId;
            var self = this;

            $.ajax({
                url     : this.filterAttributeUrl,
                type    : 'POST',
                data: {
                    "attributes": form,
                    "mapId": mapId,
                    "storeListId": storeListId
                },
                showLoader: true
            }).done($.proxy(function(response) {
                response = JSON.parse(response);
                self.options.jsonLocations = response;
                self.Amastyload(response);
            }));

        },

        initializeMap: function() {
            this.infowindow = [];
            this.marker[this.options.mapId] = [];
            var myOptions = {
                zoom: 9,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            this.map[this.options.mapId] = [];
            this.map[this.options.mapId] = new google.maps.Map(document.getElementById(this.options.mapId), myOptions);
        },

        processLocation: function(locations) {
            var template = baloonTemplate.baloon; // document.getElementById("amlocator_window_template").innerHTML;
            var curtemplate = "";

            if (typeof locations.totalRecords=="undefined" || locations.totalRecords==0){
                this.map[this.options.mapId].setCenter(new google.maps.LatLng( document.getElementById("am_lat").value, document.getElementById("am_lng").value ));
                return false;
            }

            for (var i = 0; i < locations.totalRecords; i++) {

                curtemplate = template;
                curtemplate = curtemplate.replace("{{name}}", locations.items[i].name);
                curtemplate = curtemplate.replace("{{country}}", locations.items[i].country);
                curtemplate = curtemplate.replace("{{state}}", locations.items[i].state);
                curtemplate = curtemplate.replace("{{city}}", locations.items[i].city);
                curtemplate = curtemplate.replace("{{description}}", locations.items[i].description);
                curtemplate = curtemplate.replace("{{zip}}", locations.items[i].zip);
                curtemplate = curtemplate.replace("{{address}}", locations.items[i].address);
                curtemplate = curtemplate.replace("{{phone}}", locations.items[i].phone);
                curtemplate = curtemplate.replace("{{email}}", locations.items[i].email);
                curtemplate = curtemplate.replace("{{website}}", locations.items[i].website);
                curtemplate = curtemplate.replace("{{lat}}", locations.items[i].lat);
                curtemplate = curtemplate.replace("{{lng}}", locations.items[i].lng);
                curtemplate = curtemplate.replace("{{locatorPage}}", '<a href="' + locatorPageUrl + '?locationId=' + locations.items[i].id + '" target="_blank">' + widgetLinkText + '</a>');

                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].name,'name');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].country,'country');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].state,'state');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].city,'city');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].description,'description');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].zip,'zip');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].address,'address');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].phone,'phone');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].email,'email');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].website,'website');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].lat,'lat');
                curtemplate = this.replaceIfStatement(curtemplate, locations.items[i].lng,'lng');

                curtemplate = this.showAttributeInfo(curtemplate, locations.items[i], locations.currentStoreId);

                if (locations.items[i].store_img != "") {
                    curtemplate = curtemplate.replace("{{photo}}",locations.items[i].store_img);
                } else {
                    curtemplate = curtemplate.replace(/<img[^>]*>/g,"");
                }
                if (locations.items[i].marker_img != "") {
                    markerImage = amMediaUrl + locations.items[i].marker_img;
                } else {
                    markerImage = "";
                }
                this.createMarker(locations.items[i].lat, locations.items[i].lng,  curtemplate, markerImage, locations.items[i].id);
            }
            var bounds = new google.maps.LatLngBounds();
            for (var locationId in this.marker[this.options.mapId]) {
                bounds.extend(this.marker[this.options.mapId][locationId].getPosition());
            }

            var self = this;
            this.map[this.options.mapId].fitBounds(bounds);
            if (locations.totalRecords == 1) {
                google.maps.event.addListenerOnce(this.map[this.options.mapId], 'bounds_changed', function() {
                    self.map[self.options.mapId].setZoom(mapZoom);
                });
            }

            var activeLocation = this.options.activeLocation;
            if (activeLocation) {
                google.maps.event.addListenerOnce(this.map[this.options.mapId], 'tilesloaded', function() {
                    self.gotoPoint(activeLocation);
                });
            }
        },

        showAttributeInfo: function (curtemplate, item, currentStoreId) {
            var attributeTemplate = baloonTemplate.attributeTemplate;
            if (item.attributes) {
                for (var j = 0; j < item.attributes.length; j++) {
                    var label = item.attributes[j].frontend_label;
                    var labels = item.attributes[j].labels;
                    if (labels[currentStoreId]) {
                        label = labels[currentStoreId];
                    }

                    var value = item.attributes[j].value;
                    if (item.attributes[j].boolean_title) {
                        value = item.attributes[j].boolean_title;
                    }
                    if (item.attributes[j].option_title) {
                        var optionTitles = item.attributes[j].option_title;
                        value = '<br>';
                        for (var k = 0; k < optionTitles.length; k++) {
                            value += '- ';
                            if (optionTitles[k][currentStoreId]) {
                                value += optionTitles[k][currentStoreId];
                            } else {
                                value += optionTitles[k][0];
                            }
                            value += '<br>';
                        }
                    }
                    attributeTemplate = attributeTemplate.replace("{{title}}",label);
                    curtemplate += attributeTemplate.replace("{{value}}",value);

                    attributeTemplate = baloonTemplate.attributeTemplate;
                }
            }
            return curtemplate;
        },

        gotoPoint: function(myPoint) {
            var mapId = this.closeAllInfoWindows();
            $('[data-mapid=' +  mapId + ']').removeClass('active');
            // add class if click on marker
            $('[data-mapid=' +  mapId + '][data-amid=' + myPoint + ']').addClass('active');
            this.map[mapId].setCenter(
                new google.maps.LatLng(
                    this.marker[mapId][myPoint].position.lat(),
                    this.marker[mapId][myPoint].position.lng()
                )
            );
            this.map[mapId].setZoom(mapZoom);
            this.marker[mapId][myPoint]['infowindow'].open(
                this.map[mapId],
                this.marker[mapId][myPoint]
            );
        },

        replaceIfStatement: function(text,value,template){
            var patt = new RegExp("\{\{if"+template+"\}\}([\\s\\S]*)\{\{\/\if"+template+"}\}","g");
            var cuteText = patt.exec(text);
            if (cuteText!=null ){
                if (value=="" || value==null){
                    text = text.replace(cuteText[0], '');
                }else{
                    var finalText = cuteText[1].replace('{{'+template+'}}', value);
                    text = text.replace(cuteText[0], finalText);
                }

                return text;
            }
            return text;
        },

        createMarker: function(lat, lon, html, marker, locationId) {
            var image = marker.split('/').pop();
            if (marker && image != 'null') {
                var marker = {
                    url: marker,
                    size: new google.maps.Size(48, 48),
                    scaledSize: new google.maps.Size(48, 48)
                };
                var newmarker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat, lon),
                    map: this.map[this.options.mapId],
                    icon: marker
                });
            } else {
                var newmarker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat, lon),
                    map: this.map[this.options.mapId]
                });
            }

            newmarker['infowindow'] = new google.maps.InfoWindow({
                content: html
            });
            newmarker['locationId'] = locationId;
            var self = this;
            google.maps.event.addListener(newmarker, 'click', function() {
                self.gotoPoint(this.locationId);
            });

            // using locationId instead 0, 1, 2, i counter
            this.marker[this.options.mapId][locationId] = newmarker;
        },

        closeAllInfoWindows: function () {
            var mapId = this.element.context.id;
            var spans = document.getElementById(mapId).getElementsByTagName('span');

            for(var i = 0, l = spans.length; i < l; i++){
                spans[i].className = spans[i].className.replace(/\active\b/,'');
            }

            if (typeof this.marker[mapId] !=="undefined") {
                for (var marker in this.marker[mapId]) {
                    if (this.marker[mapId].hasOwnProperty(marker)) {
                        this.marker[mapId][marker]['infowindow'].close();
                    }
                }
            }

            return mapId;
        },

    });

    return $.mage.amLocator;
});
