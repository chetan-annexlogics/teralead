<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 11:05:26
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/Settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:69643985464cb89f662cf90-90876613%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c508ea3bc1b53b8c7902cd084a56eb4c41293136' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/Settings.tpl',
      1 => 1691060297,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '69643985464cb89f662cf90-90876613',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ENABLE' => 0,
    'QUALIFIED_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb89f66410c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb89f66410c')) {function content_64cb89f66410c($_smarty_tpl) {?>
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3><?php echo vtranslate('KanbanView','KanbanView');?>
</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="summaryWidgetContainer">
        <div class="row-fluid">
            <span class="span2"><h4><?php echo vtranslate('LBL_ENABLE_MODULE','KanbanView');?>
</h4></span>
            <input type="checkbox" name="enable_module" id="enable_module" value="1" <?php if ($_smarty_tpl->tpl_vars['ENABLE']->value=='1'){?>checked="" <?php }?>/>
        </div>
    </div>
    <div class="clearfix"></div>
    <div>
        <div style="padding: 10px; text-align: justify; font-size: 14px; border: 1px solid #ececec; border-left: 5px solid #2a9bbc; border-radius: 5px; overflow: hidden;">
            <h4 style="color: #2a9bbc; margin: 0px -15px 10px -15px; padding: 0px 15px 8px 15px; border-bottom: 1px solid #ececec;"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<?php echo vtranslate('LBL_INFO_BLOCK',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</h4>
            <?php echo vtranslate('LBL_INFO_BLOCK_ON_SETTING_PAGE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>

        </div>
    </div>
</div><?php }} ?>