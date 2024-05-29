<?php /* Smarty version Smarty-3.1.7, created on 2024-05-27 12:28:58
         compiled from "/var/www/teralead/includes/runtime/../../layouts/v7/modules/VTDevKBView/VtdevStoreRequire.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7230147966547c8adb6050-53473480%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e3a4be9ff36137858d64aedaede0f6e6169b34bc' => 
    array (
      0 => '/var/www/teralead/includes/runtime/../../layouts/v7/modules/VTDevKBView/VtdevStoreRequire.tpl',
      1 => 1716539865,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7230147966547c8adb6050-53473480',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'VTDEVLICENSE' => 0,
    'SITE_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_66547c8add777',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_66547c8add777')) {function content_66547c8add777($_smarty_tpl) {?>
<div class="installationContents" style="border:1px solid #ccc;padding:2%;"><form name="activateLicenseForm" action="index.php" method="post" id="installation_step2" class="form-horizontal"><input type="hidden" class="step" value="2" /><div class="row"><label><strong><?php echo vtranslate('Thank you for choosing the VTDevKBView extension',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></label></div><div class="clearfix">&nbsp;</div><div class="row"><div><span><?php if ($_smarty_tpl->tpl_vars['VTDEVLICENSE']->value['notInstalled']){?><?php echo vtranslate('Please download then install VTEDEV store to use all our extensions',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
<?php }else{ ?><?php echo vtranslate('Please active VTEDEV store to use all our extensions',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
<?php }?></span></div></div><div class="row" style="margin-bottom:10px; margin-top: 5px"><span class="col-lg-1"><strong><?php echo vtranslate('LBL_VTIGER_URL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></span><span class="col-lg-4"><?php echo $_smarty_tpl->tpl_vars['SITE_URL']->value;?>
</span></div><?php if (!$_smarty_tpl->tpl_vars['VTDEVLICENSE']->value['valid']){?><div class="alert alert-danger" id="error_message"><?php echo $_smarty_tpl->tpl_vars['VTDEVLICENSE']->value['message'];?>
</div><?php }?><div class="row"><div><span><?php echo vtranslate('if you encounter any problems while installing extensions,',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 <?php echo vtranslate('please Contact Us!',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></div></div><div class="row"><ul style="padding-left: 10px;"><li style="list-style-type: none;"><i class="fa fa-envelope fa-2x"></i>&nbsp <a style="color: #0088cc; text-decoration:none;" href="mailto:Support@vtdevsolutions.com">Support@vtdevsolutions.com</a></li><li style="list-style-type: none;"><i class="fa fa-phone fa-2x"></i>&nbsp <span>+1 (209) 437-4542</span></li><li style="list-style-type: none;"><i class="fa fa-skype fa-2x"></i>&nbsp <a style="color: #0088cc; text-decoration:none;" href="skype:profile_name?vtdev_support"><?php echo vtranslate('Chat with us on Skype',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</a></li><li style="list-style-type: none;"><i class="fa fa-whatsapp fa-2x"></i>&nbsp <a style="color: #0088cc; text-decoration:none;" href="whatsapp://send?text=Hello World!&phone=+12094374542"><?php echo vtranslate('Chat with us on WhatsApp',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</a></li></ul></div><div class="row"><center><?php if ($_smarty_tpl->tpl_vars['VTDEVLICENSE']->value['notInstalled']){?><a href="https://vtdevsolutions.com/Extensions/Stable_Zip/VTDEVStore.zip" download>Download VTDEVStore extension now</a><?php }else{ ?><a href="index.php?module=VTDEVStore&parent=Settings&view=Settings">Active VTDEVStore extension</a><?php }?></center></div></div><div class="clearfix"></div></form></div><?php }} ?>