<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 10:59:19
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/Warnings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:189900940064cb8887e1a9c9-71651693%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a2654aa76572b5f343f393966baaa243a6a9e4d6' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/Warnings.tpl',
      1 => 1691057505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '189900940064cb8887e1a9c9-71651693',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'WARNINGS' => 0,
    'default_socket_timeout' => 0,
    'max_execution_time' => 0,
    'max_input_time' => 0,
    'memory_limit' => 0,
    'post_max_size' => 0,
    'upload_max_filesize' => 0,
    'simplexml' => 0,
    'dieOnError' => 0,
    'short_open_tag' => 0,
    'mysqlStrictMode' => 0,
    'ERROR_NUM' => 0,
    'MESSAGES' => 0,
    'VTVERSION' => 0,
    'USER_AND_ROLE_ERROR' => 0,
    'max_input_vars' => 0,
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb8887e7a42',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb8887e7a42')) {function content_64cb8887e7a42($_smarty_tpl) {?><div id="globalmodal">
    <div id="massEditContainer" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header contentsBackground">
                <button aria-hidden="true" class="close " data-dismiss="modal" type="button"><span aria-hidden="true" class='fa fa-close'></span></button>
                <h4><?php echo vtranslate('Warnings','VTEStore');?>
 (<?php echo $_smarty_tpl->tpl_vars['WARNINGS']->value;?>
)</h4>
            </div>
            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;">
                <div name="massEditContent" style="overflow: hidden; width: auto; height: auto;">
                    <div class="modal-body tabbable">
                        <div >
                            <?php echo vtranslate('It is recommended to have php.ini values set as above.','VTEStore');?>

                        </div>
                        <div class="summaryWidgetContainer" style="border:1px solid #ccc;">
                            <div style="float: left;text-align: center;width: 100%;">
                                <span style="font-size: 15px;"><strong>PHP.ini <?php echo vtranslate('Requirements','VTEStore');?>
:</strong></span>
                                <span style="text-decoration: underline"><strong><br><?php echo vtranslate('php_ini_desc','VTEStore');?>
</strong></span>
                            </div>
                            <table cellspacing="2px" cellpadding="2px">
                                <tr>
                                    <td width="200"></td>
                                    <td width="170"><strong><?php echo vtranslate('Current Value','VTEStore');?>
</strong></td>
                                    <td width="170"><strong><?php echo vtranslate('Minimum Requirement','VTEStore');?>
</strong></td>
                                    <td><strong><?php echo vtranslate('Recommended Value','VTEStore');?>
</strong></td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['default_socket_timeout']->value>=60){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>default_socket_timeout</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['default_socket_timeout']->value;?>
</td>
                                    <td>60</td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['default_socket_timeout']->value<600){?>#ff8000<?php }else{ ?>#009900<?php }?>">600</td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['max_execution_time']->value==0||$_smarty_tpl->tpl_vars['max_execution_time']->value>=60){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>max_execution_time</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['max_execution_time']->value;?>
</td>
                                    <td>60</td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['max_execution_time']->value>0&&$_smarty_tpl->tpl_vars['max_execution_time']->value<600){?>#ff8000<?php }else{ ?>#009900<?php }?>">600</td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['max_input_time']->value>=60||$_smarty_tpl->tpl_vars['max_input_time']->value==-1){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>max_input_time</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['max_input_time']->value;?>
</td>
                                    <td>60</td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['max_input_time']->value<600&&$_smarty_tpl->tpl_vars['max_input_time']->value!=-1){?>#ff8000<?php }else{ ?>#009900<?php }?>">600</td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['memory_limit']->value>=256){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>memory_limit</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['memory_limit']->value;?>
M</td>
                                    <td>256M</td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['memory_limit']->value<1024){?>#ff8000<?php }else{ ?>#009900<?php }?>">1024M</td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['post_max_size']->value>=12){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>post_max_size</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['post_max_size']->value;?>
M</td>
                                    <td>12M</td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['post_max_size']->value<50){?>#ff8000<?php }else{ ?>#009900<?php }?>">50M</td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['upload_max_filesize']->value>=12){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>upload_max_filesize</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['upload_max_filesize']->value;?>
M</td>
                                    <td>12M</td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['upload_max_filesize']->value<50){?>#ff8000<?php }else{ ?>#009900<?php }?>">50M</td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['simplexml']->value==1){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>SimpleXML</td>
                                    <td><?php if ($_smarty_tpl->tpl_vars['simplexml']->value==1){?><?php echo vtranslate('OK','VTEStore');?>
<?php }else{ ?><?php echo vtranslate('Not Installed','VTEStore');?>
<?php }?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['dieOnError']->value=='false'){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>dieOnError</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['dieOnError']->value;?>
</td>
                                    <td></td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['dieOnError']->value=='true'){?>#ff8000<?php }else{ ?>#009900<?php }?>">false</td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['short_open_tag']->value=='On'){?>#ff8000<?php }else{ ?>#009900<?php }?>">
                                    <td>short_open_tag</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['short_open_tag']->value;?>
</td>
                                    <td></td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['short_open_tag']->value=='On'){?>#ff8000<?php }else{ ?>#009900<?php }?>">Off</td>
                                </tr>
                                <tr style="color: <?php if ($_smarty_tpl->tpl_vars['mysqlStrictMode']->value=='false'){?>#009900<?php }else{ ?>#ff8000<?php }?>">
                                    <td>Mysql Strict Mode</td>
                                    <td><?php if ($_smarty_tpl->tpl_vars['mysqlStrictMode']->value=='false'){?><?php echo vtranslate('Correct','VTEStore');?>
<?php }else{ ?><?php echo vtranslate('Incorrect','VTEStore');?>
<?php }?></td>
                                    <td></td>
                                    <td style="color: <?php if ($_smarty_tpl->tpl_vars['mysqlStrictMode']->value=='true'){?>#ff8000<?php }else{ ?>#009900<?php }?>"></td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: center;">
                                        <a class="btn btn-primary" href="https://www.vtexperts.com/premium-extension-pack-php-ini-requirements/" target="_blank">Click here for php.ini instructions</a>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <br>
                        <div class="summaryWidgetContainer" style="border:1px solid #ccc;">
                            <div style="text-align: center;width: 100%;">
                                <span style="font-size: 15px;"><strong><?php echo vtranslate('Errors','VTEStore');?>
 (<?php echo $_smarty_tpl->tpl_vars['ERROR_NUM']->value;?>
)</strong></span>
                                <span style="text-decoration: underline"><strong><br><?php echo vtranslate('error_desc','VTEStore');?>
</strong></span>
                            </div>

                            <div class="summaryWidgetContainer" style="border:1px solid #ccc; max-height: 350px; overflow: auto">
                                <table>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <strong><?php echo vtranslate('File Permissions','VTEStore');?>
:</strong>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 layouts/vlayout/modules: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['layouts_vlayout_modules']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 layouts/vlayout/modules/Settings: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['layouts_vlayout_modules_settings']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['VTVERSION']->value=='vt7'){?>
                                                <br>Folder layouts/v7/modules: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['layouts_v7_modules']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                                <br>Folder layouts/v7/modules/Settings: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['layouts_v7_modules_settings']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font><?php }?>
                                                <br>Folder layouts/v7/modules/Settings/Workflows/Tasks: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['layouts_v7_modules_settings_workflows_tasks']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font><?php }?>
                                            <?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 modules: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['modules']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 user_privileges: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['user_privileges']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('User Ids Insufficient permissions','VTEStore');?>
 sharing_file: <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['insufficient_permissions_sharing_file'])){?><font color="red"><?php echo implode(', ',$_smarty_tpl->tpl_vars['MESSAGES']->value['insufficient_permissions_sharing_file']);?>
 </font> <?php }else{ ?><font color="green">0</font><?php }?>
                                            <br><?php echo vtranslate('User Ids Insufficient permissions','VTEStore');?>
 privileges_file: <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['insufficient_permissions_privileges_file'])){?><font color="red"><?php echo implode(', ',$_smarty_tpl->tpl_vars['MESSAGES']->value['insufficient_permissions_privileges_file']);?>
 </font> <?php }else{ ?><font color="green">0</font><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 test: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['test']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 test/templates_c/v7: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['test_templates_c_vlayout']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 test/vtlib: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['test_vtlib']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 storage: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['storage']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 cache: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['cache']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 10px">
                                            <br><?php echo vtranslate('File','VTEStore');?>
 tabdata.php: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['tabdata']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('File','VTEStore');?>
 parent_tabdata.php: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['parent_tabdata']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('File','VTEStore');?>
 config.inc.php: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['config']==1){?><font color="green">OK</font><?php }else{ ?><font color="red">$root_directory missing '/' at the end</font> <a href="https://www.vtexperts.com/vtiger-extension-insufficient-permissions/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 languages: <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['language_folder_missing'])||!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['insufficient_permissions_language_folder'])){?><font color="red"><?php echo count($_smarty_tpl->tpl_vars['MESSAGES']->value['language_folder_missing'])+count($_smarty_tpl->tpl_vars['MESSAGES']->value['insufficient_permissions_language_folder']);?>
</font><?php }else{ ?><font color="green">OK</font><?php }?>
                                            <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['language_folder_missing'])){?><br><?php echo vtranslate('Language Folder Missing','VTEStore');?>
: <font color="red"><?php echo implode(', ',$_smarty_tpl->tpl_vars['MESSAGES']->value['language_folder_missing']);?>
</font><?php }?>
                                            <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['insufficient_permissions_language_folder'])){?><br><?php echo vtranslate('Insufficient Permissions Language Folder','VTEStore');?>
: <font color="red"><?php echo implode(', ',$_smarty_tpl->tpl_vars['MESSAGES']->value['insufficient_permissions_language_folder']);?>
</font><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 modules/Settings: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['modules_settings']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font><?php }?>
                                            <br><?php echo vtranslate('Folder','VTEStore');?>
 modules/com_vtiger_workflow/tasks: <?php if ($_smarty_tpl->tpl_vars['MESSAGES']->value['modules_com_vtiger_workflow_tasks']==1){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('Insufficient permissions','VTEStore');?>
</font><?php }?>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 10px">
                                            <strong><?php echo vtranslate('Users and Roles','VTEStore');?>
