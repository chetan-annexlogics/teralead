{************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}
{assign var=HEADER_COLOR value=$WIDGET->get('pick_color')}
{assign var=WIDGET_COLOR value=$WIDGET->get('widget_color')}
{assign var=HEADER_TEXT_COLOR value=CustomDashboards_Widget_Model::getTextColor($HEADER_COLOR)}
<header data-url="{$WIDGET->getUrl()}" data-refresh-time="{$WIDGET->get('refresh_time')}" data-tabid="{$WIDGET->get('dashboardtabid')}" class="panel_header sticky_header" style="z-index: 1000; background-color: {if $HEADER_COLOR} {$HEADER_COLOR}; color: {$HEADER_TEXT_COLOR}; {else} #ffffff; {/if}">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}
</header>
{assign var="dataColor" value=CustomDashboards_Gauge_Model::getValueByName($WIDGET,'dataColor')}
{assign var="sub_title" value=CustomDashboards_Gauge_Model::getValueByName($WIDGET,'sub_title')}
{assign var="backgroundColor" value=CustomDashboards_Gauge_Model::getValueByName($WIDGET,'backgroundColor')}
{assign var="decimal" value=CustomDashboards_Gauge_Model::getValueByName($WIDGET,'decimal')}
{assign var="formatLargeNumber" value=CustomDashboards_Gauge_Model::getValueByName($WIDGET,'formatLargeNumber')}
{assign var="icon" value=CustomDashboards_Gauge_Model::getValueByName($WIDGET,'icon')}

<div name="panel_content" class="panel_content" style="height: 65%;padding-top: 5px;{if $backgroundColor}background-color:{$backgroundColor};{else if $WIDGET_COLOR}background-color:{$WIDGET_COLOR}{/if}">
    {foreach from=$DATA item=VALUE key=FIELD_NAME}
        {assign var="final_value" value=CustomDashboards_Gauge_Model::formatFinalValue($VALUE,$decimal,$formatLargeNumber)}
        {if $SYMBOL_PLACEMENT eq 'first'}
            <div class="gauge" style="color:{if $dataColor} {$dataColor} {elseif $HEADER_COLOR} {$HEADER_COLOR} {else} #ffffff; {/if}">
                {if $icon}<i class="{$icon}"></i>{/if} {$final_value}
            </div>
        {else}
            <div class="gauge" style="color:{if $dataColor} {$dataColor} {elseif $HEADER_COLOR} {$HEADER_COLOR} {else} #ffffff; {/if}">
                {$final_value} {if $icon}<i class="{$icon}"></i>{/if}
            </div>
        {/if}
    {/foreach}

</div>
<div style="{if $backgroundColor}background-color:{$backgroundColor};{/if}">
<var class="italic_small_size">{$sub_title}</var>
</div>
