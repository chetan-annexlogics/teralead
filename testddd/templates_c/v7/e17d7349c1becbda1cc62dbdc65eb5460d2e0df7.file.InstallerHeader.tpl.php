<?php /* Smarty version Smarty-3.1.7, created on 2024-01-22 21:04:38
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEWEBHOOKS/InstallerHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:135922276465aed8668af0b4-32365501%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e17d7349c1becbda1cc62dbdc65eb5460d2e0df7' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEWEBHOOKS/InstallerHeader.tpl',
      1 => 1705957470,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '135922276465aed8668af0b4-32365501',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'LABELS' => 0,
    'QUALIFIED_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65aed8668e6bb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65aed8668e6bb')) {function content_65aed8668e6bb($_smarty_tpl) {?>
<div class="editContainer" style="padding-left: 2%;padding-right: 2%"><div class="row"><?php $_smarty_tpl->tpl_vars['LABELS'] = new Smarty_variable(array("step1"=>"LBL_REQUIREMENTS","step2"=>"LBL_LICENSING","step3"=>"LBL_COMPLETE"), null, 0);?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("BreadCrumbs.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('ACTIVESTEP'=>1,'BREADCRUMB_LABELS'=>$_smarty_tpl->tpl_vars['LABELS']->value,'MODULE'=>$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value), 0);?>
</div><div class="clearfix"></div><?php }} ?>