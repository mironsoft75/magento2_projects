/**
 * @author Evince Team
 * @copyright Copyright (c) 2018 Evince (http://evincemage.com/)
 */

require([
    'mage/translate',
    'jquery',
    'jquery/ui'

], function ($t,$) {
    'use strict';

    var $searchModule = $('#search-diamonds'),
        $resultTable = $('#result_table'),
        hiddenTd = false;

    //Shape section control
    $searchModule.find('.shape ul li').on('click', function() {
        customMultiselect($(this), 'shapes');
    });

    //Certificate sections control
    $searchModule.find('.certificate ul li').on('click', function() {
        customMultiselect($(this), 'certificates');
    });

    //Fancy Color section control
    $searchModule.find('.fancycolor ul li').on('click', function() {

        customMultiselect($(this), 'fancycolor');

    });

    function customMultiselect($el, type) {

        var name = $el.attr('attid');

        if (!$el.hasClass('selected')) {
            $el.addClass('selected');

            if(type == 'certificates'){
                $searchModule.find('.diamond_'+type+'_select #certi_option_'+name).attr('checked','checked');
            }else {
                $searchModule.find('.diamond_'+type+'_select input[value='+name+']').attr('checked','checked');
            }

        } else {
            $el.removeClass('selected');
            if(type == 'certificates'){
                $searchModule.find('.diamond_'+type+'_select #certi_option_'+name).removeAttr('checked');
            }else {
                $searchModule.find('.diamond_'+type+'_select input[value='+name+']').removeAttr('checked');
            }
        }
    }

    //Responsive Result Table
    adjustResultTable();

    $(window).resize(adjustResultTable);
    function adjustResultTable() {

        var h = $(window).width();
        var nCount = parseInt($("#result_table tr:nth-child(2) td").length);

        if (!document.addEventListener) return false;

        if (h < 960) {

            if (!hiddenTd) {
                hiddenTd = true;
                $resultTable.find('tr').each(function () {
                    for (var i = 4; i <= nCount - 4; i++)
                        $(this).find('td').eq(i).hide();
                });

                for (var i = 4; i <= nCount - 4; i++)
                    $resultTable.find('th:eq(' + i + ')').hide();
            }

        } else {

            if (hiddenTd) {
                hiddenTd = false;
                $resultTable.find('tr').each(function () {
                    for (var i = 4; i <= nCount - 4; i++)
                        $(this).find('td').eq(i).show();
                });

                for (var i = 4; i <= nCount - 4; i++)
                    $resultTable.find('th:eq(' + i + ')').show();
            }
        }
    }

    //More Options control
    $searchModule.find('.options_control').on('click', function() {

        if ($searchModule.find('.more_options').hasClass('hideops')) {
            $(this).text("Less Options");
            $searchModule.find('.more_options').removeClass('hideops').hide().fadeIn(400);
        } else {
            $(this).text("More Options");
            $searchModule.find('.more_options').addClass('hideops').hide();
        }
    });



    //Custom Slider class

    var labelSlider = function($slider, $select) {

        var self	   = this;
        this.slider    = $slider;
        this.select    = $select;
        this.items     = $select.children();
        this.qty	   = $select.children().length;
        this.width     = 0;
        this.height    = 0;
        this.start	   = $select.find('option:selected:first').index();
        this.end	   = $select.find('option:selected:last').index()+1;
        this.slider.slider({
            min: 0,
            max: this.qty,
            range: true,
            values: [this.start, this.end],
            slide: function(e, ui) {
                if (ui.values[1] - ui.values[0] < 1)
                    return false;
            },

            change: function(e, ui) {
                for (var i = 0; i < self.qty; i++)
                    if (i >= ui.values[0] && i < ui.values[1]) {
                        self.items.eq(i).attr('selected', 'selected');
                    } else {
                        self.items.eq(i).removeAttr('selected');
                    }
            }
        }).touchit();

        var options = [];
        this.items.each(function(){options.push('<b>'+$(this).text()+'</b>')});
        this.width = 100/options.length;
        this.slider.after('<div class="ui-slider-legend"><p class="first" style="width:'+this.width+'%;"><span style=""></span>'+
            options.join('</p><p style="width:'+this.width+'%;"><span style=""></span>')+'</p></div>');
    };

    var numberSlider = function(type, decimal) {
        decimal = decimal === undefined ? false : true;
        var rules = {
            price: [
                [0, 3000, 100],
                [3000, 10000, 500],
                [10000, 20000, 1000],
                [20000, 100000, 5000],
                [100000, 150000, 10000],
                [250000, 1000000, 50000],
                [1000000, 5000000, 100000],
            ],
            carat: [
                [0.18, 1, 0.02],
                [1, 2, 0.05],
                [2, 2.5, 0.1],
                [2.5, 4, 0.25],
                [4, 10, 0.5]
            ],
            tableper: [
                [0, 10, 1],
                [10, 30, 2],
                [30, 60, 3],
                [60, 90, 2],
                [90, 100, 1]
            ],
            depth: [
                [0, 10, 1],
                [10, 30, 2],
                [30, 60, 3],
                [60, 90, 2],
                [90, 100, 1]
            ]
        };

        var createArrayByRule = function(rule) {
            var a = [], b = [];
            for (var i = 0; i < rule.length; i++)
                for (var j = rule[i][0]; j <= rule[i][1]; j += rule[i][2])
                    a.push(j);
            for (var i = 0; i < a.length; i++)
                b.push(i * 10);
            return {
                trueValues: a,
                values: b
            };
        };

        var findNearest = function(includeLeft, includeRight, value, values) {

            var nearest = null, diff = null;
            for (var i = 0; i < values.length; i++) {
                if ((includeLeft && values[i] <= value) || (includeRight && values[i] >= value)) {
                    var newDiff = Math.abs(value - values[i]);
                    if (diff == null || newDiff < diff) {
                        nearest = values[i];
                        diff = newDiff;
                    }
                }
            }
            return nearest;
        };

        var getRealValue = function(sliderValue, tv, values, d) {

            for (var i = 0; i < values.length; i++) {
                if (d) {
                    if (Math.round(values[i] * 100) >= Math.round(sliderValue * 100))
                        return tv[i];
                } else {
                    if (values[i] >= sliderValue)
                        return tv[i];
                }
            }
            return 0;
        };

        var getFakeValue = function(inputValue, tv, values, d) {

            for (var i = 0; i < tv.length; i++) {
                if (d) {
                    if (Math.round(tv[i] * 100) >= Math.round(inputValue * 100))
                        return values[i];
                } else {
                    if (tv[i] >= inputValue)
                        return values[i];
                }
            }
            return 0;

        };

        var setRangeValues = function(side, value) {

            value = getFakeValue(value, arrayByRule.trueValues, arrayByRule.values, decimal);
            $slider.slider("values", side, value);
        };

        var arrayByRule = createArrayByRule(rules[type]),
            $slider = $("#"+type+"_slider"),
            $leftVal = $slider.find('.slider-left'),
            $rightVal = $slider.find('.slider-right'),
            rangeMin = rules[type][0][0],
            rangeMax = rules[type][4][1];

        $slider.slider({
            orientation: 'horizontal',
            range: true,
            min: arrayByRule.values[0],
            max: arrayByRule.values[arrayByRule.values.length - 1],
            values: [arrayByRule.values[0], arrayByRule.values[arrayByRule.values.length - 1]],
            slide: function (event, ui) {
                var includeLeft = event.keyCode != $.ui.keyCode.RIGHT,
                    includeRight = event.keyCode != $.ui.keyCode.LEFT,
                    value = findNearest(includeLeft, includeRight, ui.value, arrayByRule.values),
                    n = getRealValue(value, arrayByRule.trueValues, arrayByRule.values, decimal);

                if (ui.value == ui.values[0]) {
                    $slider.slider('values', 0, value);
                    decimal ? $leftVal.val(n.toFixed(2)) : $leftVal.val(n);
                } else {
                    $slider.slider('values', 1, value);
                    decimal ? $rightVal.val(n.toFixed(2)) : $rightVal.val(n);
                }
            },

            create: function (event, ui) {
                setRangeValues(0, $leftVal.val());
                setRangeValues(1, $rightVal.val());
            }

        }).addClass(type).touchit();

        $leftVal.on('keyup blur', function(e) {
            var v = this.value = this.value.replace(/[^0-9\.]/g,''),
                currentRightValue = getRealValue($slider.slider('values', 1), arrayByRule.trueValues, arrayByRule.values, decimal);

            if ((e.type == 'keyup' && e.keyCode == 13) || e.type == 'blur') {
                if (v.length) {
                    if (v < rangeMin) {
                        setRangeValues(0, rangeMin);
                        this.value = rangeMin;
                    } else if (v > currentRightValue) {
                        setRangeValues(0, currentRightValue);
                        this.value = currentRightValue;
                    } else {
                        setRangeValues(0, v);
                    }
                } else {
                    setRangeValues(0, rangeMin);
                    this.value = rangeMin;
                }
            }
        });

        $rightVal.on('keyup blur', function(e) {
            var v = this.value = this.value.replace(/[^0-9\.]/g,''),
                currentLeftValue = getRealValue($slider.slider('values', 0), arrayByRule.trueValues, arrayByRule.values, decimal);

            if ((e.type == 'keyup' && e.keyCode == 13) || e.type == 'blur') {
                if (v.length) {
                    if (v > rangeMax) {
                        setRangeValues(1, rangeMax);
                        this.value = rangeMax;
                    } else if (v < currentLeftValue) {
                        setRangeValues(1, currentLeftValue);
                        this.value = currentLeftValue;
                    } else {
                        setRangeValues(1, v);
                    }
                } else {
                    setRangeValues(1, rangeMax);
                    this.value = rangeMax;
                }
            }
        });
    };

    $(window).keydown(function(event) {
        if (event.keyCode == 13)
            return false;
    });

    //If search module container exists hook slider to DOM
    if ($searchModule.length) {
        if ($('#carat_slider').length)
            new numberSlider('carat', true);
        if ($('#price_slider').length)
            new numberSlider('price');
        if ($('#tableper_slider').length)
            new numberSlider('tableper');
        if ($('#depth_slider').length)
            new numberSlider('depth');

        // Label sliders

        if ($('#cut_slider').length)
            new labelSlider($('#cut_slider'), $('#cut_slider_select'));
        if ($('#color_slider').length)
            new labelSlider($('#color_slider'), $('#color_slider_select'));
        if ($('#clarity_slider').length)
            new labelSlider($('#clarity_slider'), $('#clarity_slider_select'));
        if ($('#symmetry_slider').length)
            new labelSlider($('#symmetry_slider'), $('#symmetry_slider_select'));
        if ($('#fluorescence_slider').length)
            new labelSlider($('#fluorescence_slider'), $('#fluorescence_slider_select'));
        if ($('#polish_slider').length)
            new labelSlider($('#polish_slider'), $('#polish_slider_select'));
        if ($('#colorintensity_slider').length)
            new labelSlider($('#colorintensity_slider'), $('#colorintensity_slider_select'));

        $searchModule.find('.ui-slider-handle:even').addClass('left-handle');
        $searchModule.find('.ui-slider-handle:odd').addClass('right-handle');
    }

    // Certificate Modal
    var nanowebPopup = function() {
        var that = this;
        var $modal = $('<div id="wh-modal" class="wh-modal" />').html(
            '<div class="wh-content">' +
            '<h3 class="header-top">' +
            '<span class="table">' +
            '<span class="table-cell">' +
            $t('Certificate') +
            '</span>' +
            '</span>' +
            '</h3>' +
            '<div class="cont-scroll-wrapper"></div>' +
            '<h3 class="bottom">' +
            '<span class="table">' +
            '<span class="table-cell">' +
            '<button class="wh-close ds_button">'+$t('Close')+'</button>' +
            '</span>' +
            '</span>' +
            '</h3>' +
            '</div>');

        var $overlay = $('<div class="wh-overlay wh-close" />');

        this.init = function() {
            $overlay.html($modal);
            $('body').append($overlay);
        }

        this.showPopup = function(link) {
            fillPopup(link);
            setTimeout(function(){$modal.addClass('wh-show');}, 0);
            setTimeout(function(){$overlay.addClass('wh-show');}, 0);
            $('body').addClass('wh-no-scroll');
        }

        this.hidePopup = function() {
            $modal.find('.cont-scroll-wrapper').html('');
            setTimeout(function(){$modal.removeClass('wh-show');}, 0);
            setTimeout(function(){$overlay.removeClass('wh-show');}, 0);
            $('body').removeClass('wh-no-scroll');
        }

        var fillPopup = function(link) {
            var $frame = $('<iframe />').attr('src',link).css({
                'width' : '100%',
                'height': '100%',
                'border': '0 none'
            });

            $modal.find('.cont-scroll-wrapper').html($frame);
        }

        $('body').on('click', '.wh-close', function(e) {
            if ($(e.target).hasClass('wh-close'))
                that.hidePopup();
        });

        $('body').on('click', '.wh-open', function() {
            that.showPopup();
        });

        $(document).keypress(function(e) {
            if (e.keyCode == 27) {
                that.hidePopup();
            }
        });
    }

    var nanoPop = new nanowebPopup();

    nanoPop.init();
    $('#diamond-product-attribute-table td a').on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var link = $(this).attr('href');
        var isiPad = (navigator.userAgent.match(/iPad/i) != null);
        var isiPhone = (navigator.userAgent.match(/iPhone/i) != null);
        var isiPod = (navigator.userAgent.match(/iPod/i) != null);

        if (!isiPad && !isiPhone && !isiPod) {
            nanoPop.showPopup(link);
        } else {
            window.open(link);
        }
    });

    $resultTable.find('.shopit').click(function(e) {

        e.stopImmediatePropagation();

        window.location = $(this).data('buy');

    });

});
