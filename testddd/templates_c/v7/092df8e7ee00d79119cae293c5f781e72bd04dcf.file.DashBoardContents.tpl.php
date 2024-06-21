<?php /* Smarty version Smarty-3.1.7, created on 2024-01-04 06:45:44
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/dashboards/DashBoardContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17364434486596541870d3c5-87064592%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '092df8e7ee00d79119cae293c5f781e72bd04dcf' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/dashboards/DashBoardContents.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17364434486596541870d3c5-87064592',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CURRENT_USER' => 0,
    'DASHBOARD_TABS' => 0,
    'TAB_DATA' => 0,
    'SELECTED_TAB' => 0,
    'IS_SHARED' => 0,
    'MODULE_PERMISSION' => 0,
    'MODULE' => 0,
    'MODULE_NAME' => 0,
    'DASHBOARD_TABS_LIMIT' => 0,
    'SELECTED_BOARD' => 0,
    'TABID' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6596541873ee5',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6596541873ee5')) {function content_6596541873ee5($_smarty_tpl) {?>

    
<input type="hidden" id="userDateFormat" value="<?php echo $_smarty_tpl->tpl_vars['CURRENT_USER']->value->get('date_format');?>
" /><div class="dashBoardContainer clearfix"><div class="tabContainer"><ul class="nav nav-tabs tabs sortable container-fluid"><?php  $_smarty_tpl->tpl_vars['TAB_DATA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['TAB_DATA']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['DASHBOARD_TABS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['TAB_DATA']->key => $_smarty_tpl->tpl_vars['TAB_DATA']->value){
$_smarty_tpl->tpl_vars['TAB_DATA']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['TAB_DATA']->key;
?><li class="<?php if ($_smarty_tpl->tpl_vars['TAB_DATA']->value["id"]==$_smarty_tpl->tpl_vars['SELECTED_TAB']->value){?>active<?php }?> dashboardTab" data-tabid="<?php echo $_smarty_tpl->tpl_vars['TAB_DATA']->value["id"];?>
" data-tabname="<?php echo $_smarty_tpl->tpl_vars['TAB_DATA']->value["tabname"];?>
"><a data-toggle="tab" href="#tab_<?php echo $_smarty_tpl->tpl_vars['TAB_DATA']->value["id"];?>
"><div><span class="name textOverflowEllipsis" value="<?php echo $_smarty_tpl->tpl_vars['TAB_DATA']->value["tabname"];?>
" style="width:10%"><strong><?php echo $_smarty_tpl->tpl_vars['TAB_DATA']->value["tabname"];?>
</strong></span><span class="editTabName hide"><input type="text" name="tabName"/></span><i class="fa fa-bars moveTab hide"></i></div></a></li><?php } ?><div class="moreSettings pull-right <?php if ($_smarty_tpl->tpl_vars['IS_SHARED']->value==true){?>hide<?php }?>" ><button class="btn btn-success saveFieldSequence hide" id="savePositionWidgets"><?php echo vtranslate('LBL_SAVE_LAYOUT','VReports');?>
</button><span class="dropdown dashBoardDropDown"><button class="btn btn-default dropdown dashBoardDropDown" id="openAddWidget" <?php if (!$_smarty_tpl->tpl_vars['MODULE_PERMISSION']->value){?> disabled="disabled" <?php }?> style="margin-right: 10px"><?php echo vtranslate('LBL_ADD_WIDGET','VReports');?>
</button></span><span class="dropdown dashBoardDropDown"><button class="btn btn-default reArrangeTabs dropdown-toggle" type="button" data-toggle="dropdown"><?php echo vtranslate('LBL_MORE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;&nbsp;<span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right moreDashBoards" style="margin-top: 21%;"><li style="font-weight: bold;padding: 4px 6px;"><?php echo vtranslate('LBL_WIDGETS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</li><li><a class = "editWidgets" href="#"><?php echo vtranslate('LBL_EDIT_WIDGETS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a class = "dynamicFilter" href="#"><?php echo vtranslate('LBL_DYNAMIC_FILTER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li class="divider"></li><li style="font-weight: bold;padding: 4px 6px;"><?php echo vtranslate('LBL_TABS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</li><li id="newDashBoardLi"<?php if (count($_smarty_tpl->tpl_vars['DASHBOARD_TABS']->value)==$_smarty_tpl->tpl_vars['DASHBOARD_TABS_LIMIT']->value){?>class="disabled"<?php }?>><a data-action="add" class="addNewDashBoard" href="#"><?php echo vtranslate('LBL_ADD_NEW_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a class = "renameTabs" href="#"><?php echo vtranslate('LBL_RENAME_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a data-action="duplicate" class = "addNewDashBoard" href="#"><?php echo vtranslate('LBL_DUPLICATE_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a class = "deleteTab" href="#"><?php echo vtranslate('LBL_DELETE_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a class = "reArrangeTabs" href="#"><?php echo vtranslate('LBL_REARRANGE_DASHBOARD_TABS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li class="divider"></li><li style="font-weight: bold;padding: 4px 6px;"><?php echo vtranslate('LBL_BOARDS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</li><li><a class = "addBoards" href="#"><?php echo vtranslate('LBL_ADD_NEW_BOARD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a class = "editBoards" href="#"><?php echo vtranslate('LBL_EDIT_BOARD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li><a class = "deleteBoard" href="#"><?php echo vtranslate('LBL_DELETE_BOARD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li></ul></span><span class="notification-dynamic" rel="tooltip" data-original-title="Dynamic Filter is active for this tab"></span><button class="btn-success updateSequence pull-right hide"><?php echo vtranslate('LBL_SAVE_ORDER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button></div></ul><div class="tab-content" data-boardid="<?php echo $_smarty_tpl->tpl_vars['SELECTED_BOARD']->value;?>
"><?php  $_smarty_tpl->tpl_vars['TAB_DATA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['TAB_DATA']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['DASHBOARD_TABS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['TAB_DATA']->key => $_smarty_tpl->tpl_vars['TAB_DATA']->value){
$_smarty_tpl->tpl_vars['TAB_DATA']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['TAB_DATA']->key;
?><div id="tab_<?php echo $_smarty_tpl->tpl_vars['TAB_DATA']->value["id"];?>
" data-tabid="<?php echo $_smarty_tpl->tpl_vars['TAB_DATA']->value["id"];?>
" data-tabname="<?php echo $_smarty_tpl->tpl_vars['TAB_DATA']->value["tabname"];?>
" class="tab-pane fade <?php if ($_smarty_tpl->tpl_vars['TAB_DATA']->value["id"]==$_smarty_tpl->tpl_vars['SELECTED_TAB']->value){?>in active<?php }?>"><?php if ($_smarty_tpl->tpl_vars['TAB_DATA']->value["id"]==$_smarty_tpl->tpl_vars['SELECTED_TAB']->value){?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("dashboards/DashBoardTabContents.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TABID'=>$_smarty_tpl->tpl_vars['TABID']->value), 0);?>
<?php }?></div><?php } ?></div></div></div><?php }} ?>