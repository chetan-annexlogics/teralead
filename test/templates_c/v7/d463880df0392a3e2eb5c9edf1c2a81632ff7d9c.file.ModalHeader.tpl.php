<?php /* Smarty version Smarty-3.1.7, created on 2024-05-27 12:23:47
         compiled from "/var/www/teralead/includes/runtime/../../layouts/v7/modules/Vtiger/ModalHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:59261480066547b53b78d44-85204501%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd463880df0392a3e2eb5c9edf1c2a81632ff7d9c' => 
    array (
      0 => '/var/www/teralead/includes/runtime/../../layouts/v7/modules/Vtiger/ModalHeader.tpl',
      1 => 1716539994,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '59261480066547b53b78d44-85204501',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'TITLE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_66547b53b7a54',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_66547b53b7a54')) {function content_66547b53b7a54($_smarty_tpl) {?>
<div class="modal-header"><div class="clearfix"><div class="pull-right " ><button type="button" class="close" aria-label="Close" data-dismiss="modal"><span aria-hidden="true" class='fa fa-close'></span></button></div><h4 class="pull-left"><?php echo $_smarty_tpl->tpl_vars['TITLE']->value;?>
</h4></div></div>    <?php }} ?>