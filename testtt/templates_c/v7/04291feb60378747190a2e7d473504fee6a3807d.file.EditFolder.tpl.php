<?php /* Smarty version Smarty-3.1.7, created on 2024-01-04 06:46:07
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Reports/EditFolder.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8112367126596542f546de2-47397644%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '04291feb60378747190a2e7d473504fee6a3807d' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Reports/EditFolder.tpl',
      1 => 1627027149,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8112367126596542f546de2-47397644',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FOLDER_MODEL' => 0,
    'MODULE' => 0,
    'FOLDER_ID' => 0,
    'FOLDER_NAME' => 0,
    'HEADER_TITLE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6596542f5636e',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6596542f5636e')) {function content_6596542f5636e($_smarty_tpl) {?>
<div id="addFolderContainer" class="modal-dialog" style='min-width:350px;'><div class='modal-content'><?php $_smarty_tpl->tpl_vars['FOLDER_ID'] = new Smarty_variable($_smarty_tpl->tpl_vars['FOLDER_MODEL']->value->getId(), null, 0);?><?php ob_start();?><?php echo Vtiger_Util_Helper::tosafeHTML(vtranslate($_smarty_tpl->tpl_vars['FOLDER_MODEL']->value->getName(),$_smarty_tpl->tpl_vars['MODULE']->value));?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['FOLDER_NAME'] = new Smarty_variable($_tmp1, null, 0);?><?php ob_start();?><?php echo vtranslate('LBL_ADD_NEW_FOLDER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['HEADER_TITLE'] = new Smarty_variable($_tmp2, null, 0);?><?php if ($_smarty_tpl->tpl_vars['FOLDER_ID']->value){?><?php ob_start();?><?php echo vtranslate('LBL_EDIT_FOLDER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['HEADER_TITLE'] = new Smarty_variable($_tmp3.": ".($_smarty_tpl->tpl_vars['FOLDER_NAME']->value), null, 0);?><?php }?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>$_smarty_tpl->tpl_vars['HEADER_TITLE']->value), 0);?>
<form class="form-horizontal contentsBackground" id="addFolder" method="post" action="index.php"><input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
" /><input type="hidden" name="action" value="Folder" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="folderid" value="<?php echo $_smarty_tpl->tpl_vars['FOLDER_MODEL']->value->getId();?>
" /><div class="modal-body"><div class="form-group"><label for="foldername" class="col-sm-4 control-label"><?php echo vtranslate('LBL_FOLDER_NAME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<span class="redColor">*</span></label><div class="col-sm-7"><input id="foldername" name="foldername" class="form-control col-lg-12" data-rule-required="true" type="text" value="<?php echo $_smarty_tpl->tpl_vars['FOLDER_NAME']->value;?>
"/></div></div><div class="form-group"><label for="description" class="col-sm-4 control-label"><?php echo vtranslate('LBL_FOLDER_DESCRIPTION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label><div class="col-sm-7"><textarea name="description" class="form-control col-sm-12" rows="3" placeholder="<?php echo vtranslate('LBL_WRITE_YOUR_DESCRIPTION_HERE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><?php echo vtranslate($_smarty_tpl->tpl_vars['FOLDER_MODEL']->value->getDescription(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
</textarea></div></div></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('ModalFooter.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</form></div></div><?php }} ?>