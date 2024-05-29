<?php /* Smarty version Smarty-3.1.7, created on 2024-02-06 21:30:54
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/dashboards/DashBoardHeader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:70818995465c2a50e47a531-40537616%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'caaab2a8e0bbaaa3f6b8fb5eed8e0f3c9aa01680' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/dashboards/DashBoardHeader.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '70818995465c2a50e47a531-40537616',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE_NAME' => 0,
    'SELECTABLE_WIDGETS' => 0,
    'WIDGET' => 0,
    'FOLDERS' => 0,
    'FOLDER' => 0,
    'MODULE' => 0,
    'FOLDERID' => 0,
    'VIEWNAME' => 0,
    'ICON_CLASS' => 0,
    'USER_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65c2a50e51604',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65c2a50e51604')) {function content_65c2a50e51604($_smarty_tpl) {?>
<div id="addWidgetContainer" class="modelContainer modal-dialog">
	<div class="modal-content" style="width: 100%">
		<div class="table-addwidget-scroller">
			<table name="listAddWidget" class="table no-border">
				<thead>
					<tr>
						<td width="25%"><h4 class="lists-header"><?php echo vtranslate('LBL_STANDARD_WIDGETS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</h4></td>
						<td width="25%"><h4 class="lists-header"><?php echo vtranslate('LBL_CHART_REPORTS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</h4></td>
						<td width="25%"><h4 class="lists-header"><?php echo vtranslate('LBL_DETAIL_REPORTS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</h4></td>
						<td width="25%"><h4 class="lists-header"><?php echo vtranslate('LBL_SHARED_REPORTS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</h4></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="100%">
							<hr>
						</td>
					</tr>
					<tr>
						<td width="25%">
							<?php $_smarty_tpl->tpl_vars["MINILISTWIDGET"] = new Smarty_variable('', null, 0);?>
							<?php if ($_smarty_tpl->tpl_vars['SELECTABLE_WIDGETS']->value['other']){?>
								<?php  $_smarty_tpl->tpl_vars['WIDGET'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['WIDGET']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['SELECTABLE_WIDGETS']->value['other']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['WIDGET']->key => $_smarty_tpl->tpl_vars['WIDGET']->value){
$_smarty_tpl->tpl_vars['WIDGET']->_loop = true;
?>
									<?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->getName()!='MiniList'&&$_smarty_tpl->tpl_vars['MODULE_NAME']->value=='VReports'){?>
										<div class="chartReport">
											<a id="addWidget" class="filterName listViewFilterElipsis" name="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getName();?>
" onclick="VReports_DashBoard_Js.addWidget(this, '<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrl();?>
')" href="javascript:void(0);"
											   data-linkid="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('linkid');?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getName();?>
" <?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->getName()=='Gauge'){?>data-width = '2' data-height = '2'<?php }else{ ?> data-width="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getWidth();?>
" data-height="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getHeight();?>
<?php }?>">
												<?php echo vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</a>
										</div>
									<?php }else{ ?>
										<div class="chartReport">
											<a id="addWidget" class="filterName listViewFilterElipsis" name="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getName();?>
" onclick="VReports_DashBoard_Js.addMiniListWidget(this, '<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrl();?>
')" href="javascript:void(0);"
											   data-linkid="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('linkid');?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getName();?>
" data-width="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getWidth();?>
" data-height="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getHeight();?>
">
												<?php echo vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</a>
										</div>
									<?php }?>
								<?php } ?>
							<?php }else{ ?>
								<var class="italic_small_size">No Records</var>
							<?php }?>
						</td>
						<td width="25%">
							<?php if ($_smarty_tpl->tpl_vars['SELECTABLE_WIDGETS']->value['myWidget']){?>
								<?php  $_smarty_tpl->tpl_vars['FOLDER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FOLDER']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FOLDERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FOLDER']->key => $_smarty_tpl->tpl_vars['FOLDER']->value){
$_smarty_tpl->tpl_vars['FOLDER']->_loop = true;
?>
									<?php ob_start();?><?php echo vtranslate($_smarty_tpl->tpl_vars['FOLDER']->value->getName(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['VIEWNAME'] = new Smarty_variable($_tmp1, null, 0);?>
									<?php $_smarty_tpl->tpl_vars["FOLDERID"] = new Smarty_variable($_smarty_tpl->tpl_vars['FOLDER']->value->getId(), null, 0);?>
									<div data-filter-id=<?php echo $_smarty_tpl->tpl_vars['FOLDERID']->value;?>
>
										<h6 class='filterName' data-filter-id=<?php echo $_smarty_tpl->tpl_vars['FOLDERID']->value;?>
>
											<?php ob_start();?><?php echo strlen($_smarty_tpl->tpl_vars['VIEWNAME']->value)>25;?>
<?php $_tmp2=ob_get_clean();?><?php if ($_tmp2){?><?php echo substr($_smarty_tpl->tpl_vars['VIEWNAME']->value,0,25);?>
..<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['VIEWNAME']->value;?>
<?php }?>
										</h6>
										<ul class="chartReport myWidget lists-menu">
											<?php  $_smarty_tpl->tpl_vars['WIDGET'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['WIDGET']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['SELECTABLE_WIDGETS']->value['myWidget']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['WIDGET']->key => $_smarty_tpl->tpl_vars['WIDGET']->value){
$_smarty_tpl->tpl_vars['WIDGET']->_loop = true;
?>
												<?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('folderid')==$_smarty_tpl->tpl_vars['FOLDERID']->value){?>
													<?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->getName()=='MiniList'){?>
														<?php $_smarty_tpl->tpl_vars["MINILISTWIDGET"] = new Smarty_variable($_smarty_tpl->tpl_vars['WIDGET']->value, null, 0);?> 
													<?php }else{ ?>
														<?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('report_type')=='Chart'){?>
															<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('fa fa-pie-chart', null, 0);?>
														<?php }elseif($_smarty_tpl->tpl_vars['WIDGET']->value->get('report_type')=='Pivot'){?>
															<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('fa fa-table', null, 0);?>
														<?php }elseif($_smarty_tpl->tpl_vars['WIDGET']->value->get('report_type')=='SqlReport'){?>
															<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-list', null, 0);?>
														<?php }?>
														<li style="font-size:12px;" class="chartReport <?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('is_show')==true){?>hide<?php }?>" data-id="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('reportid');?>
">
															<span class="<?php echo $_smarty_tpl->tpl_vars['ICON_CLASS']->value;?>
" style="font-size:9px;"></span>&nbsp;
															<a id="addWidget" class="filterName listViewFilterElipsis" onclick="VReports_DashBoard_Js.addWidget(this, '<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrl();?>
')" href="javascript:void(0);"
															   data-linkid="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('linkid');?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getName();?>
" data-width="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getWidth();?>
" data-height="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getHeight();?>
" title="<?php echo vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
">
																<?php ob_start();?><?php echo strlen(vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value))>25;?>
<?php $_tmp3=ob_get_clean();?><?php if ($_tmp3){?><?php echo substr(vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value),0,25);?>
...<?php }else{ ?><?php echo vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
<?php }?></a>
														</li>
													<?php }?>
												<?php }?>
											<?php } ?>
										</ul>
									</div>
								<?php } ?>
							<?php }else{ ?>
								<var class="italic_small_size">No Records</var>
							<?php }?>
						</td>
						<td width="25%">
							<?php if ($_smarty_tpl->tpl_vars['SELECTABLE_WIDGETS']->value['detail']){?>
								<?php  $_smarty_tpl->tpl_vars['FOLDER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FOLDER']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FOLDERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FOLDER']->key => $_smarty_tpl->tpl_vars['FOLDER']->value){
$_smarty_tpl->tpl_vars['FOLDER']->_loop = true;
?>
									<?php ob_start();?><?php echo vtranslate($_smarty_tpl->tpl_vars['FOLDER']->value->getName(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp4=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['VIEWNAME'] = new Smarty_variable($_tmp4, null, 0);?>
									<?php $_smarty_tpl->tpl_vars["FOLDERID"] = new Smarty_variable($_smarty_tpl->tpl_vars['FOLDER']->value->getId(), null, 0);?>
									<div data-filter-id=<?php echo $_smarty_tpl->tpl_vars['FOLDERID']->value;?>
>
										<h6 class='filterName' data-filter-id=<?php echo $_smarty_tpl->tpl_vars['FOLDERID']->value;?>
>
											<?php ob_start();?><?php echo strlen($_smarty_tpl->tpl_vars['VIEWNAME']->value)>25;?>
<?php $_tmp5=ob_get_clean();?><?php if ($_tmp5){?><?php echo substr($_smarty_tpl->tpl_vars['VIEWNAME']->value,0,25);?>
..<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['VIEWNAME']->value;?>
<?php }?>
										</h6>
										<ul class="chartReport myWidget lists-menu">
											<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-detailreport', null, 0);?>
											<?php  $_smarty_tpl->tpl_vars['WIDGET'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['WIDGET']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['SELECTABLE_WIDGETS']->value['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['WIDGET']->key => $_smarty_tpl->tpl_vars['WIDGET']->value){
$_smarty_tpl->tpl_vars['WIDGET']->_loop = true;
?>
												<?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('folderid')==$_smarty_tpl->tpl_vars['FOLDERID']->value){?>
												<li style="font-size:12px;" class="chartReport"><span class="<?php echo $_smarty_tpl->tpl_vars['ICON_CLASS']->value;?>
" style="font-size:9px;"></span>
													<a id="addWidget" class="filterName listViewFilterElipsis" onclick="VReports_DashBoard_Js.addWidget(this, '<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrl();?>
')" href="javascript:void(0);"
													   data-linkid="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('linkid');?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getName();?>
" data-width="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getWidth();?>
" data-height="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getHeight();?>
"title="<?php echo vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
">
														<?php ob_start();?><?php echo strlen(vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value))>25;?>
<?php $_tmp6=ob_get_clean();?><?php if ($_tmp6){?><?php echo substr(vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value),0,25);?>
...<?php }else{ ?><?php echo vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
<?php }?></a>
												</li>
												<?php }?>
											<?php } ?>
										</ul>
									</div>
								<?php } ?>
							<?php }else{ ?>
								<var class="italic_small_size">No Records</var>
							<?php }?>
						</td>
						<td width="25%">
							<?php if ($_smarty_tpl->tpl_vars['SELECTABLE_WIDGETS']->value['share']){?>
								<?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('report_type')=='Chart'){?>
									<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('fa fa-pie-chart', null, 0);?>
								<?php }elseif($_smarty_tpl->tpl_vars['WIDGET']->value->get('report_type')=='Tabular'){?>
									<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-detailreport', null, 0);?>
								<?php }elseif($_smarty_tpl->tpl_vars['WIDGET']->value->get('report_type')=='Pivot'){?>
									<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('fa fa-table', null, 0);?>
								<?php }elseif($_smarty_tpl->tpl_vars['WIDGET']->value->get('report_type')=='SqlReport'){?>
									<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-list', null, 0);?>
									<?php if ($_smarty_tpl->tpl_vars['USER_NAME']->value!='admin'){?>
										<?php $_smarty_tpl->tpl_vars["BLOCK_LINK"] = new Smarty_variable('block', null, 0);?>
									<?php }?>
									<?php $_smarty_tpl->tpl_vars["ICON_CLASS"] = new Smarty_variable('vicon-list', null, 0);?>
								<?php }?>
								<?php  $_smarty_tpl->tpl_vars['FOLDER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FOLDER']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FOLDERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FOLDER']->key => $_smarty_tpl->tpl_vars['FOLDER']->value){
$_smarty_tpl->tpl_vars['FOLDER']->_loop = true;
?>
									<?php ob_start();?><?php echo vtranslate($_smarty_tpl->tpl_vars['FOLDER']->value->getName(),$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp7=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['VIEWNAME'] = new Smarty_variable($_tmp7, null, 0);?>
									<?php $_smarty_tpl->tpl_vars["FOLDERID"] = new Smarty_variable($_smarty_tpl->tpl_vars['FOLDER']->value->getId(), null, 0);?>
									<div data-filter-id=<?php echo $_smarty_tpl->tpl_vars['FOLDERID']->value;?>
>
										<h6 class='filterName' data-filter-id=<?php echo $_smarty_tpl->tpl_vars['FOLDERID']->value;?>
>
											<?php ob_start();?><?php echo strlen($_smarty_tpl->tpl_vars['VIEWNAME']->value)>25;?>
<?php $_tmp8=ob_get_clean();?><?php if ($_tmp8){?><?php echo substr($_smarty_tpl->tpl_vars['VIEWNAME']->value,0,25);?>
..<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['VIEWNAME']->value;?>
<?php }?>
										</h6>
										<ul class="chartReport myWidget lists-menu">
											<?php  $_smarty_tpl->tpl_vars['WIDGET'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['WIDGET']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['SELECTABLE_WIDGETS']->value['share']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['WIDGET']->key => $_smarty_tpl->tpl_vars['WIDGET']->value){
$_smarty_tpl->tpl_vars['WIDGET']->_loop = true;
?>
												<li style="font-size:12px;" class="chartReport"><span class="<?php echo $_smarty_tpl->tpl_vars['ICON_CLASS']->value;?>
" style="font-size:9px;"></span>
													<a id="addWidget" class="filterName listViewFilterElipsis" onclick="VReports_DashBoard_Js.addWidget(this, '<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrl();?>
')" href="javascript:void(0);"
													   data-linkid="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->get('linkid');?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getName();?>
" data-width="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getWidth();?>
" data-height="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getHeight();?>
" title="<?php echo vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
">
														<?php ob_start();?><?php echo strlen(vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value))>25;?>
<?php $_tmp9=ob_get_clean();?><?php if ($_tmp9){?><?php echo substr(vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value),0,25);?>
...<?php }else{ ?><?php echo vtranslate($_smarty_tpl->tpl_vars['WIDGET']->value->getTitle(),$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
<?php }?></a>
												</li>
											<?php } ?>
										</ul>
									</div>
								<?php } ?>
							<?php }else{ ?>
								<var class="italic_small_size">No Records</var>
							<?php }?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php }} ?>