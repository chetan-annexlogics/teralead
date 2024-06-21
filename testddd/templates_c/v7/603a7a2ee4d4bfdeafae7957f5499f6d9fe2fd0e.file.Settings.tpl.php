<?php /* Smarty version Smarty-3.1.7, created on 2024-01-22 21:04:44
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEWEBHOOKS/Settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:133658272565aed86c169651-52117706%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '603a7a2ee4d4bfdeafae7957f5499f6d9fe2fd0e' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEWEBHOOKS/Settings.tpl',
      1 => 1705957470,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '133658272565aed86c169651-52117706',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_65aed86c17321',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_65aed86c17321')) {function content_65aed86c17321($_smarty_tpl) {?>
<style>
    .web-hook-info{
        border: 1px solid rgb(217, 217, 217);
        border-left: #52a9cd solid 4px;
        max-height: 419px;
        height: 120px;
    }

    .web-hook-info > .label-info{
        color: #52a9cd;
        background-color: white !important;
    }

    .web-hook-info > .content-info{
        resize: none;
        border: none;
        width: 100%;
        color: #9b9997;
        max-height: 140px;
        height: 140px;
    }
</style>
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3><?php echo vtranslate('VTEWEBHOOKS','VTEWEBHOOKS');?>
</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <div class="web-hook-info col-lg-12 col-sm-12 col-xs-12">
                <div class="label-info">
                    <h5>
                        <span class="glyphicon glyphicon-info-sign"></span> Info
                    </h5>
                </div>
                <span>Webhooks have to be creating using workflows. Please go to Automation > Workflows, create new/edit existing workflow and you see under "Add Tasks" - there will be new option for "Webhooks".</br></br>NOTE: Please refer to the extension user guide for more details. User guide includes detailed explanation of how the extension.</span>
            </div>
        </div>
    </div>
</div><?php }} ?>