:</strong>
                                            <br><?php echo vtranslate('User Ids Invalid Id','VTEStore');?>
: <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_invalid'])){?><font color="red"><?php echo implode(', ',$_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_invalid']);?>
 </font> <a class="user_ids_invalid" data-url="index.php?module=VTEStore&action=ActionAjax&mode=userIdsInvalid&userids=<?php echo implode(',',$_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_invalid']);?>
" style="text-decoration: underline!important;"><?php echo vtranslate('Click here to fix','VTEStore');?>
</a><?php }else{ ?><font color="green">0</font><?php }?>
                                            <br><?php echo vtranslate('User Ids Invalid Role','VTEStore');?>
: <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_invalid_role'])){?><font color="red"><?php echo implode(', ',$_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_invalid_role']);?>
 </font> <a class="user_ids_invalid_role" data-url="index.php?module=VTEStore&action=ActionAjax&mode=userIdsInvalidRole&userids=<?php echo implode(',',$_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_invalid_role']);?>
" style="text-decoration: underline!important;"><?php echo vtranslate('Click here to fix','VTEStore');?>
</a><?php }else{ ?><font color="green">0</font><?php }?>
                                            <br><?php echo vtranslate('User Ids Missing','VTEStore');?>
 sharing_file: <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_missing_sharing_file'])){?><font color="red"><?php echo implode(', ',$_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_missing_sharing_file']);?>
 </font> <a class="user_ids_missing_file" data-url="index.php?module=VTEStore&action=ActionAjax&mode=userIdsMissingFile&userids=<?php echo implode(',',$_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_missing_sharing_file']);?>
