<?php /* Smarty version Smarty-3.1.7, created on 2023-12-05 10:48:35
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Contacts/ShowCustomPopup.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1855551274656f000359af53-69747746%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '475a21e089e993f3697e021591f44c823f2f24ab' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Contacts/ShowCustomPopup.tpl',
      1 => 1698546383,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1855551274656f000359af53-69747746',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SINGLE_MODULE' => 0,
    'MODULE' => 0,
    'PICKIST_DEPENDENCY_DATASOURCE' => 0,
    'MODULE_NAME' => 0,
    'RECORD_ID' => 0,
    'ADD_FIELDS' => 0,
    'FIELD_NAME' => 0,
    'ALL_FIELDS' => 0,
    'RECORD_MODEL' => 0,
    'DEFAULT_VALUE' => 0,
    'FIELD_MODEL' => 0,
    'refrenceList' => 0,
    'COUNTER' => 0,
    'isReferenceField' => 0,
    'refrenceListCount' => 0,
    'DISPLAYID' => 0,
    'REFERENCED_MODULE_STRUCTURE' => 0,
    'value' => 0,
    'REFERENCED_MODULE_NAME' => 0,
    'TAXCLASS_DETAILS' => 0,
    'taxCount' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_656f000362d6c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_656f000362d6c')) {function content_656f000362d6c($_smarty_tpl) {?><div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">

            <h4><?php echo vtranslate($_smarty_tpl->tpl_vars['SINGLE_MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo vtranslate('LBL_EDIT');?>
</h4>
        </div>
        <form id="ContactCustomPopup" name="ContactCustomPopup" action="index.php" enctype="multipart/form-data">
            <div class="modal-body">
                <?php if (!empty($_smarty_tpl->tpl_vars['PICKIST_DEPENDENCY_DATASOURCE']->value)){?>
                    <input type="hidden" name="picklistDependency" value='<?php echo Vtiger_Util_Helper::toSafeHTML($_smarty_tpl->tpl_vars['PICKIST_DEPENDENCY_DATASOURCE']->value);?>
' />
                <?php }?>
                <input type="hidden" name="module" value="Contacts">
                <input type="hidden" name="source_module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE_NAME']->value;?>
">
                <input type="hidden" name="record" id="recordId" value="<?php echo $_smarty_tpl->tpl_vars['RECORD_ID']->value;?>
">
                <input type="hidden" name="action" value="ActionAjax">
                <input type="hidden" name="mode" value="doUpdateFields">
                <div class="fieldBlockContainer">
                    <table class="table table-borderless">
                        <tr>
                            <?php $_smarty_tpl->tpl_vars['COUNTER'] = new Smarty_variable(0, null, 0);?>
                            <?php  $_smarty_tpl->tpl_vars['FIELD_NAME'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD_NAME']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ADD_FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_NAME']->key => $_smarty_tpl->tpl_vars['FIELD_NAME']->value){
$_smarty_tpl->tpl_vars['FIELD_NAME']->_loop = true;
?>
                            <?php $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['ALL_FIELDS']->value[$_smarty_tpl->tpl_vars['FIELD_NAME']->value], null, 0);?>
                            <?php $_smarty_tpl->tpl_vars['DEFAULT_VALUE'] = new Smarty_variable($_smarty_tpl->tpl_vars['RECORD_MODEL']->value->get($_smarty_tpl->tpl_vars['FIELD_NAME']->value), null, 0);?>
                            <?php $_smarty_tpl->tpl_vars['FIELD_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->set('fieldvalue',$_smarty_tpl->tpl_vars['DEFAULT_VALUE']->value), null, 0);?>
                            <?php $_smarty_tpl->tpl_vars["isReferenceField"] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType(), null, 0);?>
                            <?php $_smarty_tpl->tpl_vars["refrenceList"] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getReferenceList(), null, 0);?>
                            <?php $_smarty_tpl->tpl_vars["refrenceListCount"] = new Smarty_variable(count($_smarty_tpl->tpl_vars['refrenceList']->value), null, 0);?>
                            <?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isEditable()==true){?>
                                <?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')=="19"){?>
                                    <?php if ($_smarty_tpl->tpl_vars['COUNTER']->value=='1'){?>
                                        <td></td><td></td></tr><tr>
                                        <?php $_smarty_tpl->tpl_vars['COUNTER'] = new Smarty_variable(0, null, 0);?>
                                    <?php }?>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['COUNTER']->value==2){?>
                                    </tr><tr>
                                    <?php $_smarty_tpl->tpl_vars['COUNTER'] = new Smarty_variable(1, null, 0);?>
                                <?php }else{ ?>
                                    <?php $_smarty_tpl->tpl_vars['COUNTER'] = new Smarty_variable($_smarty_tpl->tpl_vars['COUNTER']->value+1, null, 0);?>
                                <?php }?>
                                <td class="fieldLabel alignMiddle">
                                    <?php if ($_smarty_tpl->tpl_vars['isReferenceField']->value=="reference"){?>
                                        <?php if ($_smarty_tpl->tpl_vars['refrenceListCount']->value>1){?>
                                            <?php $_smarty_tpl->tpl_vars["DISPLAYID"] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'), null, 0);?>
                                            <?php $_smarty_tpl->tpl_vars["REFERENCED_MODULE_STRUCTURE"] = new Smarty_variable($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getUITypeModel()->getReferenceModule($_smarty_tpl->tpl_vars['DISPLAYID']->value), null, 0);?>
                                            <?php if (!empty($_smarty_tpl->tpl_vars['REFERENCED_MODULE_STRUCTURE']->value)){?>
                                                <?php $_smarty_tpl->tpl_vars["REFERENCED_MODULE_NAME"] = new Smarty_variable($_smarty_tpl->tpl_vars['REFERENCED_MODULE_STRUCTURE']->value->get('name'), null, 0);?>
                                            <?php }?>
                                            <select style="width: 140px;" class="select2 referenceModulesList">
                                                <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['refrenceList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
                                                    <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['value']->value==$_smarty_tpl->tpl_vars['REFERENCED_MODULE_NAME']->value){?> selected <?php }?>><?php echo vtranslate($_smarty_tpl->tpl_vars['value']->value,$_smarty_tpl->tpl_vars['value']->value);?>
</option>
                                                <?php } ?>
                                            </select>
                                        <?php }else{ ?>
                                            <?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['MODULE']->value);?>

                                        <?php }?>
                                    <?php }elseif($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')=="83"){?>
                                        <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getUITypeModel()->getTemplateName(),$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('COUNTER'=>$_smarty_tpl->tpl_vars['COUNTER']->value,'MODULE'=>$_smarty_tpl->tpl_vars['MODULE']->value), 0);?>

                                        <?php if ($_smarty_tpl->tpl_vars['TAXCLASS_DETAILS']->value){?>
                                            <?php $_smarty_tpl->tpl_vars['taxCount'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['TAXCLASS_DETAILS']->value)%2, null, 0);?>
                                            <?php if ($_smarty_tpl->tpl_vars['taxCount']->value==0){?>
                                                <?php if ($_smarty_tpl->tpl_vars['COUNTER']->value==2){?>
                                                    <?php $_smarty_tpl->tpl_vars['COUNTER'] = new Smarty_variable(1, null, 0);?>
                                                <?php }else{ ?>
                                                    <?php $_smarty_tpl->tpl_vars['COUNTER'] = new Smarty_variable(2, null, 0);?>
                                                <?php }?>
                                            <?php }?>
                                        <?php }?>
                                    <?php }else{ ?>
                                        <?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['MODULE']->value);?>

                                    <?php }?>
                                    &nbsp;<?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->isMandatory()==true){?> <span class="redColor">*</span> <?php }?>
                                </td>
                                <?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')!='83'){?>
                                    <td class="fieldValue" <?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType()=='boolean'){?> style="width:25%" <?php }?> <?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('uitype')=='19'){?> colspan="3" <?php $_smarty_tpl->tpl_vars['COUNTER'] = new Smarty_variable($_smarty_tpl->tpl_vars['COUNTER']->value+1, null, 0);?> <?php }?>>
                                      <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getUITypeModel()->getTemplateName(),$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

                                    </td>
                                <?php }?>
                            <?php }?>
                            <?php } ?>
                            
                            <?php if ((1 & $_smarty_tpl->tpl_vars['COUNTER']->value)){?>
                                <td></td>
                                <td></td>
                            <?php }?>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">

                <button class="btn btn-success" type="button" name="contactButtonsSave" ><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>
            </div>
        </form>
    </div>
</div><?php }} ?>