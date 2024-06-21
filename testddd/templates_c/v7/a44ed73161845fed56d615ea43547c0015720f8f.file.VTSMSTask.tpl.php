<?php /* Smarty version Smarty-3.1.7, created on 2024-01-22 21:05:38
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Settings/Workflows/Tasks/VTSMSTask.tpl" */ ?>
<?php /*%%SmartyHeaderCode:202944247465aed8a26cf3b1-35458237%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a44ed73161845fed56d615ea43547c0015720f8f' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Settings/Workflows/Tasks/VTSMSTask.tpl',
      1 => 1627027149,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '202944247465aed8a26cf3b1-35458237',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'TASK_OBJECT' => 0,
    'RECORD_STRUCTURE_MODEL' => 0,
    'FIELD_VALUE' => 0,
    'FIELD' => 0,
    'ALL_FIELD_OPTIONS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65aed8a26e5cb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65aed8a26e5cb')) {function content_65aed8a26e5cb($_smarty_tpl) {?>
<div class="row" style="margin-bottom: 70px;"><div class="col-lg-9"><div class="row form-group"><div class="col-lg-2"><?php echo vtranslate('LBL_RECEPIENTS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
<span class="redColor">*</span></div><div class="col-lg-8"><div class="row"><div class="col-lg-5"><input type="text" class="inputElement fields" data-rule-required="true" name="sms_recepient" value="<?php echo $_smarty_tpl->tpl_vars['TASK_OBJECT']->value->sms_recepient;?>
" /></div><div class="col-lg-6"><select class="select2 task-fields" style="min-width: 150px;" data-placeholder="<?php echo vtranslate('LBL_SELECT_FIELDS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
"><option></option><?php  $_smarty_tpl->tpl_vars['FIELD'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD']->_loop = false;
 $_smarty_tpl->tpl_vars['FIELD_VALUE'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['RECORD_STRUCTURE_MODEL']->value->getFieldsByType('phone'); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD']->key => $_smarty_tpl->tpl_vars['FIELD']->value){
$_smarty_tpl->tpl_vars['FIELD']->_loop = true;
 $_smarty_tpl->tpl_vars['FIELD_VALUE']->value = $_smarty_tpl->tpl_vars['FIELD']->key;
?><option value=",$<?php echo $_smarty_tpl->tpl_vars['FIELD_VALUE']->value;?>
">(<?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD']->value->getModule()->get('name'),$_smarty_tpl->tpl_vars['FIELD']->value->getModule()->get('name'));?>
)  <?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD']->value->get('label'),$_smarty_tpl->tpl_vars['FIELD']->value->getModule()->get('name'));?>
</option><?php } ?></select></div></div></div></div><div class="row form-group"><div class="col-lg-2"><?php echo vtranslate('LBL_ADD_FIELDS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div><div class="col-lg-10"><select class="select2 task-fields" style="min-width: 150px;" data-placeholder="<?php echo vtranslate('LBL_SELECT_FIELDS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
"><option></option><?php echo $_smarty_tpl->tpl_vars['ALL_FIELD_OPTIONS']->value;?>
</select></div><div class="col-lg-2"> &nbsp; </div><div class="col-lg-10"> &nbsp; </div><div class="col-lg-2"><?php echo vtranslate('LBL_SMS_TEXT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div><div class="col-lg-6"><textarea name="content" class="inputElement fields" style="height: inherit;"><?php echo $_smarty_tpl->tpl_vars['TASK_OBJECT']->value->content;?>
</textarea></div></div></div></div>	
<?php }} ?>