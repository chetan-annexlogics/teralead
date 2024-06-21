<?php /* Smarty version Smarty-3.1.7, created on 2024-01-03 14:21:28
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/DetailViewActions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:33140202465956d680dead3-90475165%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7101b5cdd48e550a988257bd69eb1b67c116b105' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/DetailViewActions.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '33140202465956d680dead3-90475165',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DETAILVIEW_ACTIONS' => 0,
    'DETAILVIEW_LINK' => 0,
    'LINK_ICON_CLASS' => 0,
    'LINK_URL' => 0,
    'LINK_NAME' => 0,
    'DASHBOARD_TABS' => 0,
    'MODULE' => 0,
    'TAB_INFO' => 0,
    'REPORT_MODEL' => 0,
    'REPORT_NAME' => 0,
    'COUNT' => 0,
    'REPORT_LIMIT' => 0,
    'DETAILVIEW_LINKS' => 0,
    'LINKNAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65956d680fc61',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65956d680fc61')) {function content_65956d680fc61($_smarty_tpl) {?>
<div class="listViewPageDiv"><div class="reportHeader"><div class="row"><div class="col-lg-4 detailViewButtoncontainer"><div class="btn-toolbar"><div class="btn-group"><?php  $_smarty_tpl->tpl_vars['DETAILVIEW_LINK'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DETAILVIEW_ACTIONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->key => $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value){
$_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['LINK_URL'] = new Smarty_variable($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->getUrl(), null, 0);?><?php $_smarty_tpl->tpl_vars['LINK_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->getLabel(), null, 0);?><?php $_smarty_tpl->tpl_vars['LINK_ICON_CLASS'] = new Smarty_variable($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('linkiconclass'), null, 0);?><?php if ($_smarty_tpl->tpl_vars['LINK_ICON_CLASS']->value=='vtGlyph vticon-attach'){?><div class="btn-group"><?php }?><button <?php if ($_smarty_tpl->tpl_vars['LINK_URL']->value){?> onclick='window.location.href = "<?php echo $_smarty_tpl->tpl_vars['LINK_URL']->value;?>
"' <?php }?> type="button"class="cursorPointer btn btn-default <?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('customclass');?>
 dropdown-toggle"title="<?php echo $_smarty_tpl->tpl_vars['LINK_NAME']->value;?>
" data-toggle="dropdown"data-dashboard-tab-count='<?php echo count($_smarty_tpl->tpl_vars['DASHBOARD_TABS']->value);?>
'><?php if ($_smarty_tpl->tpl_vars['LINK_NAME']->value){?> <?php echo $_smarty_tpl->tpl_vars['LINK_NAME']->value;?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['LINK_ICON_CLASS']->value){?><?php if ($_smarty_tpl->tpl_vars['LINK_ICON_CLASS']->value=='icon-pencil'){?>&nbsp;&nbsp;&nbsp;<?php }?><i class="fa <?php if ($_smarty_tpl->tpl_vars['LINK_ICON_CLASS']->value=='icon-pencil'){?>fa-pencil<?php }elseif($_smarty_tpl->tpl_vars['LINK_ICON_CLASS']->value=='vtGlyph vticon-attach'){?>vicon-pin<?php }?>" style="font-size: 13px;"></i><?php }?></button><?php if ($_smarty_tpl->tpl_vars['LINK_ICON_CLASS']->value=='vtGlyph vticon-attach'){?><ul class='dropdown-menu dashBoardTabMenu'><li class="dropdown-header popover-title"><?php echo vtranslate('LBL_DASHBOARD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</li><?php  $_smarty_tpl->tpl_vars['TAB_INFO'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['TAB_INFO']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DASHBOARD_TABS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['TAB_INFO']->key => $_smarty_tpl->tpl_vars['TAB_INFO']->value){
$_smarty_tpl->tpl_vars['TAB_INFO']->_loop = true;
?><li class='dashBoardTab' data-tab-name="<?php echo $_smarty_tpl->tpl_vars['TAB_INFO']->value['tabname'];?>
" data-tab-id='<?php echo $_smarty_tpl->tpl_vars['TAB_INFO']->value['id'];?>
'><a href='javascript:void(0)'> <?php echo $_smarty_tpl->tpl_vars['TAB_INFO']->value['tabname'];?>
 <i class="fa <?php if ($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->isPinnedToDashboard($_smarty_tpl->tpl_vars['TAB_INFO']->value['id'])){?>vicon-unpin<?php }else{ ?>vicon-pin<?php }?>" style="font-size: 13px;float:right;margin-top: 3%;"></i></a></li><?php } ?></ul><?php }?><?php if ($_smarty_tpl->tpl_vars['LINK_ICON_CLASS']->value=='vtGlyph vticon-attach'){?></div><?php }?><?php } ?></div></div></div><div class="col-lg-4 textAlignCenter"><h3 name="reportName" class="marginTop0px"><?php echo $_smarty_tpl->tpl_vars['REPORT_NAME']->value;?>
</h3><?php if ($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->getReportType()=='tabular'||$_smarty_tpl->tpl_vars['REPORT_MODEL']->value->getReportType()=='summary'){?><div id="noOfRecords"><?php echo vtranslate('LBL_NO_OF_RECORDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <span id="countValue"><?php echo $_smarty_tpl->tpl_vars['COUNT']->value;?>
</span></div><?php if ($_smarty_tpl->tpl_vars['COUNT']->value>$_smarty_tpl->tpl_vars['REPORT_LIMIT']->value){?><span class="redColor" id="moreRecordsText"> (<?php echo vtranslate('LBL_MORE_RECORDS_TXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
)</span><?php }else{ ?><span class="redColor hide" id="moreRecordsText"> (<?php echo vtranslate('LBL_MORE_RECORDS_TXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
)</span><?php }?><?php }?></div><div class='col-lg-4 detailViewButtoncontainer'><span class="pull-right"><div class="btn-toolbar"><div class="btn-group"><?php  $_smarty_tpl->tpl_vars['DETAILVIEW_LINK'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DETAILVIEW_LINKS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->key => $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value){
$_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['LINKNAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->getLabel(), null, 0);?><button class="btn btn-default <?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('mode')){?>"<?php }else{ ?>reportActions"<?php }?><?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('mode')){?>data-mode="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('mode');?>
" onclick="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->get('onlick');?>
"<?php }else{ ?>name="<?php echo $_smarty_tpl->tpl_vars['LINKNAME']->value;?>
" data-href="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_LINK']->value->getUrl();?>
&source=<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->getReportType();?>
<?php }?>"><?php echo $_smarty_tpl->tpl_vars['LINKNAME']->value;?>
</button><?php } ?></div></div></span></div></div></div></div><?php }} ?>