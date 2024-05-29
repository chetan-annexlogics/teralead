/**
 * VGS Multi FROM Address Module
 *
 *
 * @package        VGSMultiSender Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */
jQuery(document).ready(function () {
   
    if (jQuery('[name="module"]').val() == 'Emails') {
        var options = "<option selected>--</option>";
        var params = {
            module: 'VGSMultiSender',
            action: 'SaveSMTPConfig',
            mode: 'getUserSMTPs',
        };
        AppConnector.request(params).then(
                function (data) {
                    if (data.success && jQuery('.fromEmailField').length == 0) {
                        var response = eval(data.result.smtps);
                        var i = 0;
                        $.each( response, function( index, value ){
                            if(i == 0){
                                options += "<option selected value='"+ index +"'>" + value + "</option>";
                            }else{
                                options += "<option value='"+ index +"'>" + value + "</option>";
                            }
                            
                            i++;
                            
                        });
                        
                        jQuery('<div class="row-fluid fromEmailField padding10"><span class="span8"><span class="row-fluid"><span class="span2">From<span class="redColor">*</span></span><span class="span9"><select class="chzn-select" name="chooseFromEmail">' + options + '</select></span></span></span></div>').insertBefore('div.toEmailField');
                        jQuery(".chzn-select").chosen();
                        jQuery('input[name="module"]').val('VGSMultiSender');
                    }
                },
                function (error, err) {

                }
        );
    }else if (jQuery('[name="module"]').val() == 'Users' && jQuery('#view').val() == 'PreferenceDetail') {
        if(jQuery('#vgs-multismtp').length == 0){
            jQuery('.detailViewButtoncontainer > .btn-toolbar').append('<div id="vgs-multismtp" class="btn-group"><button class="btn" onclick="window.location.href=\'index.php?module=VGSMultiSender&view=SMTPindex\'"><strong>SMTP Config</strong></button></div>')
        }
    }    

});
