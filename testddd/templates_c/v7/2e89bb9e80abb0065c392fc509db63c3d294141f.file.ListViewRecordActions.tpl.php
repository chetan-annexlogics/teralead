<?php /* Smarty version Smarty-3.1.7, created on 2024-01-03 14:25:14
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ListViewRecordActions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:147198054665956e4a481dc6-87558782%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2e89bb9e80abb0065c392fc509db63c3d294141f' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ListViewRecordActions.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '147198054665956e4a481dc6-87558782',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SEARCH_MODE_RESULTS' => 0,
    'LISTVIEW_ENTRY' => 0,
    'MODULE' => 0,
    'DASHBOARD_TABS' => 0,
    'REPORT_TYPE' => 0,
    'TAB_INFO' => 0,
    'CURRENT_USER_MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65956e4a49f5c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65956e4a49f5c')) {function content_65956e4a49f5c($_smarty_tpl) {?>
<!--LIST VIEW RECORD ACTIONS--><div class="table-actions reportListActions"><?php if (!$_smarty_tpl->tpl_vars['SEARCH_MODE_RESULTS']->value){?><span class="input" ><input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
" class="listViewEntriesCheckBox"/></span><?php }?><?php $_smarty_tpl->tpl_vars["REPORT_TYPE"] = new Smarty_variable($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('reporttype'), null, 0);?><span class="dropdown"><a style="font-size:13px;" title="<?php echo vtranslate('LBL_PIN_CHART_TO_DASHBOARD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"class="fa icon action vicon-pin pinToDashboard"  data-recordid="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('reportid');?>
"data-primemodule="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('primarymodule');?>
" data-toggle='dropdown'data-dashboard-tab-count='<?php echo count($_smarty_tpl->tpl_vars['DASHBOARD_TABS']->value);?>
'data-report-type="<?php echo $_smarty_tpl->tpl_vars['REPORT_TYPE']->value;?>
"></a><ul class='dropdown-menu dashBoardTabMenu'><li class="dropdown-header popover-title"><?php echo vtranslate('LBL_DASHBOARD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</li><?php  $_smarty_tpl->tpl_vars['TAB_INFO'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['TAB_INFO']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DASHBOARD_TABS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['TAB_INFO']->key => $_smarty_tpl->tpl_vars['TAB_INFO']->value){
$_smarty_tpl->tpl_vars['TAB_INFO']->_loop = true;
?><li class='dashBoardTab' data-tab-name="<?php echo $_smarty_tpl->tpl_vars['TAB_INFO']->value['tabname'];?>
" data-tab-id='<?php echo $_smarty_tpl->tpl_vars['TAB_INFO']->value['id'];?>
'><a href='javascript:void(0);'><?php echo $_smarty_tpl->tpl_vars['TAB_INFO']->value['tabname'];?>
 <i class="fa <?php if (in_array($_smarty_tpl->tpl_vars['TAB_INFO']->value['id'],$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('pinned'))){?>vicon-unpin<?php }else{ ?>vicon-pin<?php }?>" style="font-size: 13px;float:right;margin-top: 3%;"></i></a></li><?php } ?></ul></span><?php if ($_smarty_tpl->tpl_vars['CURRENT_USER_MODEL']->value->isAdminUser()){?><span class="more dropdown action"><span href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v icon"></i></span><ul class="dropdown-menu"><li><a data-id="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
" href="javascript:void(0);" data-url="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getEditViewUrl();?>
" name="editlink"><?php echo vtranslate('LBL_EDIT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a data-id="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
" class="deleteRecordButton" href="javascript:void(0);"><?php echo vtranslate('LBL_DELETE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li></ul></span><?php }elseif($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->isEditableBySharing()){?><span class="more dropdown action"><span href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v icon"></i></span><ul class="dropdown-menu"><li><a data-id="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
" href="javascript:void(0);" data-url="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getEditViewUrl();?>
" name="editlink"><?php echo vtranslate('LBL_EDIT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a data-id="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
" class="deleteRecordButton" href="javascript:void(0);"><?php echo vtranslate('LBL_DELETE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li></ul></span><?php }?><div class="btn-group inline-save hide"><button class="button btn-success btn-small save" name="save"><i class="fa fa-check"></i></button><button class="button btn-danger btn-small cancel" name="Cancel"><i class="fa fa-close"></i></button></div></div><?php }} ?>