<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 10:58:28
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/ConfigureViewAjax.tpl" */ ?>
<?php /*%%SmartyHeaderCode:157155631664cb8854395e60-47900879%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '30faacd73ee5156c308e53cf7bb29992627ffda9' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/KanbanView/ConfigureViewAjax.tpl',
      1 => 1691060297,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '157155631664cb8854395e60-47900879',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'PRIMARY_SETTING' => 0,
    'PRIMARY_FIELDS' => 0,
    'PRIMARY_NAME' => 0,
    'PRIMARY_FIELD_KEY' => 0,
    'PRIMARY_FIELD_VALUE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb88543b006',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb88543b006')) {function content_64cb88543b006($_smarty_tpl) {?>
<style>#primaryFieldValue ul li.select2-search-choice,.icon-move{cursor: move;}</style><div class="modal-dialog"><div class="modal-content"><form class="form-horizontal" id="KanbanConfigure" name="KanbanConfigure" method="post" action="index.php"><input type="hidden" name="primaryFieldValue"/><input type="hidden" name="otherField"/><div class="modal-header"><div class="clearfix"><div class="pull-right "><button type="button" class="close" aria-label="Close" data-dismiss="modal"><span aria-hidden="true" class="fa fa-close"></span></button></div><h4 class="pull-left"><?php echo vtranslate('LBL_CONFIGURE_KANBAN_VIEW',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h4></div></div><div class="modal-body" ><div class="col-sm-12 col-xs-12 input-group"><div class="form-group"><label class="col-sm-4 control-label fieldLabel"><strong><?php echo vtranslate('LBL_DEFAULT_PAGE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></label><div class="fieldValue col-lg-3 col-md-3 col-sm-3 input-group"><input style="top:7px" type="checkbox" name="isDefaultPage" <?php if ($_smarty_tpl->tpl_vars['PRIMARY_SETTING']->value['is_default_page']==1){?> checked <?php }?>/></div></div></div><div class="col-sm-12 col-xs-12 input-group"><div class="form-group"><label class="col-sm-4 control-label fieldLabel"><strong><?php echo vtranslate('LBL_PRIMARY_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong><span class="redColor">*</span></label><div class="fieldValue col-lg-3 col-md-3 col-sm-3 input-group"><select class="select2" name="primaryField" data-rule-required="true"><?php  $_smarty_tpl->tpl_vars['PRIMARY_NAME'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PRIMARY_NAME']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['PRIMARY_FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PRIMARY_NAME']->key => $_smarty_tpl->tpl_vars['PRIMARY_NAME']->value){
$_smarty_tpl->tpl_vars['PRIMARY_NAME']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['PRIMARY_NAME']->value['fieldid'];?>
" <?php if ($_smarty_tpl->tpl_vars['PRIMARY_NAME']->value['fieldid']==$_smarty_tpl->tpl_vars['PRIMARY_SETTING']->value['primary_field']){?> selected <?php }?>><?php echo $_smarty_tpl->tpl_vars['PRIMARY_NAME']->value['fieldlabel'];?>
</option><?php } ?></select></div></div></div><div class="col-sm-12 col-xs-12 input-group"><div class="form-group"><div class="fieldValue col-lg-9 col-md-9 col-sm-9 input-group center-block" id="primaryFieldValue"><select id="primaryValueSelectElement" name="primaryFieldValue" multiple class="select2" data-rule-required="true"><?php  $_smarty_tpl->tpl_vars['PRIMARY_FIELD_VALUE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['PRIMARY_FIELD_VALUE']->_loop = false;
 $_smarty_tpl->tpl_vars['PRIMARY_FIELD_KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['PRIMARY_SETTING']->value['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['PRIMARY_FIELD_VALUE']->key => $_smarty_tpl->tpl_vars['PRIMARY_FIELD_VALUE']->value){
$_smarty_tpl->tpl_vars['PRIMARY_FIELD_VALUE']->_loop = true;
 $_smarty_tpl->tpl_vars['PRIMARY_FIELD_KEY']->value = $_smarty_tpl->tpl_vars['PRIMARY_FIELD_VALUE']->key;
?><option value="<?php echo $_smarty_tpl->tpl_vars['PRIMARY_FIELD_KEY']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['PRIMARY_SETTING']->value['primary_value_setting']&&in_array($_smarty_tpl->tpl_vars['PRIMARY_FIELD_KEY']->value,$_smarty_tpl->tpl_vars['PRIMARY_SETTING']->value['primary_value_setting'])){?> selected <?php }?>><?php echo vtranslate($_smarty_tpl->tpl_vars['PRIMARY_FIELD_VALUE']->value,'HelpDesk');?>
</option><?php } ?></select></div></div></div><hr><div class="col-sm-12 col-xs-12"><h4 class="textAlignCenter"><?php echo vtranslate('LBL_CONFIGURE_TILE_FIELDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h4></div><div class="col-sm-6 col-xs-6 input-group center-block"><div class="form-group"><table class="table table-container listOtherField" style="table-layout: fixed;"><colgroup><col style="width: 10%"><col style="width: 80%"><col style="width: 10%"></colgroup><?php if ($_smarty_tpl->tpl_vars['PRIMARY_SETTING']->value['other_field']){?><?php  $_smarty_tpl->tpl_vars['OTHER_FIELD'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['OTHER_FIELD']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['PRIMARY_SETTING']->value['other_field']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['OTHER_FIELD']->key => $_smarty_tpl->tpl_vars['OTHER_FIELD']->value){
$_smarty_tpl->tpl_vars['OTHER_FIELD']->_loop = true;
?><tr class="otherField"><td width="5%" class="listViewEntryValue"><span style="line-height: 30px"><img src="layouts/v7/skins/images/drag.png" class="icon-move alignMiddle" title="Move to Change Priority"></span></td><td ><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("FieldSelect.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('MULTIPLE'=>0), 0);?>
</td><td><span class="otherFieldAction" style="line-height: 30px"><i title="Delete"  class="fa fa-trash alignMiddle deleteOtherField" style="cursor: pointer"></i></span></td></tr><?php } ?><?php }else{ ?><tr class="otherField"><td width="5%" ><span style="line-height: 30px"><img src="layouts/v7/skins/images/drag.png" class="icon-move alignMiddle" title="Move to Change Priority"></span></td><td ><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("FieldSelect.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('MULTIPLE'=>0,'OTHER_FIELD'=>array()), 0);?>
</td><td><span class="otherFieldAction" style="line-height: 30px"><i title="Delete"  class="fa fa-trash alignMiddle deleteOtherField" style="cursor: pointer"></i></span></td></tr><?php }?></table><table class="hide fieldBasic"><tr class="otherField"><td><span style="line-height: 30px"><img src="layouts/v7/skins/images/drag.png" class="icon-move alignMiddle" title="Move to Change Priority"></span></td><td><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("FieldSelect.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('MULTIPLE'=>0,'NOCHOSEN'=>true,'OTHER_FIELD'=>array()), 0);?>
</td><td><span class="otherFieldAction" style="line-height: 30px"><i title="Delete"  class="fa fa-trash alignMiddle deleteOtherField" style="cursor: pointer"></i></span></td></tr></table><div class="col-sm-12 col-xs-12" ><div class="form-group"><span class="btn addButton btn-default btnAddMore"><?php echo vtranslate('LBL_CLICK_HERE_TO_ADD_MORE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div></div></div></div></div><div class="clearfix"></div><div class="modal-footer"><button class="btn btn-success" id="save_kanbanview_setting" type="button"><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></div></form></div></div>
<?php }} ?>