define([
        'jquery',
        'Magento_Ui/js/modal/modal',
        'mage/url',
        'Magento_Ui/js/modal/confirm'
    ],
    function ($, modal, url, confirmation) {
        'use strict';
        var options = {
            type: 'popup',
            title: $.mage.__('compatible vehicles'),
            responsive: false,
            innerScroll: true,
            modalClass: 'compatible-vehicle-modal',
        };
        return function (widget) {
            $.widget('mage.catalogAddToCart', widget, {
                submitForm: function (form) {
                    /**
                     custom condition to check
                     **/
                    var showpopup=true;
                    var toShowPopUp = $("#check_aces").val();

                    var selectedoption= $('#fitment-option').val();

                    if(selectedoption == "NotSelected" || selectedoption)
                    {
                        showpopup =false;
                    }
                    //Changes this
                    // if (toShowPopUp) {
                    if (toShowPopUp && showpopup) {
                        /**
                         checking the value of popup select option
                         **/

                        var el = '<input type="hidden" name="fitment" class="fitment-option" id="fitment-option" value=""></input>';

                        $('.fitment-option').remove();
                        $('.option-fitment li').removeClass('selected-li');
                        $("#product_addtocart_form").append(el);

                        var popup = modal(options, $('#myModel'));
                        $(".option_popup").css("display", "block");
                        $('#myModel').modal('openModal');

                        // $("body").click(function (e) {
                        //     var checkclass = e.target.className;
                        //     if (checkclass.indexOf("_show") >= 0) {
                        //         $(".action-close").trigger("click");
                        //     }
                        // })

                        // selected li
                        $('.option-fitment li').click(function () {
                            $('.option-fitment li').removeClass('selected-li');
                            $(this).addClass('selected-li');
                            $('#fitment-option').prop('value', $(this).text());
                        });
                        selectedoption= $('#fitment-option').val();
                        if(selectedoption == "")
                        {
                            selectedoption= $('#fitment-option').val("NotSelected");

                        }
                        // else
                        // {
                        //     selectedoption= "set";
                        //     console.log(selectedoption);
                        // }
                        /*
                         * handle onclick add to cart button
                         */

                        $("#popup-addtocart").click(function (e) {

                            $('#myModel').modal("closeModal");

                            var addToCartButton, self = this;

                            if (form.has('input[type="file"]').length && form.find('input[type="file"]').val() !== '') {
                                self.element.off('submit');
                                addToCartButton = $(form).find(this.options.addToCartButtonSelector);
                                addToCartButton.prop('disabled', true);
                                addToCartButton.addClass(this.options.addToCartButtonDisabledClass);
                                form.submit();
                            } else {
                                self.ajaxSubmit(form);
                            }

                        }.bind(this));
                    }
                    else {

                        var addToCartButton, self = this;
                        /**
                         * handle the submission
                         */
                        if (form.has('input[type="file"]').length && form.find('input[type="file"]').val() !== '') {
                            self.element.off('submit');
                            // disable 'Add to Cart' button
                            addToCartButton = $(form).find(this.options.addToCartButtonSelector);
                            addToCartButton.prop('disabled', true);
                            addToCartButton.addClass(this.options.addToCartButtonDisabledClass);
                            form.submit();
                        } else {
                            self.ajaxSubmit(form);
                        }
                    }
                }
            });
            return $.mage.catalogAddToCart;
        }
    });