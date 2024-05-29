<?php /* Smarty version Smarty-3.1.7, created on 2024-01-03 13:51:54
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ModuleHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8563391266595667aa45c94-49073674%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '053ea987eab85efa540c53ef396f8123f8775cbc' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ModuleHeader.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8563391266595667aa45c94-49073674',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'MODULE_MODEL' => 0,
    'DEFAULT_FILTER_ID' => 0,
    'CVURL' => 0,
    'DEFAULT_FILTER_URL' => 0,
    'VIEW' => 0,
    'REPORT_NAME' => 0,
    'VIEWNAME' => 0,
    'FOLDERS' => 0,
    'FOLDER' => 0,
    'FOLDERNAME' => 0,
    'DASHBOARD_BOARDS' => 0,
    'BOARD_DATA' => 0,
    'BOARDID' => 0,
    'LISTVIEW_LINKS' => 0,
    'LISTVIEW_BASICACTION' => 0,
    'childLinks' => 0,
    'childLink' => 0,
    'USER_NAME' => 0,
    'BLOCK_LINK' => 0,
    'ICON_CLASS' => 0,
    'FIELDS_INFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6595667aa8874',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6595667aa8874')) {function content_6595667aa8874($_smarty_tpl) {?>

<div class="col-sm-12 col-xs-12 module-action-bar clearfix coloredBorderTop"><div class="module-action-content clearfix"><span class="col-lg-7 col-md-7"><span><?php $_smarty_tpl->tpl_vars['MODULE_MODEL'] = new Smarty_variable(Vtiger_Module_Model::getInstance($_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['DEFAULT_FILTER_ID'] = new Smarty_variable($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getDefaultCustomFilter(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['DEFAULT_FILTER_ID']->value){?><?php $_smarty_tpl->tpl_vars['CVURL'] = new Smarty_variable(("&viewname=").($_smarty_tpl->tpl_vars['DEFAULT_FILTER_ID']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['DEFAULT_FILTER_URL'] = new Smarty_variable(($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getListViewUrl()).($_smarty_tpl->tpl_vars['CVURL']->value), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['DEFAULT_FILTER_URL'] = new Smarty_variable($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getListViewUrlWithAllFilter(), null, 0);?><?php }?><a title="<?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
" href='<?php echo $_smarty_tpl->tpl_vars['DEFAULT_FILTER_URL']->value;?>
'><h4 class="module-title pull-left">&nbsp;<?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;</h4></a></span><span><p class="current-filter-name pull-left">&nbsp;<span class="fa fa-angle-right" aria-hidden="true"></span>&nbsp;<?php if ($_smarty_tpl->tpl_vars['VIEW']->value=='Detail'||$_smarty_tpl->tpl_vars['VIEW']->value=='ChartDetail'){?><?php echo $_smarty_tpl->tpl_vars['REPORT_NAME']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['VIEW']->value;?>
<?php }?>&nbsp;</p></span><?php if ($_smarty_tpl->tpl_vars['VIEWNAME']->value){?><?php if ($_smarty_tpl->tpl_vars['VIEWNAME']->value!='All'){?><?php  $_smarty_tpl->tpl_vars['FOLDER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FOLDER']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FOLDERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FOLDER']->key => $_smarty_tpl->tpl_vars['FOLDER']->value){
$_smarty_tpl->tpl_vars['FOLDER']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['FOLDER']->value->getId()==$_smarty_tpl->tpl_vars['VIEWNAME']->value){?><?php $_smarty_tpl->tpl_vars['FOLDERNAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['FOLDER']->value->getName(), null, 0);?><?php break 1?><?php }?><?php } ?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['FOLDERNAME'] = new Smarty_variable(vtranslate('LBL_ALL_REPORTS',$_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?><?php }?><span><p class="current-filter-name filter-name pull-left"><span class="fa fa-angle-right" aria-hidden="true"></span>&nbsp;<?php echo $_smarty_tpl->tpl_vars['FOLDERNAME']->value;?>
&nbsp;</p></span><?php }?><?php if ($_smarty_tpl->tpl_vars['DASHBOARD_BOARDS']->value){?><div style="text-align: right;padding-top: 4px" class="headerTabContainer"><select name="header-board" style="width: 300px;text-align: left" class="select2"><optgroup label="My Boards"><option value="1">Default</option><?php  $_smarty_tpl->tpl_vars['BOARD_DATA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BOARD_DATA']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['DASHBOARD_BOARDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['BOARD_DATA']->key => $_smarty_tpl->tpl_vars['BOARD_DATA']->value){
$_smarty_tpl->tpl_vars['BOARD_DATA']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['BOARD_DATA']->key;
?><?php if ($_smarty_tpl->tpl_vars['BOARD_DATA']->value["id"]==1||$_smarty_tpl->tpl_vars['BOARD_DATA']->value['shared']){?><?php continue 1?><?php }?><option <?php if ($_smarty_tpl->tpl_vars['BOARDID']->value==$_smarty_tpl->tpl_vars['BOARD_DATA']->value["id"]){?>selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['BOARD_DATA']->value["id"];?>
"><?php echo $_smarty_tpl->tpl_vars['BOARD_DATA']->value["boardname"];?>
</option><?php } ?></optgroup><optgroup label="Shared Boards"><?php  $_smarty_tpl->tpl_vars['BOARD_DATA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BOARD_DATA']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['DASHBOARD_BOARDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['BOARD_DATA']->key => $_smarty_tpl->tpl_vars['BOARD_DATA']->value){
$_smarty_tpl->tpl_vars['BOARD_DATA']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['BOARD_DATA']->key;
?><?php if (!$_smarty_tpl->tpl_vars['BOARD_DATA']->value['shared']){?><?php continue 1?><?php }?><option <?php if ($_smarty_tpl->tpl_vars['BOARDID']->value==$_smarty_tpl->tpl_vars['BOARD_DATA']->value["id"]){?>selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['BOARD_DATA']->value["id"];?>
"><?php echo $_smarty_tpl->tpl_vars['BOARD_DATA']->value["boardname"];?>
</option><?php } ?></optgroup></select></div><?php }?></span><span class="col-lg-5 col-md-5 pull-right"><div id="appnav" class="navbar-right"><?php  $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_LINKS']->value['LISTVIEWBASIC']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->key => $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->_loop = true;
?><?php $_smarty_tpl->tpl_vars["childLinks"] = new Smarty_variable($_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value->getChildLinks(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['childLinks']->value&&$_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value->get('linklabel')=='LBL_ADD_RECORD'){?><span class="btn-group"><button class="btn btn-default dropdown-toggle module-buttons" data-toggle="dropdown" id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_listView_basicAction_Add"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value->getLabel(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<i class="caret icon-white"></i></button><ul class="dropdown-menu"><?php  $_smarty_tpl->tpl_vars["childLink"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["childLink"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['childLinks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["childLink"]->key => $_smarty_tpl->tpl_vars["childLink"]->value){
$_smarty_tpl->tpl_vars["childLink"]->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['childLink']->value->getLabel()=='LBL_CHARTS'){?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('fa fa-pie-chart', null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['childLink']->value->getLabel()=='LBL_DETAIL_REPORT'){?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-detailreport', null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['childLink']->value->getLabel()=='LBL_PIVOT'){?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('fa fa-table', null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['childLink']->value->getLabel()=='LBL_SQL_REPORT'){?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-list', null, 0);?><?php if ($_smarty_tpl->tpl_vars['USER_NAME']->value!='admin'){?><?php $_smarty_tpl->tpl_vars["BLOCK_LINK"] = new Smarty_variable('block', null, 0);?><?php }?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-list', null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['BLOCK_LINK']->value!='block'){?><li id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_listView_basicAction_<?php echo Vtiger_Util_Helper::replaceSpaceWithUnderScores($_smarty_tpl->tpl_vars['childLink']->value->getLabel());?>
" data-edition-disable="<?php echo $_smarty_tpl->tpl_vars['childLink']->value->disabled;?>
" data-edition-message="<?php echo $_smarty_tpl->tpl_vars['childLink']->value->message;?>
"><a <?php if ($_smarty_tpl->tpl_vars['childLink']->value->disabled!='1'){?> <?php if (stripos($_smarty_tpl->tpl_vars['childLink']->value->getUrl(),'javascript:')===0){?> onclick='<?php echo substr($_smarty_tpl->tpl_vars['childLink']->value->getUrl(),strlen("javascript:"));?>
;' <?php }else{ ?> href='<?php echo $_smarty_tpl->tpl_vars['childLink']->value->getUrl();?>
' <?php }?> <?php }else{ ?> href="javascript:void(0);" <?php }?>><i class='<?php echo $_smarty_tpl->tpl_vars['ICON_CLASS']->value;?>
' style="font-size:13px;"></i>&nbsp; <?php echo vtranslate($_smarty_tpl->tpl_vars['childLink']->value->getLabel(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><?php }?><?php } ?></ul></span><?php }?><?php } ?></div></span></div><?php $_smarty_tpl->tpl_vars['FIELDS_INFO'] = new Smarty_variable(VReports_Field_Model::getListViewFieldsInfo(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['FIELDS_INFO']->value!=null){?><script type="text/javascript">var uimeta = (function () {var fieldInfo = <?php echo $_smarty_tpl->tpl_vars['FIELDS_INFO']->value;?>
;return {field: {get: function (name, property) {if (name && property === undefined) {return fieldInfo[name];}if (name && property) {return fieldInfo[name][property]}},isMandatory: function (name) {if (fieldInfo[name]) {return fieldInfo[name].mandatory;}return false;},getType: function (name) {if (fieldInfo[name]) {return fieldInfo[name].type}return false;}}};})();</script><?php }?><div class="rssAddFormContainer hide"></div></div><?php }} ?>