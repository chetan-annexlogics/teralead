<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 10:58:25
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/ModuleHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7114855664cb88518423b2-22932944%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8d5fbd56134be924d67a8ff693a9599b030ed5ab' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/ModuleHeader.tpl',
      1 => 1691060297,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7114855664cb88518423b2-22932944',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'KANBAN_PARENT_MODULE' => 0,
    'VIEWID' => 0,
    'FIELDS_INFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb885184822',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb885184822')) {function content_64cb885184822($_smarty_tpl) {?>

<div class="col-sm-12 col-xs-12 module-action-bar clearfix coloredBorderTop" style="border-bottom: 1px solid #DDDDDD;"><div class="module-action-content clearfix"><div class="col-lg-7 col-md-7 module-breadcrumb module-breadcrumb-<?php echo $_REQUEST['view'];?>
 transitionsAllHalfSecond"><a class="btn btn-default module-buttons pull-left"  href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_PARENT_MODULE']->value;?>
&view=List&viewname=<?php echo $_smarty_tpl->tpl_vars['VIEWID']->value;?>
&goback=1"><b> <?php echo vtranslate('LBL_GO_BACK_TO_LISTVIEW','KanbanView');?>
</b></a><button class="btn btn-default module-buttons pull-left" id="btnConfig" onclick="KanbanView_Js.getSettingView('<?php echo $_smarty_tpl->tpl_vars['KANBAN_PARENT_MODULE']->value;?>
','KanbanView')"><b><?php echo vtranslate('LBL_CONFIGURE_KANBAN_VIEW','KanbanView');?>
</b></button></div><div class="col-lg-5 col-md-5 pull-right"></div></div></div><?php if ($_smarty_tpl->tpl_vars['FIELDS_INFO']->value!=null){?><script type="text/javascript">var uimeta = (function () {var fieldInfo = <?php echo $_smarty_tpl->tpl_vars['FIELDS_INFO']->value;?>
;return {field: {get: function (name, property) {if (name && property === undefined) {return fieldInfo[name];}if (name && property) {return fieldInfo[name][property]}},isMandatory: function (name) {if (fieldInfo[name]) {return fieldInfo[name].mandatory;}return false;},getType: function (name) {if (fieldInfo[name]) {return fieldInfo[name].type}return false;}},};})();</script><?php }?>
<?php }} ?>