<?php /* Smarty version Smarty-3.1.7, created on 2024-01-04 06:45:44
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/dashboards/DashBoardTabContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:89937782465965418745368-03328266%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa588edd9212df76be76a9627ff83d491f12087f' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/dashboards/DashBoardTabContents.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '89937782465965418745368-03328266',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'NOTIFICATION_DYNAMIC' => 0,
    'TABID' => 0,
    'WIDGETS' => 0,
    'WIDGET' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6596541875642',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6596541875642')) {function content_6596541875642($_smarty_tpl) {?>

<style type="text/css">.grid-stack {margin-bottom: 100px;background: white;}.grid-stack-item-content {color: #2c3e50;text-align: center;background-color: white;box-shadow: 1px 1px 10px 1px #acaaaa;}.panel_content{height: 85%;}.panel_header {text-align: left;padding-left: 3%;padding-right: 3%;border-bottom: 1px solid #d2d1d1;}</style><div class='dashBoardTabContainer'><input type="hidden" class="check-dynamicfilter" value="<?php echo $_smarty_tpl->tpl_vars['NOTIFICATION_DYNAMIC']->value;?>
"><div class="device-xs visible-xs"></div><div class="device-sm visible-sm"></div><div class="device-md visible-md"></div><div class="device-lg visible-lg"></div><div class="device-xl visible-xl"></div><div class="dashBoardTabContents clearfix"><div class="grid-stack grid-stack-tab<?php echo $_smarty_tpl->tpl_vars['TABID']->value;?>
"><?php  $_smarty_tpl->tpl_vars['WIDGET'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['WIDGET']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['WIDGETS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['WIDGET']->key => $_smarty_tpl->tpl_vars['WIDGET']->value){
$_smarty_tpl->tpl_vars['WIDGET']->_loop = true;
?><div class="grid-stack-item dashboardWidgetGridStack"<?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('min_height')){?>data-min-height="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('min_height');?>
"<?php }?><?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('max_height')){?>data-max-height="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('max_height');?>
"<?php }?>data-record="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('widgetid');?>
"data-gs-x="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getPositionX();?>
" data-gs-y="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getPositionY();?>
"data-gs-width="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getWidth();?>
"data-gs-height="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getHeight();?>
"data-url="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrl();?>
"data-url-detail="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrlReportDetail();?>
"data-url-edit="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrlReportEdit();?>
"data-url-delete="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getDeleteUrl();?>
"<?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('linklabel')){?>data-widget-type="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('linklabel');?>
"><?php }else{ ?>data-widget-type="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('title');?>
"><?php }?><div class="panel panel-default grid-stack-item-content"></div></div><?php } ?></div></div></div><?php }} ?>