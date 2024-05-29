{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}
{strip}
    {assign var=FIELD_VALUE_MAPPING value=ZEND_JSON::decode($TASK_OBJECT->field_value_mapping)}
	<script type="text/javascript" src="layouts/v7/modules/VTEWEBHOOKS/resources/VTEWEBHOOKS.js"></script>
    <div class="row form-group">
        <div class="col-sm-6 col-xs-6">
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    Description
                </div>
                <div class="col-sm-9 col-xs-9">
                    <textarea style="height: 50px;" class="inputElement" name="webhook_description">{$FIELD_VALUE_MAPPING[0]['webhook_description']}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-6 col-xs-6">
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    Method
                </div>
                <div class="col-sm-9 col-xs-9">
                    <select class="select2" name="webhook_method">
                        <option value="post">POST</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-6 col-xs-6">
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    URL
                </div>
                <div class="col-sm-9 col-xs-9">
                    <input name="webhook_url" class="inputElement" data-rule-required="true" value="{$FIELD_VALUE_MAPPING[0]['webhook_url']}" aria-required="true" type="text">
                </div>
            </div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-6 col-xs-6">
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    Content Type
                </div>
                <div class="col-sm-9 col-xs-9">
                    <select class="select2" name="webhook_content_type">
                        <option value="json">JSON</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-6 col-xs-6">
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    Authorization Type
                </div>
                <div class="col-sm-9 col-xs-9">
                    <input {if $FIELD_VALUE_MAPPING[0]['webhook_authorization'] eq 'on' || empty($FIELD_VALUE_MAPPING[0]['webhook_authorization'])}checked{/if} type="radio" value="on" class="authorization-input" name="webhook_authorization" id="authorization_basic"/>&nbsp;<label for="authorization_basic"> Basic authentication</label>&nbsp;&nbsp;&nbsp;
                    <input {if $FIELD_VALUE_MAPPING[0]['webhook_authorization'] eq 'off'}checked{/if} type="radio" value="off" class="authorization-input" name="webhook_authorization" id="authorization_none"/>&nbsp;<label for="authorization_none"> No authentication</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-group authorization-info" {if $FIELD_VALUE_MAPPING[0]['webhook_authorization'] eq 'off'}style="display:none;"{/if}>
        <div class="col-sm-6 col-xs-6">
            <div class="row">
                <div class="col-sm-3 col-xs-3">

                </div>
                <div class="col-sm-9 col-xs-9">
                    <div class="row form-group">
                        <div class="col-sm-3 col-xs-3">User Name</div>
                        <div class="col-sm-9 col-xs-9"><input value="{$FIELD_VALUE_MAPPING[0]['webhook_authorization_username']}" class="inputElement" name="webhook_authorization_username" type="text"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-3 col-xs-3">Password</div>
                        <div class="col-sm-9 col-xs-9"><input value="{$FIELD_VALUE_MAPPING[0]['webhook_authorization_password']}" class="inputElement" name="webhook_authorization_password" type="text"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <input type="hidden" value="{$TASK_ID}" name="webhook_task_id">
        <input type="hidden" value="" name="">
    </div>
	<div>
        <strong>{vtranslate('Parameters',$QUALIFIED_MODULE)}</strong>&nbsp;&nbsp;&nbsp;
		<button type="button" class="btn btn-default" id="addFieldBtn">{vtranslate('LBL_ADD',$QUALIFIED_MODULE)}</button>
	</div><br>

	<div class="conditionsContainer" id="save_fieldvaluemapping">
		{assign var=FIELD_VALUE_MAPPING value=ZEND_JSON::decode($TASK_OBJECT->field_value_mapping)}
		{assign var=RECORD_STRUCTURE value=$TASK_OBJECT->getModuleFields($MODULE_MODEL->get('name'))}

		<input type="hidden" id="fieldValueMapping" name="field_value_mapping" value='{Vtiger_Util_Helper::toSafeHTML($TASK_OBJECT->field_value_mapping)}' />
        {assign var=MAX_INDEX value=0}
		{foreach from=$FIELD_VALUE_MAPPING key=KEY item=FIELD_MAP}
            {if $FIELD_MAP['fieldname']}
                <div class="row conditionRow" style="margin-bottom: 15px;{if $FIELD_MAP['group'] > 1}margin-left: {($FIELD_MAP['group']-1)*15}px{/if}">
                    <div class="cursorPointer col-sm-1 col-xs-1">
                        <center> <i class="alignMiddle deleteCondition fa fa-trash" style="position: relative; top: 4px;"></i> </center>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <input type="text" class="inputElement" data-field-value="{$FIELD_MAP['value']}" name="fieldname" value="{$FIELD_MAP['fieldname']}" placeholder="param name..."/>
                        <select name="fieldname" data-field-value="{$FIELD_MAP['value']}" data-field-type="{$FIELD_MAP['valuetype']}" class="select2" style="min-width: 250px;display: none;" data-placeholder="{vtranslate('LBL_SELECT_FIELD',$QUALIFIED_MODULE)}">
                            {foreach from=$RECORD_STRUCTURE  item=FIELDS}
                                {foreach from=$FIELDS item=FIELD_MODEL}
                                    {if (!($FIELD_MODEL->get('workflow_fieldEditable') eq true)) or ($MODULE_MODEL->get('name')=="Documents" and in_array($FIELD_MODEL->get('name'),$RESTRICTFIELDS))}
                                        {continue}
                                    {/if}
                                    {assign var=FIELD_INFO value=$FIELD_MODEL->getFieldInfo()}
                                    {assign var=FIELD_NAME value=$FIELD_MODEL->getName()}
                                    {assign var=FIELD_MODULE_MODEL value=$FIELD_MODEL->getModule()}
                                    <option value="{$FIELD_MODEL->get('workflow_columnname')}" data-fieldtype="{$FIELD_MODEL->getFieldType()}" data-field-name="{$FIELD_MODEL->get('name')}"
                                            {if ($FIELD_MODULE_MODEL->get('name') eq 'Events') and ($FIELD_NAME eq 'recurringtype')}
                                                {assign var=PICKLIST_VALUES value=Calendar_Field_Model::getReccurencePicklistValues()}
                                                {$FIELD_INFO['picklistvalues'] = $PICKLIST_VALUES}
                                            {/if}
                                            data-fieldinfo='{Vtiger_Functions::jsonEncode($FIELD_INFO)}' >
                                        {vtranslate($FIELD_MODEL->get('workflow_columnlabel'), $SOURCE_MODULE)}
                                    </option>
                                {/foreach}
                            {/foreach}
                        </select>
                    </div>

                    <div class="fieldUiHolder col-sm-4 col-xs-4">
                        <input type="text" class="getPopupUi inputElement" readonly="" input name="fieldValue" value="{$FIELD_MAP['value']}" />
                        <input type="hidden" name="valuetype" value="{$FIELD_MAP['valuetype']}" />
                    </div>
                    <div class="col-sm-4 col-xs-4">
                        <button type="button" class="btn btn-default add-group" data-group="{$FIELD_MAP['group']}" data-parent="{$FIELD_MAP['parent']}" data-index = "{$FIELD_MAP['index']}">Add Child</button>
                    </div>
                </div>
                {if $MAX_INDEX > $FIELD_MAP['index']}
                    {assign var=MAX_INDEX value=$MAX_INDEX}
                {else}
                    {assign var=MAX_INDEX value=$FIELD_MAP['index']}
                {/if}
            {/if}
		{/foreach}
        <input type="hidden" id="max_parent_index" value="{$MAX_INDEX}">
		{include file="FieldExpressions.tpl"|@vtemplate_path:$QUALIFIED_MODULE}
	</div>
	<div class="row basicAddFieldContainer hide" style="">
		<div class="cursorPointer col-sm-1 col-xs-1">
			<center> <i class="alignMiddle deleteCondition fa fa-trash" style="position: relative; top: 4px;"></i> </center>
		</div>
		<div class="col-sm-3 col-xs-3">
            <input type="text" class="inputElement" name="fieldname" value="" placeholder="param name..."/>
			<select name="fieldname" data-placeholder="{vtranslate('LBL_SELECT_FIELD',$QUALIFIED_MODULE)}" style="min-width: 250px;display: none;">
				{foreach from=$RECORD_STRUCTURE item=FIELDS}
					{foreach from=$FIELDS item=FIELD_MODEL}
						{if (!($FIELD_MODEL->get('workflow_fieldEditable') eq true))  or ($MODULE_MODEL->get('name')=="Documents" and in_array($FIELD_MODEL->get('name'),$RESTRICTFIELDS))}
							{continue}
						{/if}
						{assign var=FIELD_INFO value=$FIELD_MODEL->getFieldInfo()}
						{assign var=FIELD_NAME value=$FIELD_MODEL->getName()}
						{assign var=FIELD_MODULE_MODEL value=$FIELD_MODEL->getModule()}
						<option value="{$FIELD_MODEL->get('workflow_columnname')}" data-fieldtype="{$FIELD_MODEL->getFieldType()}" data-field-name="{$FIELD_MODEL->get('name')}"
								{if ($FIELD_MODULE_MODEL->get('name') eq 'Events') and ($FIELD_NAME eq 'recurringtype')}
									{assign var=PICKLIST_VALUES value=Calendar_Field_Model::getReccurencePicklistValues()}
									{$FIELD_INFO['picklistvalues'] = $PICKLIST_VALUES}
								{/if}
								data-fieldinfo='{Vtiger_Functions::jsonEncode($FIELD_INFO)}' >
							{vtranslate($FIELD_MODEL->get('workflow_columnlabel'), $SOURCE_MODULE)}
						</option>
					{/foreach}
				{/foreach}
			</select>
		</div>
		<div class="fieldUiHolder col-sm-4 col-xs-4">
			<input type="text" class="inputElement" readonly="" name="fieldValue" value=""  placeholder="field value..."/>
			<input type="hidden" name="valuetype" value="rawtext" />
		</div>
        <div class="col-sm-4 col-xs-4">
            <button type="button" class="btn btn-default add-group">Add Child</button>
        </div>
	</div>
    <div>
        <strong>{vtranslate('Response',$QUALIFIED_MODULE)}</strong>&nbsp;&nbsp;&nbsp;
        <button type="button" class="btn btn-default" id="addFieldResponseBtn">{vtranslate('LBL_ADD',$QUALIFIED_MODULE)}</button>
    </div>
    <br>
    <div class="response_conditionsContainer" id="save_response_fieldvaluemapping">
        {assign var=FIELD_VALUE_MAPPING value=ZEND_JSON::decode($TASK_OBJECT->field_value_mapping)}
        {assign var=RECORD_STRUCTURE value=$TASK_OBJECT->getModuleFields($MODULE_MODEL->get('name'))}

        <input type="hidden" id="response_fieldValueMapping" name="response_field_value_mapping" value='{Vtiger_Util_Helper::toSafeHTML($TASK_OBJECT->field_value_mapping)}' />
        {foreach from=$FIELD_VALUE_MAPPING item=FIELD_MAP}
            {if $FIELD_MAP['vt_map_field']}
                <div class="row conditionRow" style="margin-bottom: 15px;">
                    <div class="cursorPointer col-sm-1 col-xs-1">
                        <center> <i class="alignMiddle response_deleteCondition fa fa-trash" style="position: relative; top: 4px;"></i> </center>
                    </div>
                    <div class="col-sm-3 col-xs-3">
                        <select name="module_field_name_response_map" data-field-value="{$FIELD_MAP['value']}" data-field-type="{$FIELD_MAP['valuetype']}" class="select2" style="min-width: 250px;" data-placeholder="{vtranslate('LBL_SELECT_FIELD',$QUALIFIED_MODULE)}">
                            {foreach from=$RECORD_STRUCTURE  item=FIELDS}
                                {foreach from=$FIELDS item=FIELD_MODEL}
                                    {if (!($FIELD_MODEL->get('workflow_fieldEditable') eq true)) or ($MODULE_MODEL->get('name')=="Documents" and in_array($FIELD_MODEL->get('name'),$RESTRICTFIELDS))}
                                        {continue}
                                    {/if}
                                    {assign var=FIELD_INFO value=$FIELD_MODEL->getFieldInfo()}
                                    {assign var=FIELD_NAME value=$FIELD_MODEL->getName()}
                                    {assign var=FIELD_MODULE_MODEL value=$FIELD_MODEL->getModule()}
                                    <option {if $FIELD_MAP['vt_map_field'] eq $FIELD_MODEL->get('workflow_columnname')}selected{/if} value="{$FIELD_MODEL->get('workflow_columnname')}" data-fieldtype="{$FIELD_MODEL->getFieldType()}" data-field-name="{$FIELD_MODEL->get('name')}"
                                            {if ($FIELD_MODULE_MODEL->get('name') eq 'Events') and ($FIELD_NAME eq 'recurringtype')}
                                                {assign var=PICKLIST_VALUES value=Calendar_Field_Model::getReccurencePicklistValues()}
                                                {$FIELD_INFO['picklistvalues'] = $PICKLIST_VALUES}
                                            {/if}
                                            data-fieldinfo='{Vtiger_Functions::jsonEncode($FIELD_INFO)}' >
                                        {vtranslate($FIELD_MODEL->get('workflow_columnlabel'), $SOURCE_MODULE)}
                                    </option>
                                {/foreach}
                            {/foreach}
                        </select>
                    </div>

                    <div class="fieldUiHolder col-sm-4 col-xs-4">
                        <input type="text" class="inputElement"  name="api_response_field_name" value="{$FIELD_MAP['api_map_field']}" />
                    </div>
                </div>
            {/if}
        {/foreach}
        {include file="FieldExpressions.tpl"|@vtemplate_path:$QUALIFIED_MODULE}
    </div>
    <div class="row response_basicAddFieldContainer hide" style="margin-bottom: 15px;">
        <div class="cursorPointer col-sm-1 col-xs-1">
            <center> <i class="alignMiddle response_deleteCondition fa fa-trash" style="position: relative; top: 4px;"></i> </center>
        </div>
        <div class="col-sm-3 col-xs-3">
            <select name="module_field_name_response_map" data-placeholder="{vtranslate('LBL_SELECT_FIELD',$QUALIFIED_MODULE)}" style="min-width: 250px;">
                <option value="">Select field</option>
                {foreach from=$RECORD_STRUCTURE item=FIELDS}
                    {foreach from=$FIELDS item=FIELD_MODEL}
                        {assign var=FIELD_INFO value=$FIELD_MODEL->getFieldInfo()}
                        {assign var=FIELD_NAME value=$FIELD_MODEL->getName()}
                        {assign var=FIELD_MODULE_MODEL value=$FIELD_MODEL->getModule()}
                        <option value="{$FIELD_MODEL->get('workflow_columnname')}" data-fieldtype="{$FIELD_MODEL->getFieldType()}" data-field-name="{$FIELD_MODEL->get('name')}"
                                {if ($FIELD_MODULE_MODEL->get('name') eq 'Events') and ($FIELD_NAME eq 'recurringtype')}
                                    {assign var=PICKLIST_VALUES value=Calendar_Field_Model::getReccurencePicklistValues()}
                                    {$FIELD_INFO['picklistvalues'] = $PICKLIST_VALUES}
                                {/if}
                                data-fieldinfo='{Vtiger_Functions::jsonEncode($FIELD_INFO)}' >
                            {vtranslate($FIELD_MODEL->get('workflow_columnlabel'), $SOURCE_MODULE)}
                        </option>
                    {/foreach}
                {/foreach}
            </select>
        </div>
        <div class="fieldUiHolder col-sm-4 col-xs-4">
            <input type="text" class="inputElement"  name="api_response_field_name" value="" />
        </div>
    </div>
    <br>
    <div style="margin-bottom: 60px;"></div>
{/strip}
