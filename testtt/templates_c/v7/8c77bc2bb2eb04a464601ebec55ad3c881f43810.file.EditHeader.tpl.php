<?php /* Smarty version Smarty-3.1.7, created on 2023-10-12 08:40:34
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Reports/EditHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20869134426527b1022e7057-94135048%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8c77bc2bb2eb04a464601ebec55ad3c881f43810' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Reports/EditHeader.tpl',
      1 => 1627027149,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20869134426527b1022e7057-94135048',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'LABELS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6527b1022ef86',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6527b1022ef86')) {function content_6527b1022ef86($_smarty_tpl) {?>
<div class="editContainer" style="padding-left: 2%;padding-right: 2%"><div class="row"><?php $_smarty_tpl->tpl_vars['LABELS'] = new Smarty_variable(array("step1"=>"LBL_REPORT_DETAILS","step2"=>"LBL_SELECT_COLUMNS","step3"=>"LBL_FILTERS"), null, 0);?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("BreadCrumbs.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('ACTIVESTEP'=>1,'BREADCRUMB_LABELS'=>$_smarty_tpl->tpl_vars['LABELS']->value,'MODULE'=>$_smarty_tpl->tpl_vars['MODULE']->value), 0);?>
</div><div class="clearfix"></div><?php }} ?>