<?php /* Smarty version Smarty-3.1.7, created on 2023-08-03 12:40:12
         compiled from "/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Calendar/CalendarSharedUsers.tpl" */ ?>
<?php /*%%SmartyHeaderCode:108428511864cba02c12d388-96557632%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1ab3c29d66ea514e563b73ee39c713fc6d43e9c3' => 
    array (
      0 => '/home/customer/www/crm.teraleads.com/public_html/includes/runtime/../../layouts/v7/modules/Calendar/CalendarSharedUsers.tpl',
      1 => 1627027149,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '108428511864cba02c12d388-96557632',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SHAREDUSERS_INFO' => 0,
    'CURRENTUSER_MODEL' => 0,
    'CURRENT_USER_ID' => 0,
    'MODULE' => 0,
    'SHAREDUSERS' => 0,
    'ID' => 0,
    'USER' => 0,
    'SHAREDGROUPS' => 0,
    'GROUP' => 0,
    'INVISIBLE_CALENDAR_VIEWS_EXISTS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_64cba02c15bac',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_64cba02c15bac')) {function content_64cba02c15bac($_smarty_tpl) {?>

<?php $_smarty_tpl->tpl_vars['SHARED_USER_INFO'] = new Smarty_variable(Zend_Json::encode($_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['CURRENT_USER_ID'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENTUSER_MODEL']->value->getId(), null, 0);?><input type="hidden" id="sharedUsersInfo" value= <?php echo Zend_Json::encode($_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value);?>
 /><div class="sidebar-widget-contents" name='calendarViewTypes'><div id="calendarview-feeds"><ul class="list-group feedslist"><li class="activitytype-indicator calendar-feed-indicator mass-edit-option" style="background-color:#2c3b49; color:#FFFFFF;"><span><?php echo vtranslate('LBL_MASS_SELECT');?>
</span><span class="activitytype-actions pull-right"><input class="mass-select" type="checkbox"></span></li><li class="activitytype-indicator calendar-feed-indicator" style="background-color: <?php echo $_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value[$_smarty_tpl->tpl_vars['CURRENT_USER_ID']->value]['color'];?>
;"><span><?php echo vtranslate('LBL_MINE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span><span class="activitytype-actions pull-right"><input class="toggleCalendarFeed cursorPointer" type="checkbox" data-calendar-sourcekey="Events_<?php echo $_smarty_tpl->tpl_vars['CURRENT_USER_ID']->value;?>
" data-calendar-feed="Events"data-calendar-feed-color="<?php echo $_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value[$_smarty_tpl->tpl_vars['CURRENT_USER_ID']->value]['color'];?>
" data-calendar-fieldlabel="<?php echo vtranslate('LBL_MINE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"data-calendar-userid="<?php echo $_smarty_tpl->tpl_vars['CURRENT_USER_ID']->value;?>
" data-calendar-group="false" data-calendar-feed-textcolor="white">&nbsp;&nbsp;<i class="fa fa-pencil editCalendarFeedColor cursorPointer"></i>&nbsp;&nbsp;</span></li><?php $_smarty_tpl->tpl_vars['INVISIBLE_CALENDAR_VIEWS_EXISTS'] = new Smarty_variable('false', null, 0);?><?php  $_smarty_tpl->tpl_vars['USER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['USER']->_loop = false;
 $_smarty_tpl->tpl_vars['ID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SHAREDUSERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['USER']->key => $_smarty_tpl->tpl_vars['USER']->value){
$_smarty_tpl->tpl_vars['USER']->_loop = true;
 $_smarty_tpl->tpl_vars['ID']->value = $_smarty_tpl->tpl_vars['USER']->key;
?><?php if ($_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value[$_smarty_tpl->tpl_vars['ID']->value]['visible']!='0'){?><li class="activitytype-indicator calendar-feed-indicator" style="background-color: <?php echo $_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value[$_smarty_tpl->tpl_vars['ID']->value]['color'];?>
;"><span class="userName textOverflowEllipsis" title="<?php echo $_smarty_tpl->tpl_vars['USER']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['USER']->value;?>
</span><span class="activitytype-actions pull-right"><input class="toggleCalendarFeed cursorPointer" type="checkbox" data-calendar-sourcekey="Events_<?php echo $_smarty_tpl->tpl_vars['ID']->value;?>
" data-calendar-feed="Events"data-calendar-feed-color="<?php echo $_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value[$_smarty_tpl->tpl_vars['ID']->value]['color'];?>
" data-calendar-fieldlabel="<?php echo $_smarty_tpl->tpl_vars['USER']->value;?>
"data-calendar-userid="<?php echo $_smarty_tpl->tpl_vars['ID']->value;?>
" data-calendar-group="false" data-calendar-feed-textcolor="white">&nbsp;&nbsp;<i class="fa fa-pencil editCalendarFeedColor cursorPointer"></i>&nbsp;&nbsp;<i class="fa fa-trash deleteCalendarFeed cursorPointer"></i></span></li><?php }else{ ?><?php $_smarty_tpl->tpl_vars['INVISIBLE_CALENDAR_VIEWS_EXISTS'] = new Smarty_variable('true', null, 0);?><?php }?><?php } ?><?php  $_smarty_tpl->tpl_vars['GROUP'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['GROUP']->_loop = false;
 $_smarty_tpl->tpl_vars['ID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SHAREDGROUPS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['GROUP']->key => $_smarty_tpl->tpl_vars['GROUP']->value){
$_smarty_tpl->tpl_vars['GROUP']->_loop = true;
 $_smarty_tpl->tpl_vars['ID']->value = $_smarty_tpl->tpl_vars['GROUP']->key;
?><?php if ($_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value[$_smarty_tpl->tpl_vars['ID']->value]['visible']!='0'){?><li class="activitytype-indicator calendar-feed-indicator" style="background-color: <?php echo $_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value[$_smarty_tpl->tpl_vars['ID']->value]['color'];?>
;"><span class="userName textOverflowEllipsis" title="<?php echo $_smarty_tpl->tpl_vars['GROUP']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['GROUP']->value;?>
</span><span class="activitytype-actions pull-right"><input class="toggleCalendarFeed cursorPointer" type="checkbox" data-calendar-sourcekey="Events_<?php echo $_smarty_tpl->tpl_vars['ID']->value;?>
" data-calendar-feed="Events"data-calendar-feed-color="<?php echo $_smarty_tpl->tpl_vars['SHAREDUSERS_INFO']->value[$_smarty_tpl->tpl_vars['ID']->value]['color'];?>
" data-calendar-fieldlabel="<?php echo $_smarty_tpl->tpl_vars['GROUP']->value;?>
"data-calendar-userid="<?php echo $_smarty_tpl->tpl_vars['ID']->value;?>
" data-calendar-group="true" data-calendar-feed-textcolor="white">&nbsp;&nbsp;<i class="fa fa-pencil editCalendarFeedColor cursorPointer"></i>&nbsp;&nbsp;<i class="fa fa-trash deleteCalendarFeed cursorPointer"></i></span></li><?php }else{ ?><?php $_smarty_tpl->tpl_vars['INVISIBLE_CALENDAR_VIEWS_EXISTS'] = new Smarty_variable('true', null, 0);?><?php }?><?php } ?></ul><ul class="hide dummy"><li class="activitytype-indicator calendar-feed-indicator feed-indicator-template"><span></span><span class="activitytype-actions pull-right"><input class="toggleCalendarFeed cursorPointer" type="checkbox" data-calendar-sourcekey="" data-calendar-feed="Events"data-calendar-feed-color="" data-calendar-fieldlabel=""data-calendar-userid="" data-calendar-group="" data-calendar-feed-textcolor="white">&nbsp;&nbsp;<i class="fa fa-pencil editCalendarFeedColor cursorPointer"></i>&nbsp;&nbsp;<i class="fa fa-trash deleteCalendarFeed cursorPointer"></i></span></li></ul><input type="hidden" class="invisibleCalendarViews" value="<?php echo $_smarty_tpl->tpl_vars['INVISIBLE_CALENDAR_VIEWS_EXISTS']->value;?>
" /></div></div>
<?php }} ?>