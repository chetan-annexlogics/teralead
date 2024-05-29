<?php /* Smarty version Smarty-3.1.7, created on 2024-01-05 15:24:29
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTDevKBView/Settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1161208942657c02c39cfc36-53403919%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5db70fce9b5aec52d0183c609105e49a88f04ee6' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTDevKBView/Settings.tpl',
      1 => 1704468248,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1161208942657c02c39cfc36-53403919',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_657c02c3a1212',
  'variables' => 
  array (
    'ENABLE' => 0,
    'QUALIFIED_MODULE' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_657c02c3a1212')) {function content_657c02c3a1212($_smarty_tpl) {?>
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3><?php echo vtranslate('VTDevKBView','VTDevKBView');?>
</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="summaryWidgetContainer">
        <div class="row-fluid">
            <span class="span2"><h4><?php echo vtranslate('LBL_ENABLE_MODULE','VTDevKBView');?>
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