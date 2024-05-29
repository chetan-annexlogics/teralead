<div class="SMTPLicense">
    <div style="width: 90%;margin: auto;margin-top: 2%;">
        <input type="hidden" id="isvalid" value="{$IS_VALIDATED}">
        <div class="row-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="fieldLabel" >
                                    <label class="pull-right marginRight10px muted"><b>{vtranslate('License Id (write anything)', $QUALIFIED_MODULE)}</b></label>
                                </td>
                                <td class="fieldValue">
                                    <input type="text" id="licenseid" name="licenseid" value="{$LICENSEID}" class="inputField">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="manual-activation" style="display:none;">
                        <table class="table table-bordered" style="margin-top:2%;">
                            <tbody>
                                <tr>
                                    <td class="fieldLabel">
                                        <label class="pull-right marginRight10px muted"><b>{vtranslate('Activation Id', $QUALIFIED_MODULE)}</b></label>
                                    </td>
                                    <td class="fieldValue">
                                        <input type="text" id="activationid" name="activationid" class="inputField">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row-fluid" style="margin-top: 3%">
                    <div class="span12 clearfix" style="margin-top: 3%">
                        <span class="pull-right">
                            <button class="btn btn-success activateButton" id="activate" type="button"><strong>{vtranslate('Activate License', $QUALIFIED_MODULE)}</strong></button>
                            <button class="btn btn-success activateButton" id="deactivate" type="button"><strong>{vtranslate('Deactivate License', $QUALIFIED_MODULE)}</strong></button>
                            <a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="module_name" value="{$MODULE}">
    {/strip}
        <script type="text/javascript">
            jQuery('#js_strings').html('{Zend_Json::encode($JS_LANG)}');
        </script>
</div>
<link type='text/css' rel='stylesheet' href='layouts/v7/modules/VGSMultiSender/css/VGSMultiSender.css'>