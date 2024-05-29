{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Vtiger/views/DashBoard.php *}
    
{strip}
<input type="hidden" id="userDateFormat" value="{$CURRENT_USER->get('date_format')}" />
<div class="dashBoardContainer clearfix">
        <div class="tabContainer">
            {if $DASHBOARD_BOARDS}
            <div class="nav nav-tabs tabs sortable container-fluid">
                <div class="col-xs-7">
					{if $SOURCEMODULE}
                        {assign var="KANBAN_RECORDS" value=Vtiger_Kanban_Model::getRecords($SOURCEMODULE)}
                        <div class="viewTabsGroup">
                            <div class="viewTabsGroupWrap">
                                <div class="emptyGray"></div>
                                <div class="btn-group" style="z-index: 1100;" role="group">
                                    <a class="btn btn-default btn-sm dropdown-toggle viewTab" href="index.php?module={$SOURCEMODULE}&view=List&app={$SELECTED_MENU_CATEGORY}">
                                        <div class="fa fa-align-justify" aria-hidden="true"></div> {vtranslate('List',$SOURCEMODULE)}
                                    </a>
                                </div>
                                {if $KANBAN_RECORDS neq false }
                                    <div class="btn-group listViewVisualPipelines" style="z-index: 1100;" role="group">
                                        {if $KANBAN_RECORDS|@count eq 1}
                                            <a class="btn btn-default btn-sm dropdown-toggle viewTab" href="index.php?module={$SOURCEMODULE}&view=Kanban&record={$KANBAN_RECORDS[0].id}&app={$SELECTED_MENU_CATEGORY}">
                                                <div class="fa fa-columns" aria-hidden="true"></div> Pipeline
                                            </a>
                                        {else}
                                            <button type="button" class="viewTab btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <span class="fa fa-columns" aria-hidden="true"></span> Pipeline <span class="caret pull-right" style="margin-top: 7px"></span>
                                            </button>
                                            <ul class="dropdown-menu fade-move" role="menu">
                                                {foreach from=$KANBAN_RECORDS item=item key=key name=name}
                                                    <li class="selectFreeRecords">
                                                        <a id="Contacts_listView_massAction_LBL_VISUAL_PIPELINE_{$item.id}" href="index.php?module={$SOURCEMODULE}&view=Kanban&record={$item.id}&app={$SELECTED_MENU_CATEGORY}">
                                                            {if $item.name}
                                                                {$item.name}
                                                            {else}
                                                                Visual Pipeline #{$item.id}
                                                            {/if}
                                                        </a>
                                                    </li>
                                                {/foreach}
                                            </ul>
                                            {/if}
                                    </div>
                                {/if}
                                <div class="btn-group" style="z-index: 1100;" role="group">
                                        <a class="btn btn-default btn-sm dropdown-toggle viewTab activeView" href="index.php?module={$SOURCEMODULE}&view=ModuleDashboard&boardid={$BOARDID}">
                                            <div class="app-icon-list fal fa-tachometer-alt-average"></div> {vtranslate('LBL_DASHBOARD',$MODULE)}
                                        </a>
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div style="text-align: right;padding-top: 4px" class="headerTabContainer hide" >
                        <select name="header-board" style="width: 300px;text-align: left" class="select2">
                            <optgroup label="My Boards">
                                <option value="1">Default</option>
                                {foreach key=index item=BOARD_DATA from=$DASHBOARD_BOARDS}
                                    {if $BOARD_DATA["id"] eq 1 or $BOARD_DATA['shared']}
                                        {continue}
                                    {/if}
                                    <option {if $BOARDID eq $BOARD_DATA["id"] }selected{/if} value="{$BOARD_DATA["id"]}">{$BOARD_DATA["boardname"]}</option>
                                {/foreach}
                            </optgroup>
                            <optgroup label="Shared Boards">
                                {foreach key=index item=BOARD_DATA from=$DASHBOARD_BOARDS}
                                    {if !$BOARD_DATA['shared']}
                                        {continue}
                                    {/if}
                                    <option {if $BOARDID eq $BOARD_DATA["id"] }selected{/if} value="{$BOARD_DATA["id"]}">{$BOARD_DATA["boardname"]}</option>
                                {/foreach}
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>

        {/if}
            <div class="row">
                <div class="col-lg-1 sortable">
                    <div style="text-align: right;width: 100%;margin-left:5px" class="headerTabContainer">
						<span class="dropdown dashBoardDropDown">
                            <button class="btn btn-default reArrangeTabs dropdown-toggle" type="button" data-toggle="dropdown">{vtranslate('LBL_BOARDS',$MODULE)}
                            &nbsp;&nbsp;<span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-right moreDashBoards" style="margin-top: 10px;padding-top: 5px;right: unset;left: 0;">

                                <li style="font-weight: bold;padding: 4px 6px;">{vtranslate('My Boards',$MODULE_NAME)}</li>
                                {foreach key=index item=BOARD_DATA from=$DASHBOARD_BOARDS}
                                    {if $BOARD_DATA["id"] eq 1 or $BOARD_DATA['shared']}
                                        {continue}
                                    {/if}
                                    <li><a class="selectboard {if $BOARDID eq $BOARD_DATA["id"] }selected{/if}" data-id="{$BOARD_DATA["id"]}">{$BOARD_DATA["boardname"]}</a></li>
                                {/foreach}
                                 <li style="font-weight: bold;padding: 4px 6px;">{vtranslate('Shared Boards',$MODULE_NAME)}</li>
                                 {foreach key=index item=BOARD_DATA from=$DASHBOARD_BOARDS}
                                     {if !$BOARD_DATA['shared']}
                                         {continue}
                                     {/if}
                                     <li><a class="selectboard {if $BOARDID eq $BOARD_DATA["id"] }selected{/if}" data-id="{$BOARD_DATA["id"]}">{$BOARD_DATA["boardname"]}</a></li>
                                 {/foreach}
                            </ul>
                        </span>
                        {*<select name="header-board" style="text-align: left" class="select2 btn btn-default dropdown dashBoardDropDown">
                            <optgroup label="My Boards">
                                <option value="1">Default</option>
                                {foreach key=index item=BOARD_DATA from=$DASHBOARD_BOARDS}
                                    {if $BOARD_DATA["id"] eq 1 or $BOARD_DATA['shared']}
                                        {continue}
                                    {/if}
                                    <option {if $BOARDID eq $BOARD_DATA["id"] }selected{/if} value="{$BOARD_DATA["id"]}">{$BOARD_DATA["boardname"]}</option>
                                {/foreach}
                            </optgroup>
                            <optgroup label="Shared Boards">
                                {foreach key=index item=BOARD_DATA from=$DASHBOARD_BOARDS}
                                    {if !$BOARD_DATA['shared']}
                                        {continue}
                                    {/if}
                                    <option {if $BOARDID eq $BOARD_DATA["id"] }selected{/if} value="{$BOARD_DATA["id"]}">{$BOARD_DATA["boardname"]}</option>
                                {/foreach}
                            </optgroup>
                        </select>*}
                    </div>
                </div>
                <div class="col-lg-11">
                    <ul class="nav nav-tabs tabs sortable container-fluid">
                        {foreach key=index item=TAB_DATA from=$DASHBOARD_TABS}
                            <li class="{if $TAB_DATA["id"] eq $SELECTED_TAB}active{/if} dashboardTab" data-tabid="{$TAB_DATA["id"]}" data-tabname="{$TAB_DATA["tabname"]}">
                                <a data-toggle="tab" href="#tab_{$TAB_DATA["id"]}">
                                    <div>
                                <span class="name textOverflowEllipsis dasb_tab" value="{$TAB_DATA["tabname"]}" style="width:10%">
                                    {$TAB_DATA["tabname"]}
                                </span>
                                        <span class="editTabName hide">
                                    <input type="text" name="tabName"/>
                                </span>
                                        <i class="fa fa-bars moveTab hide"></i>
                                    </div>
                                </a>
                            </li>
                        {/foreach}
                        <div class="moreSettings pull-right {if $IS_SHARED eq true}hide{/if}" >
                            <button class="btn btn-success saveFieldSequence hide" id="savePositionWidgets"><strong>{vtranslate('LBL_SAVE_LAYOUT','CustomDashboards')}</strong></button>
                            <span class="dropdown dashBoardDropDown">
                    {*{include file="dashboards/DashBoardHeader.tpl"|vtemplate_path:$MODULE_NAME DASHBOARDHEADER_TITLE=vtranslate($MODULE, $MODULE)}*}
                        <button class="btn btn-default dropdown dashBoardDropDown" id="openAddWidget" {if !$MODULE_PERMISSION} disabled="disabled" {/if} style="margin-right: 10px">{vtranslate('LBL_ADD_WIDGET','CustomDashboards')}</button>
                    </span>
                            <span class="dropdown dashBoardDropDown">
                        <button class="btn btn-default reArrangeTabs dropdown-toggle" type="button" data-toggle="dropdown">{vtranslate('LBL_MORE',$MODULE)}
                            &nbsp;&nbsp;<span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right moreDashBoards" style="margin-top: 10px;padding-top: 5px;">

                            <li style="font-weight: bold;padding: 4px 6px;">{vtranslate('LBL_WIDGETS',$MODULE_NAME)}</li>
                            <li><a class = "editWidgets" href="#">{vtranslate('LBL_EDIT_WIDGETS',$MODULE)}</a></li>
                            <li><a class = "dynamicFilter" href="#">{vtranslate('LBL_DYNAMIC_FILTER',$MODULE)}</a></li>
                            <li class="divider"></li>

                            <li style="font-weight: bold;padding: 4px 6px;">{vtranslate('LBL_TABS',$MODULE_NAME)}</li>
                            <li id="newDashBoardLi"{if count($DASHBOARD_TABS) eq $DASHBOARD_TABS_LIMIT}class="disabled"{/if}>
                                <a data-action="add" class="addNewDashBoard" href="#">{vtranslate('LBL_ADD_NEW_TAB',$MODULE)}</a>
                            </li>
                            <li><a class = "renameTabs" href="#">{vtranslate('LBL_RENAME_TAB',$MODULE)}</a></li>
                            <li><a data-action="duplicate" class = "addNewDashBoard" href="#">{vtranslate('LBL_DUPLICATE_TAB',$MODULE)}</a></li>
                            <li><a class = "deleteTab" href="#">{vtranslate('LBL_DELETE_TAB',$MODULE)}</a></li>
                            <li><a class = "reArrangeTabs" href="#">{vtranslate('LBL_REARRANGE_DASHBOARD_TABS',$MODULE)}</a></li>
                            <li class="divider"></li>

                            <li style="font-weight: bold;padding: 4px 6px;">{vtranslate('LBL_BOARDS',$MODULE_NAME)}</li>
                            <li><a class = "addBoards" href="#">{vtranslate('LBL_ADD_NEW_BOARD',$MODULE)}</a></li>
                            <li><a class = "editBoards" href="#">{vtranslate('LBL_EDIT_BOARD',$MODULE)}</a></li>
                            <li><a class = "deleteBoard" href="#">{vtranslate('LBL_DELETE_BOARD',$MODULE)}</a></li>
                        </ul>
                    </span>
                            <span class="notification-dynamic" rel="tooltip" data-original-title="Dynamic Filter is active for this tab">

                    </span>
                            <button class="btn-success updateSequence pull-right hide">{vtranslate('LBL_SAVE_ORDER',$MODULE)}</button>
                        </div>
                    </ul>
                </div>
            </div>

            <div class="tab-content" data-boardid="{$SELECTED_BOARD}">
                {foreach key=index item=TAB_DATA from=$DASHBOARD_TABS}
                    <div id="tab_{$TAB_DATA["id"]}" data-tabid="{$TAB_DATA["id"]}" data-tabname="{$TAB_DATA["tabname"]}" class="tab-pane fade {if $TAB_DATA["id"] eq $SELECTED_TAB}in active{/if}">
                        {if $TAB_DATA["id"] eq $SELECTED_TAB}
                            {include file="dashboards/DashBoardTabContents.tpl"|vtemplate_path:$MODULE TABID=$TABID}
                        {/if}
                    </div>
                {/foreach}
            </div>
        </div>
</div>
{/strip}