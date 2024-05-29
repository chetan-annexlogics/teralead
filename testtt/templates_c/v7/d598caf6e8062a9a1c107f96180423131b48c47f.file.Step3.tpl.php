<?php /* Smarty version Smarty-3.1.7, created on 2024-01-22 21:04:38
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEWEBHOOKS/Step3.tpl" */ ?>
<?php /*%%SmartyHeaderCode:161055666265aed8668f6888-58715493%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd598caf6e8062a9a1c107f96180423131b48c47f' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEWEBHOOKS/Step3.tpl',
      1 => 1705957470,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '161055666265aed8668f6888-58715493',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65aed866902c1',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65aed866902c1')) {function content_65aed866902c1($_smarty_tpl) {?>
<div class="installationContents" style="border:1px solid #ccc;padding:2%;"><form name="EditWorkflow" action="index.php" method="post" id="installation_step3" class="form-horizontal"><input type="hidden" class="step" value="3" /><div class="row"><label><h3><?php echo vtranslate('LBL_INSTALLATION_COMPLETED',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</h3></label></div><div class="clearfix">&nbsp;</div><div class="row"><div><span>The <?php echo vtranslate($_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value,$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 <?php echo vtranslate('LBL_HAS_BEEN_SUCCESSFULLY',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></div></div><div class="row"><div><span><?php echo vtranslate('LBL_MORE_EXTENSIONS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 - <a style="color: #0088cc; text-decoration:none;" href="http://www.vtexperts.com" target="_blank">http://www.VTExperts.com</a></span></div></div><div class="row"><div><span><?php echo vtranslate('LBL_FEEL_FREE_CONTACT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></div></div><div class="clearfix">&nbsp;</div><div class="row"><ul style="padding-left: 10px;"><li><?php echo vtranslate('LBL_EMAIL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
: &nbsp;&nbsp;<a style="color: #0088cc; text-decoration:none;" href="mailto:Support@VTExperts.com">Support@VTExperts.com</a></li><li><?php echo vtranslate('LBL_PHONE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
: &nbsp;&nbsp;<span>+1 (818) 495-5557</span></li><li><?php echo vtranslate('LBL_CHAT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
: &nbsp;&nbsp;<?php echo vtranslate('LBL_AVAILABLE_ON',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 <a style="color: #0088cc; text-decoration:none;" href="http://www.vtexperts.com" target="_blank">http://www.VTExperts.com</a></li></ul></div><div class="row" style="text-align: center;"><button class="btn btn-success" name="btnFinish" type="button"><strong><?php echo vtranslate('LBL_FINISH',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></button></div><div class="clearfix"></div></form></div><?php }} ?>