<?php /* Smarty version Smarty-3.1.7, created on 2024-05-28 07:28:32
         compiled from "/var/www/teralead/includes/runtime/../../layouts/v7/modules/VTDevKBView/Index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1896871166665587a08850d6-09499765%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e08486dcf1a55511b9b6685415a33af10d743dfe' => 
    array (
      0 => '/var/www/teralead/includes/runtime/../../layouts/v7/modules/VTDevKBView/Index.tpl',
      1 => 1716539859,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1896871166665587a08850d6-09499765',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'USER_MODEL' => 0,
    'LEFTPANELHIDE' => 0,
    'FIELD_SETTING' => 0,
    'KANBAN_SOURCE_MODULE' => 0,
    'PRIMARY_FIELD_SELECT' => 0,
    'PICKIST_DEPENDENCY_DATASOURCE' => 0,
    'PRIMARY_FIELD_BLOCK' => 0,
    'colors' => 0,
    'color' => 0,
    'instanceColor' => 0,
    'LIST_RECORDS' => 0,
    'RECORD_MODEL' => 0,
    'BACKGROUND_CARD' => 0,
    'CV_ID' => 0,
    'NAME_FIELD' => 0,
    'FONT_COLOR' => 0,
    'MODULE_MODEL' => 0,
    'FIELD_MODEL' => 0,
    'ARR_SELECTED_FIELD_MODELS' => 0,
    'fieldDataType' => 0,
    'FIELD_DISPLAY_VALUE' => 0,
    'FIELD_VALUE' => 0,
    'LABEL_MAPPING' => 0,
    'PICKLIST_COLOR_MAP' => 0,
    'PICKLIST_COLOR' => 0,
    'PICKLIST_KEY_ID' => 0,
    'PICKLIST_TEXT_COLOR' => 0,
    'PICKLIST_VALUES' => 0,
    'PICKLIST_VALUE' => 0,
    'PICKLIST_KEY' => 0,
    'PICKLIST_CLASS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_665587a08cbf3',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_665587a08cbf3')) {function content_665587a08cbf3($_smarty_tpl) {?>
<form id="detailView"><?php $_smarty_tpl->tpl_vars['LEFTPANELHIDE'] = new Smarty_variable($_smarty_tpl->tpl_vars['USER_MODEL']->value->get('leftpanelhide'), null, 0);?><div class="essentials-toggle" title="<?php echo vtranslate('LBL_LEFT_PANEL_SHOW_HIDE','Vtiger');?>
"><span class="essentials-toggle-marker fa <?php if ($_smarty_tpl->tpl_vars['LEFTPANELHIDE']->value=='1'){?>fa-chevron-right<?php }else{ ?>fa-chevron-left<?php }?> cursorPointer"></span></div><?php if ($_smarty_tpl->tpl_vars['FIELD_SETTING']->value['primary_value_setting']){?><style>.kbParentContainer{width: 100%;overflow-x:scroll;}.kbContainer{margin-left: 20px;margin-top: 12px;}</style><div class="kbParentContainer "><div class="kbContainer "><input id="kbSourceModule" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
"><input type="hidden" id="primaryFieldName" value="<?php echo $_smarty_tpl->tpl_vars['PRIMARY_FIELD_SELECT']->value;?>
"><input type="hidden" id="primaryFieldId" value="<?php echo $_smarty_tpl->tpl_vars['FIELD_SETTING']->value['primary_field'];?>
"><?php if (!empty($_smarty_tpl->tpl_vars['PICKIST_DEPENDENCY_DATASOURCE']->value)){?><input type="hidden" name="picklistDependency" value='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['PICKIST_DEPENDENCY_DATASOURCE']->value);?>
' /><?php }?><?php  $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FIELD_SETTING']->value['primary_value_setting']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->key => $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value){
$_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->_loop = true;
?><div class="kanbanBox"><input type="hidden" name="primaryValue" value="<?php echo $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value;?>
"  ><?php  $_smarty_tpl->tpl_vars['color'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['color']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['colors']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['color']->key => $_smarty_tpl->tpl_vars['color']->value){
$_smarty_tpl->tpl_vars['color']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['color']->value[$_smarty_tpl->tpl_vars['PRIMARY_FIELD_SELECT']->value]==$_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value){?><?php $_smarty_tpl->tpl_vars['instanceColor'] = new Smarty_variable($_smarty_tpl->tpl_vars['color']->value['color'], null, 0);?><?php }?><?php } ?><?php if (!$_smarty_tpl->tpl_vars['instanceColor']->value){?><?php $_smarty_tpl->tpl_vars['instanceColor'] = new Smarty_variable('#5cb85c', null, 0);?><?php }?><div class="kbBoxHeader" style="background:<?php echo $_smarty_tpl->tpl_vars['instanceColor']->value;?>
"><span class="kbBoxTitle"><?php echo vtranslate($_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value,'HelpDesk');?>
</span><span class="kbTopIcon pull-right"><a class="removeThisBox" data-box-name = "<?php echo $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value;?>
"><img src="layouts/v7/modules/VTDevKBView/images/close.png" alt="Close"/></a></span></div><div class="kbBoxContent"><?php  $_smarty_tpl->tpl_vars['RECORD_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['RECORD_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LIST_RECORDS']->value[$_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['RECORD_MODEL']->key => $_smarty_tpl->tpl_vars['RECORD_MODEL']->value){
$_smarty_tpl->tpl_vars['RECORD_MODEL']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['BACKGROUND_CARD'] = new Smarty_variable($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get('vtdevkb_color'), null, 0);?><?php $_smarty_tpl->tpl_vars['FONT_COLOR'] = new Smarty_variable($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get('font_color'), null, 0);?><div class="kbBoxTask" <?php if (!empty($_smarty_tpl->tpl_vars['BACKGROUND_CARD']->value)){?>style="background:<?php echo $_smarty_tpl->tpl_vars['BACKGROUND_CARD']->value;?>
 "<?php }?>><input type="hidden" name="recordId" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->getId();?>
"><input type="hidden" name="sequence" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['sequence'];?>
"><div class="kbTaskHeader"><span class="kbTaskTitle pull-left"><a href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
&view=Detail&record=<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->getId();?>
&cvid=<?php echo $_smarty_tpl->tpl_vars['CV_ID']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['NAME_FIELD']->value);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['FONT_COLOR']->value)){?>style="color:<?php echo $_smarty_tpl->tpl_vars['FONT_COLOR']->value;?>
 !important; "<?php }?>><?php $_smarty_tpl->tpl_vars['MODULE_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->getModule(), null, 0);?><?php  $_smarty_tpl->tpl_vars['NAME_FIELD'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['NAME_FIELD']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getNameFields(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['NAME_FIELD']->key => $_smarty_tpl->tpl_vars['NAME_FIELD']->value){
$_smarty_tpl->tpl_vars['NAME_FIELD']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getField($_smarty_tpl->tpl_vars['NAME_FIELD']->value), null, 0);?><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getPermissions()){?><?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['NAME_FIELD']->value);?>
&nbsp;<?php }?><?php } ?></a></span><span class="pull-right kbEditIcon"><a href="javascript:void(0)" data-url="index.php?module=VTDevKBView&view=QuickEditAjax&record=<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->getId();?>
&source_module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
" title="Edit" class="fa fa-pencil alignMiddle kbQuickEdit"></a></span><span class="kbEyeIcon pull-right"><a href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
&view=Detail&record=<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->getId();?>
&cvid=<?php echo $_smarty_tpl->tpl_vars['CV_ID']->value;?>
" title="<?php echo vtranslate('LBL_GO_TO_DETAIL_VIEW','VTDevKBView');?>
"><img src="layouts/v7/modules/VTDevKBView/images/eye.png" alt="Show more"/></a></span><span class="clearfix"></span></div><div class="kbTaskContent"><?php  $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ARR_SELECTED_FIELD_MODELS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MODEL']->key => $_smarty_tpl->tpl_vars['FIELD_MODEL']->value){
$_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->set('fieldvalue',$_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name'))), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_VALUE'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'), null, 0);?><?php if ($_smarty_tpl->tpl_vars['fieldDataType']->value=='multipicklist'){?><?php $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue')), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE'] = new Smarty_variable(Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'))), null, 0);?><?php }?><?php if (mb_strtoupper($_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value, 'UTF-8')=="ALL LEADS"){?><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name')=="cf_919"||$_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name')=="cf_921"||$_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name')=="cf_929"){?><?php continue 1?><?php }?><?php }elseif($_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value!="Closed"){?><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name')=="cf_929"){?><?php continue 1?><?php }?><?php }?><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')!="83"){?><div class="kbTaskSection1 fieldValue" data-field-name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldName();?>
<?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')=='33'){?>[]<?php }?>" data-uitype = "<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype');?>
" data-record-id="<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->getId();?>
" ><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')=='19'||$_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')=='21'){?><div class="kbLabelContainer" style="width: 100%;text-align: center;"><span class="kbLabel" title="<?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['FONT_COLOR']->value)){?>style="color:<?php echo $_smarty_tpl->tpl_vars['FONT_COLOR']->value;?>
 !important; "<?php }?>><?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value);?>
</span></div><div class="kbValueContainer" id="<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
_detailView_fieldValue_<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName();?>
" style="width: 100%; border: none; border-top: 1px solid #eaeaea;"><span class="value pull-left" data-field-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" style="max-width: 95%;max-height: 60px; line-height: 20px;<?php if (!empty($_smarty_tpl->tpl_vars['FONT_COLOR']->value)){?>color:<?php echo $_smarty_tpl->tpl_vars['FONT_COLOR']->value;?>
 !important; <?php }?>" title="<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name'))));?>
"><?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name')));?>
</span><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isEditable()=='true'&&($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType()!=Vtiger_Field_Model::REFERENCE_TYPE)){?><span class="hide edit pull-left"><?php if ($_smarty_tpl->tpl_vars['fieldDataType']->value=='multipicklist'){?><input type="hidden" class="fieldBasicData" data-name='<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
[]' data-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-displayvalue='<?php echo $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE']->value;?>
' data-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE']->value;?>
" /><?php }else{ ?><input type="hidden" class="fieldBasicData" data-name='<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
' data-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-displayvalue='<?php echo $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE']->value;?>
' data-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE']->value;?>
" /><?php }?></span><span class="action pull-right"><a href="javascript:void(0);" class="editAction fa fa-pencil"></a></span><?php }?></div><?php }else{ ?><div class="kbLabelContainer"><span class="kbLabel" title="<?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['FONT_COLOR']->value)){?>style="color:<?php echo $_smarty_tpl->tpl_vars['FONT_COLOR']->value;?>
 !important;"  <?php }?>><?php if (isset($_smarty_tpl->tpl_vars['LABEL_MAPPING']->value[$_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label')])){?> <?php echo $_smarty_tpl->tpl_vars['LABEL_MAPPING']->value[$_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label')];?>
<?php }else{ ?><?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value);?>
<?php }?></span></div><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')=='15'||$_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')=='16'){?><?php $_smarty_tpl->tpl_vars['PICKLIST_COLOR_MAP'] = new Smarty_variable(Settings_Picklist_Module_Model::getPicklistColorMap($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName()), null, 0);?><style type="text/css"><?php  $_smarty_tpl->tpl_vars['PICKLIST_COLOR'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->_loop = false;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY_ID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['PICKLIST_COLOR_MAP']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->key => $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value){
$_smarty_tpl->tpl_vars['PICKLIST_COLOR']->_loop = true;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY_ID']->value = $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->key;
?><?php $_smarty_tpl->tpl_vars['PICKLIST_TEXT_COLOR'] = new Smarty_variable(Settings_Picklist_Module_Model::getTextColor($_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value), null, 0);?>.picklist-<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getId();?>
-<?php echo $_smarty_tpl->tpl_vars['PICKLIST_KEY_ID']->value;?>
 {background-color: <?php echo $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value;?>
;color: <?php echo $_smarty_tpl->tpl_vars['PICKLIST_TEXT_COLOR']->value;?>
 !important;}<?php } ?></style><?php $_smarty_tpl->tpl_vars['PICKLIST_VALUES'] = new Smarty_variable(Vtiger_Util_Helper::getPickListValues($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName()), null, 0);?><?php  $_smarty_tpl->tpl_vars['PICKLIST_VALUE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PICKLIST_VALUE']->_loop = false;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['PICKLIST_VALUES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PICKLIST_VALUE']->key => $_smarty_tpl->tpl_vars['PICKLIST_VALUE']->value){
$_smarty_tpl->tpl_vars['PICKLIST_VALUE']->_loop = true;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY']->value = $_smarty_tpl->tpl_vars['PICKLIST_VALUE']->key;
?><?php if ($_smarty_tpl->tpl_vars['PICKLIST_VALUE']->value==$_smarty_tpl->tpl_vars['FIELD_VALUE']->value){?><?php $_smarty_tpl->tpl_vars['PICKLIST_CLASS'] = new Smarty_variable("picklist-".($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getId())."-".($_smarty_tpl->tpl_vars['PICKLIST_KEY']->value), null, 0);?><?php }?><?php } ?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['PICKLIST_CLASS'] = new Smarty_variable('', null, 0);?><?php $_smarty_tpl->tpl_vars['PICKLIST_COLOR'] = new Smarty_variable('', null, 0);?><?php }?><div class="kbValueContainer" id="<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
_detailView_fieldValue_<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName();?>
"><span class="value pull-left <?php echo $_smarty_tpl->tpl_vars['PICKLIST_CLASS']->value;?>
" data-field-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" title="<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name'))));?>
" ><?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name')));?>
</span><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isEditable()=='true'&&($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType()!=Vtiger_Field_Model::REFERENCE_TYPE)){?><span class="hide edit pull-left"><?php if ($_smarty_tpl->tpl_vars['fieldDataType']->value=='multipicklist'){?><input type="hidden" class="fieldBasicData" data-name='<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
[]' data-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-displayvalue='<?php echo $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE']->value;?>
' data-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE']->value;?>
" /><?php }else{ ?><input type="hidden" class="fieldBasicData" data-name='<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
' data-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-displayvalue='<?php echo $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE']->value;?>
' data-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE']->value;?>
" /><?php }?></span><span class="action pull-right"><a href="javascript:void(0);"  class="editAction fa fa-pencil"></a></span><?php }?></div><?php }?><div class="clearfix"></div></div><?php }?><?php } ?></div><div class="kbTaskFooter"><span class="clearfix"></span></div></div><?php } ?><div class="addBtn"><a href="javascript:void(0)" data-url="index.php?module=VTDevKBView&view=QuickEditAjax&primaryFieldName=<?php echo $_smarty_tpl->tpl_vars['PRIMARY_FIELD_SELECT']->value;?>
&primaryFieldValue=<?php echo $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value;?>
&source_module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
" class="kbQuickCreate"><img src="layouts/v7/modules/VTDevKBView/images/plus.png" alt="Add"/><span style="margin-left: 8px">Add New</span></a></div></div></div><?php } ?></div></div><?php }?></form>
<?php }} ?>