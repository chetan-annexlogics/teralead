<?php /* Smarty version Smarty-3.1.7, created on 2023-08-10 12:47:19
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VGSMultiSender/SettingsIndex.tpl" */ ?>
<?php /*%%SmartyHeaderCode:209334404664d4dc57baf5b6-13597748%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8a283f90e43d8ecf9170069f21b4fd313bde15e5' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VGSMultiSender/SettingsIndex.tpl',
      1 => 1689003662,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '209334404664d4dc57baf5b6-13597748',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'PARENT_MODULE' => 0,
    'RMU_FIELDS_ARRAY' => 0,
    'RMU_FIELDS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64d4dc57bd195',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64d4dc57bd195')) {function content_64d4dc57bd195($_smarty_tpl) {?><div class="accountsSMTP">
    <div class="accountsSMTP_header">
        <h3 style="text-align: center"><?php echo vtranslate('Multi SMTP accounts',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h3>
        <input type="hidden" id="parent_view" value="<?php echo $_smarty_tpl->tpl_vars['PARENT_MODULE']->value;?>
">
        <div class="row" style="margin: 1em;">
            <div class="alert alert-warning">
                <?php echo vtranslate('notice',$_smarty_tpl->tpl_vars['MODULE']->value);?>

            </div>
        </div>
    </div>
    <div>
        <div style="width: 100%;margin: auto;">
            <div style="width:100%; height: 1%;" class="clearfix">
                <button class="btn pull-right btn-success btn-sm" onclick="window.location.href='index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&view=SettingsAddNew&parent=Settings';return false;">
                    <?php echo vtranslate('Add New',$_smarty_tpl->tpl_vars['MODULE']->value);?>

                </button>
            </div>
            <div class="accountsSMTP_over">
                <table class="table table-bordered listViewEntriesTable">
                    <thead>
                    <tr class="listViewHeaders">
                        <th> <?php echo vtranslate('User Name',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('SMTP Server',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('SMTP User',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('Password',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('From email address',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('From Name',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('Batch Count',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('Batch Delay',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('Requires Authentication',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                        <th> <?php echo vtranslate('Actions',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 </th>
                    </tr>
                    </thead>
                    <?php  $_smarty_tpl->tpl_vars['RMU_FIELDS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['RMU_FIELDS']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RMU_FIELDS_ARRAY']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['RMU_FIELDS']->key => $_smarty_tpl->tpl_vars['RMU_FIELDS']->value){
$_smarty_tpl->tpl_vars['RMU_FIELDS']->_loop = true;
?>
                        <tr class="listViewEntries">
                            <td class="listViewEntryValue" nowrap> <?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['user_name'];?>
</td>
                            <td class="listViewEntryValue" nowrap> <?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['server_name'];?>
</td>
                            <td class="listViewEntryValue" nowrap> <?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['smtpuser'];?>
 </td>
                            <td class="listViewEntryValue" nowrap> <?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['password'];?>
 </td>
                            <td class="listViewEntryValue" nowrap> <?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['email_from'];?>
 </td>
                            <td class="listViewEntryValue" nowrap> <?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['from_name'];?>
 </td>
                            <td class="listViewEntryValue" nowrap> <?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['batch_count'];?>
 </td>
                            <td class="listViewEntryValue" nowrap> <?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['batch_delay'];?>
 </td>
                            <td class="listViewEntryValue" nowrap> <?php if ($_smarty_tpl->tpl_vars['RMU_FIELDS']->value['smtp_auth']){?>Yes<?php }else{ ?>No<?php }?></td>
                            <td class="listViewEntryValue" nowrap>
                                <a class="lockRecordButton" id="<?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['id'];?>
" data-locked="<?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['locked'];?>
">
                                    <?php if ($_smarty_tpl->tpl_vars['RMU_FIELDS']->value['locked']){?>
                                        <i title="Locked" class="fas fa-lock"></i>
                                    <?php }else{ ?>
                                        <i title="Unlocked" class="fas fa-lock-open"></i>
                                    <?php }?>
                                </a>
                                <a class="editRecordButton" id="<?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['id'];?>
"
                                   href="?module=VGSMultiSender&view=SettingsAddNew&parent=Settings&id=<?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['id'];?>
">
                                    <i title="Edit" class="fa fa-pencil"></i>
                                </a>
                                <a  class="deleteRecordButton" id="<?php echo $_smarty_tpl->tpl_vars['RMU_FIELDS']->value['id'];?>
">
                                    <i title="Delete" class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
<link type='text/css' rel='stylesheet' href='layouts/v7/modules/VGSMultiSender/css/VGSMultiSender.css'><?php }} ?>