<?php /* Smarty version Smarty-3.1.7, created on 2024-01-03 13:51:54
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ListViewContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13389926816595667aab50e7-98147343%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9b14d0e88a8c16814f26e6d4b3f37bcd00790536' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ListViewContents.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13389926816595667aab50e7-98147343',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT_USER_MODEL' => 0,
    'LEFTPANELHIDE' => 0,
    'VIEW' => 0,
    'VIEWID' => 0,
    'PAGING_MODEL' => 0,
    'OPERATOR' => 0,
    'LISTVIEW_COUNT' => 0,
    'PAGE_NUMBER' => 0,
    'LISTVIEW_ENTRIES_COUNT' => 0,
    'SEARCH_DETAILS' => 0,
    'NO_SEARCH_PARAMS_CACHE' => 0,
    'ORDER_BY' => 0,
    'SORT_ORDER' => 0,
    'LIST_HEADER_FIELDS' => 0,
    'CURRENT_TAG' => 0,
    'FOLDER_ID' => 0,
    'FOLDER_VALUE' => 0,
    'VIEWNAME' => 0,
    'SEARCH_MODE_RESULTS' => 0,
    'MODULE' => 0,
    'LISTVIEW_MODEL' => 0,
    'LISTVIEW_HEADERS' => 0,
    'COLUMN_NAME' => 0,
    'LISTVIEW_HEADER_KEY' => 0,
    'NEXT_SORT_ORDER' => 0,
    'FASORT_IMAGE' => 0,
    'MODULE_MODEL' => 0,
    'LISTVIEW_HEADER' => 0,
    'DATA_TYPE' => 0,
    'FIELD_INFO' => 0,
    'PICKLIST_VALUES' => 0,
    'PICKLIST_LABEL' => 0,
    'PICKLIST_KEY' => 0,
    'SEARCH_VALUES' => 0,
    'ICON_CLASS' => 0,
    'LISTVIEW_ENTRIES' => 0,
    'LISTVIEW_ENTRY' => 0,
    'LISTVIEW_HEADERNAME' => 0,
    'LISTVIEW_ENTRY_RAWVALUE' => 0,
    'LISTVIEW_ENTRY_VALUE' => 0,
    'identified' => 0,
    'owner' => 0,
    'exclude' => 0,
    'sharingtypevalue' => 0,
    'ortheruser' => 0,
    'LISTVIEW_MAX_TEXTLENGTH' => 0,
    'COLSPAN_WIDTH' => 0,
    'IS_MODULE_EDITABLE' => 0,
    'LIST_VIEW_MODEL' => 0,
    'SINGLE_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6595667ab15d7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6595667ab15d7')) {function content_6595667ab15d7($_smarty_tpl) {?>
<style>
	.listview-table .table-actions.reportListActions{
		width: 90px !important;
	}
	.listview-table tr td:first-child, .listview-table tr th:first-child{
		width: 90px !important;
	}
	.listview-table-norecords .table-actions, .listview-table .table-actions {
		width: 75px;
	}
</style>
<div class="col-sm-12 col-xs-12 "><?php $_smarty_tpl->tpl_vars['LEFTPANELHIDE'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_USER_MODEL']->value->get('leftpanelhide'), null, 0);?><div class="essentials-toggle" title="<?php echo vtranslate('LBL_LEFT_PANEL_SHOW_HIDE','Vtiger');?>
"><span class="essentials-toggle-marker fa <?php if ($_smarty_tpl->tpl_vars['LEFTPANELHIDE']->value=='1'){?>fa-chevron-right<?php }else{ ?>fa-chevron-left<?php }?> cursorPointer"></span></div><input type="hidden" name="view" id="view" value="<?php echo $_smarty_tpl->tpl_vars['VIEW']->value;?>
" /><input type="hidden" name="cvid" value="<?php echo $_smarty_tpl->tpl_vars['VIEWID']->value;?>
" /><input type="hidden" name="pageStartRange" id="pageStartRange" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->getRecordStartRange();?>
" /><input type="hidden" name="pageEndRange" id="pageEndRange" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->getRecordEndRange();?>
" /><input type="hidden" name="previousPageExist" id="previousPageExist" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->isPrevPageExists();?>
" /><input type="hidden" name="nextPageExist" id="nextPageExist" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->isNextPageExists();?>
" /><input type="hidden" name="Operator" id="Operator" value="<?php echo $_smarty_tpl->tpl_vars['OPERATOR']->value;?>
" /><input type="hidden" name="totalCount" id="totalCount" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_COUNT']->value;?>
" /><input type='hidden' name="pageNumber" value="<?php echo $_smarty_tpl->tpl_vars['PAGE_NUMBER']->value;?>
" id='pageNumber'><input type='hidden' name="pageLimit" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->getPageLimit();?>
" id='pageLimit'><input type="hidden" name="noOfEntries" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value;?>
" id="noOfEntries"><input type="hidden" name="currentSearchParams" value="<?php echo Vtiger_Util_Helper::toSafeHTML(Zend_JSON::encode($_smarty_tpl->tpl_vars['SEARCH_DETAILS']->value));?>
" id="currentSearchParams" /><input type="hidden" name="noFilterCache" value="<?php echo $_smarty_tpl->tpl_vars['NO_SEARCH_PARAMS_CACHE']->value;?>
" id="noFilterCache" ><input type="hidden" name="orderBy" value="<?php echo $_smarty_tpl->tpl_vars['ORDER_BY']->value;?>
" id="orderBy"><input type="hidden" name="sortOrder" value="<?php echo $_smarty_tpl->tpl_vars['SORT_ORDER']->value;?>
" id="sortOrder"><input type="hidden" name="list_headers" value='<?php echo $_smarty_tpl->tpl_vars['LIST_HEADER_FIELDS']->value;?>
'/><input type="hidden" name="tag" value="<?php echo $_smarty_tpl->tpl_vars['CURRENT_TAG']->value;?>
" /><input type="hidden" name="folder_id" value="<?php echo $_smarty_tpl->tpl_vars['FOLDER_ID']->value;?>
" /><input type="hidden" name="folder_value" value="<?php echo $_smarty_tpl->tpl_vars['FOLDER_VALUE']->value;?>
" /><input type="hidden" name="folder" value="<?php echo $_smarty_tpl->tpl_vars['VIEWNAME']->value;?>
" /><?php if (!$_smarty_tpl->tpl_vars['SEARCH_MODE_RESULTS']->value){?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ListViewActions.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }?><div id="table-content" class="table-container"><form name='list' id='listedit' action='' onsubmit="return false;"><table id="listview-table"  class="table <?php if ($_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value=='0'){?>listview-table-norecords <?php }?> listview-table"><thead><tr class="listViewContentHeader"><th><?php if (!$_smarty_tpl->tpl_vars['SEARCH_MODE_RESULTS']->value){?><div class="table-actions"><div class="dropdown" style="float:left;margin-left:6px;"><span class="input dropdown-toggle" title="<?php echo vtranslate('LBL_CLICK_HERE_TO_SELECT_ALL_RECORDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" data-toggle="dropdown"><input class="listViewEntriesMainCheckBox" type="checkbox"></span></div></div><?php }elseif($_smarty_tpl->tpl_vars['SEARCH_MODE_RESULTS']->value){?><?php echo vtranslate('LBL_ACTIONS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }?></th><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['VIEWNAME']->value;?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars["LISTVIEW_HEADERS"] = new Smarty_variable($_smarty_tpl->tpl_vars['LISTVIEW_MODEL']->value->getListViewHeadersForVtiger7($_tmp1), null, 0);?><?php  $_smarty_tpl->tpl_vars['LISTVIEW_HEADER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = false;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key => $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = true;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key;
?><th <?php if ($_smarty_tpl->tpl_vars['COLUMN_NAME']->value==$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value){?> nowrap="nowrap" <?php }?> <?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value=='reportname'){?>style="width: 30%!important;" <?php }?>><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value=='schedule'){?><?php echo vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value[$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value]['label'],$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }else{ ?><a href="#" class="listViewContentHeaderValues" data-nextsortorderval="<?php if ($_smarty_tpl->tpl_vars['COLUMN_NAME']->value==$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value){?><?php echo $_smarty_tpl->tpl_vars['NEXT_SORT_ORDER']->value;?>
<?php }else{ ?>ASC<?php }?>" data-columnname="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value;?>
"><?php if ($_smarty_tpl->tpl_vars['COLUMN_NAME']->value==$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value){?><i class="fa fa-sort <?php echo $_smarty_tpl->tpl_vars['FASORT_IMAGE']->value;?>
"></i><?php }else{ ?><i class="fa fa-sort customsort"></i><?php }?>&nbsp;<?php echo vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value[$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value]['label'],$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;</a><?php if ($_smarty_tpl->tpl_vars['COLUMN_NAME']->value==$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value){?><a href="#" class="removeSorting"><i class="fa fa-remove"></i></a><?php }?><?php }?></th><?php } ?></tr><?php if ($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->isQuickSearchEnabled()&&!$_smarty_tpl->tpl_vars['SEARCH_MODE_RESULTS']->value){?><tr class="searchRow"><th class="inline-search-btn"><div class="table-actions"><button class="btn btn-success btn-sm" data-trigger="listSearch"><?php echo vtranslate("LBL_SEARCH",$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button></div></th><?php  $_smarty_tpl->tpl_vars['LISTVIEW_HEADER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = false;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key => $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = true;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key;
?><th><?php $_smarty_tpl->tpl_vars["DATA_TYPE"] = new Smarty_variable($_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value['type'], null, 0);?><?php if ($_smarty_tpl->tpl_vars['DATA_TYPE']->value=='string'){?><div class="row-fluid"><input type="text" name="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value;?>
" class="listSearchContributor inputElement" value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_DETAILS']->value[$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value]['searchValue'];?>
" data-fieldinfo='<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['FIELD_INFO']->value, ENT_QUOTES, 'UTF-8', true);?>
'/></div><?php }elseif($_smarty_tpl->tpl_vars['DATA_TYPE']->value=='picklist'){?><?php $_smarty_tpl->tpl_vars['PICKLIST_VALUES'] = new Smarty_variable(VReports_Field_Model::getPicklistValueByField($_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['SEARCH_VALUES'] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['SEARCH_DETAILS']->value[$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value]['searchValue']), null, 0);?><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value=='modifiedtime'){?><?php continue 1?><?php }?><div class="row-fluid"><select class="select2 listSearchContributor report-type-select" name="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value;?>
" multiple data-fieldinfo='<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['FIELD_INFO']->value, ENT_QUOTES, 'UTF-8', true);?>
'><?php  $_smarty_tpl->tpl_vars['PICKLIST_LABEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PICKLIST_LABEL']->_loop = false;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['PICKLIST_VALUES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PICKLIST_LABEL']->key => $_smarty_tpl->tpl_vars['PICKLIST_LABEL']->value){
$_smarty_tpl->tpl_vars['PICKLIST_LABEL']->_loop = true;
 $_smarty_tpl->tpl_vars['PICKLIST_KEY']->value = $_smarty_tpl->tpl_vars['PICKLIST_LABEL']->key;
?><?php if ($_smarty_tpl->tpl_vars['PICKLIST_LABEL']->value=='Chart'){?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('fa fa-pie-chart greenColor', null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['PICKLIST_LABEL']->value=='Pivot'){?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('fa fa-table fa-2x blueColor', null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['PICKLIST_LABEL']->value=='Detail'){?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-detailreport', null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['PICKLIST_LABEL']->value=='Sql Report'){?><?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-list', null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['PICKLIST_LABEL']->value=='Sql Report'&&$_smarty_tpl->tpl_vars['CURRENT_USER_MODEL']->value->user_name!='admin'){?><?php continue 1?><?php }else{ ?><option value="<?php echo $_smarty_tpl->tpl_vars['PICKLIST_KEY']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['PICKLIST_KEY']->value,$_smarty_tpl->tpl_vars['SEARCH_VALUES']->value)&&($_smarty_tpl->tpl_vars['PICKLIST_KEY']->value!='')){?> selected<?php }?> <?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value=='reporttype'){?>class='<?php echo $_smarty_tpl->tpl_vars['ICON_CLASS']->value;?>
'<?php }?>><?php echo $_smarty_tpl->tpl_vars['PICKLIST_LABEL']->value;?>
</option><?php }?><?php } ?></select></div><?php }?><input type="hidden" class="operatorValue" value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_DETAILS']->value[$_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value]['comparator'];?>
"></th><?php } ?></tr><?php }?></thead><tbody class="overflow-y"><?php  $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['listview']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->key => $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['listview']['index']++;
?><tr class="listViewEntries" data-id='<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
' data-recordUrl='<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getDetailViewUrl();?>
' id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_listView_row_<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['listview']['index']+1;?>
"><td class = "listViewRecordActions"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ListViewRecordActions.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</td><?php  $_smarty_tpl->tpl_vars['LISTVIEW_HEADER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = false;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key => $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = true;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key;
?><?php $_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value, null, 0);?><?php $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_RAWVALUE'] = new Smarty_variable($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getRaw($_smarty_tpl->tpl_vars['LISTVIEW_HEADER_KEY']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE'] = new Smarty_variable($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get($_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value), null, 0);?><td class="listViewEntryValue" style="vertical-align: top;" data-name="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_RAWVALUE']->value;?>
" data-rawvalue="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_RAWVALUE']->value;?>
" data-field-type=""><span class="fieldValue"><span class="value"><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value=='reporttype'){?><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value=='summary'||$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value=='tabular'){?><center title="<?php echo vtranslate('LBL_DETAIL_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><span class='vicon-detailreport' style="font-size:17px;"></span></center><?php }elseif($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value=='chart'){?><center title="<?php echo vtranslate('LBL_CHART_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><span class='fa fa-pie-chart fa-2x greenColor' style="font-size:1.7em;"></span></center><?php }elseif($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value=='sql'){?><center title="<?php echo vtranslate('LBL_SQL_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><span class='vicon-list' style="font-size:1.7em;"></span></center><?php }else{ ?><center title="<?php echo vtranslate('LBL_PIVOT_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><span class='fa fa-table fa-2x blueColor' style="font-size:1.7em;"></span></center><?php }?><?php }elseif($_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value=='primarymodule'){?><span title="<?php echo Vtiger_Util_Helper::tosafeHTML(decode_html(vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value,$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value)));?>
"><?php echo Vtiger_Util_Helper::tosafeHTML(decode_html(vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value,$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value)));?>
</span><?php }elseif($_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value=='foldername'){?><span title="<?php echo Vtiger_Util_Helper::tosafeHTML(vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value,$_smarty_tpl->tpl_vars['MODULE']->value));?>
"><?php echo Vtiger_Util_Helper::tosafeHTML(vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value,$_smarty_tpl->tpl_vars['MODULE']->value));?>
</span><?php }else{ ?><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value=='reportname'){?><a href="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getDetailViewUrl();?>
" target="_blank"><?php }?><span <?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value=='modifiedtime'){?> style="white-space: nowrap" <?php }?> title="<?php echo Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value);?>
"><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value=='sharingtype'){?><?php ob_start();?><?php echo Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('owner'));?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars["owner"] = new Smarty_variable($_tmp2, null, 0);?><?php ob_start();?><?php echo Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value);?>
<?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->tpl_vars["sharingtypevalue"] = new Smarty_variable($_tmp3, null, 0);?><?php $_smarty_tpl->tpl_vars['identified'] = new Smarty_variable(explode(",",($_smarty_tpl->tpl_vars['sharingtypevalue']->value)), null, 0);?><?php $_smarty_tpl->tpl_vars['exclude'] = new Smarty_variable(((($_smarty_tpl->tpl_vars['identified']->value[0]).(', ')).($_smarty_tpl->tpl_vars['owner']->value)).(', '), null, 0);?><?php ob_start();?><?php echo str_replace($_smarty_tpl->tpl_vars['exclude']->value,'',$_smarty_tpl->tpl_vars['sharingtypevalue']->value);?>
<?php $_tmp4=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['ortheruser'] = new Smarty_variable($_tmp4, null, 0);?><span style="white-space: nowrap" title="<?php echo $_smarty_tpl->tpl_vars['ortheruser']->value;?>
"><i class="fa fa-<?php if ($_smarty_tpl->tpl_vars['identified']->value[0]=='Private'){?>lock<?php }else{ ?>unlock<?php }?>">&nbsp;</i><?php echo $_smarty_tpl->tpl_vars['owner']->value;?>
</span><?php }elseif(strlen(Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value))>$_smarty_tpl->tpl_vars['LISTVIEW_MAX_TEXTLENGTH']->value){?><?php echo textlength_check(Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value));?>
<?php }else{ ?><?php echo Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY_VALUE']->value);?>
<?php }?></span><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADERNAME']->value=='reportname'){?></a> <br><div><?php if (strlen(Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('description')))>200){?><span title="<?php echo substr(Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('description')),0,200);?>
"><var class="italic_small_size" style="color: #666"><?php echo substr(decode_html($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('description')),0,200);?>
</var></span><?php }else{ ?><span title="<?php echo Vtiger_Util_Helper::tosafeHTML($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('description'));?>
"><var class="italic_small_size" style="color: #666"><?php echo decode_html($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('description'));?>
</var></span><?php }?></div><?php }?><?php }?></span></span></span></td><?php } ?></tr><?php } ?><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value=='0'){?><tr class="emptyRecordsDiv"><?php ob_start();?><?php echo count($_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value);?>
<?php $_tmp5=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['COLSPAN_WIDTH'] = new Smarty_variable($_tmp5+1, null, 0);?><td colspan="<?php echo $_smarty_tpl->tpl_vars['COLSPAN_WIDTH']->value;?>
"><div class="emptyRecordsDiv"><div class="emptyRecordsContent"><?php $_smarty_tpl->tpl_vars['SINGLE_MODULE'] = new Smarty_variable("SINGLE_".($_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?><?php echo vtranslate('LBL_NO');?>
 <?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo vtranslate('LBL_FOUND');?>
.<?php if ($_smarty_tpl->tpl_vars['IS_MODULE_EDITABLE']->value){?> <a href="<?php echo $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getCreateRecordUrl();?>
"> <?php echo vtranslate('LBL_CREATE');?>
 </a> <?php if (Users_Privileges_Model::isPermitted($_smarty_tpl->tpl_vars['MODULE']->value,'Import')&&$_smarty_tpl->tpl_vars['LIST_VIEW_MODEL']->value->isImportEnabled()){?> <?php echo vtranslate('LBL_OR',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <a style="color:blue" href="#" onclick="return Vtiger_Import_Js.triggerImportAction()"> <?php echo vtranslate('LBL_IMPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </a><?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }else{ ?><?php echo vtranslate($_smarty_tpl->tpl_vars['SINGLE_MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }?><?php }?></div></div></td></tr><?php }?></tbody></table></form></div><div id="scroller_wrapper" class="bottom-fixed-scroll"><div id="scroller" class="scroller-div"></div></div></div>
<?php }} ?>