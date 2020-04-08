require(['jquery',"prototype"], function (jQuery) {
    
    setLocation = function(lochref){
        window.location.href=lochref;
    };
    myObj = {
        printLabelUrl: '',
        wh: jQuery(window).height(),
        ww: jQuery(window).width(),
        shipperCountry: '',
        shipperCity: '',
        shipperZip: '',
        shipperState: '',
        recieverCountry: '',
        recieverCity: '',
        recieverZip: '',
        recieverState: '',
        openWindow: function (param1, param2) {
            jQuery(param1).css({'visibility': 'hidden', 'display': 'block'});
            var h = jQuery(param2).height();
            var w = jQuery(param2).width();
            var wh = this.wh;
            var ww = this.ww;
            if (h >= wh) {
                h = wh - 20;
                jQuery(param2).css({'height': (h - 30)});
            }
            else {
                h = h + 30;
            }
            jQuery('.back-over').fadeIn(200);
            var t = wh - h;
            t = t / 2;
            var l = ww - w
            l = l / 2;
            jQuery(param1).css({'visibility': 'visible', 'left': l + 'px', 'display': 'none', 'height': h, 'top': t + 'px'}).fadeIn(500);

        },
        openCalc: function () {
            this.cropValues();
            this.openWindow('.cal-rate-part', '.cal-form');
        },
        defaultVal: function () {
            this.shipperCountry = $('aramex_shipment_shipper_country').value;
            this.shipperCity = $('aramex_shipment_shipper_city').value;
            this.shipperZip = $('aramex_shipment_shipper_postal').value;
            this.shipperState = $('aramex_shipment_shipper_state').value;

            this.recieverCountry = $('aramex_shipment_receiver_country').value;
            this.recieverCity = $('aramex_shipment_receiver_city').value;
            this.recieverZip = $('aramex_shipment_receiver_postal').value;
            this.recieverState = $('aramex_shipment_receiver_state').value;
        },
        cropValues: function () {
            this.defaultVal();
            var orginCountry = this.getId('origin_country');
            this.setSelectedValue(orginCountry, this.shipperCountry);
            $('origin_city').value = this.shipperCity;
            $('origin_zipcode').value = this.shipperZip;
            $('origin_state').value = this.shipperState;

            var desCountry = this.getId('destination_country');
            this.setSelectedValue(desCountry, this.recieverCountry);
            $('destination_city').value = this.recieverCity;
            $('destination_zipcode').value = this.recieverZip;
            $('destination_state').value = this.recieverState;
        },
        getId: function (id) {
            return document.getElementById(id);
        },
        setSelectedValue: function (selectObj, valueToSet) {
            for (var i = 0; i < selectObj.options.length; i++) {
                if (selectObj.options[i].value == valueToSet) {
                    selectObj.options[i].selected = true;
                    return;
                }
            }
        },
        openPickup: function () {
            this.defaultVal();
            var pickupCountry = this.getId('pickup_country');
            this.setSelectedValue(pickupCountry, this.shipperCountry);
            $('pickup_city').value = this.shipperCity;
            $('pickup_zip').value = this.shipperZip;
            $('pickup_state').value = this.shipperState;
            $('pickup_address').value = $('aramex_shipment_shipper_street').value;
            $('pickup_company').value = $('aramex_shipment_shipper_company').value;
            $('pickup_contact').value = $('aramex_shipment_shipper_name').value;
            $('pickup_email').value = $('aramex_shipment_shipper_email').value;
            this.openWindow('.schedule-pickup-part', '.pickup-form');
        },
        close: function () {
            jQuery('.back-over').fadeOut(500);
            jQuery('.cal-rate-part, .schedule-pickup-part').fadeOut(200);
        },
        calcRate: function () {
            this.ajax('calc-rate-form', '.result', '.rate-result');
        },
        schedulePickup: function () {
            this.ajax('pickup-form', '.pickup-res', '.pickup-result');

        },
        ajax: function (formId, result1, result2) {
            jQuery('#loading-mask').css({'z-index': '1000'});
            formId = "#"+formId;
            jQuery.ajax({
                url: jQuery(formId).attr('action'),
                method: 'post',
                data: jQuery(formId).serialize(),
                done: function (transport) {
                    json = transport.responseText.evalJSON();
                    if (json.type == 'success') {
                        jQuery(result1).html(json.html);
                    } else {
                        var error = "<div class='error'>" + json.error + "</div>";
                        jQuery(result1).html(error).show();
                    }
                    jQuery(result2).show();
                }
            });
        },
        printLabel: function () {
            setLocation(this.printLabelUrl);
        }
    }
});