<?php /* Smarty version Smarty-3.1.7, created on 2024-01-12 11:38:25
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ChartReportContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:96433020665a124b1a35f73-83254371%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9aa8b117c0ca08259c9a65f3ec8465be7c1a3801' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VReports/ChartReportContents.tpl',
      1 => 1704289893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '96433020665a124b1a35f73-83254371',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'REPORT_CHART_MODEL' => 0,
    'POSITION' => 0,
    'DATA_CHART' => 0,
    'DATA' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65a124b1a3d40',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65a124b1a3d40')) {function content_65a124b1a3d40($_smarty_tpl) {?>




<input type='hidden' name='displaylabel' value="<?php echo $_smarty_tpl->tpl_vars['REPORT_CHART_MODEL']->value->get('displaylabel');?>
" />

<br>
<style>
    #chartjs-tooltip {
        opacity: 1;
        position: absolute;
        background: rgba(0, 0, 0,1);
        color: white;
        border-radius: 3px;
        -webkit-transition: all .1s ease;
        transition: all .1s ease;
        pointer-events: none;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
    }
</style>
<div class="device-xs visible-xs"></div>
<div class="device-sm visible-sm"></div>
<div class="device-md visible-md"></div>
<div class="device-lg visible-lg"></div>
<div class="device-xl visible-xl"></div>

<div class="dashboardWidgetContent" style="">
    <div class="grid-stack">
            <div class="grid-stack-item dashboardWidgetGridStack"
                 <?php if ($_smarty_tpl->tpl_vars['POSITION']->value){?>
                     data-gs-x=<?php echo $_smarty_tpl->tpl_vars['POSITION']->value->x;?>

                     data-gs-y=<?php echo $_smarty_tpl->tpl_vars['POSITION']->value->y;?>

                     data-gs-width=<?php echo $_smarty_tpl->tpl_vars['POSITION']->value->width;?>

                     data-gs-height=<?php echo $_smarty_tpl->tpl_vars['POSITION']->value->height;?>

                 <?php }else{ ?>
                     data-gs-x="3"
                     data-gs-y="0"
                     data-gs-width="6"
                     data-gs-height="6"
                 <?php }?>
                 >
                <div class="panel panel-default grid-stack-item-content">
                    <div id='chartcontent' name='chartcontent' style="width: 100%;height: 100%;" data-mode='Reports'>
                        <input type="hidden" name="datachart" value="<?php echo $_smarty_tpl->tpl_vars['DATA_CHART']->value;?>
"/>
                        <canvas id="chart-area"></canvas>
                        <?php echo $_smarty_tpl->tpl_vars['DATA']->value;?>

                    </div>
                </div>
            </div>
    </div>
</div>
<br>

<?php }} ?>