<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 10:58:20
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/MyAccount.tpl" */ ?>
<?php /*%%SmartyHeaderCode:185202142764cb884cc97e42-07299640%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a1f22f68e2fc5a506c3adf12455ee756510f3690' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/MyAccount.tpl',
      1 => 1691057505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185202142764cb884cc97e42-07299640',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CUSTOMERDATA' => 0,
    'CUSTOMER_STATUS' => 0,
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb884cca57a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb884cca57a')) {function content_64cb884cca57a($_smarty_tpl) {?><div class="modal-content">
    <div class="modal-header contentsBackground">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class='fa fa-close'></span></button><h4><?php echo vtranslate('LBL_MY_ACCOUNT','VTEStore');?>
</h4>
    </div>
    <form class="form-horizontal MyAccountForm">
        <input type="hidden" name="module" value="VTEStore"/>
        <input type="hidden" name="parent" value="Settings"/>
        <input type="hidden" name="action" value="ActionAjax"/>
        <input type="hidden" name="mode" value="updateMyAccount"/>
        <input type="hidden" name="customerid" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['id'];?>
"/>
        <div class="modal-body">
            <div class="control-group">
                <span><strong><?php echo vtranslate('LBL_ACCOUNT_DETAILS','VTEStore');?>
</strong></span>
            </div>
            <div class="control-group">
                <label class="col-md-4 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_USERNAME','VTEStore');?>
</label>
                <div class="col-md-8"><input disabled="disabled" type="text" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['username'];?>
" style="max-width: 400px;" name="username" aria-required="true" data-rule-required="true" /></div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <label class="col-md-4 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_EMAIL_ADDRESS','VTEStore');?>
</label>
                <div class="col-md-8"><input type="text" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['email'];?>
" style="max-width: 400px;" name="emailAddress" aria-required="true" data-rule-required="true" /></div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <label class="col-md-4 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_FIRST_NAME','VTEStore');?>
</label>
                <div class="col-md-8"><input type="text" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['firstname'];?>
" style="max-width: 400px;" name="firstName" aria-required="true" data-rule-required="true" /></div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <label class="col-md-4 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_LAST_NAME','VTEStore');?>
</label>
                <div class="col-md-8"><input type="text" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['lastname'];?>
" style="max-width: 400px;" name="lastName" aria-required="true" data-rule-required="true" /></div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <label class="col-md-4 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_PHONE','VTEStore');?>
</label>
                <div class="col-md-8"><input type="text" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['phone'];?>
" style="max-width: 400px;" name="phone" aria-required="true" data-rule-required="true" /></div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <label class="col-md-4 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_COMPANY_NAME','VTEStore');?>
</label>
                <div class="col-md-8"><input type="text" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['companyname'];?>
" style="max-width: 400px;" name="companyName" aria-required="true" data-rule-required="true" /></div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <div class="col-md-12">
                    <hr />
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <div class="col-md-12"><span><strong><?php echo vtranslate('LBL_SUBCRIPTION','VTEStore');?>
</strong></span></div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <label class="col-md-4 control-label"><?php echo vtranslate('LBL_URL','VTEStore');?>
</label>
                <div class="col-md-8"><input disabled="disabled" type="text" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['vtiger_url'];?>
" style="max-width: 400px;" name="vtiger_url" aria-required="true" data-rule-required="true" /></div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <label class="col-md-4 control-label"><?php echo vtranslate('LBL_STATUS','VTEStore');?>
</label>
                <div class="col-md-8">
                    <?php if ($_smarty_tpl->tpl_vars['CUSTOMER_STATUS']->value=='ok'){?>
                        <a href="https://www.vtexperts.com/subscription-status/" target="_blank"> <?php echo vtranslate('LBL_ACTIVE','VTEStore');?>
 - <?php echo vtranslate('Expires in','VTEStore');?>
 <?php echo $_smarty_tpl->tpl_vars['CUSTOMERDATA']->value['remain_date'];?>
 <?php echo vtranslate('days','VTEStore');?>
 <img width="12" height="12" border="0" alt="<?php echo vtranslate('has_subscription_tooltip','VTEStore');?>
" title="<?php echo vtranslate('has_subscription_tooltip','VTEStore');?>
" src="layouts/v7/modules/VTEStore/resources/images/tooltip.png"></a>
                    <?php }else{ ?>
                        <a href="https://www.vtexperts.com/subscription-status/" target="_blank"><?php echo vtranslate('LBL_TRIAL','VTEStore');?>
 <img width="12" height="12" border="0" alt="<?php echo vtranslate('no_subscription_tooltip','VTEStore');?>
" title="<?php echo vtranslate('no_subscription_tooltip','VTEStore');?>
"src="layouts/v7/modules/VTEStore/resources/images/tooltip.png"></a>
                    <?php }?>
                    &nbsp;<button class="btn btn-success" type="button" id="btnRefresh" name="btnRefresh"><strong><?php echo vtranslate('LBL_REFRESH','VTEStore');?>
</strong></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="control-group">
                <div class="col-md-4"></div>
                <div class="col-md-8">
                    <button id="ManageSubscription" name="ManageSubscription" class="btn btn-warning ManageSubscription" type="button"><?php echo vtranslate('LBL_MANAGE_SUBSCRIPTION','VTEStore');?>
</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-md-2 pull-left">
                    <a id="logoutVTEStore" class="btn btn-primary"><?php echo vtranslate('LBL_LOGOUT','VTEStore');?>
</a>
                </div>
                <div class="col-md-10 text-right">
                    <button class="btn btn-success" type="submit" name="saveButton"><strong><?php echo vtranslate('LBL_SAVE','VTEStore');?>
</strong></button>
                    <a class="cancelLink" type="reset" data-dismiss="modal"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                </div>
            </div>
        </div>
    </form>
</div><?php }} ?>