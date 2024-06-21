<?php /* Smarty version Smarty-3.1.7, created on 2024-01-03 13:51:47
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/Settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11702088446595667378db53-31687388%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b4b5d6773bc1b3a1440e5bcc28deb342d52f3a90' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/Settings.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11702088446595667378db53-31687388',
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
  'unifunc' => 'content_6595667379c1b',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6595667379c1b')) {function content_6595667379c1b($_smarty_tpl) {?>
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3><?php echo vtranslate('VReports','VReports');?>
</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="summaryWidgetContainer">
        <div class="row-fluid">
            <h4 style="width: 27%; float: left; margin-top: 0"><?php echo vtranslate('LBL_ENABLE_MODULE','VReports');?>
</h4>
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
    <div class="clearfix"></div>
    <div style="margin-top: 20px;">
        <button id="phpiniWarnings" name="phpiniWarnings" class="btn btn-danger" style="margin-right:5px;">Find Error</button>
    </div>
</div><?php }} ?>