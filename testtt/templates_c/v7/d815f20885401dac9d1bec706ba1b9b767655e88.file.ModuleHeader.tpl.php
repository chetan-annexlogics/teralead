<?php /* Smarty version Smarty-3.1.7, created on 2024-05-24 07:10:53
         compiled from "/home/customer/www/crm3.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTDevKBView/ModuleHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:68052453566503d7d2af2a4-92632613%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd815f20885401dac9d1bec706ba1b9b767655e88' => 
    array (
      0 => '/home/customer/www/crm3.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTDevKBView/ModuleHeader.tpl',
      1 => 1716366912,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '68052453566503d7d2af2a4-92632613',
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
  'unifunc' => 'content_66503d7d2b76f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_66503d7d2b76f')) {function content_66503d7d2b76f($_smarty_tpl) {?>

<div class="col-sm-12 col-xs-12 module-action-bar clearfix coloredBorderTop" style="border-bottom: 1px solid #DDDDDD;"><div class="module-action-content clearfix"><div class="col-lg-5 col-md-5 module-breadcrumb module-breadcrumb-<?php echo $_REQUEST['view'];?>
 transitionsAllHalfSecond"><div class="dropdown"><button class="dropbtn"><span class="app-icon-list fa fa-cog"></span><span class="app-name textOverflowEllipsis"> Settings</span></button><div class="dropdown-content"><a class="pull-left"href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_PARENT_MODULE']->value;?>
&view=List&viewname=<?php echo $_smarty_tpl->tpl_vars['VIEWID']->value;?>
&goback=1"><b> <?php echo vtranslate('Back to listview','VTDevKBView');?>
</b></a><a class="pull-left" href="javascript:void(0);" id="btnConfig" onclick="VTDevKBView_Js.getSettingView('<?php echo $_smarty_tpl->tpl_vars['KANBAN_PARENT_MODULE']->value;?>
','VTDevKBView')"><b><?php echo vtranslate('Config Kanban','VTDevKBView');?>
</b></a></div></div></div><div class="pull-left" style="margin-top: 7px;"><div class="dropdown"><select class="select2" style="min-width: 200px;" id ="selectModule"><option value="Contacts" <?php if ($_REQUEST['source_module']=="Contacts"){?> selected<?php }?>>Form Leads</option><option value="PBXManager" <?php if ($_REQUEST['source_module']=="PBXManager"){?> selected<?php }?>>Call Leads</option></select></div></div><div class="col-lg-5 col-md-5 pull-right"></div><?php if ($_smarty_tpl->tpl_vars['FIELDS_INFO']->value!=null){?><script type="text/javascript">var uimeta = (function () {var fieldInfo = <?php echo $_smarty_tpl->tpl_vars['FIELDS_INFO']->value;?>
;return {field: {get: function (name, property) {if (name && property === undefined) {return fieldInfo[name];}if (name && property) {return fieldInfo[name][property]}},isMandatory: function (name) {if (fieldInfo[name]) {return fieldInfo[name].mandatory;}return false;},getType: function (name) {if (fieldInfo[name]) {return fieldInfo[name].type}return false;}},};})();</script><?php }?><script type="text/javascript">$("body").delegate("#selectModule", "change", function(){var selectedModule = $(this).val();var kbSourceModule = $("#kbSourceModule").val();if(selectedModule != kbSourceModule){window.location.href = "index.php?module=VTDevKBView&view=Index&source_module=" + selectedModule;}})</script>
<?php }} ?>