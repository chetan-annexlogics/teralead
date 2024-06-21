<?php /* Smarty version Smarty-3.1.7, created on 2023-08-10 12:47:44
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VGSMultiSender/SMTPAddNew.tpl" */ ?>
<?php /*%%SmartyHeaderCode:212996365964d4dc70a210c5-93708047%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0da55f67ee085db4132aaafd3484c2470160c57' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VGSMultiSender/SMTPAddNew.tpl',
      1 => 1688715136,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '212996365964d4dc70a210c5-93708047',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'PARENT_MODULE' => 0,
    'MODULE' => 0,
    'USER_LIST' => 0,
    'RECORD' => 0,
    'USER_ID' => 0,
    'selected' => 0,
    'USER_NAME' => 0,
    'CURRENT_USER_ID' => 0,
    'checked' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64d4dc70a437c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64d4dc70a437c')) {function content_64d4dc70a437c($_smarty_tpl) {?>
<div class="addSMTPFormWrap">
    <input type="hidden" id="parent_view" value="<?php echo $_smarty_tpl->tpl_vars['PARENT_MODULE']->value;?>
">
    <h3 style="padding-bottom: 1em;text-align: center"><?php echo vtranslate('Multi SMTP accounts',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h3>
    
    <div class="addSMTPForm">
        <table class="table table-bordered table-condensed themeTableColor" style="margin-top: 1em;">
            <tbody>
                <?php if ($_smarty_tpl->tpl_vars['PARENT_MODULE']->value=='Settings'){?>
                     <tr>
                        <td width="50%" colspan="2">
                            <label class="muted pull-right marginRight10px"><?php echo vtranslate('User Name',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                        </td>
                        <td colspan="2" style="border-left: none;">
                            <select name='user_id' class="select2-container select2 selectUserName">
                                    <option value=""></option>
                                <?php  $_smarty_tpl->tpl_vars['USER_NAME'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['USER_NAME']->_loop = false;
 $_smarty_tpl->tpl_vars['USER_ID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['USER_LIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['USER_NAME']->key => $_smarty_tpl->tpl_vars['USER_NAME']->value){
$_smarty_tpl->tpl_vars['USER_NAME']->_loop = true;
 $_smarty_tpl->tpl_vars['USER_ID']->value = $_smarty_tpl->tpl_vars['USER_NAME']->key;
?>
                                    <?php $_smarty_tpl->tpl_vars['selected'] = new Smarty_variable('', null, 0);?>
                                    <?php if ($_smarty_tpl->tpl_vars['RECORD']->value['userid']==$_smarty_tpl->tpl_vars['USER_ID']->value){?>
                                        <?php $_smarty_tpl->tpl_vars['selected'] = new Smarty_variable('selected="selected"', null, 0);?>
                                    <?php }?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['USER_ID']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['selected']->value;?>
><?php echo $_smarty_tpl->tpl_vars['USER_NAME']->value;?>
</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                <?php }else{ ?>
                    <input type="hidden" name='user_id' value="<?php echo $_smarty_tpl->tpl_vars['CURRENT_USER_ID']->value;?>
">
                <?php }?>
                <?php if (!isset($_smarty_tpl->tpl_vars['RECORD']->value)){?>
                    <?php $_smarty_tpl->tpl_vars['RECORD'] = new Smarty_variable(array(), null, 0);?>
                <?php }?>
                <input type="hidden" name='id' id="id" value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value['id'];?>
">
                <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('SMTP Server',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                    </td>
                    <td colspan="2" style="border-left: none;">
                        <input type="text"
                               name="server_name"
                               id="server_name"
                               value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value['server_name'];?>
"
                               class="inputField">
                    </td>
                </tr>
                <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('SMTP User',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                    </td>
                    <td colspan="2" style="border-left: none;">
                        <input type="text"
                               name="user_name"
                               id="user_name"
                               value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value['user_name'];?>
"
                               class="inputField">
                    </td>
                </tr>
                   <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('Password',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                    </td>
                    <td colspan="2" style="border-left: none;">
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value['password'];?>
"
                               value=""
                               class="inputField">
                    </td>
                </tr>
                <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('From Address',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                    </td>
                    <td colspan="2" style="border-left: none;">
                        <input type="text"
                               name="email_from"
                               id="email_from"
                               value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value['email_from'];?>
"
                               class="inputField">
                    </td>
                </tr>
                <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('From Name',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                    </td>
                    <td colspan="2" style="border-left: none;">
                        <input type="text"
                               name="from_name"
                               id="from_name"
                               value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value['from_name'];?>
"
                               class="inputField">
                    </td>
                </tr>
                <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('Batch Count',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                    </td>
                    <td colspan="2" style="border-left: none;">
                        <input type="text"
                               name="batch_count"
                               id="batch_count"
                               value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value['batch_count'];?>
"
                               class="inputField">
                    </td>
                </tr>
                <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('Batch Delay',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                    </td>
                    <td colspan="2" style="border-left: none;">
                        <input type="text"
                               name="batch_delay"
                               id="batch_delay"
                               value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value['batch_delay'];?>
"
                               class="inputField">
                    </td>
                </tr>
                <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px"><?php echo vtranslate('Auth',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label>
                    </td>
                    <?php $_smarty_tpl->tpl_vars['checked'] = new Smarty_variable('', null, 0);?>
                    <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['RECORD']->value['smtp_auth'];?>
<?php $_tmp1=ob_get_clean();?><?php if (($_tmp1)){?>
                        <?php $_smarty_tpl->tpl_vars['checked'] = new Smarty_variable(" checked='checked' ", null, 0);?>
                    <?php }?>
                    <td colspan="2" style="border-left: none;" class="checkboxField">
                        <input type="checkbox"
                               name="smtp_auth"
                               id="smtp_auth"
                                <?php echo $_smarty_tpl->tpl_vars['checked']->value;?>

                               value="" class="inputCheckbox">
                    </td>
                </tr> 
            </tbody>
        </table>

        <div class="buttonsRaw clearfix">
            <button class="btn btn-success pull-right" style="margin-bottom: 0.5em;" id="add_entry">
                <?php echo vtranslate('Save',$_smarty_tpl->tpl_vars['MODULE']->value);?>

            </button>
            <a class="pull-right cancelLink" style="margin-right: 2%;" href="javascript:history.go(-1)"><?php echo vtranslate('Cancel',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
        </div>
    </div>
</div>
<link type='text/css' rel='stylesheet' href='layouts/v7/modules/VGSMultiSender/css/VGSMultiSender.css'><?php }} ?>