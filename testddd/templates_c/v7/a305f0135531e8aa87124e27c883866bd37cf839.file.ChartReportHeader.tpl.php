<?php /* Smarty version Smarty-3.1.7, created on 2024-01-12 11:38:24
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ChartReportHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:114461757665a124b01424b1-57706111%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a305f0135531e8aa87124e27c883866bd37cf839' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ChartReportHeader.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '114461757665a124b01424b1-57706111',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DATE_FILTERS' => 0,
    'MODULE' => 0,
    'REPORT_MODEL' => 0,
    'IS_ADMIN' => 0,
    'RECORD' => 0,
    'PRIMARY_MODULE' => 0,
    'SECONDARY_MODULES' => 0,
    'ADVANCED_FILTERS' => 0,
    'CHART_MODEL' => 0,
    'SORT_BY' => 0,
    'LIMIT' => 0,
    'ORDER_BY' => 0,
    'PRIMARY_MODULE_RECORD_STRUCTURE' => 0,
    'BLOCK_LABEL' => 0,
    'LINEITEM_FIELD_IN_CALCULATION' => 0,
    'key' => 0,
    'BLOCK_FIELDS' => 0,
    'SECONDARY_MODULE_RECORD_STRUCTURES' => 0,
    'MODULE_LABEL' => 0,
    'SECONDARY_MODULE_RECORD_STRUCTURE' => 0,
    'filterConditionNotExists' => 0,
    'SELECTED_ADVANCED_FILTER_FIELDS' => 0,
    'RECORD_STRUCTURE' => 0,
    'SELECTED_ADVANCED_FILTER_FIELD' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65a124b01d1f1',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65a124b01d1f1')) {function content_65a124b01d1f1($_smarty_tpl) {?>
<div class=""><div class="reportsDetailHeader"><input type="hidden" name="date_filters" data-value='<?php echo Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($_smarty_tpl->tpl_vars['DATE_FILTERS']->value));?>
' /><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("DetailViewActions.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<div class="filterElements filterConditionsDiv<?php if (!$_smarty_tpl->tpl_vars['REPORT_MODEL']->value->isEditableBySharing()&&!$_smarty_tpl->tpl_vars['IS_ADMIN']->value){?> hide<?php }?>"><form name='chartDetailForm' id='chartDetailForm' method="POST"><input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
" /><input type="hidden" name="action" value="ChartSave" /><input type="hidden" name="recordId" id="recordId" value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value;?>
" /><input type="hidden" name="reportname" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('reportname');?>
" /><input type="hidden" name="folderid" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('folderid');?>
" /><input type="hidden" name="reports_description" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('reports_description');?>
" /><input type="hidden" name="primary_module" value="<?php echo $_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value;?>
" /><input type="hidden" name="secondary_modules" value=<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['SECONDARY_MODULES']->value);?>
 /><input type="hidden" name="advanced_filter" id="advanced_filter" value=<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['ADVANCED_FILTERS']->value);?>
 /><input type="hidden" name='groupbyfield' value=<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->getGroupByField();?>
 /><input type="hidden" name='sort_by' <?php if ($_smarty_tpl->tpl_vars['SORT_BY']->value){?> value='<?php echo Zend_JSON::encode($_smarty_tpl->tpl_vars['SORT_BY']->value);?>
' <?php }else{ ?> value='[]' <?php }?>/><input type="hidden" name='limit' value='<?php echo $_smarty_tpl->tpl_vars['LIMIT']->value;?>
'/><input type="hidden" name='order_by' value='<?php echo $_smarty_tpl->tpl_vars['ORDER_BY']->value;?>
'/><input type="hidden" name='datafields' value=<?php echo Zend_JSON::encode($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getDataFields());?>
 /><input type="hidden" name='charttype' value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType();?>
" /><input type="hidden" name='formatlargenumber' value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('formatlargenumber');?>
" /><input type="hidden" name='legendposition' id="legendposition" value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition();?>
" /><input type="hidden" name='displaygrid' id="displaygrid" value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaygrid');?>
" /><input type="hidden" name='displaylabel' id="displaylabel" value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaylabel');?>
" /><input type="hidden" name='formatlargenumber' id="formatlargenumber" value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('formatlargenumber');?>
" /><input type="hidden" name='legendvalue' id="legendvalue" value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue');?>
" /><input type="hidden" name='drawline' id="drawline" value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('drawline');?>
" /><script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script><?php $_smarty_tpl->tpl_vars['RECORD_STRUCTURE'] = new Smarty_variable(array(), null, 0);?><?php $_smarty_tpl->tpl_vars['PRIMARY_MODULE_LABEL'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value,$_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value), null, 0);?><?php  $_smarty_tpl->tpl_vars['BLOCK_FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->_loop = false;
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
$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value[$_smarty_tpl->tpl_vars['key']->value] = $_smarty_tpl->tpl_vars['BLOCK_FIELDS']->value;?><?php } ?><?php } ?><br/><div class="well filterConditionContainer bg-white"><div><span class="cursorPointer header-text" name="modify_charts" data-val="<?php echo $_smarty_tpl->tpl_vars['filterConditionNotExists']->value;?>
"><span><i class="fa m-r-8 fa-chevron-right"></i>&nbsp;&nbsp;<?php echo vtranslate('LBL_MODIFY_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></span><div id="chart-content-conditions" class='row hide' style="margin-top: 12px"><span class="col-lg-3"><div><span><?php echo vtranslate('LBL_SELECT_GROUP_BY_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span><span class="redColor">*</span></div><br><div><select id='groupbyfield' name='groupbyfield' class="col-lg-10" data-validation-engine="validate[required]" style='min-width:300px;'></select></div></span><span class="col-lg-2">&nbsp;</span><span class="col-lg-3" style="padding: 0px!important;"><div><span><?php echo vtranslate('LBL_SELECT_DATA_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span><span class="redColor">*</span></div><br><div><select id='datafields' name='datafields[]' class="col-lg-10" data-validation-engine="validate[required]" style='min-width:300px;'></select></div></span><span class="col-lg-2">&nbsp;</span><span class="col-lg-2"><div><span><?php echo vtranslate('LBL_LEGEND_POSITION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div><select id='legend_position' name='legendposition' style='min-width:100px;' class="select2"><option value="none" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='top'){?>selected="selected"<?php }?>><?php echo vtranslate('None',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="top" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='top'){?>selected="selected"<?php }?>><?php echo vtranslate('Top',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="left" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='left'){?>selected="selected"<?php }?>><?php echo vtranslate('Left',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="right" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='right'){?>selected="selected"<?php }?>><?php echo vtranslate('Right',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="bottom" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='bottom'){?>selected="selected"<?php }?>><?php echo vtranslate('Bottom',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><br><br><span id="advancedOptions" class="cursorPointer advanced-option" style="color: blue"><?php echo vtranslate('LBL_ADVANCED_OPTIONS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></span><span class="col-lg-2">&nbsp;</span><br><br><br><br><br><br><br><br><div class="col-lg-12"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ChartReportHeaderAdvancedOptions.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div></div><br><div class='hide'><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("chartReportHiddenContents.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div></div><br/><?php $_smarty_tpl->tpl_vars['filterConditionNotExists'] = new Smarty_variable((count($_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELDS']->value[1]['columns'])==0&&count($_smarty_tpl->tpl_vars['SELECTED_ADVANCED_FILTER_FIELDS']->value[2]['columns'])==0), null, 0);?><span class="cursorPointer header-text" name="modify_condition" data-val="<?php echo $_smarty_tpl->tpl_vars['filterConditionNotExists']->value;?>
"><span><i class="fa m-r-8 <?php if ($_smarty_tpl->tpl_vars['filterConditionNotExists']->value==true){?> fa-chevron-right <?php }else{ ?> fa-chevron-down <?php }?>"></i>&nbsp;&nbsp;<?php echo vtranslate('LBL_MODIFY_CONDITION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></span><button type="button" class="button-header-vreport btn-add-group hide" name="addgroup" ><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo vtranslate('ADD_GROUP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button><div id='filterContainer' class='<?php if ($_smarty_tpl->tpl_vars['filterConditionNotExists']->value==true){?> hide <?php }?>'><br/><div id="conditionClone" style="display: none"><div class="button-action" style="margin: 20px auto; width: 200px; display: inherit"><select class="group-condition" style="height: 30px;width: 70px"><option value="or"><?php echo vtranslate('LBL_OR',$_smarty_tpl->tpl_vars['MODULE']->value);?>
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
<?php }?></div></div><?php if ($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->isEditableBySharing()||$_smarty_tpl->tpl_vars['IS_ADMIN']->value){?><div class="row textAlignCenter reportActionButtons hide" style="height: 40px"><button class="btn btn-primary generateReportChart" data-mode="save" value="<?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></div><?php }?></div></form></div></div><div id="reportContentsDiv"><?php }} ?>