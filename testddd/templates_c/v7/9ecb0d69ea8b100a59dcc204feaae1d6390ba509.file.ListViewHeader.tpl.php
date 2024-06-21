<?php /* Smarty version Smarty-3.1.7, created on 2023-08-10 12:58:29
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Settings/PickListDependency/ListViewHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:207289079764d4def56ba9f3-46173435%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9ecb0d69ea8b100a59dcc204feaae1d6390ba509' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Settings/PickListDependency/ListViewHeader.tpl',
      1 => 1627027149,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '207289079764d4def56ba9f3-46173435',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'PICKLIST_MODULES_LIST' => 0,
    'MODULE_MODEL' => 0,
    'MODULE_NAME' => 0,
    'FOR_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64d4def56e01a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64d4def56e01a')) {function content_64d4def56e01a($_smarty_tpl) {?>



<div class="listViewPageDiv" id="listViewContent">
    <div class="col-sm-12 col-xs-12 ">
        <div id="listview-actions" class="listview-actions-container">
            <div class = "row">
                <div class='col-md-6'>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <span class="pull-right listViewActions" style="padding-right: 15px;">
                            <select class="select2 pickListSupportedModules" name="pickListSupportedModules" style="min-width: 220px;">
                                <option value=""><?php echo vtranslate('LBL_ALL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</option>
                                <?php  $_smarty_tpl->tpl_vars['MODULE_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['MODULE_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['PICKLIST_MODULES_LIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['MODULE_MODEL']->key => $_smarty_tpl->tpl_vars['MODULE_MODEL']->value){
$_smarty_tpl->tpl_vars['MODULE_MODEL']->_loop = true;
?>
                                    <?php $_smarty_tpl->tpl_vars['MODULE_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('name'), null, 0);?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['MODULE_NAME']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['MODULE_NAME']->value==$_smarty_tpl->tpl_vars['FOR_MODULE']->value){?> selected <?php }?>>
                                        <?php if ($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('label')=='Calendar'){?>
                                            <?php echo vtranslate('LBL_TASK',$_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('label'));?>

                                        <?php }else{ ?>
                                            <?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('label'));?>

                                        <?php }?>
                                    </option>
                                <?php } ?>
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <br>
            <div class="list-content row"><?php }} ?>