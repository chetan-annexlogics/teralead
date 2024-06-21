<?php /* Smarty version Smarty-3.1.7, created on 2023-08-07 15:57:56
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Vtiger/FindDuplicateHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:155559267064d11484eedef1-77427461%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cb22b2c90c3d633f9e7254bf3bf548d4ebd1833b' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Vtiger/FindDuplicateHeader.tpl',
      1 => 1627027149,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '155559267064d11484eedef1-77427461',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'HEADER_TITLE' => 0,
    'LISTVIEW_ENTRIES_COUNT' => 0,
    'LISTVIEW_LINKS' => 0,
    'LISTVIEW_BASICACTION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64d11484f008c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64d11484f008c')) {function content_64d11484f008c($_smarty_tpl) {?>

<div class="container-fluid">
	<div class="row">
		<?php ob_start();?><?php echo ((vtranslate('LBL_DUPLICATE')).(' ')).(vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value));?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['HEADER_TITLE'] = new Smarty_variable($_tmp1, null, 0);?>
		<h3>
			<div class="col-lg-7">
				<?php echo $_smarty_tpl->tpl_vars['HEADER_TITLE']->value;?>

			 </div>
			<div class="col-lg-5">
				<div class="alert alert-static">
					<span class="fa fa-info-circle icon"></span>
					<span class="message"><?php echo vJsTranslate('JS_ALLOWED_TO_SELECT_MAX_OF_THREE_RECORDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span>
				</div>
			</div>
		</h3>
	</div>
	<div class="row">
		<div class="col-lg-1">
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value>0){?>
				<?php  $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_LINKS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->key => $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->_loop = true;
?>
					<button id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_listView_basicAction_<?php echo Vtiger_Util_Helper::replaceSpaceWithUnderScores($_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value->getLabel());?>
" class="btn btn-danger pull-left" 
						<?php if (stripos($_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value->getUrl(),'javascript:')===0){?> onclick='<?php echo substr($_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value->getUrl(),strlen("javascript:"));?>
;'<?php }else{ ?> onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value->getUrl();?>
"'<?php }?>>
							<strong><?php echo vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_BASICACTION']->value->getLabel(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong>
					</button>
				<?php } ?>
			<?php }?>
		</div>
		<div class="col-lg-11">
			<div class="col-lg-1">
				&nbsp;
			</div>
			<div class="col-lg-9 select-deselect-container" >
				<div class="hide messageContainer" style = "height:30px;">
					<center><a id="selectAllMsgDiv" href="#"><?php echo vtranslate('LBL_SELECT_ALL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;(<span id="totalRecordsCount" value=""></span>)</a></center>
				</div>
				<div class="hide messageContainer" style = "height:30px;">
					<center><a id="deSelectAllMsgDiv" href="#"><?php echo vtranslate('LBL_DESELECT_ALL_RECORDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></center>
				</div>
			</div>
			<?php $_smarty_tpl->tpl_vars['RECORD_COUNT'] = new Smarty_variable($_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value, null, 0);?>
			<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("Pagination.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('SHOWPAGEJUMP'=>true), 0);?>

		</div>
	</div>
</div><?php }} ?>