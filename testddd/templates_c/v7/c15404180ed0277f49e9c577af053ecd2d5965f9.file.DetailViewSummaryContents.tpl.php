<?php /* Smarty version Smarty-3.1.7, created on 2023-11-24 15:10:51
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/CRMManager/DetailViewSummaryContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:151687616560bcfb479684-09362426%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c15404180ed0277f49e9c577af053ecd2d5965f9' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/CRMManager/DetailViewSummaryContents.tpl',
      1 => 1700838451,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '151687616560bcfb479684-09362426',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6560bcfb47b2d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6560bcfb47b2d')) {function content_6560bcfb47b2d($_smarty_tpl) {?>
<form id="detailView" method="POST"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SummaryViewWidgets.tpl',$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</form><?php }} ?>