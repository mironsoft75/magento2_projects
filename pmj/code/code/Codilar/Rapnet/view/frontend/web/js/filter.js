define([
    'jquery',
    'mage/url'
], function ($, murl) {
    return function (config) {
        $("#price-slider").slider({
            range: true,
            min: config.priceValues[0],
            max: config.priceValues[1],
            values: [config.priceValues[0], config.priceValues[1]],
            step: 100,
            slide: function (event, ui) {
                $("#price_from").val(ui.values[0]);
                $("#price_to").val(ui.values[1]);
            },
            change: function (event, ui) {
                searchresults(event)
            }

        });
        $("#carat-slider").slider({
            range: true,
            min: Number(config.caratValues[0]),
            max: Number(config.caratValues[1]),
            values: [config.caratValues[0], config.caratValues[1]],
            step: 0.01,
            slide: function (event, ui) {
                $("#carat_from").val(ui.values[0]);
                $("#carat_to").val(ui.values[1]);

            },
            change: function (event, ui) {
                searchresults(event)
            }
        });
        $("#tableper-slider").slider({
            range: true,
            min: config.tablePercentValues[0],
            max: config.tablePercentValues[1],
            values: [config.tablePercentValues[0], config.tablePercentValues[1]],
            step: 1,
            slide: function (event, ui) {
                $("#tableper_from").val(ui.values[0]);
                $("#tableper_to").val(ui.values[1]);

            },
            change: function (event, ui) {
                searchresults(event)
            }
        });
        $("#depth-slider").slider({
            range: true,
            min: config.depthPercentValues[0],
            max: config.depthPercentValues[1],
            values: [config.depthPercentValues[0], config.depthPercentValues[1]],
            step: 1,
            slide: function (event, ui) {
                $("#depth_from").val(ui.values[0]);
                $("#depth_to").val(ui.values[1]);

            },
            change: function (event, ui) {
                searchresults(event)
            }
        });
        $(document).on("click", "a.page", function (event) {
            event.preventDefault();
            var p = $(this).attr('href').split('p=');
            var updateUrl = $(this).attr('href').split('?');
            let baseUrl = murl.build("rapnet");
            let searchUrl = baseUrl + "?" + updateUrl[1];
            window.history.pushState({url: searchUrl}, '', searchUrl);
            $.ajax({
                url: murl.build('rapnet/index/pagedata' + "?" + updateUrl[1]),
                type: 'POST',
                showLoader: true,
                success: function (response) {
                    $('.codilar_rapnet_listing_table').html(response);
                    return true;
                },
                error: function (res) {

                }
            });
        });

        $(document).on("click", "a.action", function (event) {
            event.preventDefault();
            var p = $(this).attr('href').split('p=');
            var updateUrl = $(this).attr('href').split('?');
            let baseUrl = murl.build("rapnet");
            let searchUrl = baseUrl + "?" + updateUrl[1];
            window.history.pushState({url: searchUrl}, '', searchUrl);
            $.ajax({
                url: murl.build('rapnet/index/pagedata' + "?" + updateUrl[1]),
                type: 'POST',
                showLoader: true,
                success: function (response) {
                    $('.codilar_rapnet_listing_table').html(response);
                    return true;
                },
                error: function (res) {

                }
            });
        });
        $(document).on("click", ".codilar_rapnet_listing_div ul li", function (event) {
            event.preventDefault();
            $(this).toggleClass('selected');
        });

        $(document).on("click", "#shape-list .shape-item", function (event) {
            // console.log("hello",event);
            searchresults(event)
        });
        $(document).on("click", "#color-list .color", function (event) {
            // console.log("color",event);
            searchresults(event)
        });
        $(document).on("click", "#certificate-list .certificate", function (event) {
            // console.log("color",event);
            searchresults(event)
        });
        $(document).on("click", "#clarity-list .clarity", function (event) {
            // console.log("color",event);
            searchresults(event)
        });
        $(document).on("click", "#cut-list .cut", function (event) {
            // console.log("color",event);
            searchresults(event)
        });
        $(document).on("click", "#polish-list .polish", function (event) {
            // console.log("color",event);
            searchresults(event)
        });
        $(document).on("click", "#symmetry-list .symmetry", function (event) {
            // console.log("color",event);
            searchresults(event)
        });
        $(document).on("click", "#fluoresence-list .fluoresence", function (event) {
            // console.log("color",event);
            searchresults(event)
        });


            function searchresults(event) {
            if (event.name !== 'slidechange') {
                event.preventDefault()
            }
            let shapeValues = getSelectedValues($('#shape-list .shape-item.selected'));
            let certificateValues = getSelectedValues($('#certificate-list .certificate.selected'));
            let colorValues = getSelectedValues($('#color-list .color.selected'));
            let cutValues = getSelectedValues($('#cut-list .cut.selected'));
            let polishValues = getSelectedValues($('#polish-list .polish.selected'));
            let symmetryValues = getSelectedValues($('#symmetry-list .symmetry.selected'));
            let clarityValues = getSelectedValues($('#clarity-list .clarity.selected'));
            let fluoresenceValues = getSelectedValues($('#fluoresence-list .fluoresence.selected'));
            let priceValues = ($('#price_from').val() + "-" + $('#price_to').val());
            let caratValues = ($('#carat_from').val() + "-" + $('#carat_to').val());
            let tableperValues = ($('#tableper_from').val() + "-" + $('#tableper_to').val());
            let depthValues = ($('#depth_from').val() + "-" + $('#depth_to').val());

            let selectedValues = [];
            if (shapeValues.length !== 0) {
                selectedValues.push("diamond_shape=" + shapeValues.join(","));
            }
            if (certificateValues.length !== 0) {
                selectedValues.push("diamond_lab=" + certificateValues.join(","));
            }
            if (colorValues.length !== 0) {
                selectedValues.push("diamond_color=" + colorValues.join(","));
            }
            if (cutValues.length !== 0) {
                selectedValues.push("diamond_cut=" + cutValues.join(","));
            }
            if (polishValues.length !== 0) {
                selectedValues.push("diamond_polish=" + polishValues.join(","));
            }
            if (symmetryValues.length !== 0) {
                selectedValues.push("diamond_symmetry=" + symmetryValues.join(","));
            }
            if (clarityValues.length !== 0) {
                selectedValues.push("diamond_clarity=" + clarityValues.join(","));
            }
            if (fluoresenceValues.length !== 0) {
                selectedValues.push("diamond_fluoresence=" + fluoresenceValues.join(","));
            }

            selectedValues.push("diamond_price=" + priceValues);
            selectedValues.push("diamond_carats=" + caratValues);
            selectedValues.push("diamond_table_percent=" + tableperValues);
            selectedValues.push("diamond_depth_percent=" + depthValues);
            if (selectedValues.length !== 0) {
                let paramUrl = selectedValues.join("&");
                let baseUrl = murl.build("rapnet");
                let searchUrl = baseUrl + "?" + paramUrl;
                window.history.pushState({url: searchUrl}, '', searchUrl);
                $.ajax({
                    url: murl.build('rapnet/index/pagedata' + "?" + paramUrl),
                    type: 'POST',
                    showLoader: true,
                    success: function (response) {
                        $('.codilar_rapnet_listing_table').html(response);
                        return true;
                    },
                    error: function (res) {

                    }
                });
            }
            //     console.log($(".rapnet-table-top .toolbar-number").html());
            //     let countValue =   $(".rapnet-table-top .toolbar-number").html().trim().split(' ');
            //     console.log("countvalue",countValue);
            // let mainValue = countValue[5];
            // let countText =  mainValue === undefined ? "Loading...." : "Results Count:"+ mainValue;
            // $(".rapnet-table-top .toolbar-number").html(countText);

        }

        // $(document).bind("click", ".codilar_rapnet_listing_div .rapnet_search_button", function (event) {
        //     searchresults(event);
        // });

        function getSelectedValues(lists) {
            let values = [];
            $(lists).each(function (key, list) {
                values.push($(list).data('value'));
            });
            return values;
        }

        $('#price_from').on('blur', (e) => {
            $("#price-slider").slider("values", 0, e.target.value);
            searchresults(e)
        });
        $('#price_to').on('blur', (e) => {
            $("#price-slider").slider("values", 1, e.target.value);
            searchresults(e)
        });
        $('#carat_from').on('blur', (e) => {
            $("#carat-slider").slider("values", 0, e.target.value);
            searchresults(e)
        });
        $('#carat_to').on('blur', (e) => {
            $("#carat-slider").slider("values", 1, e.target.value);
            searchresults(e)
        });
        $('#tableper_from').on('blur', (e) => {
            $("#tableper-slider").slider("values", 0, e.target.value);
            searchresults(e)
        });
        $('#tableper_to').on('blur', (e) => {
            $("#tableper-slider").slider("values", 1, e.target.value);
            searchresults(e)
        });
        $('#depth_from').on('blur', (e) => {
            $("#depth-slider").slider("values", 0, e.target.value);
            searchresults(e)
        });
        $('#depth_to').on('blur', (e) => {
            $("#depth-slider").slider("values", 1, e.target.value);
            searchresults(e)
        });
    }
});