" style="text-decoration: underline!important;"><?php echo vtranslate('Click here to fix','VTEStore');?>
</a><?php }else{ ?><font color="green">0</font><?php }?>
                                            <br><?php echo vtranslate('User Ids Missing','VTEStore');?>
 privileges_file: <?php if (!empty($_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_missing_privileges_file'])){?><font color="red"><?php echo implode(', ',$_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_missing_privileges_file']);?>
 </font> <a class="user_ids_missing_file" data-url="index.php?module=VTEStore&action=ActionAjax&mode=userIdsMissingFile&userids=<?php echo implode(',',$_smarty_tpl->tpl_vars['MESSAGES']->value['user_ids_missing_privileges_file']);?>
" style="text-decoration: underline!important;"><?php echo vtranslate('Click here to fix','VTEStore');?>
</a><?php }else{ ?><font color="green">0</font><?php }?>
                                            
                                            <?php if ($_smarty_tpl->tpl_vars['USER_AND_ROLE_ERROR']->value==1){?>
                                                <br><br><span style="color: #0000ff"><?php echo vtranslate('fix_user_and_role','VTEStore');?>
</span>
                                            <?php }?>
                                            <br /><br />
                                            <strong><?php echo vtranslate('PHP.ini Requirements','VTEStore');?>
:</strong>
                                            <br>max_input_vars: <?php if ($_smarty_tpl->tpl_vars['max_input_vars']->value>=10000){?><font color="green">OK</font><?php }else{ ?><font color="red"><?php echo vtranslate('`max_input_vars` should be greater than 10.000','VTEStore');?>
</font> <a href="https://www.vtexperts.com/premium-extension-pack-php-ini-requirements/" target="_blank" style="text-decoration: underline!important;"> <?php echo vtranslate('LBL_MORE_DETAILS','VTEStore');?>
</a><?php }?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div style="float: left;padding: 10px 0;text-align: center;width: 100%; line-height: 24px;">
                            Need help? Contact us - the support is free.<br>
                            <b>Email</b>: help@vtexperts.com&nbsp;-&nbsp;<b>Phone</b>: +1 (818) 495-5557<br>
                            <a href="javascript:void(0);" onclick="window.open('https://v2.zopim.com/widget/livechat.html?&amp;key=1P1qFzYLykyIVMZJPNrXdyBilLpj662a=en', '_blank', 'location=yes,height=600,width=500,scrollbars=yes,status=yes');"> <img src="layouts/vlayout/modules/VTEStore/resources/images/livechat.png" style="height: 28px"></a><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right cancelLinkContainer" style="margin-top: 0px;"><a class="cancelLink" type="reset" data-dismiss="modal"><strong><?php echo vtranslate('LBL_CLOSE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></div>
            </div>
        </div>
    </div>
</div>
<?php }} ?>