<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 10:58:20
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/Settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:59462526664cb884cc2a7e4-57209587%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '53c825278c8284eec790c6b9f690259e12f43374' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/VTEStore/Settings.tpl',
      1 => 1691057505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '59462526664cb884cc2a7e4-57209587',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'USE_CUSTOM_HEADER' => 0,
    'SEARCH_KEY' => 0,
    'SEARCHMODE' => 0,
    'SEARCH_FILTER' => 0,
    'EXT_CAGETORIES' => 0,
    'EXT_CAGETORIE' => 0,
    'LOG_FILE' => 0,
    'MODULE' => 0,
    'EXTENSIONS_INSTALLED' => 0,
    'MODULE_DETAIL' => 0,
    'CUSTOMERLOGINED' => 0,
    'GO_TO_EXTENSION_LIST' => 0,
    'MEMBERSHIP_ACTIVATED' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cb884cc44cd',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cb884cc44cd')) {function content_64cb884cc44cd($_smarty_tpl) {?>
<link href="layouts/v7/modules/VTEStore/resources/ui-choose.css" rel="stylesheet" />
<script src="layouts/v7/modules/VTEStore/resources/ui-choose.js"></script>
<script src="layouts/v7/modules/VTEStore/resources/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="layouts/v7/modules/VTEStore/resources/fancybox215/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="layouts/v7/modules/VTEStore/resources/fancybox215/jquery.fancybox.css?v=2.1.5" media="screen" />
<script type="text/javascript" src="layouts/v7/modules/VTEStore/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <?php if ($_smarty_tpl->tpl_vars['USE_CUSTOM_HEADER']->value>0){?>
            <div class="col-md-2" style="padding: 0px;">
                <a href="index.php?module=VTEStore&parent=Settings&view=Settings&reset=1">
                    <h5 style="font-weight: bold; font-size: 20px;"><?php echo vtranslate('MODULE_LBL_CUSTOM','VTEStore');?>
</h5>
                </a>
            </div>
            <div class="col-md-10" style="padding: 0px;">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('HeaderStoreCustom.tpl','VTEStore'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>
        <?php }else{ ?>
            <div class="col-md-2" style="padding: 0px;">
                <a href="index.php?module=VTEStore&parent=Settings&view=Settings&reset=1">
                    <h5 style="font-weight: bold; font-size: 20px;"><?php echo vtranslate('MODULE_LBL','VTEStore');?>
</h5>
                </a>
            </div>
            <div class="col-md-10" style="padding: 0px;">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('HeaderStore.tpl','VTEStore'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>
        <?php }?>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="summaryWidgetContainer" id="VTEStore_settings">
        <div class="container-fluid" id="importModules">
            <div class="row-fluid">
                <div class="row">
                    <div class="col-md-6" style="padding: 0px;">
                        <input type="text" id="searchExtension" class="listSearchContributor inputElement" placeholder="<?php echo vtranslate('LBL_SEARCH_FOR_AN_EXTENSION','VTEStore');?>
" value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_KEY']->value;?>
"/>
                    </div>
                    <div class="col-md-6">
                        <button id="btnSearchExtension" class="btn btn-default"><?php echo vtranslate('LBL_SEARCH','VTEStore');?>
</button>
                        <span id="reset_search_value"><?php if ($_smarty_tpl->tpl_vars['SEARCHMODE']->value==1){?><?php echo vtranslate('LBL_FILTER','VTEStore');?>
: <?php echo $_smarty_tpl->tpl_vars['SEARCH_FILTER']->value;?>
 <u><a href="index.php?module=VTEStore&parent=Settings&view=Settings&reset=1">(<?php echo vtranslate('LBL_CLICK_TO_RESET_THE_SEARCH','VTEStore');?>
)</a></u><?php }?></span>
                        <input type="hidden" id="selectedCagetories" name="selectedCagetories">
                    </div>
                </div>
                <div class="row hide">
                    <h4 style="padding-bottom: 10px;"><?php echo vtranslate('LBL_SELECT_CATEGORY','VTEStore');?>
</h4>
                    <select class="ui-choose" multiple="multiple" id="extension_categories">
                        <?php  $_smarty_tpl->tpl_vars['EXT_CAGETORIE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['EXT_CAGETORIE']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['EXT_CAGETORIES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['EXT_CAGETORIE']->key => $_smarty_tpl->tpl_vars['EXT_CAGETORIE']->value){
$_smarty_tpl->tpl_vars['EXT_CAGETORIE']->_loop = true;
?>
                            <option value="<?php echo $_smarty_tpl->tpl_vars['EXT_CAGETORIE']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['EXT_CAGETORIE']->value->name;?>
</option>
                        <?php } ?>
                    </select>
                </div>
                <br>
            </div>
            </div>

            <div class="contents" id="extensionContainer">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('VTEModules.tpl','VTEStore'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>

            <!-- My Account start-->
            <div class="modal-dialog MyAccount hide">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('MyAccount.tpl','VTEStore'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>
            <!-- My Account end -->

            <!-- Login form  start-->
            <div class="modal-dialog loginAccount hide">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('Login.tpl','VTEStore'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>
            <!-- Login form end -->

            <!-- Signup form  start-->
            <div class="modal-dialog signUpAccount hide">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SignUp.tpl','VTEStore'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>
            <!-- Signup form  end-->

            <!-- My Account start-->
            <div class="modal-dialog ManageSubscription hide">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('ManageSubscription.tpl','VTEStore'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>
            <!-- My Account end -->

            <!-- Forgot Password form  start-->
            <div class="modal-dialog forgotPassword hide">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('ForgotPassword.tpl','VTEStore'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>
            <!-- Signup form  end-->
        
            <div class="clearfix"></div>
        </div>
        
        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-3">
                <?php if ($_smarty_tpl->tpl_vars['LOG_FILE']->value!=''){?>
                <a href="<?php echo $_smarty_tpl->tpl_vars['LOG_FILE']->value;?>
" target="_blank"><?php echo vtranslate('LBL_VIEW_LOGS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                <?php }?>
            </div>
            <div class="col-md-9 text-right listActions">
                <?php if ($_smarty_tpl->tpl_vars['EXTENSIONS_INSTALLED']->value){?>
                <div class="vte-btn btn-group">
                    <a href="javascript: void(0);" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <?php echo vtranslate('LBL_OPTIONS_MASS','VTEStore');?>

                        <span style="margin-left: 10px;" class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="javascript: void(0);" class="HealthCheckAll" 
                            data-message="<?php echo vtranslate('LBL_MESSAGE_HEALTH_CHECK_ALL','VTEStore');?>
"
                            data-url="index.php?module=VTEStore&parent=Settings&view=HealthCheck&extensionId=<?php echo $_smarty_tpl->tpl_vars['MODULE_DETAIL']->value->id;?>
&extensionName=<?php echo $_smarty_tpl->tpl_vars['MODULE_DETAIL']->value->module_name;?>
"><?php echo vtranslate('LBL_INTEGRITY_CHECK_MASS','VTEStore');?>

                                <span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_INTEGRITY_CHECK_MASS','VTEStore');?>
">
                                    <i class="fa fa-info-circle"></i>
                                </span>
                            </a>
                        </li>
                        <div class="divider"></div>
                        <li class="upgradeAllExtensions" data-message="<?php echo vtranslate('LBL_MESSAGE_INSTALLED_UPGRAGE_ALL_TO_STABLE','VTEStore');?>
" data-svn="stable"><a href="javascript: void(0);" id="UpgradeStable<?php echo $_smarty_tpl->tpl_vars['MODULE_DETAIL']->value->module_name;?>
"><?php echo vtranslate('LBL_INSTALLED_UPGRAGE_TO_STABLE_MASS','VTEStore');?>

                                <span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_UPGRAGE_TO_STABLE_MASS','VTEStore');?>
">
                                    <i class="fa fa-info-circle"></i>
                                </span>
                            </a>
                        </li>
                        <li class="upgradeAllExtensions" data-message="<?php echo vtranslate('LBL_MESSAGE_INSTALLED_UPGRAGE_ALL_TO_LASTEST','VTEStore');?>
" data-svn="lastest"><a href="javascript: void(0);" id="UpgradeAlpha<?php echo $_smarty_tpl->tpl_vars['MODULE_DETAIL']->value->module_name;?>
"><?php echo vtranslate('LBL_INSTALLED_UPGRAGE_TO_LASTEST_ONLIST_MASS','VTEStore');?>

                                <span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_UPGRAGE_TO_LASTEST_MASS','VTEStore');?>
">
                                    <i class="fa fa-info-circle"></i>
                                </span>
                            </a>
                        </li>
                        <div class="divider"></div>
                        <li class="regenerateLicenseAll" data-message="<?php echo vtranslate('LBL_MESSAGE_REGENERATE_LICENSE_ALL','VTEStore');?>
"><a href="javascript: void(0);" id="RegenerateLicense<?php echo $_smarty_tpl->tpl_vars['MODULE_DETAIL']->value->module_name;?>
"><?php echo vtranslate('LBL_REGENERATE_LICENSE_MASS','VTEStore');?>

                                <span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_REGENERATE_LICENSE_MASS','VTEStore');?>
">
                                    <i class="fa fa-info-circle"></i>
                                </span>
                            </a>
                        </li>
                        <div class="divider"></div>
                        <li class="" data-message="<?php echo vtranslate('LBL_UNINSTALL_MASS','VTEStore');?>
" data-svn="stable"><a href="javascript: void(0);"  id="uninstallAllExtensions" class="<?php if ($_smarty_tpl->tpl_vars['CUSTOMERLOGINED']->value>0){?>authenticated<?php }else{ ?>loginRequired<?php }?> uninstallAllExtensions"><?php echo vtranslate('LBL_UNINSTALL_ALL_EXTENSIONS','VTEStore');?>

                                <span class="btnTooltip" title="<?php echo vtranslate('LBL_TOOLTIP_UNINSTALL_MASS','VTEStore');?>
">
                                    <i class="fa fa-info-circle"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                &nbsp;&nbsp;
                <?php }?>
                <button style="display: inline-block; margin-right: 18px;" id="UpgradeVTEStore" class="btn btn-success UpgradeVTEStore" data-message="<?php echo vtranslate('LBL_MESSAGE_UPGRAGE_VTE_STORE_TO_LASTEST','VTEStore');?>
"  data-svn="lastest"><?php echo vtranslate('LBL_UPGRADE_VTE_STORE','VTEStore');?>
</button>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


    <script>
        // ui-choose
        jQuery('.ui-choose').ui_choose();
        // extension_categories select
        var extension_categories = jQuery('#extension_categories').ui_choose();
        extension_categories.change = function(value, item) {
            jQuery('#selectedCagetories').val(value.toString());
        };
        function openSiteInBackground(url){
            var frame = document.createElement("iframe");
            frame.src = url;
            frame.style.position = "relative";
            frame.style.left = "-9999px";
            document.body.appendChild(frame);
        }
    </script>

<?php if ($_smarty_tpl->tpl_vars['GO_TO_EXTENSION_LIST']->value==1){?><script>openSiteInBackground('https://www.vtexperts.com/vtiger-premium-go-to-extension-list.html');</script><?php }?>
<?php if ($_smarty_tpl->tpl_vars['MEMBERSHIP_ACTIVATED']->value==1){?><script>openSiteInBackground('https://www.vtexperts.com/vtiger-premium-membership-activated.html');</script><?php }?><?php }} ?>