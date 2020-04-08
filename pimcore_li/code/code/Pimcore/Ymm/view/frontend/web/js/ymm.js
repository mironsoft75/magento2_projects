require([
    'jquery'
], function ($) {
    'use strict';


    //toggle
    $('.co-title-container').click(function () {
        $('.co-ymm-form').slideToggle();
        $(this).toggleClass("active");
    });


    //disabled selects 
    function disableSelects() {
        // $('select:not(:first)').prop('disabled', true);
    }


    // function to add options to the select
    function addOptions(data, selectId) {
        $.each(data, function (indexInArray, valueOfElement) {
            $(selectId).append($('<option/>').val(valueOfElement).text(valueOfElement));
        });
    }

    function addOptionsToSelect(data, selectId) {
        var sortable = [];
        for (var key in data) {
            sortable.push([key, data[key]]);
        }
        sortable.sort(function(a, b) {
            var nameA=a[1], nameB=b[1];
            if (nameA < nameB) //sort string ascending
                return -1;
            if (nameA > nameB)
                return 1;
            return 0 //default return value (no sorting)
        });
        $.each(sortable, function (key, value) {
            $(selectId).append($('<option/>').val(value[0]).text(value[1]));
        });

    }

    //function to delete options from select
    function removeOptions(selectId) {
        $(selectId).each(function () {
            if ($(this).val()) {
                $(this).remove();
            }
        });
    }

    function removeAllOptions() {
        //  removeOptions('#year option');
        //  removeOptions('#make option');
        removeOptions('#model option');
    }

    //getting make details from selected year
    function getMake() {
        var selectedYear = $('#year option:selected').val() ? $('#year option:selected').text() : '';
        $.getJSON(window.checkout.baseUrl + '/rest/V1/Ymm/make:year', {
            year: selectedYear
        }, function (data) {
            if (data) {
                $('#make').prop('disabled', false);
                $('.co-btn-reset').prop("disabled", false);
            }
            $('#model').prop('disabled', true);
            addOptionsToSelect($.parseJSON(data), '#make');
        })
    }

    $('#year').change(function () {
        $('.co-btn-reset').prop("disabled", true);
        $('#make').prop('selectedIndex', 0);
        $('#make').prop('disabled', true);
        removeOptions('#make option');
        getMake();
        removeOptions('#model option');
        $('#model').prop('selectedIndex', 0);
        $('#model').prop('disabled', true);
        getModel();
    });


    //getting model details from selected make
    function getModel() {
        var year = $('#year option:selected').val() ? $('#year option:selected').text() : '';
        var make = $('#make option:selected').val() ? $('#make option:selected').text() : '';
        if (!make) {
            removeOptions('#model option');
            return;
        }
        $.getJSON(window.checkout.baseUrl + '/rest/V1/Ymm/model:year:make', {
            year: year,
            make: make
        }, function (data) {
            if (data) {
                $('#model').prop('disabled', false);
                $('.co-btn-reset').prop("disabled", false);
            }
            addOptionsToSelect($.parseJSON(data), '#model');
        })
    }

    $('#make').change(function () {
        $('.co-btn-reset').prop("disabled", true);
        $('#model').prop('selectedIndex', 0);
        $('#model').prop('disabled', true);
        removeOptions('#model option');
        getModel();
    });

    // get submodel details from model baseid
    // function getSubmodel(selectedmodel) {
    //     $.getJSON(window.checkout.baseUrl+'/rest/V1/Ymm/submodel:baseid',{
    //         baseid: $('#model option:selected').val(),
    //     }, function(data){             
    //     if(data){
    //         $('#submodel').prop('disabled', false);
    //     }
    //     removeOptions('#submodel option');  
    //     addOptions(data, '#submodel');
    // })
    // }
    // $('#model').change(function () { 
    //     getSubmodel($('#model option:selected').val());
    //     $('.co-btn-reset').attr("disabled", false);
    // });

    ///resetting the form
    $('.co-btn-reset').click(function () {
        $('.co-ymm-form').trigger('reset');
        $('option:selected', $('#year')).removeAttr('selected');
        $('option:selected', $('#make')).removeAttr('selected');
        removeOptions('#model option');
        $('#model').prop('selectedIndex', 0).change();
        $.post(window.BASE_URL + 'pimcore_ymm/ymm/index', {clearSelectedYmm: true})
            .done(function (data) {
                //console.log(window.BASE_URL + 'pimcore_ymm/ymm/index');
                //console.log(data);
            });
        window.isYmmSelected = false;
    });

    $('.co-btn-go').click(function (e) {
        if (!$("#make option:selected").val() && !$("#year option:selected").val()) {
            e.preventDefault();
            alert("Please select an year or make");
        }
    });
});