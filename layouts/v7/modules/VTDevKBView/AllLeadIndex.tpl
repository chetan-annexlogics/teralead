{*/* * *******************************************************************************
* The content of this file is subject to the VTDevKBView ("License");
* You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is vtdevsolutions.com
* Portions created by vtdevsolutions.com are Copyright(C)vtdevsolutions.com
* All Rights Reserved.
* ****************************************************************************** */*}
{strip}
<form id="detailView">

    {assign var=LEFTPANELHIDE value=$USER_MODEL->get('leftpanelhide')}
    <div class="essentials-toggle" title="{vtranslate('LBL_LEFT_PANEL_SHOW_HIDE', 'Vtiger')}">
        <span class="essentials-toggle-marker fa {if $LEFTPANELHIDE eq '1'}fa-chevron-right{else}fa-chevron-left{/if} cursorPointer"></span>
    </div>
    {if $FIELD_SETTING['primary_value_setting']}
        <style>
            .kbParentContainer{
                width: 100%;
                overflow-x:scroll;
            }
            .kbContainer{
                margin-left: 20px;
                margin-top: 12px;
            }
        </style>
        <div class="kbParentContainer ">
            <div class="kbContainer ">
                {assign var=COUNTER value=1}
          
                {foreach item=PRIMARY_FIELD_BLOCK  from=$FIELD_SETTING['primary_value_setting']}
                    <div class="kanbanBox">
                        
                        {foreach item=color from=$colors}
                            {if $color[$PRIMARY_FIELD_SELECT]==$PRIMARY_FIELD_BLOCK}{$instanceColor=$color['color']}{/if}
                        {/foreach}
                        {if !$instanceColor}{$instanceColor='#5cb85c'}{/if}
                        <div class="kbBoxHeader" style="background:{$instanceColor}">
                            <span class="kbBoxTitle">{vtranslate($PRIMARY_FIELD_BLOCK,'HelpDesk')}</span>
                            
                        </div>
                        <div class="kbBoxContent">
                            <input id="kbSourceModule" type="hidden" value="{$KANBAN_SOURCE_MODULE}">
                            {assign var=FIELDVALUE value=' '|explode:$PRIMARY_FIELD_BLOCK}
                            {foreach item=MODULENAME from=$MODULE_LIST}
                            
                            {foreach item=RECORD_MODEL from=$LIST_RECORDS[$MODULENAME][$FIELDVALUE[0]|lower]}

                                {assign var=BACKGROUND_CARD value= $RECORD_MODEL['RECORD']->get('vtdevkb_color')}
                                {assign var=FONT_COLOR value= $RECORD_MODEL['RECORD']->get('font_color')}
                                <div class="kbBoxTask" {if !empty($BACKGROUND_CARD)}style="background:{$BACKGROUND_CARD} "{/if}>
                                <input id="kbCurrentModule" type="hidden" value="{$MODULENAME}">
                                <input type="hidden" name="primaryValue" value="{$MODULE_FIELD_SETTING[$MODULENAME]['primary_value_setting'][$COUNTER]}"  >
                                <input type="hidden" id="primaryFieldName" value="{$PRIMARY_FIELD_SELECT[$MODULENAME]}">
                                <input type="hidden" id="primaryFieldId" value="{$MODULE_FIELD_SETTING[$MODULENAME]['primary_field']}">
                                    <input type="hidden" name="recordId" value="{$RECORD_MODEL['RECORD']->getId()}">
                                    <input type="hidden" name="sequence" value="{$RECORD_MODEL['sequence']}">
                                    <div class="kbTaskHeader">
                                        <span class="kbTaskTitle pull-left">
                                            <a href="index.php?module={$MODULENAME}&view=Detail&record={$RECORD_MODEL['RECORD']->getId()}&cvid={$CV_ID}" title="{$RECORD_MODEL['RECORD']->get($NAME_FIELD)}" {if !empty($FONT_COLOR)}style="color:{$FONT_COLOR} !important; "{/if}>
                                                {assign var=MODULE_MODEL value=$RECORD_MODEL['RECORD']->getModule()}
                                                {foreach item=NAME_FIELD from=$MODULE_MODEL->getNameFields()}
                                                    {assign var=FIELD_MODEL value=$MODULE_MODEL->getField($NAME_FIELD)}
                                                    {if $FIELD_MODEL->getPermissions()}
                                                            {$RECORD_MODEL['RECORD']->get($NAME_FIELD)}&nbsp;
                                                    {/if}
                                                {/foreach}
                                            </a>
                                        </span>
                                        <span class="pull-right kbEditIcon">
                                            <a href="javascript:void(0)" data-url="index.php?module=VTDevKBView&view=QuickEditAjax&record={$RECORD_MODEL['RECORD']->getId()}&source_module={$MODULENAME}" title="Edit" class="fa fa-pencil alignMiddle kbQuickEdit"></a>
                                        </span>
                                        <span class="kbEyeIcon pull-right">
                                            <a href="index.php?module={$MODULENAME}&view=Detail&record={$RECORD_MODEL['RECORD']->getId()}&cvid={$CV_ID}" title="{vtranslate('LBL_GO_TO_DETAIL_VIEW', 'VTDevKBView')}"><img src="layouts/v7/modules/VTDevKBView/images/eye.png" alt="Show more"/></a>
                                        </span>
                                        <span class="clearfix"></span>
                                    </div>
                                    <div class="kbTaskContent">
                                        {foreach item=FIELD_MODEL from=$ARR_SELECTED_FIELD_MODELS[$MODULENAME]}
                                            {assign var=FIELD_MODEL value=$FIELD_MODEL->set('fieldvalue',$RECORD_MODEL['RECORD']->get($FIELD_MODEL->get('name')))}
                                            {assign var=FIELD_VALUE value=$FIELD_MODEL->get('fieldvalue')}
                                            {if $fieldDataType eq 'multipicklist'}
                                                {assign var=FIELD_DISPLAY_VALUE value=$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}
                                            {else}
                                                {assign var=FIELD_DISPLAY_VALUE value=Vtiger_Util_Helper::toSafeHTML($FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue')))}
                                            {/if}
                                            {if $PRIMARY_FIELD_BLOCK|upper eq "ALL LEADS" }
                                                {if $FIELD_MODEL->get('name') eq "cf_919" or $FIELD_MODEL->get('name') eq "cf_921" or $FIELD_MODEL->get('name') eq "cf_929"}
                                                    {continue}
                                                {/if}
                                            {elseif $PRIMARY_FIELD_BLOCK neq "Closed" }
                                                {if $FIELD_MODEL->get('name') eq "cf_929"}
                                                    {continue}
                                                {/if}
                                            {/if}
                                            {if $FIELD_MODEL->get('uitype') neq "83" }
                                                <div class="kbTaskSection1 fieldValue" data-field-name="{$FIELD_MODEL->getFieldName()}{if $FIELD_MODEL->get('uitype') eq '33'}[]{/if}" data-uitype = "{$FIELD_MODEL->get('uitype')}" data-record-id="{$RECORD_MODEL['RECORD']->getId()}" >
                                                    {if $FIELD_MODEL->get('uitype') eq '19' or $FIELD_MODEL->get('uitype') eq '21'}
                                                        <div class="kbLabelContainer" style="width: 100%;text-align: center;">
                                                            <span class="kbLabel" title="{vtranslate($FIELD_MODEL->get('label'),$MODULENAME)}" {if !empty($FONT_COLOR)}style="color:{$FONT_COLOR} !important; "{/if}>
                                                                {vtranslate($FIELD_MODEL->get('label'),$MODULENAME)}
                                                            </span>
                                                        </div>
                                                        <div class="kbValueContainer" id="{$MODULENAME}_detailView_fieldValue_{$FIELD_MODEL->getName()}" style="width: 100%; border: none; border-top: 1px solid #eaeaea;">
                                                            <span class="value pull-left" data-field-type="{$FIELD_MODEL->getFieldDataType()}" style="max-width: 95%;max-height: 60px; line-height: 20px;{if !empty($FONT_COLOR)}color:{$FONT_COLOR} !important; {/if}" title="{$FIELD_MODEL->getDisplayValue($RECORD_MODEL['RECORD']->get($FIELD_MODEL->get('name')))|strip_tags}">
                                                                {$FIELD_MODEL->getDisplayValue($RECORD_MODEL['RECORD']->get($FIELD_MODEL->get('name')))}
                                                            </span>
                                                            {if $FIELD_MODEL->isEditable() eq 'true' && ($FIELD_MODEL->getFieldDataType()!=Vtiger_Field_Model::REFERENCE_TYPE)}
                                                                <span class="hide edit pull-left">
                                                                    {if $fieldDataType eq 'multipicklist'}
                                                                        <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}[]' data-type="{$FIELD_MODEL->getFieldDataType()}" data-displayvalue='{$FIELD_DISPLAY_VALUE}' data-value="{$FIELD_VALUE}" />
                                                                    {else}
                                                                        <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}' data-type="{$FIELD_MODEL->getFieldDataType()}" data-displayvalue='{$FIELD_DISPLAY_VALUE}' data-value="{$FIELD_VALUE}" />
                                                                    {/if}
                                                                </span>
                                                                <span class="action pull-right"><a href="javascript:void(0);" class="editAction fa fa-pencil"></a></span>
                                                            {/if}
                                                        </div>
                                                    {else}
                                                        <div class="kbLabelContainer">
                                                            <span class="kbLabel" title="{vtranslate($FIELD_MODEL->get('label'),$MODULENAME)}" {if !empty($FONT_COLOR)}style="color:{$FONT_COLOR} !important;"  {/if}>
                                                                {if isset($LABEL_MAPPING[$FIELD_MODEL->get('label')])} {$LABEL_MAPPING[$FIELD_MODEL->get('label')]}
                                                                    {else}
                                                                    {vtranslate($FIELD_MODEL->get('label'),$MODULENAME)}
                                                                {/if}
                                                            </span>
                                                        </div>
                                                        {if $FIELD_MODEL->get('uitype') eq '15' or $FIELD_MODEL->get('uitype') eq '16'}

                                                            {assign var=PICKLIST_COLOR_MAP value=Settings_Picklist_Module_Model::getPicklistColorMap($FIELD_MODEL->getName())}

                                                            <style type="text/css">
                                                                {foreach item=PICKLIST_COLOR key=PICKLIST_KEY_ID from=$PICKLIST_COLOR_MAP}
                                                                {assign var=PICKLIST_TEXT_COLOR value=Settings_Picklist_Module_Model::getTextColor($PICKLIST_COLOR)}
                                                                .picklist-{$FIELD_MODEL->getId()}-{$PICKLIST_KEY_ID} {
                                                                    background-color: {$PICKLIST_COLOR};
                                                                    color: {$PICKLIST_TEXT_COLOR} !important;
                                                                }
                                                                {/foreach}
                                                            </style>

                                                            {assign var=PICKLIST_VALUES value= Vtiger_Util_Helper::getPickListValues($FIELD_MODEL->getName())}
                                                            {foreach key=PICKLIST_KEY item=PICKLIST_VALUE from=$PICKLIST_VALUES}
                                                                {if $PICKLIST_VALUE eq $FIELD_VALUE}
                                                                    {assign var=PICKLIST_CLASS value= "picklist-{$FIELD_MODEL->getId()}-{$PICKLIST_KEY}"}
                                                                {/if}
                                                            {/foreach}

                                                        {else}
                                                            {assign var=PICKLIST_CLASS value= ""}
                                                            {assign var=PICKLIST_COLOR value=""}
                                                        {/if}
                                                        <div class="kbValueContainer" id="{$MODULENAME}_detailView_fieldValue_{$FIELD_MODEL->getName()}">
                                                            <span class="value pull-left {$PICKLIST_CLASS}" data-field-type="{$FIELD_MODEL->getFieldDataType()}" title="{$FIELD_MODEL->getDisplayValue($RECORD_MODEL['RECORD']->get($FIELD_MODEL->get('name')))|strip_tags}" >
                                                                {$FIELD_MODEL->getDisplayValue($RECORD_MODEL['RECORD']->get($FIELD_MODEL->get('name')))}
                                                            </span>
                                                            {if $FIELD_MODEL->isEditable() eq 'true' && ($FIELD_MODEL->getFieldDataType()!=Vtiger_Field_Model::REFERENCE_TYPE)}
                                                                <span class="hide edit pull-left">
                                                                    {if $fieldDataType eq 'multipicklist'}
                                                                        <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}[]' data-type="{$FIELD_MODEL->getFieldDataType()}" data-displayvalue='{$FIELD_DISPLAY_VALUE}' data-value="{$FIELD_VALUE}" />
                                                                    {else}
                                                                        <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}' data-type="{$FIELD_MODEL->getFieldDataType()}" data-displayvalue='{$FIELD_DISPLAY_VALUE}' data-value="{$FIELD_VALUE}" />
                                                                    {/if}
                                                                </span>
                                                                <span class="action pull-right"><a href="javascript:void(0);"  class="editAction fa fa-pencil"></a></span>
                                                            {/if}
                                                        </div>
                                                    {/if}
                                                    <div class="clearfix"></div>
                                                </div>
                                            {/if}
                                        {/foreach}

                                    </div>
                                    <div class="kbTaskFooter">
                                        <span class="clearfix"></span>
                                    </div>
                                </div>
                            {/foreach}
                             {/foreach}
                          
                        </div>
                    </div>
                    {assign var="COUNTER" value=$COUNTER+1}
                {/foreach}
            </div>
        </div>
    {/if}
</form>
{/strip}
