<?php /* Smarty version Smarty-3.1.7, created on 2024-01-12 11:38:24
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ChartReportHeaderAdvancedOptions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:51215831565a124b01e4ac5-89305355%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3bc3ed093c09d97e24b0f6413b3d24435149ac79' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ChartReportHeaderAdvancedOptions.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '51215831565a124b01e4ac5-89305355',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'LIMIT' => 0,
    'CHART_MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65a124b020613',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65a124b020613')) {function content_65a124b020613($_smarty_tpl) {?>
<div class='tab-content contentsBackground hide' style="height:auto; background-color: white;"><br><div class="row tab-pane active"><div><div class="col-lg-3"><div><span><?php echo vtranslate('LBL_SORT_BY',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div><select class="col-lg-10 select2" multiple id="sort_by" name="sort_by[]" tabindex="-1" style="min-width:300px;"></select></div><br><br><br><div><div class="col-lg-6" style="padding-left: 0px!important;"><?php echo vtranslate('LBL_LIMIT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div><div class="col-lg-3"></div><div class="col-lg-6"><?php echo vtranslate('LBL_ORDER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></div><br><div><div class="col-lg-6" style="padding-left: 0px!important;"><input type="text" data-fieldtype="string" value="<?php echo $_smarty_tpl->tpl_vars['LIMIT']->value;?>
" class="inputElement" style="max-width: 150px" name="sort_limit"></div><div class="col-lg-3"></div><div class="col-lg-6"><select class="col-lg-12 select2" id="order_by" name="order_by" style="max-width: 100px"><option value="ASC" selected >ASC</option><option value="DESC">DESC</option></select></div></div></div><span class="col-lg-2">&nbsp;</span><span class="col-lg-3"><div><span><?php echo vtranslate('LBL_DISPLAY_GRID',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='display_grid' name='displaygrid'style='min-width:300px;' class="select2 col-lg-10"><option value="0"<?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaygrid')=='0'){?>selected="selected"<?php }?>><?php echo vtranslate('No',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="1"<?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaygrid')=='1'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><br><div><span><?php echo vtranslate('LBL_DISPLAY_LABEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='display_label_chart'name='displaylabel' style='min-width:300px;'class="select2 col-lg-10"><option value="0"<?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaylabel')=='0'){?>selected="selected"<?php }?>><?php echo vtranslate('No',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="1"<?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('displaylabel')!='0'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div></span><span class="col-lg-1">&nbsp;</span><span class="col-lg-3"><div><span><?php echo vtranslate('LBL_LEGEND_VALUE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='legendvalue' name='legendvalue' style='min-width:300px;'class="select2"><option value="0"<?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='0'){?>selected="selected"<?php }?>><?php echo vtranslate('No',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="1"<?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='1'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="2" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='2'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes - Value (Percentage)',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="3" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='3'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes - Value',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="4" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('legendvalue')=='4'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes - Percentage',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><br><div><span><?php echo vtranslate('LBL_FOTMAT_LARGE_MUNBER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row"><select id='formatlargenumber' name='formatlargenumber'style='min-width:300px;' class="select2"><option value="0"<?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('formatlargenumber')=='0'){?>selected="selected"<?php }?>><?php echo vtranslate('No',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="1"<?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('formatlargenumber')=='1'){?>selected="selected"<?php }?>><?php echo vtranslate('Yes',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><br><div class="label-drawline" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='barChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='horizontalBarChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='stackedChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='barFunnelChart'){?> style="display: none" <?php }?>><span><?php echo vtranslate('Draw Horizontal Line',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div><br><div class="row input-drawline" <?php if ($_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='barChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='horizontalBarChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='stackedChart'&&$_smarty_tpl->tpl_vars['CHART_MODEL']->value->getChartType()!='barFunnelChart'){?> style="display: none" <?php }?>><input type="number" id='drawline' name='drawline'style='max-width:300px;' class="inputElement"value="<?php echo $_smarty_tpl->tpl_vars['CHART_MODEL']->value->get('drawline');?>
"/></div></span></div></div><br><br><div class='row alert-info' style="padding: 20px;"><span class='span alert-info'><span><i class="fa fa-info-circle"></i>&nbsp;&nbsp;&nbsp;<?php echo vtranslate('LBL_PLEASE_SELECT_ATLEAST_ONE_GROUP_FIELD_AND_DATA_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php echo vtranslate('LBL_FOR_BAR_GRAPH_AND_LINE_GRAPH_SELECT_3_MAX_DATA_FIELDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></span></div></div><?php }} ?>