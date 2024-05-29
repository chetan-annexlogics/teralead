{************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}
<div style='padding-top: 0;margin-bottom: 2%;padding-right:15px;'>
    <input type="hidden" id="widget_{$WIDGET->get('id')}_currentPage" value="{$CURRENT_PAGE}">
	{* Comupte the nubmer of columns required *}
	{assign var="SPANSIZE" value=12}
	{assign var=HEADER_COUNT value=$MINILIST_WIDGET_MODEL->getHeaderCount()}
	{if $HEADER_COUNT}
		{if $HEADER_COUNT eq 5}
		 	{assign var=HEADER_COUNT value=6}
		{/if}
		{assign var="SPANSIZE" value=12/$HEADER_COUNT}
	{/if}
    
	<div class="row" style="padding:5px; display: flex;background: #11b096;color: white;">
	    {assign var=HEADER_FIELDS value=$MINILIST_WIDGET_MODEL->getHeaders()}
	 	{foreach item=FIELD from=$HEADER_FIELDS}
    {assign var=label value=$FIELD->get('label')}
    {if $label eq 'First Name'}
        <div class="col-lg-{$SPANSIZE} first-name-column"><strong>{vtranslate($label,$BASE_MODULE)}</strong></div>
    {elseif $label eq 'Last Name'}
        <div class="col-lg-{$SPANSIZE} last-name-column"><strong>{vtranslate($label,$BASE_MODULE)}</strong></div>
    {elseif $label eq 'Mobile'}
        <div class="col-lg-{$SPANSIZE} mobile-phone-column"><strong>{vtranslate($label,$BASE_MODULE)}</strong></div>
    {elseif $label eq 'Description'}
        <div class="col-lg-{$SPANSIZE} description-column"><strong>{vtranslate($label,$BASE_MODULE)}</strong></div>
    {else}
        <div class="col-lg-{$SPANSIZE}"><strong>{vtranslate($label,$BASE_MODULE)}</strong></div>
    {/if}
    {/foreach}
	</div>

	{assign var="MINILIST_WIDGET_RECORDS" value=$MINILIST_WIDGET_MODEL->getRecords()}

	{foreach item=RECORD from=$MINILIST_WIDGET_RECORDS}
<div class="row miniListContent" style="padding:5px;display:flex;align-items: center;">
    {foreach item=FIELD key=NAME from=$HEADER_FIELDS name="minilistWidgetModelRowHeaders"}
        {assign var=customClass value=""}
        {if $FIELD->get('label') eq 'First Name'}
            {assign var=customClass value="first-name-column"}
        {elseif $FIELD->get('label') eq 'Last Name'}
            {assign var=customClass value="last-name-column"}
		{elseif $FIELD->get('label') eq 'Mobile'}
            {assign var=customClass value="mobile-phone-column"}
		{elseif $FIELD->get('label') eq 'Description'}
            {assign var=customClass value="description-column"}
        {/if}
        <div class="col-lg-{$SPANSIZE} textOverflowEllipsis {$customClass}" title="{strip_tags($RECORD->get($NAME))}" style="padding-right: 5px;display: flex;align-items: center;justify-content: space-between;">
            {if $FIELD->get('uitype') eq '71' || ($FIELD->get('uitype') eq '72' && $FIELD->getName() eq 'unit_price')}
                {assign var=CURRENCY_ID value=$USER_MODEL->get('currency_id')}
                {if $FIELD->get('uitype') eq '72' && $NAME eq 'unit_price'}
                    {assign var=CURRENCY_ID value=getProductBaseCurrency($RECORD_ID, $RECORD->getModuleName())}
                {/if}
                {assign var=CURRENCY_INFO value=getCurrencySymbolandCRate($CURRENCY_ID)}
                {if $RECORD->get($NAME) neq NULL}
                    &nbsp;{CurrencyField::appendCurrencySymbol($RECORD->get($NAME), $CURRENCY_INFO['symbol'])}&nbsp;
                {else}
                    {$RECORD->get($NAME)}&nbsp;
                {/if}
            {else}
                {$RECORD->get($NAME)}&nbsp;
            {/if}
            {if $smarty.foreach.minilistWidgetModelRowHeaders.last}
                <a href="{$RECORD->getDetailViewUrl()}" class="pull-right"><i title="{vtranslate('LBL_SHOW_COMPLETE_DETAILS',$MODULE_NAME)}" class="fa fa-list"></i></a>
            {/if}
        </div>
    {/foreach}
</div>
{/foreach}

    
    {if $MORE_EXISTS}
        <div class="moreLinkDiv" style="padding-top:10px;padding-bottom:5px;">
            <a class="miniListMoreLink" data-linkid="{$WIDGET->get('linkid')}" data-widgetid="{$WIDGET->get('id')}" onclick="Vtiger_MiniList_Widget_Js.registerMoreClickEvent(event);">{vtranslate('LBL_MORE')}...</a>
        </div>
    {/if}
</div>

<style>
.miniListContent:nth-child(odd) {
    background-color: #dadada; /* or any shade of grey you prefer */
}

.miniListContent:nth-child(even) {
    background-color: white;
}

.miniListContent .textOverflowEllipsis:last-child {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: clip !important;
}
.row.miniListContent:hover {
    background-color: #11b096;
	color:white;
}
.col-lg-2.first-name-column, .col-lg-2.last-name-column {
    width: 132px;
}
.col-lg-2.mobile-phone-column {
    width: 132px;
}
.col-lg-2.description-column {
    width: 33%;
}
</style>