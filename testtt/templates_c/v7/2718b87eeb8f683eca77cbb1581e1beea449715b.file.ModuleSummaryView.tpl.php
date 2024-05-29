<?php /* Smarty version Smarty-3.1.7, created on 2023-11-24 15:10:51
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/CRMManager/ModuleSummaryView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16702778306560bcfb4330e2-52471477%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2718b87eeb8f683eca77cbb1581e1beea449715b' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/CRMManager/ModuleSummaryView.tpl',
      1 => 1700838451,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16702778306560bcfb4330e2-52471477',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6560bcfb44c1e',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6560bcfb44c1e')) {function content_6560bcfb44c1e($_smarty_tpl) {?>
<div class="recordDetails"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SummaryViewContents.tpl',$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><?php }} ?>