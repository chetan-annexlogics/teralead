{strip}
	{foreach key=index item=jsModel from=$SCRIPTS}
		<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
	{/foreach}

	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="form-horizontal recordEditView" id="QuickCreate" name="QuickCreate" method="post" action="index.php">
				{assign var=HEADER_TITLE value={vtranslate('LBL_QUICK_CREATE', $MODULE)}|cat:" "|cat:{vtranslate($SINGLE_MODULE, $MODULE)}}
				{include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}

				<div class="modal-body">
					{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
						<input type="hidden" name="picklistDependency" value='{Vtiger_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
					{/if}
					{if $MODULE eq 'Events'}
						<input type="hidden" name="calendarModule" value="Events">
						{if !empty($PICKIST_DEPENDENCY_DATASOURCE_EVENT)}
							<input type="hidden" name="picklistDependency" value='{Vtiger_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE_EVENT)}' />
						{/if}
					{/if}
					{if $MODULE eq 'Events'}
						<input type="hidden" name="source_module" value="Calendar">
					{else}
						<input type="hidden" name="source_module" value="{$MODULE}">
					{/if}
					<input type="hidden" name="module" value="VTDevKBView">
					<input type="hidden" name="action" value="QuickAjax">
					<input type="hidden" name="record" value="{$RECORD}">
					<div class="quickCreateContent">
						<table class="massEditTable table no-border">
							<tr>
								{assign var=COUNTER value=0}
								{foreach key=FIELD_NAME item=FIELD_MODEL from=$RECORD_STRUCTURE name=blockfields}
								{assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
								{assign var="referenceList" value=$FIELD_MODEL->getReferenceList()}
								{assign var="referenceListCount" value=count($referenceList)}
								{if $FIELD_MODEL->get('uitype') eq "19"}
								{if $COUNTER eq '1'}
								<td></td><td></td></tr><tr>
								{assign var=COUNTER value=0}
								{/if}
								{/if}
								{if $COUNTER eq 2}
							</tr><tr>
								{assign var=COUNTER value=1}
								{else}
								{assign var=COUNTER value=$COUNTER+1}
								{/if}
								<td class='fieldLabel col-lg-2'>
									{if $isReferenceField neq "reference"}<label class="muted pull-right">{/if}
										{if $isReferenceField eq "reference"}
											{if $referenceListCount > 1}
												{assign var="DISPLAYID" value=$FIELD_MODEL->get('fieldvalue')}
												{assign var="REFERENCED_MODULE_STRUCT" value=$FIELD_MODEL->getUITypeModel()->getReferenceModule($DISPLAYID)}
												{if !empty($REFERENCED_MODULE_STRUCT)}
													{assign var="REFERENCED_MODULE_NAME" value=$REFERENCED_MODULE_STRUCT->get('name')}
												{/if}
												<span class="pull-right">
                                                        <select style="width:150px;" class="select2 referenceModulesList {if $FIELD_MODEL->isMandatory() eq true}reference-mandatory{/if}">
															{foreach key=index item=value from=$referenceList}
																<option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if} >{vtranslate($value, $value)}</option>
															{/foreach}
														</select>
                                                    </span>
											{else}
												<label class="muted pull-right">{vtranslate($FIELD_MODEL->get('label'), $MODULE)}&nbsp;{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}</label>
											{/if}
										{else if $FIELD_MODEL->get('uitype') eq '83'}
											{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) COUNTER=$COUNTER MODULE=$MODULE PULL_RIGHT=true}
											{if $TAXCLASS_DETAILS}
												{assign 'taxCount' count($TAXCLASS_DETAILS)%2}
												{if $taxCount eq 0}
													{if $COUNTER eq 2}
														{assign var=COUNTER value=1}
													{else}
														{assign var=COUNTER value=2}
													{/if}
												{/if}
											{/if}
										{else}
											{vtranslate($FIELD_MODEL->get('label'), $MODULE)}&nbsp;{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}
										{/if}
										{if $isReferenceField neq "reference"}</label>{/if}
								</td>
								{if $FIELD_MODEL->get('uitype') neq '83'}
									<td class="fieldValue col-lg-4" {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
										{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
									</td>
								{/if}
								{/foreach}
							</tr>
							{if $PRIMARY_FIELD_NAME}
								<tr>
									<td class='fieldLabel col-lg-2'>
										<label class="muted pull-right">{vtranslate($PRIMARY_FIELD_NAME->get('label'), $MODULE)}&nbsp;</label>
									</td>
									<td class="fieldValue col-lg-4">
										{include file=vtemplate_path($PRIMARY_FIELD_NAME->getUITypeModel()->getTemplateName(),$MODULE) FIELD_MODEL=$PRIMARY_FIELD_NAME}
									</td>

								</tr>
							{/if}
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<center>
						{if $BUTTON_NAME neq null}
							{assign var=BUTTON_LABEL value=$BUTTON_NAME}
						{else}
							{assign var=BUTTON_LABEL value={vtranslate('LBL_SAVE', $MODULE)}}
						{/if}
						{assign var="EDIT_VIEW_URL" value=$MODULE_MODEL->getCreateRecordUrl()}
						<button class="btn" id="goToFullForm" data-edit-view-url="{$EDIT_VIEW_URL}" type="button"><strong>{vtranslate('LBL_GO_TO_FULL_FORM', $MODULE)}</strong></button>
						<button {if $BUTTON_ID neq null} id="{$BUTTON_ID}" {/if} class="btn btn-success" type="submit" name="saveButton"><strong>{$BUTTON_LABEL}</strong></button>
						<a href="#" class="cancelLink" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
					</center>
				</div>
			</form>
		</div>
		{if $FIELDS_INFO neq null}
			<script type="text/javascript">
				var quickcreate_uimeta = (function() {
					var fieldInfo  = {$FIELDS_INFO};
					return {
						field: {
							get: function(name, property) {
								if(name && property === undefined) {
									return fieldInfo[name];
								}
								if(name && property) {
									return fieldInfo[name][property]
								}
							},
							isMandatory : function(name){
								if(fieldInfo[name]) {
									return fieldInfo[name].mandatory;
								}
								return false;
							},
							getType : function(name){
								if(fieldInfo[name]) {
									return fieldInfo[name].type
								}
								return false;
							}
						},
					};
				})();
			</script>
		{/if}
	</div>
{/strip}