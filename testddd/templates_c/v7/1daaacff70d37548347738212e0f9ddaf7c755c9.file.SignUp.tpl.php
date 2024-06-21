<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 10:58:20
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/SignUp.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173684959164cb884ccafb12-44966387%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1daaacff70d37548347738212e0f9ddaf7c755c9' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/SignUp.tpl',
      1 => 1691057505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173684959164cb884ccafb12-44966387',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'VTIGER_URL' => 0,
    'HATCHBUCK_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb884ccb5ba',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb884ccb5ba')) {function content_64cb884ccb5ba($_smarty_tpl) {?><div class="modal-content">
    <div class="modal-header contentsBackground">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class='fa fa-close'></span></button>
        <h4><?php echo vtranslate('LBL_SIGN_UP_TO_VTE_STORE','VTEStore');?>
</h4>
    </div>
    <form class="form-horizontal signUpForm">
        <input type="hidden" name="module" value="VTEStore"/>
        <input type="hidden" name="parent" value="Settings"/>
        <input type="hidden" name="action" value="ActionAjax"/>
        <input type="hidden" name="userAction" value="signup"/>
        <input type="hidden" name="mode" value="registerAccount"/>
        <input type="hidden" name="vtiger_url" value="<?php echo $_smarty_tpl->tpl_vars['VTIGER_URL']->value;?>
"/>

        <div class="modal-body">
            <div class="row">
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_EMAIL_ADDRESS','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="text" class="inputElement" style="max-width: 210px;" name="emailAddress" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_FIRST_NAME','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="text" class="inputElement" style="max-width: 210px;" name="firstName" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_LAST_NAME','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="text" class="inputElement" style="max-width: 210px;" name="lastName" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_PHONE','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="text" class="inputElement" style="max-width: 210px;" name="phone" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_COMPANY_NAME','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="text" class="inputElement" style="max-width: 210px;" name="companyName" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_USERNAME','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="text" class="inputElement" style="max-width: 210px;" id="signupUsername" name="signupUsername" aria-required="true" data-rule-required="true" /><span id="UsernameAlreadyExists" style="display: none; color: red; ">&nbsp;&nbsp;<?php echo vtranslate('Username already exists!','VTEStore');?>
</span></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_PASSWORD','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="password" class="inputElement" style="max-width: 210px;" name="password" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_CONFIRM_PASSWORD','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="password" class="inputElement" style="max-width: 210px;" name="confirmPassword" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-9"><span><input type="checkbox" name="savePassword" value="1" checked> &nbsp; &nbsp;<?php echo vtranslate('LBL_REMEMBER_ME','VTEStore');?>
</span></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row-fluid">
                <div class="pull-left" style="width: 350px; color: red"><?php echo vtranslate('Once you click Register','VTEStore');?>
</div>
                <div class="pull-right">
                    <div class="pull-right cancelLinkContainer" style="margin-top:0px;">
                        <a class="cancelLink" type="reset"data-dismiss="modal"><?php echo vtranslate('LBL_CANCEL','VTEStore');?>
</a>
                    </div>
                    <button class="btn btn-success" type="submit" id="btnSaveButton" name="saveButton" disabled><strong><?php echo vtranslate('LBL_REGISTER','VTEStore');?>
</strong></button>
                </div>
            </div>
        </div>
    </form>
    <div style="display: none"><iframe src="<?php echo $_smarty_tpl->tpl_vars['HATCHBUCK_URL']->value;?>
/modules/VTEStore/hatchbuck.form.html" width="99%" height="600px" id="hatchbuckForm" style="display: none"></iframe></div>
</div><?php }} ?>