<?php /* Smarty version Smarty-3.1.7, created on 2023-08-18 10:59:33
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/Step2.tpl" */ ?>
<?php /*%%SmartyHeaderCode:203911060664df4f158402a1-47682259%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a0cc9a3ea1c3a576fa31aa2c620808d9e64397c4' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/Step2.tpl',
      1 => 1691060297,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203911060664df4f158402a1-47682259',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'SITE_URL' => 0,
    'VTELICENSE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64df4f15887fa',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64df4f15887fa')) {function content_64df4f15887fa($_smarty_tpl) {?>
<div class="installationContents" style="border:1px solid #ccc;padding:2%;"><form name="activateLicenseForm" action="index.php" method="post" id="installation_step2" class="form-horizontal"><input type="hidden" class="step" value="2" /><div class="row"><label><strong><?php echo vtranslate('LBL_WELCOME',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 <?php echo vtranslate('MODULE_LBL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 <?php echo vtranslate('LBL_INSTALLATION_WIZARD',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></label></div><div class="clearfix">&nbsp;</div><div class="row"><div><span><?php echo vtranslate('LBL_YOU_ARE_REQUIRED_VALIDATE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></div></div><div class="row" style="margin-bottom:10px; margin-top: 5px"><span class="col-lg-1"><strong><?php echo vtranslate('LBL_VTIGER_URL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></span><span class="col-lg-4"><?php echo $_smarty_tpl->tpl_vars['SITE_URL']->value;?>
</span></div><div class="row" style="margin-bottom:10px; margin-top: 5px"><span class="col-lg-1"><span class="redColor">*</span><strong><?php echo vtranslate('LBL_LICENSE_KEY',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></span><span class="col-lg-4"><input type="text" id="license_key" name="license_key" value="" data-validation-engine="validate[required]" class="inputElement" name="summary"></span></div><?php if ($_smarty_tpl->tpl_vars['VTELICENSE']->value->result=='bad'||$_smarty_tpl->tpl_vars['VTELICENSE']->value->result=='invalid'){?><div class="alert alert-danger" id="error_message"><?php echo $_smarty_tpl->tpl_vars['VTELICENSE']->value->message;?>
</div><?php }?><div class="row"><div><span><?php echo vtranslate('LBL_HAVE_TROUBLE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 <?php echo vtranslate('LBL_CONTACT_US',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></div></div><div class="row"><ul style="padding-left: 10px;"><li><?php echo vtranslate('LBL_EMAIL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
: &nbsp;&nbsp;<a style="color: #0088cc; text-decoration:none;" href="mailto:Support@VTExperts.com">Support@VTExperts.com</a></li><li><?php echo vtranslate('LBL_PHONE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
: &nbsp;&nbsp;<span>+1 (818) 495-5557</span></li><li><?php echo vtranslate('LBL_CHAT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
: &nbsp;&nbsp;<?php echo vtranslate('LBL_AVAILABLE_ON',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 <a style="color: #0088cc; text-decoration:none;" href="http://www.vtexperts.com" target="_blank">http://www.VTExperts.com</a></li></ul></div><div class="row"><center><button class="btn btn-success" name="btnActivate" type="button"><strong><?php echo vtranslate('LBL_ACTIVATE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-info" name="btnOrder" type="button" onclick="window.open('http://www.vtexperts.com/extension/vtiger-duplicate-record-check-merge/')"><strong><?php echo vtranslate('LBL_ORDER_NOW',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></button></center></div></div><div class="clearfix"></div></form></div><?php }} ?>