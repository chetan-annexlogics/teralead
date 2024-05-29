<?php /* Smarty version Smarty-3.1.7, created on 2023-08-21 10:11:07
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/RegenerateLicense.tpl" */ ?>
<?php /*%%SmartyHeaderCode:161317617164e3383b0f24b6-33365412%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1ec38be3f04cce7039cb9899c8ccf1365094dbfe' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/RegenerateLicense.tpl',
      1 => 1691057505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '161317617164e3383b0f24b6-33365412',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MESSAGE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64e3383b10432',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64e3383b10432')) {function content_64e3383b10432($_smarty_tpl) {?>
<div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header contentsBackground"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class='fa fa-close'></span></button><h4 style="color:white;"><?php echo vtranslate('LBL_REGENERATE_LICENSE','VTEStore');?>
</h4></div><div class="modal-body" id="installationLog"><div class="row-fluid"><span class="font-x-x-large"><?php echo $_smarty_tpl->tpl_vars['MESSAGE']->value;?>
</span><br><br></div></div><div class="modal-footer"><span class="pull-right"><button class="btn btn-success" id="importCompleted" onclick="app.hideModalWindow();"><?php echo vtranslate('LBL_OK','VTEStore');?>
</button></span></div></div></div><?php }} ?>