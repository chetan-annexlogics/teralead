<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 10:58:25
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/Index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:141156886264cb885185ccf3-99215613%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2842175979905622166e2f46dce2ca34f41e4888' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/Index.tpl',
      1 => 1691060297,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '141156886264cb885185ccf3-99215613',
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
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb885189c59',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb885189c59')) {function content_64cb885189c59($_smarty_tpl) {?>
<form id="detailView"><?php $_smarty_tpl->tpl_vars['LEFTPANELHIDE'] = new Smarty_variable($_smarty_tpl->tpl_vars['USER_MODEL']->value->get('leftpanelhide'), null, 0);?><div class="essentials-toggle" title="<?php echo vtranslate('LBL_LEFT_PANEL_SHOW_HIDE','Vtiger');?>
"><span class="essentials-toggle-marker fa <?php if ($_smarty_tpl->tpl_vars['LEFTPANELHIDE']->value=='1'){?>fa-chevron-right<?php }else{ ?>fa-chevron-left<?php }?> cursorPointer"></span></div><?php if ($_smarty_tpl->tpl_vars['FIELD_SETTING']->value['primary_value_setting']){?><style>.kbParentContainer{width: 100%;overflow-x:scroll;}.kbContainer{margin-left: 20px;}</style><div class="kbParentContainer "><div class="kbContainer "><input id="kbSourceModule" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
"><input type="hidden" id="primaryFieldName" value="<?php echo $_smarty_tpl->tpl_vars['PRIMARY_FIELD_SELECT']->value;?>
"><input type="hidden" id="primaryFieldId" value="<?php echo $_smarty_tpl->tpl_vars['FIELD_SETTING']->value['primary_field'];?>
"><?php if (!empty($_smarty_tpl->tpl_vars['PICKIST_DEPENDENCY_DATASOURCE']->value)){?><input type="hidden" name="picklistDependency" value='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['PICKIST_DEPENDENCY_DATASOURCE']->value);?>
' /><?php }?><?php  $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FIELD_SETTING']->value['primary_value_setting']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->key => $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value){
$_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->_loop = true;
?><div class="kanbanBox"><input type="hidden" name="primaryValue" value="<?php echo $_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value;?>
"  ><div class="kbBoxHeader"><span class="kbBoxTitle"><?php echo vtranslate($_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value,'HelpDesk');?>
</span><span class="kbBoxIconTop"></span></div><div class="kbBoxContent"><?php  $_smarty_tpl->tpl_vars['RECORD_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['RECORD_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LIST_RECORDS']->value[$_smarty_tpl->tpl_vars['PRIMARY_FIELD_BLOCK']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['RECORD_MODEL']->key => $_smarty_tpl->tpl_vars['RECORD_MODEL']->value){
$_smarty_tpl->tpl_vars['RECORD_MODEL']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['BACKGROUND_CARD'] = new Smarty_variable($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get('kanban_color'), null, 0);?><?php $_smarty_tpl->tpl_vars['FONT_COLOR'] = new Smarty_variable($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get('font_color'), null, 0);?><div class="kbBoxTask" <?php if (!empty($_smarty_tpl->tpl_vars['BACKGROUND_CARD']->value)){?>style="background:<?php echo $_smarty_tpl->tpl_vars['BACKGROUND_CARD']->value;?>
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
&nbsp;<?php }?><?php } ?></a></span><span class="kbEyeIcon pull-right"><a href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
&view=Detail&record=<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->getId();?>
&cvid=<?php echo $_smarty_tpl->tpl_vars['CV_ID']->value;?>
" title="<?php echo vtranslate('LBL_GO_TO_DETAIL_VIEW','KanbanView');?>
"><img src="layouts/v7/modules/KanbanView/images/eye.png" alt="Show more"/></a></span><span class="clearfix"></span></div><div class="kbTaskContent"><?php  $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ARR_SELECTED_FIELD_MODELS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MODEL']->key => $_smarty_tpl->tpl_vars['FIELD_MODEL']->value){
$_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->set('fieldvalue',$_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name'))), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_VALUE'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'), null, 0);?><?php if ($_smarty_tpl->tpl_vars['fieldDataType']->value=='multipicklist'){?><?php $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue')), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE'] = new Smarty_variable(Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'))), null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')!="83"){?><div class="kbTaskSection1 fieldValue" data-field-name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldName();?>
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
" /><?php }?></span><span class="action pull-left"><a href="#" onclick="return false;" class="editAction fa fa-pencil"></a></span><?php }?></div><?php }else{ ?><div class="kbLabelContainer"><span class="kbLabel" title="<?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['FONT_COLOR']->value)){?>style="color:<?php echo $_smarty_tpl->tpl_vars['FONT_COLOR']->value;?>
 !important;"  <?php }?>><?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value);?>
</span></div><div class="kbValueContainer" id="<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
_detailView_fieldValue_<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName();?>
"><span class="value pull-left" data-field-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" <?php if (!empty($_smarty_tpl->tpl_vars['FONT_COLOR']->value)){?>style="color:<?php echo $_smarty_tpl->tpl_vars['FONT_COLOR']->value;?>
 !important;" <?php }?> title="<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name'))));?>
" ><?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->get($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name')));?>
</span><?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isEditable()=='true'&&($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType()!=Vtiger_Field_Model::REFERENCE_TYPE)){?><span class="hide edit pull-left"><?php if ($_smarty_tpl->tpl_vars['fieldDataType']->value=='multipicklist'){?><input type="hidden" class="fieldBasicData" data-name='<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
[]' data-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-displayvalue='<?php echo $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE']->value;?>
' data-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE']->value;?>
" /><?php }else{ ?><input type="hidden" class="fieldBasicData" data-name='<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
' data-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-displayvalue='<?php echo $_smarty_tpl->tpl_vars['FIELD_DISPLAY_VALUE']->value;?>
' data-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE']->value;?>
" /><?php }?></span><span class="action pull-left"><a href="#" onclick="return false;" class="editAction fa fa-pencil"></a></span><?php }?></div><?php }?><div class="clearfix"></div></div><?php }?><?php } ?></div><div class="kbTaskFooter"><span class="pull-right btnEditTaskl"><a href="javascript:void(0)" data-url="index.php?module=KanbanView&view=QuickEditAjax&record=<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value['RECORD']->getId();?>
&source_module=<?php echo $_smarty_tpl->tpl_vars['KANBAN_SOURCE_MODULE']->value;?>
" title="Edit" class="fa fa-pencil alignMiddle kbQuickEdit"></a></span><span class="clearfix"></span></div></div><?php } ?></div></div><?php } ?></div></div><?php }?></form>
<?php }} ?>