/*jshint browser:true jquery:true*/
define([
    "jquery",
    "jquery/ui"
], function($){
    "use strict";

    $.widget('mage.amblogComments', {
        options: {
        },
        _create: function () {
            var self = this;
            $('#amblog_leave_comment').on('click', $.proxy(function () {
                this.getForm('form', 0);
            }, this));

            $(document).on('change', '.amblog_form .gdpr_agree',function() {
                if ($(this).is(':checked')) {
                    $(this).closest('.amblog_form').find('.amblog-customer-info').removeClass('am-hidden');
                } else {
                    $(this).closest('.amblog_form').find('.amblog-customer-info').addClass('am-hidden');
                }
            });

            $('.amblog_replyto').on('click', $.proxy(function () {
                self.getForm('am-comment-form-'+$(this).attr("data-id"), $(this).attr("data-id"));
            }));


        },

        initialize:function (params) {

            this.form = false;

            //Redirect to form after login
            if (window.location.hash){
                if (window.location.hash == '#add-comment'){
                    this.getForm('form', 0);
                } else if (window.location.hash.indexOf('#reply-to-') !== -1) {

                    var replyTo = window.location.hash.replace('#reply-to-','');
                    this.getForm('am-comment-form-' + replyTo, replyTo);
                }
            }
        },
        hideForm: function(form_id, callback){
            $(form_id).innerHTML = '';
            new Effect.Fade(form_id, {
                afterFinish: (typeof(callback) != 'undefined' ? callback() : function(){}),
                duration: 1.0
            });
        },
        getForm:function (container, id) {
            var formContainer = $('#' + container);
            if (formContainer && (formContainer.css('display') == 'none')) {

                $(this.form_selector).each(function (element) {
                    if (element.id !== container) {
                        element.innerHTML = '';
                        $(element).hide();
                        //new Effect.Fade(element.id);
                    }
                });

                this.showLoader(container);

                $(formContainer).show();
                this.loadFormToContainer(container, id);
            } else {
                //new Effect.ScrollTo(formContainer.id);
            }
            return false;
        },
        showLoader:function (who) {
            $(who).innerHTML = $(this.loader_container).innerHTML;
        },
        loadFormToContainer:function (container, id) {
            var url = decodeURI(this.options.form_url.replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')));
            var self = this;
            $.ajax({
                type: 'GET',
                url: url.replace('{{post_id}}', this.options.post_id).replace('{{session_id}}', this.options.session_id).replace('{{reply_to}}', id).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')),
            }).success(function (data) {
                try {

                    if (data.form) {
                        $('#'+container).html(data.form);

                        $( ".amblog_form" ).on( "submit", function( event ) {
                            if ($(this).validation() && $(this).validation('isValid')) {
                                self.submitForm($(this).serializeArray(), $(this));
                            }
                        });
                    }
                } catch (e) {
                    var response = {};
                }
            });

        },
        submitForm: function(formValues, form){
            var values = {};

            $.each( formValues , function(i, field) {
                values[field.name] = field.value;
            });

            if (form.find('.gdpr_agree').length && !form.find('.gdpr_agree:checked').length) {
                values['email'] = '';
                values['name'] = '';
                values['customer_id'] = 0;
            }
            if (!('name' in values)) {
                values['name'] = '';
            }
            if (!('email' in values)) {
                values['email'] = '';
            }
            var url = decodeURI(this.options.post_url);

            var self = this;

            //var container = this.form.form.parentNode.id;

            $.ajax({
                type: 'POST',
                data: values,
                url: url.replace('{{post_id}}', this.options.post_id).replace('{{session_id}}', this.options.session_id).replace('{{reply_to}}', values.reply_to).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')),
            }).success(function (data) {
                try {
                    if (data.error == 1){
                        window.scrollTo(0,0);
                        return false;
                    }

                    var form = $('#amblog_submit_comment').parents('form:first').parent('div');
                    form.hide();

                    $(data.message).insertBefore(form).show();

                    $('#amblog_submit_comment').parents('form:first').remove();
                    
                    $('.amblog_replyto').on('click', $.proxy(function () {
                        self.getForm('am-comment-form-'+$(this).attr("data-id"), $(this).attr("data-id"));
                    }));


                    if ($(this.comments_counter)){
                        $(this.comments_counter).innerHTML = data.count_code;
                    }
                } catch (e) {
                    var data = {};
                }
            });

        }

    });

    return $.mage.amblogComments;
});