<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 10:58:20
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/VTEModules.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4173543864cb884cc5a126-98982088%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '61bb6a321c56b5105d74c54ece0a1e03f1458cb1' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/VTEModules.tpl',
      1 => 1691057505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4173543864cb884cc5a126-98982088',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SEARCHMODE' => 0,
    'SEARCH_KEY' => 0,
    'STORECATS' => 0,
    'STORECAT' => 0,
    'VTEMODULES' => 0,
    'VTEMODULE' => 0,
    'VTMODULES' => 0,
    'imageSource' => 0,
    'previewImages' => 0,
    'previewImage' => 0,
    'FIRST_IMAGE' => 0,
    'CUSTOMERLOGINED' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb884cc7cdd',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb884cc7cdd')) {function content_64cb884cc7cdd($_smarty_tpl) {?>
<input type="hidden" name="searchmode" id="searchmode" value="<?php echo $_smarty_tpl->tpl_vars['SEARCHMODE']->value;?>
"/><input type="hidden" name="search_key" id="search_key" value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_KEY']->value;?>
"/><div class="row-fluid"><?php if (empty($_smarty_tpl->tpl_vars['STORECATS']->value)){?><table class="emptyRecordsDiv"><tbody><tr><td><?php echo vtranslate('LBL_NO_EXTENSIONS_FOUND','VTEStore');?>
</td></tr></tbody></table><?php }else{ ?><?php  $_smarty_tpl->tpl_vars['STORECAT'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['STORECAT']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['STORECATS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['STORECAT']->key => $_smarty_tpl->tpl_vars['STORECAT']->value){
$_smarty_tpl->tpl_vars['STORECAT']->_loop = true;
?><div class="clearfix"></div><div ><h3 style="margin-top: 60px; margin-bottom: 15px; font-size: 34px; font-weight: 900;"><u><?php echo $_smarty_tpl->tpl_vars['STORECAT']->value['store_category_name'];?>
</u></h3><?php echo $_smarty_tpl->tpl_vars['STORECAT']->value['store_category_desc'];?>
<br><br></div><div class="clearfix"></div><div class="row-fluid"><?php $_smarty_tpl->tpl_vars['VTEMODULES'] = new Smarty_variable($_smarty_tpl->tpl_vars['STORECAT']->value['extensions'], null, 0);?><?php  $_smarty_tpl->tpl_vars['VTEMODULE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['VTEMODULE']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['VTEMODULES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['VTEMODULE']->key => $_smarty_tpl->tpl_vars['VTEMODULE']->value){
$_smarty_tpl->tpl_vars['VTEMODULE']->_loop = true;
?><div class="col-sm-4 col-md-3 vtestore-module"><div class="extension_container extensionWidgetContainer"><input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
" name="extensionName"><input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->id;?>
" name="extensionId"><input type="hidden" value="<?php if (in_array($_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name,$_smarty_tpl->tpl_vars['VTMODULES']->value)){?>Upgrade<?php }else{ ?>Install<?php }?>" name="moduleAction"><a href="index.php?module=VTEStore&parent=Settings&view=Settings&mode=getModuleDetail&extensionId=<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->id;?>
&extensionName=<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
" class="module_wrapper"><?php if ($_smarty_tpl->tpl_vars['VTEMODULE']->value->primary_image!=''){?><?php $_smarty_tpl->tpl_vars['imageSource'] = new Smarty_variable($_smarty_tpl->tpl_vars['VTEMODULE']->value->primary_image, null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['imageSource'] = new Smarty_variable('layouts/v7/modules/VTEStore/resources/images/unavailable.png', null, 0);?><?php }?><img class="module-image" src="<?php echo $_smarty_tpl->tpl_vars['imageSource']->value;?>
" /><span class="hide"><?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_label;?>
</span></a><div class="module_short_description" id="short_description_<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->id;?>
" style=""><?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['VTEMODULE']->value->preview_description);?>
</div><div class="all-function"><?php $_smarty_tpl->tpl_vars['previewImages'] = new Smarty_variable(explode("||",$_smarty_tpl->tpl_vars['VTEMODULE']->value->preview_image), null, 0);?><?php $_smarty_tpl->tpl_vars['FIRST_IMAGE'] = new Smarty_variable('', null, 0);?><?php  $_smarty_tpl->tpl_vars['previewImage'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['previewImage']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['previewImages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['previewImage']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['previewImage']->key => $_smarty_tpl->tpl_vars['previewImage']->value){
$_smarty_tpl->tpl_vars['previewImage']->_loop = true;
 $_smarty_tpl->tpl_vars['previewImage']->iteration++;
?><?php if ($_smarty_tpl->tpl_vars['previewImage']->value){?><?php $_smarty_tpl->tpl_vars['FIRST_IMAGE'] = new Smarty_variable($_smarty_tpl->tpl_vars['previewImage']->value, null, 0);?><?php break 1?><?php }?><?php } ?><a href="<?php echo $_smarty_tpl->tpl_vars['FIRST_IMAGE']->value;?>
" rel="group<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->id;?>
" id="Preview<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
" class="vte-btn btn btn-default grouped_elements"><i class="fa fa-photo"></i>&nbsp;<?php echo vtranslate('LBL_PREVIEW','VTEStore');?>
</a><div style="display: none"><?php  $_smarty_tpl->tpl_vars['previewImage'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['previewImage']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['previewImages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['previewImage']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['previewImage']->key => $_smarty_tpl->tpl_vars['previewImage']->value){
$_smarty_tpl->tpl_vars['previewImage']->_loop = true;
 $_smarty_tpl->tpl_vars['previewImage']->iteration++;
?><?php if ($_smarty_tpl->tpl_vars['previewImage']->iteration>1){?><a class="grouped_elements" rel="group<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->id;?>
" href="<?php echo $_smarty_tpl->tpl_vars['previewImage']->value;?>
"></a><?php }?><?php } ?></div><a id="VideoDemo<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
" href="<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->extvideolink;?>
" class="vte-btn btn btn-default iframe"><i class="fa fa-video-camera"></i>&nbsp;<?php echo vtranslate('LBL_VIDEO','VTEStore');?>
</a><a href="javascript: void(0);" id="Preview<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
" class="vte-btn btn btn-default btnMoreDetail"><i class="fa fa-info"></i>&nbsp;<?php echo vtranslate('LBL_DETAILS','VTEStore');?>
</a><?php if (in_array($_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name,$_smarty_tpl->tpl_vars['VTMODULES']->value)){?><div class="vte-btn btn-group"><a href="javascript: void(0);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo vtranslate('LBL_OPTIONS','VTEStore');?>
<span style="margin-left: 5px;" class="caret"></span></a><ul class="dropdown-menu" role="menu"><li><a href="<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->setting_url;?>
" target="_blank" title="<?php echo vtranslate('Extension settings','VTEStore');?>
"><?php echo vtranslate('LBL_CONFIG','VTEStore');?>
</a></li><div class="divider"></div><li><a href="javascript: void(0);" class="HealthCheck" data-url="index.php?module=VTEStore&parent=Settings&view=HealthCheck&extensionId=<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->id;?>
&extensionName=<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
"><?php echo vtranslate('LBL_INTEGRITY_CHECK','VTEStore');?>
<span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_INTEGRITY_CHECK','VTEStore');?>
"><i class="fa fa-info-circle"></i></span></a></li><div class="divider"></div><li class="oneclickInstallFree" data-message="<?php echo vtranslate('LBL_MESSAGE_INSTALLED_UPGRAGE_TO_STABLE','VTEStore');?>
" data-svn="stable"><a href="javascript: void(0);" id="UpgradeStable<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
"><?php echo vtranslate('LBL_INSTALLED_UPGRAGE_TO_STABLE','VTEStore');?>
<span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_UPGRAGE_TO_STABLE','VTEStore');?>
"><i class="fa fa-info-circle"></i></span></a></li><li class="oneclickInstallFree" data-message="<?php echo vtranslate('LBL_MESSAGE_INSTALLED_UPGRAGE_TO_LASTEST','VTEStore');?>
" data-svn="lastest"><a href="javascript: void(0);" id="UpgradeAlpha<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
"><?php echo vtranslate('LBL_INSTALLED_UPGRAGE_TO_LASTEST_ONLIST','VTEStore');?>
<span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_UPGRAGE_TO_LASTEST','VTEStore');?>
"><i class="fa fa-info-circle"></i></span></a></li><div class="divider"></div><li class="oneclickRegenerateLicense" data-message="<?php echo vtranslate('LBL_MESSAGE_REGENERATE_LICENSE','VTEStore');?>
"><a href="javascript: void(0);" id="RegenerateLicense<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
"><?php echo vtranslate('LBL_REGENERATE_LICENSE','VTEStore');?>
<span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_REGENERATE_LICENSE','VTEStore');?>
"><i class="fa fa-info-circle"></i></span></a></li><div class="divider"></div><li class="uninstallExtension" data-message="<?php echo vtranslate('LBL_UNINSTALL','VTEStore');?>
" data-svn="stable"><a href="javascript: void(0);" id="UNINSTALL<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
"><?php echo vtranslate('LBL_UNINSTALL','VTEStore');?>
<span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_UNINSTALL','VTEStore');?>
"><i class="fa fa-info-circle"></i></span></a></li></ul></div><?php }else{ ?><a href="javascript: void(0);" id="Install<?php echo $_smarty_tpl->tpl_vars['VTEMODULE']->value->module_name;?>
" class="oneclickInstallFree btn btn-success vte-btn <?php if ($_smarty_tpl->tpl_vars['CUSTOMERLOGINED']->value>0){?>btn-success authenticated<?php }else{ ?>loginRequired<?php }?>" data-svn="stable"><?php echo vtranslate('LBL_INSTALL','VTEStore');?>
</a><?php }?><div class="clearfix"></div></div></div></div><?php } ?><div class="clearfix"></div></div><?php } ?><?php }?></div>


    <script>
        jQuery(document).ready(function() {
            //Watch video demo
            $(".various").fancybox({
                width    : 1280,
                height   : 720,
                fitToView   : false,
                autoSize    : false,
                closeClick  : false,
                openEffect  : 'elastic',
                closeEffect : 'none'
            });
        });
    </script>
<?php }} ?>