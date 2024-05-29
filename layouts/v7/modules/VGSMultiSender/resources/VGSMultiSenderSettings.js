/**
 * VGS Multi FROM Address Module
 *
 *
 * @package        VGSMultiSender Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */

jQuery.Class("SMTP_Js", {}, {
    hideUnusefulThings: function(){
        if(jQuery('#parent_view').val() != 'Settings'){
            jQuery(".contentsDiv").removeClass('span10').addClass('span12');
            jQuery("#leftPanel").hide();
            jQuery("#toggleButton").hide();
        }
    },
    saveEntry: function () {
        jQuery('#add_entry').on('click', function (e) { 
            var loadingMessage = jQuery('.listViewLoadingMsg').text();

            var progressIndicatorElement = jQuery.progressIndicator({
                'message': loadingMessage,
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });

            var params = {
                module: 'VGSMultiSender',
                action: 'SaveSMTPConfig',
                mode: 'addEntry',
                id: jQuery("#id").val(),
                user_id: jQuery("[name='user_id']").val(),
                server_name: jQuery("#server_name").val(),
                user_name: jQuery("#user_name").val(),
                password: jQuery("#password").val(),
                email_from: jQuery("#email_from").val(),
                smtp_auth: (jQuery('#smtp_auth').prop("checked")) ? 1 : 0,
                from_name: jQuery('#from_name').val(),
                batch_count: jQuery('#batch_count').val(),
                batch_delay: jQuery('#batch_delay').val(),
            };
            if (params.id.length > 0) {
                params.mode = 'editRecord';
            }

            if (jQuery('#parent_view').val() === 'Settings') {
                var from_settings = true;
            } else {
                var from_settings = false;
            }
            if(jQuery("#email_from").val() == ''){
                app.helper.showErrorNotification({'message': 'Email from not set!'});
            }

            AppConnector.request(params).then(
                function (data) {
                    if (data.success) {
                        var response = data.result;
                        if (response.result === 'ok') {
                            if (from_settings === true) {
                                window.location = 'index.php?module=VGSMultiSender&view=SettingsIndex&parent=Settings';
                                return false;
                            } else {
                                window.location = 'index.php?module=VGSMultiSender&view=SMTPindex';
                                return false;
                            }
                        } else {
                            app.helper.showErrorNotification({'message': response.message});
                        }
                    }
                    progressIndicatorElement.progressIndicator({
                        'mode': 'hide'
                    });
                },
                function (error, err) {
                    progressIndicatorElement.progressIndicator({
                        'mode': 'hide'
                    });
                }
            );
        });
    },
    deleteEntry: function () {
        jQuery('.deleteRecordButton').on('click', function (e) {
            var loadingMessage = jQuery('.listViewLoadingMsg').text();
            var progressIndicatorElement = jQuery.progressIndicator({
                'message': loadingMessage,
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });
            var params = {
                module: 'VGSMultiSender',
                view: 'PopupYesNo',
                record_id: jQuery(this).attr('id'),
            };

            function realDelete(record_id) {
                var loadingMessage = jQuery('.listViewLoadingMsg').text();
                var progressIndicatorElement = jQuery.progressIndicator({
                    'message': loadingMessage,
                    'position': 'html',
                    'blockInfo': {
                        'enabled': true
                    }
                });
                var params = {
                    module: 'VGSMultiSender',
                    action: 'SaveSMTPConfig',
                    mode: 'deleteRecord',
                    record_id: record_id,
                };

                var line = jQuery('a#' + record_id + '.deleteRecordButton').closest('tr');
                AppConnector.request(params).then(
                    function (data) {
                        if (data.success) {
                            var response = data.result;
                            if (response.result === 'ok') {
                                line.hide('slow');
                            } else {
                                alert(app.translate('JS_ERROR_DELETING'));
                            }
                        }
                    },
                    function (error, err) {
                        alert(app.translate('JS_ERROR_DELETING'));
                    }
                );

                progressIndicatorElement.progressIndicator({
                    'mode': 'hide'
                });
            }

            AppConnector.request(params).then(
                function (data) {
                    if (data.success) {
                        var msg = data.result;
                        if (msg) {
                            var record_id = jQuery(msg).find('input[name=record_id]').val();
                            msg = {
                                result: msg
                            };
                            app.showModalWindow(msg);

                            jQuery('#case1').on('click', function (e) {
                                app.hideModalWindow();
                                realDelete(record_id);
                            });

                            //really show the modal
                            setTimeout(function () {
                                jQuery('.myModal').show();
                                jQuery('#PopupContainer')
                                    .addClass('modal-body')
                                    .css('display', 'inline-block')
                                    .position({
                                        'of': jQuery(window),
                                        'my': 'center center',
                                        'at': 'center center',
                                        'collision': 'flip none',
                                        'offset': '0 50'
                                    });
                            });
                        } else {
                            alert(app.translate('JS_ERROR_DELETING'));
                        }
                    }
                },
                function (error, err) {
                    alert(app.translate('JS_ERROR_DELETING'));
                }
            );

            progressIndicatorElement.progressIndicator({
                'mode': 'hide'
            });
        });
    },
    lockEntry: function () {
        jQuery('.lockRecordButton').on('click', function (e) {
            var loadingMessage = jQuery('.listViewLoadingMsg').text();
            var progressIndicatorElement = jQuery.progressIndicator({
                'message': loadingMessage,
                'position': 'html',
                'blockInfo': {
                    'enabled': true
                }
            });
            if (jQuery('#parent_view').val() === 'Settings') {
                var from_settings = true;
            } else {
                var from_settings = false;
            }
            var params = {
                module: 'VGSMultiSender',
                action: 'SaveSMTPConfig',
                mode: 'addLock',
                record_id: jQuery(this).attr('id'),
                locked: (jQuery(this).attr('data-locked') > 0) ? 0 : 1,
            };

            AppConnector.request(params).then(function (data) {
                if (data.success) {
                    var response = data.result;
                    if (response.result === 'ok') {
                        if (from_settings === true) {
                            window.location = 'index.php?module=VGSMultiSender&view=SettingsIndex&parent=Settings';
                            return false;
                        } else {
                            window.location = 'index.php?module=VGSMultiSender&view=SMTPindex';
                            return false;
                        }
                    } else {
                        app.helper.showErrorNotification({'message': response.message});
                    }
                } else {
                    alert(app.translate('JS_ERROR_LOCKING'));
                }
                progressIndicatorElement.progressIndicator({'mode': 'hide'});
            }).fail(function (response) {
                if (response.message) {
                    alert(response.message);
                } else {
                    alert(app.translate('JS_ERROR_LOCKING'));
                }
                progressIndicatorElement.progressIndicator({'mode': 'hide'});
            });

            progressIndicatorElement.progressIndicator({
                'mode': 'hide'
            });
        });
    },
    registerEvents: function () {
        this.hideUnusefulThings();
        this.saveEntry();
        this.deleteEntry();
        this.lockEntry();
    },
});

var instance;
jQuery(document).ready(function () {
    instance = new SMTP_Js();
    instance.registerEvents();
});

//for show left menu
//for settings views
if (typeof Settings_Vtiger_Index_Js !== 'undefined') {
    Settings_Vtiger_Index_Js('Settings_VGSMultiSender_SettingsIndex_Js', {}, {
        registerEvents: function () {
            this._super();
        }
    });
    Settings_Vtiger_Index_Js('Settings_VGSMultiSender_SettingsAddNew_Js', {}, {
        registerEvents: function () {
            this._super();
        }
    });
}
if (typeof Vtiger_Index_Js !== 'undefined') {
//for not settings views
    Vtiger_Index_Js('Settings_VGSMultiSender_SMTPindex_Js', {}, {
        registerEvents: function () {
            this._super();
        }
    });
    Vtiger_Index_Js('Settings_VGSMultiSender_SMTPAddnew_Js', {}, {
        registerEvents: function () {
            this._super();
        }
    });
}
