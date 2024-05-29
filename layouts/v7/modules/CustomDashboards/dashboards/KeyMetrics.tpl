{************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}
{assign var=HEADER_COLOR value=$WIDGET->get('pick_color')}
{assign var=HEADER_TEXT_COLOR value=CustomDashboards_Widget_Model::getTextColor($HEADER_COLOR)}
{assign var=WIDGET_COLOR value=$WIDGET->get('widget_color')}
<header data-url="{$WIDGET->getUrl()}" data-refresh-time="{$WIDGET->get('refresh_time')}" data-tabid="{$WIDGET->get('dashboardtabid')}" class="panel_header sticky_header" style="z-index: 1000; background-color: {if $HEADER_COLOR} {$HEADER_COLOR}; color: {$HEADER_TEXT_COLOR}; {else} #ffffff; {/if}">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}
</header>
<div name="panel_content" class="panel_content" style="height: calc(100% - 41px);padding: unset;{if $WIDGET_COLOR}background-color:{$WIDGET_COLOR} {/if}">
	{include file="dashboards/KeyMetricsContents.tpl"|@vtemplate_path:$MODULE_NAME}
</div>
