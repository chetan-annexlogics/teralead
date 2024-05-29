<?php /* Smarty version Smarty-3.1.7, created on 2024-01-03 14:34:12
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ChartEditStep3.tpl" */ ?>
<?php /*%%SmartyHeaderCode:187278069065957064c52903-47792152%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7151c0a85105a35474323d08de7b99bf43ee5bc8' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ChartEditStep3.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '187278069065957064c52903-47792152',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'RECORD_ID' => 0,
    'REPORT_MODEL' => 0,
    'PRIMARY_MODULE' => 0,
    'SECONDARY_MODULES' => 0,
    'IS_DUPLICATE' => 0,
    'CHART_MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65957064c9669',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65957064c9669')) {function content_65957064c9669($_smarty_tpl) {?>
<form class="form-horizontal recordEditView padding1per" id="chart_report_step3" method="post" action="index.php"><input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
" ><input type="hidden" name="action" value="ChartSave" ><input type="hidden" name="record" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_ID']->value;?>
" ><input type="hidden" name="step" value="Step3" ><input type="hidden" name="reportname" value='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('reportname'));?>
' ><?php if ($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('members')){?><input type="hidden" name="members" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('members'));?>
' /><?php }?><input type="hidden" name="folderid" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('folderid');?>
" ><input type="hidden" name="reports_description" value='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('reports_description'));?>
' ><input type="hidden" name="primary_module" value="<?php echo $_smarty_tpl->tpl_vars['PRIMARY_MODULE']->value;?>
" ><input type="hidden" name="secondary_modules" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['SECONDARY_MODULES']->value);?>
' ><input type="hidden" name="isDuplicate" value="<?php echo $_smarty_tpl->tpl_vars['IS_DUPLICATE']->value;?>
" ><input type="hidden" name="advanced_filter" id="advanced_filter" value="" ><input type="hidden" class="step" value="3" ><input type="hidden" name='groupbyfield' value='<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->getGroupByField();?>
' ><input type="hidden" name='datafields' value='<?php echo Zend_JSON::encode($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getDataFields());?>
'><input type="hidden" name='charttype' value='<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType();?>
'><input type="hidden" name="enable_schedule" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('enable_schedule');?>
"><input type="hidden" name="schtime" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('schtime');?>
"><input type="hidden" name="schdate" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('schdate');?>
"><input type="hidden" name="schdayoftheweek" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('schdayoftheweek'));?>
'><input type="hidden" name="schdayofthemonth" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('schdayofthemonth'));?>
'><input type="hidden" name="schannualdates" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('schannualdates'));?>
'><input type="hidden" name="recipients" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('recipients'));?>
'><input type="hidden" name="specificemails" value=<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('specificemails'));?>
><input type="hidden" name="from_address" value=<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('from_address'));?>
><input type="hidden" name="subject_mail" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('subject_mail');?>
"><textarea style="display: none" name="content_mail"><?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('content_mail');?>
</textarea><input type="hidden" name="signature" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('signature'));?>
'><input type="hidden" name="signature_user" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('signature_user'));?>
'><input type="hidden" name="schtypeid" value="<?php echo $_smarty_tpl->tpl_vars['REPORT_MODEL']->value->get('schtypeid');?>
"><div style="border:1px solid #ccc;padding:1%;"><div><h4><strong><?php echo vtranslate('LBL_SELECT_CHART_TYPE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></h4></div><br><div><ul class="nav nav-tabs charttabs" name="charttab" style="text-align:center;font-size:14px;font-weight: bold;margin:0 3%;border:0px"><li class="active marginRight5px" ><a data-type="pieChart" data-toggle="tab"><img src="layouts/v7/modules/VReports/resources/image/pie.png" style="border:1px solid #ccc"/><div class="chartname"><?php echo vtranslate('LBL_PIE_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px" ><a data-type="doughnutChart" data-toggle="tab"><img src="layouts/v7/modules/VReports/resources/image/doughnut.png" style="border:1px solid #ccc"/><div class="chartname"><?php echo vtranslate('LBL_DOUGHNUT_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px"><a data-type="barChart" data-toggle="tab"><img src="layouts/v7/modules/VReports/resources/image/vbar.png" style="border:1px solid #ccc"/><div class="chartname"><?php echo vtranslate('LBL_VERTICAL_BAR_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px"><a data-type="horizontalBarChart" data-toggle="tab"><img src="layouts/v7/modules/VReports/resources/image/hbar.png" style="border:1px solid #ccc"/><div class="chartname"><?php echo vtranslate('LBL_HORIZONTAL_BAR_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px" ><a data-type="lineChart" data-toggle="tab"><img src="layouts/v7/modules/VReports/resources/image/line.png" style="border:1px solid #ccc"/><div class="chartname"><?php echo vtranslate('LBL_LINE_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px" ><a data-type="stackedChart" data-toggle="tab"><img src="layouts/v7/modules/VReports/resources/image/stacked.png" style="border:1px solid #ccc"/><div class="chartname"><?php echo vtranslate('LBL_STACKED_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px" ><a data-type="funnelChart" data-toggle="tab"><img src="layouts/v7/modules/VReports/resources/image/funnel.jpg" style="border:1px solid #ccc"/><div class="chartname"><?php echo vtranslate('LBL_FUNNEL_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li><li class="marginRight5px" ><a data-type="barFunnelChart" data-toggle="tab"><img src="layouts/v7/modules/VReports/resources/image/barfunnel.png" style="border:1px solid #ccc"/><div class="chartname"><?php echo vtranslate('LBL_BAR_FUNNEL_CHART',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></a></li></ul><div class='tab-content contentsBackground' style="height:auto;padding:4%;border:1px solid #ccc; background-color: white;"><br><div class="row tab-pane active"><div><span class="col-lg-4"><div><span><?php echo vtranslate('LBL_SELECT_GROUP_BY_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span><span class="redColor">*</span></div><br><div class="row"><select id='groupbyfield' name='groupbyfield' class="validate[required]" data-validation-engine="validate[required]" style='min-width:300px;'></select></div><br><div><span><?php echo vtranslate('LBL_LEGEND_POSITION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='legend_position' name='legendposition' style='min-width:300px;' class="select2"><option value="none" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='top'){?>selected="selected"<?php }?>><?php echo vtranslate('None',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="top" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='top'){?>selected="selected"<?php }?>><?php echo vtranslate('Top',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="left" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='left'){?>selected="selected"<?php }?>><?php echo vtranslate('Left',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="right" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='right'){?>selected="selected"<?php }?>><?php echo vtranslate('Right',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="bottom" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getLegendPosition()=='bottom'){?>selected="selected"<?php }?>><?php echo vtranslate('Bottom',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><br><div><span><?php echo vtranslate('LBL_DISPLAY_GRID',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='display_grid' name='displaygrid' style='min-width:300px;' class="select2"><option value="0" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaygrid')=='0'){?>selected="selected"<?php }?>><?php echo vtranslate('No',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="1" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaygrid')=='1'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><br><div><span><?php echo vtranslate('LBL_DISPLAY_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='display_label' data-value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaylabel');?>
" name='displaylabel' style='min-width:300px;' class="select2"><option value="0" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaylabel')=='0'){?>selected="selected"<?php }?>><?php echo vtranslate('No',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="1" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaylabel')=='1'||$_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaylabel')!='0'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div></span><span class="col-lg-2">&nbsp;</span><span class="col-lg-4"><div><span><?php echo vtranslate('LBL_SELECT_DATA_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span><span class="redColor">*</span></div><br><div class="row"><select id='datafields' name='datafields[]' class="validate[required]" data-validation-engine="validate[required]" style='min-width:300px;'></select></div><br><div><span><?php echo vtranslate('LBL_LEGEND_VALUE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='legendvalue' name='legendvalue' style='min-width:300px;' class="select2"><option value="0" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='0'){?>selected="selected"<?php }?>><?php echo vtranslate('No',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="1" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='1'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="2" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='2'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes - Value (Percentage)',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="3" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='3'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes - Value',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="4" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='4'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes - Percentage',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><br><div><span><?php echo vtranslate('LBL_FOTMAT_LARGE_MUNBER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='formatlargenumber' name='formatlargenumber' style='min-width:300px;' class="select2"><option value="0" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('formatlargenumber')=='0'){?>selected="selected"<?php }?>><?php echo vtranslate('No',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="1" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('formatlargenumber')=='1'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><br><div class="label-drawline" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='barChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='horizontalBarChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='stackedChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='barFunnelChart'){?> style="display: none" <?php }?>><span><?php echo vtranslate('Draw Horizontal Line',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row input-drawline" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='barChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='horizontalBarChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='stackedChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='barFunnelChart'){?> style="display: none" <?php }?>><input type="number" id='drawline' name='drawline' style='max-width:300px;' class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('drawline');?>
"/></div></span></div></div><br><br><div class='row alert-info' style="padding: 20px;"><span class='span alert-info'><span><i class="fa fa-info-circle"></i>&nbsp;&nbsp;&nbsp;<?php echo vtranslate('LBL_PLEASE_SELECT_ATLEAST_ONE_GROUP_FIELD_AND_DATA_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php echo vtranslate('LBL_FOR_BAR_GRAPH_AND_LINE_GRAPH_SELECT_3_MAX_DATA_FIELDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div></div></div><div class='hide'><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("chartReportHiddenContents.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div></div><br><div class="modal-overlay-footer border1px clearfix"><div class="row clearfix"><div class="textAlignCenter col-lg-12 col-md-12 col-sm-12 "><button type="button" class="btn btn-danger backStep"><strong><?php echo vtranslate('LBL_BACK',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;&nbsp;<button type="button" class="btn btn-success" id="generateReport"><strong><?php echo vtranslate('LBL_GENERATE_REPORT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;&nbsp;<a class="cancelLink" onclick="window.history.back()"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></div></div></div></form>
<?php }} ?>