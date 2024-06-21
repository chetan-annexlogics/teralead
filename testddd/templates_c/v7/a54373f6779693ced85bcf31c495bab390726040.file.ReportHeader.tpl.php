<?php /* Smarty version Smarty-3.1.7, created on 2024-01-03 14:21:28
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ReportHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:159146214465956d680b1a25-80229814%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a54373f6779693ced85bcf31c495bab390726040' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ReportHeader.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '159146214465956d680b1a25-80229814',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DATE_FILTERS' => 0,
    'REPORT_LIMIT' => 0,
    'MODULE' => 0,
    'REPORT_TYPE' => 0,
    'SELECTED_ADVANCED_FILTER_FIELDS' => 0,
    'filterConditionNotExists' => 0,
    'RECORD_ID' => 0,
    'PRIMARY_MODULE' => 0,
    'PRIMARY_MODULE_RECORD_STRUCTURE' => 0,
    'BLOCK_LABEL' => 0,
    'LINEITEM_FIELD_IN_CALCULATION' => 0,
    'key' => 0,
    'BLOCK_FIELDS' => 0,
    'SECONDARY_MODULE_RECORD_STRUCTURES' => 0,
    'MODULE_LABEL' => 0,
    'SECONDARY_MODULE_RECORD_STRUCTURE' => 0,
    'RECORD_STRUCTURE' => 0,
    'SELECTED_ADVANCED_FILTER_FIELD' => 0,
    'REPORT_MODEL' => 0,
    'IS_ADMIN' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65956d680d9ad',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65956d680d9ad')) {function content_65956d680d9ad($_smarty_tpl) {?>



<div class="reportsDetailHeader"><input type="hidden" name="date_filters" data-value='<?php echo Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($_smarty_tpl->tpl_vars['DATE_FILTERS']->value));?>
' /><input type="hidden" id="reportLimit" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_LIMIT']->value;?>
" /><form id="detailView" onSubmit="return false;"><input type="hidden" name="date_filters" data-value='<?php echo Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($_smarty_tpl->tpl_vars['DATE_FILTERS']->value));?>
' /><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("DetailViewActions.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<br><div class="well filterConditionContainer bg-white <?php if ($_smarty_tpl->tpl_vars['REPORT_TYPE']->value=='sql'){?> hide<?php }?>"><div><?php $_smarty_tpl->tpl_vars['filterConditionNotExists'] = new Smarty_variable((count($_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELDS']->value[1]['columns'])==0&&count($_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELDS']->value[2]['columns'])==0), null, 0);?><span class="cursorPointer header-text" name="modify_condition" data-val="<?php echo $_smarty_tpl->tpl_vars['filterConditionNotExists']->value;?>
"><span><i class="fa m-r-8 <?php if ($_smarty_tpl->tpl_vars['filterConditionNotExists']->value==true){?> fa-chevron-right <?php }else{ ?> fa-chevron-down <?php }?>"></i>&nbsp;&nbsp;<?php echo vtranslate('LBL_MODIFY_CONDITION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></span><button type="button" class="button-header-vreport btn-add-group hide" name="addgroup" ><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo vtranslate('ADD_GROUP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button></div><div id="filterContainer" class="filterElements filterConditionsDiv filterConditionContainer hide"><br><input type="hidden" id="recordId" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_ID']->value;?>
" /><?php $_smarty_tpl->tpl_vars['RECORD_STRUCTURE'] = new Smarty_variable(array(), null, 0);?><?php $_smarty_tpl->tpl_vars['PRIMARY_MODULE_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value,$_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value), null, 0);?><?php  $_smarty_tpl->tpl_vars['BLOCK_FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = false;
 $_smarty_tpl->tpl_vars['BLOCK_LABEL'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['PRIMARY_MODULE_RECORD_STRUCTURE']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->key => $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value){
$_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = true;
 $_smarty_tpl->tpl_vars['BLOCK_LABEL']->value = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->key;
?><?php $_smarty_tpl->tpl_vars['PRIMARY_MODULE_BLOCK_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['BLOCK_LABEL']->value,$_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['key'] = new Smarty_variable(($_smarty_tpl->tpl_vars['PRIMARY_MODULE_LABEL']->value)." ".($_smarty_tpl->tpl_vars['PRIMARY_MODULE_BLOCK_LABEL']->value), null, 0);?><?php if ($_smarty_tpl->tpl_vars['LINEITEM_FIELD_IN_CALCULATION']->value==false&&$_smarty_tpl->tpl_vars['BLOCK_LABEL']->value=='LBL_ITEM_DETAILS'){?><?php }else{ ?><?php $_smarty_tpl->createLocalArrayVariable('RECORD_STRUCTURE', null, 0);
$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value[$_smarty_tpl->tpl_vars['key']->value] = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value;?><?php }?><?php } ?><?php  $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->_loop = false;
 $_smarty_tpl->tpl_vars['MODULE_LABEL'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->key => $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->value){
$_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->_loop = true;
 $_smarty_tpl->tpl_vars['MODULE_LABEL']->value = $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->key;
?><?php $_smarty_tpl->tpl_vars['SECONDARY_MODULE_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['MODULE_LABEL']->value,$_smarty_tpl->tpl_vars['MODULE_LABEL']->value), null, 0);?><?php  $_smarty_tpl->tpl_vars['BLOCK_FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = false;
 $_smarty_tpl->tpl_vars['BLOCK_LABEL'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SECONDARY_MODULE_RECORD_STRUCTURE']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->key => $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value){
$_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = true;
 $_smarty_tpl->tpl_vars['BLOCK_LABEL']->value = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->key;
?><?php $_smarty_tpl->tpl_vars['SECONDARY_MODULE_BLOCK_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['BLOCK_LABEL']->value,$_smarty_tpl->tpl_vars['MODULE_LABEL']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['key'] = new Smarty_variable(($_smarty_tpl->tpl_vars['SECONDARY_MODULE_LABEL']->value)." ".($_smarty_tpl->tpl_vars['SECONDARY_MODULE_BLOCK_LABEL']->value), null, 0);?><?php $_smarty_tpl->createLocalArrayVariable('RECORD_STRUCTURE', null, 0);
$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value[$_smarty_tpl->tpl_vars['key']->value] = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value;?><?php } ?><?php } ?><div><div id="conditionClone" style="display: none"><div class="button-action" style="margin: 20px auto; width: 200px; display: inherit"><select class="group-condition" style="height: 30px;width: 70px"><option value="or"><?php echo vtranslate('LBL_OR',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="and"><?php echo vtranslate('LBL_AND',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select><button type="button" class="btn btn-default deleteGroup" style="margin-left: 15px"><?php echo vtranslate('LBL_DELETE_GROUP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('AdvanceFilter.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('RECORD_STRUCTURE'=>$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value,'COLUMNNAME_API'=>'getReportFilterColumnName'), 0);?>
</div><?php if (count($_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELDS']->value)>0){?><?php  $_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELD'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELD']->_loop = false;
 $_smarty_tpl->tpl_vars['GROUP_PARENT'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELD']->key => $_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELD']->value){
$_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELD']->_loop = true;
 $_smarty_tpl->tpl_vars['GROUP_PARENT']->value = $_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELD']->key;
?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('AdvanceFilter.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('RECORD_STRUCTURE'=>$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value,'ADVANCE_CRITERIA'=>$_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELD']->value,'COLUMNNAME_API'=>'getReportFilterColumnName'), 0);?>
<?php } ?><?php }else{ ?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('AdvanceFilter.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('RECORD_STRUCTURE'=>$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value,'COLUMNNAME_API'=>'getReportFilterColumnName'), 0);?>
<?php }?></div><br></div></div><div class="row DivGenerateReports" ><div class="textAlignCenter reportActionButtons hide" style="margin-bottom: 20px"><button class="btn btn-default generateReport" data-mode="generate" value="<?php echo vtranslate('LBL_GENERATE_NOW',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"/><strong><?php echo vtranslate('LBL_GENERATE_NOW',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;<?php if ($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->isEditableBySharing()||$_smarty_tpl->tpl_vars['IS_ADMIN']->value){?><button class="btn btn-primary generateReport" data-mode="save" value="<?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"/><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><?php }?></div></div></form></div><div id="reportContentsDiv"><?php }} ?>