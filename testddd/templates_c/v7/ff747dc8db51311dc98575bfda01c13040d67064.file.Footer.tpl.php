<?php /* Smarty version Smarty-3.1.7, created on 2024-05-29 08:34:45
         compiled from "/var/www/html/teralead/includes/runtime/../../layouts/v7/modules/Vtiger/Footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:93533626656e8a5f3a2d8-23475198%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ff747dc8db51311dc98575bfda01c13040d67064' => 
    array (
      0 => '/var/www/html/teralead/includes/runtime/../../layouts/v7/modules/Vtiger/Footer.tpl',
      1 => 1716539995,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '93533626656e8a5f3a2d8-23475198',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'LANGUAGE_STRINGS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6656e8a5f3b3a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6656e8a5f3b3a')) {function content_6656e8a5f3b3a($_smarty_tpl) {?>

<footer class="app-footer">
	<p>
		Powered by <a href="https://www.teraleads.com/" target="_blank">Teraleads</a>&nbsp;
	</p>
</footer>
</div>
<div id='overlayPage'>
	<!-- arrow is added to point arrow to the clicked element (Ex:- TaskManagement), 
	any one can use this by adding "show" class to it -->
	<div class='arrow'></div>
	<div class='data'>
	</div>
</div>
<div id='helpPageOverlay'></div>
<div id="js_strings" class="hide noprint"><?php echo Zend_Json::encode($_smarty_tpl->tpl_vars['LANGUAGE_STRINGS']->value);?>
</div>
<div class="modal myModal fade"></div>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('JSResources.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>

</html><?php }} ?>