<?php /* Smarty version Smarty-3.1.7, created on 2024-01-22 21:05:06
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEWEBHOOKS/taskforms/VTEWEBHOOKSTask.tpl" */ ?>
<?php /*%%SmartyHeaderCode:101351273865aed882b7bc76-25194031%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b5cfeec02efd95f0518e28d04be6704544abaf8c' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEWEBHOOKS/taskforms/VTEWEBHOOKSTask.tpl',
      1 => 1705957470,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '101351273865aed882b7bc76-25194031',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'TASK_OBJECT' => 0,
    'FIELD_VALUE_MAPPING' => 0,
    'TASK_ID' => 0,
    'QUALIFIED_MODULE' => 0,
    'MODULE_MODEL' => 0,
    'FIELD_MAP' => 0,
    'RECORD_STRUCTURE' => 0,
    'FIELDS' => 0,
    'FIELD_MODEL' => 0,
    'RESTRICTFIELDS' => 0,
    'FIELD_MODULE_MODEL' => 0,
    'FIELD_NAME' => 0,
    'PICKLIST_VALUES' => 0,
    'FIELD_INFO' => 0,
    'SOURCE_MODULE' => 0,
    'MAX_INDEX' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65aed882bea76',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65aed882bea76')) {function content_65aed882bea76($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING'] = new Smarty_variable(ZEND_JSON::decode($_smarty_tpl->tpl_vars['TASK_OBJECT']->value->field_value_mapping), null, 0);?><script type="text/javascript" src="layouts/v7/modules/VTEWEBHOOKS/resources/VTEWEBHOOKS.js"></script><div class="row form-group"><div class="col-sm-6 col-xs-6"><div class="row"><div class="col-sm-3 col-xs-3">Description</div><div class="col-sm-9 col-xs-9"><textarea style="height: 50px;" class="inputElement" name="webhook_description"><?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value[0]['webhook_description'];?>
</textarea></div></div></div></div><div class="row form-group"><div class="col-sm-6 col-xs-6"><div class="row"><div class="col-sm-3 col-xs-3">Method</div><div class="col-sm-9 col-xs-9"><select class="select2" name="webhook_method"><option value="post">POST</option></select></div></div></div></div><div class="row form-group"><div class="col-sm-6 col-xs-6"><div class="row"><div class="col-sm-3 col-xs-3">URL</div><div class="col-sm-9 col-xs-9"><input name="webhook_url" class="inputElement" data-rule-required="true" value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value[0]['webhook_url'];?>
" aria-required="true" type="text"></div></div></div></div><div class="row form-group"><div class="col-sm-6 col-xs-6"><div class="row"><div class="col-sm-3 col-xs-3">Content Type</div><div class="col-sm-9 col-xs-9"><select class="select2" name="webhook_content_type"><option value="json">JSON</option></select></div></div></div></div><div class="row form-group"><div class="col-sm-6 col-xs-6"><div class="row"><div class="col-sm-3 col-xs-3">Authorization Type</div><div class="col-sm-9 col-xs-9"><input <?php if ($_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value[0]['webhook_authorization']=='on'||empty($_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value[0]['webhook_authorization'])){?>checked<?php }?> type="radio" value="on" class="authorization-input" name="webhook_authorization" id="authorization_basic"/>&nbsp;<label for="authorization_basic"> Basic authentication</label>&nbsp;&nbsp;&nbsp;<input <?php if ($_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value[0]['webhook_authorization']=='off'){?>checked<?php }?> type="radio" value="off" class="authorization-input" name="webhook_authorization" id="authorization_none"/>&nbsp;<label for="authorization_none"> No authentication</label></div></div></div></div><div class="row form-group authorization-info" <?php if ($_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value[0]['webhook_authorization']=='off'){?>style="display:none;"<?php }?>><div class="col-sm-6 col-xs-6"><div class="row"><div class="col-sm-3 col-xs-3"></div><div class="col-sm-9 col-xs-9"><div class="row form-group"><div class="col-sm-3 col-xs-3">User Name</div><div class="col-sm-9 col-xs-9"><input value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value[0]['webhook_authorization_username'];?>
" class="inputElement" name="webhook_authorization_username" type="text"></div></div><div class="row form-group"><div class="col-sm-3 col-xs-3">Password</div><div class="col-sm-9 col-xs-9"><input value="<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value[0]['webhook_authorization_password'];?>
" class="inputElement" name="webhook_authorization_password" type="text"></div></div></div></div></div></div><div><input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['TASK_ID']->value;?>
" name="webhook_task_id"><input type="hidden" value="" name=""></div><div><strong><?php echo vtranslate('Parameters',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default" id="addFieldBtn"><?php echo vtranslate('LBL_ADD',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</button></div><br><div class="conditionsContainer" id="save_fieldvaluemapping"><?php $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING'] = new Smarty_variable(ZEND_JSON::decode($_smarty_tpl->tpl_vars['TASK_OBJECT']->value->field_value_mapping), null, 0);?><?php $_smarty_tpl->tpl_vars['RECORD_STRUCTURE'] = new Smarty_variable($_smarty_tpl->tpl_vars['TASK_OBJECT']->value->getModuleFields($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('name')), null, 0);?><input type="hidden" id="fieldValueMapping" name="field_value_mapping" value='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['TASK_OBJECT']->value->field_value_mapping);?>
' /><?php $_smarty_tpl->tpl_vars['MAX_INDEX'] = new Smarty_variable(0, null, 0);?><?php  $_smarty_tpl->tpl_vars['FIELD_MAP'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_MAP']->_loop = false;
 $_smarty_tpl->tpl_vars['KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MAP']->key => $_smarty_tpl->tpl_vars['FIELD_MAP']->value){
$_smarty_tpl->tpl_vars['FIELD_MAP']->_loop = true;
 $_smarty_tpl->tpl_vars['KEY']->value = $_smarty_tpl->tpl_vars['FIELD_MAP']->key;
?><?php if ($_smarty_tpl->tpl_vars['FIELD_MAP']->value['fieldname']){?><div class="row conditionRow" style="margin-bottom: 15px;<?php if ($_smarty_tpl->tpl_vars['FIELD_MAP']->value['group']>1){?>margin-left: <?php echo ($_smarty_tpl->tpl_vars['FIELD_MAP']->value['group']-1)*15;?>
px<?php }?>"><div class="cursorPointer col-sm-1 col-xs-1"><center> <i class="alignMiddle deleteCondition fa fa-trash" style="position: relative; top: 4px;"></i> </center></div><div class="col-sm-3 col-xs-3"><input type="text" class="inputElement" data-field-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['value'];?>
" name="fieldname" value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['fieldname'];?>
" placeholder="param name..."/><select name="fieldname" data-field-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['value'];?>
" data-field-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['valuetype'];?>
" class="select2" style="min-width: 250px;display: none;" data-placeholder="<?php echo vtranslate('LBL_SELECT_FIELD',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
"><?php  $_smarty_tpl->tpl_vars['FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELDS']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELDS']->key => $_smarty_tpl->tpl_vars['FIELDS']->value){
$_smarty_tpl->tpl_vars['FIELDS']->_loop = true;
?><?php  $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MODEL']->key => $_smarty_tpl->tpl_vars['FIELD_MODEL']->value){
$_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = true;
?><?php if ((!($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_fieldEditable')==true))||($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('name')=="Documents"&&in_array($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name'),$_smarty_tpl->tpl_vars['RESTRICTFIELDS']->value))){?><?php continue 1?><?php }?><?php $_smarty_tpl->tpl_vars['FIELD_INFO'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo(), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName(), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_MODULE_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getModule(), null, 0);?><option value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnname');?>
" data-fieldtype="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldType();?>
" data-field-name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
"<?php if (($_smarty_tpl->tpl_vars['FIELD_MODULE_MODEL']->value->get('name')=='Events')&&($_smarty_tpl->tpl_vars['FIELD_NAME']->value=='recurringtype')){?><?php $_smarty_tpl->tpl_vars['PICKLIST_VALUES'] = new Smarty_variable(Calendar_Field_Model::getReccurencePicklistValues(), null, 0);?><?php $_smarty_tpl->createLocalArrayVariable('FIELD_INFO', null, 0);
$_smarty_tpl->tpl_vars['FIELD_INFO']->value['picklistvalues'] = $_smarty_tpl->tpl_vars['PICKLIST_VALUES']->value;?><?php }?>data-fieldinfo='<?php echo Vtiger_Functions::jsonEncode($_smarty_tpl->tpl_vars['FIELD_INFO']->value);?>
' ><?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnlabel'),$_smarty_tpl->tpl_vars['SOURCE_MODULE']->value);?>
</option><?php } ?><?php } ?></select></div><div class="fieldUiHolder col-sm-4 col-xs-4"><input type="text" class="getPopupUi inputElement" readonly="" input name="fieldValue" value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['value'];?>
" /><input type="hidden" name="valuetype" value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['valuetype'];?>
" /></div><div class="col-sm-4 col-xs-4"><button type="button" class="btn btn-default add-group" data-group="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['group'];?>
" data-parent="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['parent'];?>
" data-index = "<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['index'];?>
">Add Child</button></div></div><?php if ($_smarty_tpl->tpl_vars['MAX_INDEX']->value>$_smarty_tpl->tpl_vars['FIELD_MAP']->value['index']){?><?php $_smarty_tpl->tpl_vars['MAX_INDEX'] = new Smarty_variable($_smarty_tpl->tpl_vars['MAX_INDEX']->value, null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['MAX_INDEX'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MAP']->value['index'], null, 0);?><?php }?><?php }?><?php } ?><input type="hidden" id="max_parent_index" value="<?php echo $_smarty_tpl->tpl_vars['MAX_INDEX']->value;?>
"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("FieldExpressions.tpl",$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><div class="row basicAddFieldContainer hide" style=""><div class="cursorPointer col-sm-1 col-xs-1"><center> <i class="alignMiddle deleteCondition fa fa-trash" style="position: relative; top: 4px;"></i> </center></div><div class="col-sm-3 col-xs-3"><input type="text" class="inputElement" name="fieldname" value="" placeholder="param name..."/><select name="fieldname" data-placeholder="<?php echo vtranslate('LBL_SELECT_FIELD',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
" style="min-width: 250px;display: none;"><?php  $_smarty_tpl->tpl_vars['FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELDS']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELDS']->key => $_smarty_tpl->tpl_vars['FIELDS']->value){
$_smarty_tpl->tpl_vars['FIELDS']->_loop = true;
?><?php  $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MODEL']->key => $_smarty_tpl->tpl_vars['FIELD_MODEL']->value){
$_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = true;
?><?php if ((!($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_fieldEditable')==true))||($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('name')=="Documents"&&in_array($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name'),$_smarty_tpl->tpl_vars['RESTRICTFIELDS']->value))){?><?php continue 1?><?php }?><?php $_smarty_tpl->tpl_vars['FIELD_INFO'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo(), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName(), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_MODULE_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getModule(), null, 0);?><option value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnname');?>
" data-fieldtype="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldType();?>
" data-field-name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
"<?php if (($_smarty_tpl->tpl_vars['FIELD_MODULE_MODEL']->value->get('name')=='Events')&&($_smarty_tpl->tpl_vars['FIELD_NAME']->value=='recurringtype')){?><?php $_smarty_tpl->tpl_vars['PICKLIST_VALUES'] = new Smarty_variable(Calendar_Field_Model::getReccurencePicklistValues(), null, 0);?><?php $_smarty_tpl->createLocalArrayVariable('FIELD_INFO', null, 0);
$_smarty_tpl->tpl_vars['FIELD_INFO']->value['picklistvalues'] = $_smarty_tpl->tpl_vars['PICKLIST_VALUES']->value;?><?php }?>data-fieldinfo='<?php echo Vtiger_Functions::jsonEncode($_smarty_tpl->tpl_vars['FIELD_INFO']->value);?>
' ><?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnlabel'),$_smarty_tpl->tpl_vars['SOURCE_MODULE']->value);?>
</option><?php } ?><?php } ?></select></div><div class="fieldUiHolder col-sm-4 col-xs-4"><input type="text" class="inputElement" readonly="" name="fieldValue" value=""  placeholder="field value..."/><input type="hidden" name="valuetype" value="rawtext" /></div><div class="col-sm-4 col-xs-4"><button type="button" class="btn btn-default add-group">Add Child</button></div></div><div><strong><?php echo vtranslate('Response',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default" id="addFieldResponseBtn"><?php echo vtranslate('LBL_ADD',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</button></div><br><div class="response_conditionsContainer" id="save_response_fieldvaluemapping"><?php $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING'] = new Smarty_variable(ZEND_JSON::decode($_smarty_tpl->tpl_vars['TASK_OBJECT']->value->field_value_mapping), null, 0);?><?php $_smarty_tpl->tpl_vars['RECORD_STRUCTURE'] = new Smarty_variable($_smarty_tpl->tpl_vars['TASK_OBJECT']->value->getModuleFields($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('name')), null, 0);?><input type="hidden" id="response_fieldValueMapping" name="response_field_value_mapping" value='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['TASK_OBJECT']->value->field_value_mapping);?>
' /><?php  $_smarty_tpl->tpl_vars['FIELD_MAP'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_MAP']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FIELD_VALUE_MAPPING']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MAP']->key => $_smarty_tpl->tpl_vars['FIELD_MAP']->value){
$_smarty_tpl->tpl_vars['FIELD_MAP']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['FIELD_MAP']->value['vt_map_field']){?><div class="row conditionRow" style="margin-bottom: 15px;"><div class="cursorPointer col-sm-1 col-xs-1"><center> <i class="alignMiddle response_deleteCondition fa fa-trash" style="position: relative; top: 4px;"></i> </center></div><div class="col-sm-3 col-xs-3"><select name="module_field_name_response_map" data-field-value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['value'];?>
" data-field-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['valuetype'];?>
" class="select2" style="min-width: 250px;" data-placeholder="<?php echo vtranslate('LBL_SELECT_FIELD',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
"><?php  $_smarty_tpl->tpl_vars['FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELDS']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELDS']->key => $_smarty_tpl->tpl_vars['FIELDS']->value){
$_smarty_tpl->tpl_vars['FIELDS']->_loop = true;
?><?php  $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MODEL']->key => $_smarty_tpl->tpl_vars['FIELD_MODEL']->value){
$_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = true;
?><?php if ((!($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_fieldEditable')==true))||($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('name')=="Documents"&&in_array($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name'),$_smarty_tpl->tpl_vars['RESTRICTFIELDS']->value))){?><?php continue 1?><?php }?><?php $_smarty_tpl->tpl_vars['FIELD_INFO'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo(), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName(), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_MODULE_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getModule(), null, 0);?><option <?php if ($_smarty_tpl->tpl_vars['FIELD_MAP']->value['vt_map_field']==$_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnname')){?>selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnname');?>
" data-fieldtype="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldType();?>
" data-field-name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
"<?php if (($_smarty_tpl->tpl_vars['FIELD_MODULE_MODEL']->value->get('name')=='Events')&&($_smarty_tpl->tpl_vars['FIELD_NAME']->value=='recurringtype')){?><?php $_smarty_tpl->tpl_vars['PICKLIST_VALUES'] = new Smarty_variable(Calendar_Field_Model::getReccurencePicklistValues(), null, 0);?><?php $_smarty_tpl->createLocalArrayVariable('FIELD_INFO', null, 0);
$_smarty_tpl->tpl_vars['FIELD_INFO']->value['picklistvalues'] = $_smarty_tpl->tpl_vars['PICKLIST_VALUES']->value;?><?php }?>data-fieldinfo='<?php echo Vtiger_Functions::jsonEncode($_smarty_tpl->tpl_vars['FIELD_INFO']->value);?>
' ><?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnlabel'),$_smarty_tpl->tpl_vars['SOURCE_MODULE']->value);?>
</option><?php } ?><?php } ?></select></div><div class="fieldUiHolder col-sm-4 col-xs-4"><input type="text" class="inputElement"  name="api_response_field_name" value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MAP']->value['api_map_field'];?>
" /></div></div><?php }?><?php } ?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("FieldExpressions.tpl",$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><div class="row response_basicAddFieldContainer hide" style="margin-bottom: 15px;"><div class="cursorPointer col-sm-1 col-xs-1"><center> <i class="alignMiddle response_deleteCondition fa fa-trash" style="position: relative; top: 4px;"></i> </center></div><div class="col-sm-3 col-xs-3"><select name="module_field_name_response_map" data-placeholder="<?php echo vtranslate('LBL_SELECT_FIELD',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
" style="min-width: 250px;"><option value="">Select field</option><?php  $_smarty_tpl->tpl_vars['FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELDS']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELDS']->key => $_smarty_tpl->tpl_vars['FIELDS']->value){
$_smarty_tpl->tpl_vars['FIELDS']->_loop = true;
?><?php  $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_MODEL']->key => $_smarty_tpl->tpl_vars['FIELD_MODEL']->value){
$_smarty_tpl->tpl_vars['FIELD_MODEL']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['FIELD_INFO'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo(), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName(), null, 0);?><?php $_smarty_tpl->tpl_vars['FIELD_MODULE_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getModule(), null, 0);?><option value="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnname');?>
" data-fieldtype="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldType();?>
" data-field-name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
"<?php if (($_smarty_tpl->tpl_vars['FIELD_MODULE_MODEL']->value->get('name')=='Events')&&($_smarty_tpl->tpl_vars['FIELD_NAME']->value=='recurringtype')){?><?php $_smarty_tpl->tpl_vars['PICKLIST_VALUES'] = new Smarty_variable(Calendar_Field_Model::getReccurencePicklistValues(), null, 0);?><?php $_smarty_tpl->createLocalArrayVariable('FIELD_INFO', null, 0);
$_smarty_tpl->tpl_vars['FIELD_INFO']->value['picklistvalues'] = $_smarty_tpl->tpl_vars['PICKLIST_VALUES']->value;?><?php }?>data-fieldinfo='<?php echo Vtiger_Functions::jsonEncode($_smarty_tpl->tpl_vars['FIELD_INFO']->value);?>
' ><?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('workflow_columnlabel'),$_smarty_tpl->tpl_vars['SOURCE_MODULE']->value);?>
</option><?php } ?><?php } ?></select></div><div class="fieldUiHolder col-sm-4 col-xs-4"><input type="text" class="inputElement"  name="api_response_field_name" value="" /></div></div><br><div style="margin-bottom: 60px;"></div>
<?php }} ?>