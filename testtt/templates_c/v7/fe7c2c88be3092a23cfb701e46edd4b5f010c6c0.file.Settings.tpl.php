<?php /* Smarty version Smarty-3.1.7, created on 2024-05-28 07:21:51
         compiled from "/var/www/teralead/includes/runtime/../../layouts/v7/modules/VTDevKBView/Settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4378280166655860f933947-91117691%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fe7c2c88be3092a23cfb701e46edd4b5f010c6c0' => 
    array (
      0 => '/var/www/teralead/includes/runtime/../../layouts/v7/modules/VTDevKBView/Settings.tpl',
      1 => 1716539863,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4378280166655860f933947-91117691',
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
  'unifunc' => 'content_6655860f94eab',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6655860f94eab')) {function content_6655860f94eab($_smarty_tpl) {?>
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