<?php /* Smarty version Smarty-3.1.7, created on 2024-05-30 05:29:10
         compiled from "/var/www/html/teralead/includes/runtime/../../layouts/v7/modules/VTDevKBView/ModuleHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3452226326656f8d50b5621-57026796%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2ce99418eb2970b5b428ef24c7f81d691cc3bc49' => 
    array (
      0 => '/var/www/html/teralead/includes/runtime/../../layouts/v7/modules/VTDevKBView/ModuleHeader.tpl',
      1 => 1716990228,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3452226326656f8d50b5621-57026796',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6656f8d50bab0',
  'variables' => 
  array (
    'KANBAN_PARENT_MODULE' => 0,
    'VIEWID' => 0,
    'FIELDS_INFO' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6656f8d50bab0')) {function content_6656f8d50bab0($_smarty_tpl) {?>

<div class="col-sm-12 col-xs-12 module-action-bar clearfix coloredBorderTop" style="border-bottom: 1px solid #DDDDDD;"><div class="module-action-content clearfix"><div class="col-lg-5 col-md-5 module-breadcrumb module-breadcrumb-<?php echo $_REQUEST['view'];?>
 transitionsAllHalfSecond"><div class="dropdown"><?php if ($_REQUEST['source_module']!="AllLeads"){?><button class="dropbtn"><span class="app-icon-list fa fa-cog"></span><span class="app-name textOverflowEllipsis"> Settings</span></button><?php }?><div class="dropdown-content"><a class="pull-left"href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_PARENT_MODULE']->value;?>
&view=List&viewname=<?php echo $_smarty_tpl->tpl_vars['VIEWID']->value;?>
&goback=1"><b> <?php echo vtranslate('Back to listview','VTDevKBView');?>
</b></a><a class="pull-left" href="javascript:void(0);" id="btnConfig" onclick="VTDevKBView_Js.getSettingView('<?php echo $_smarty_tpl->tpl_vars['KANBAN_PARENT_MODULE']->value;?>
','VTDevKBView')"><b><?php echo vtranslate('Config Kanban','VTDevKBView');?>
</b></a></div></div></div><div class="pull-left" style="margin-top: 7px;"><div class="dropdown"><select class="select2" style="min-width: 200px;" id ="selectModule"><option value="Contacts" <?php if ($_REQUEST['source_module']=="Contacts"){?> selected<?php }?>>Form Leads</option><option value="PBXManager" <?php if ($_REQUEST['source_module']=="PBXManager"){?> selected<?php }?>>Call Leads</option><option value="AllLeads" <?php if ($_REQUEST['source_module']=="AllLeads"){?> selected<?php }?>>All Leads</option></select></div></div><div class="col-lg-5 col-md-5 pull-right"></div><?php if ($_smarty_tpl->tpl_vars['FIELDS_INFO']->value!=null){?><script type="text/javascript">var uimeta = (function () {var fieldInfo = <?php echo $_smarty_tpl->tpl_vars['FIELDS_INFO']->value;?>
;return {field: {get: function (name, property) {if (name && property === undefined) {return fieldInfo[name];}if (name && property) {return fieldInfo[name][property]}},isMandatory: function (name) {if (fieldInfo[name]) {return fieldInfo[name].mandatory;}return false;},getType: function (name) {if (fieldInfo[name]) {return fieldInfo[name].type}return false;}},};})();</script><?php }?><script type="text/javascript">$("body").delegate("#selectModule", "change", function(){var selectedModule = $(this).val();var kbSourceModule = $("#kbSourceModule").val();if(selectedModule != kbSourceModule){window.location.href = "index.php?module=VTDevKBView&view=Index&source_module=" + selectedModule;}})</script>
<?php }} ?>