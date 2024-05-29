{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}

{strip}
<div class="col-sm-12 col-xs-12 module-action-bar clearfix coloredBorderTop" style="border-bottom: 1px solid #DDDDDD;">
    <div class="module-action-content clearfix">
        <div class="col-lg-5 col-md-5 module-breadcrumb module-breadcrumb-{$smarty.request.view} transitionsAllHalfSecond">
			<div class="dropdown">
				<button class="dropbtn">
					<span class="app-icon-list fa fa-cog"></span>
					<span class="app-name textOverflowEllipsis"> Settings</span>
				</button>
				<div class="dropdown-content">
					<a class="pull-left"
					   href="index.php?module={$KANBAN_PARENT_MODULE}&view=List&viewname={$VIEWID}&goback=1"><b> {vtranslate('Back to listview', 'VTDevKBView')}</b></a>
					<a class="pull-left" href="javascript:void(0);" id="btnConfig" onclick="VTDevKBView_Js.getSettingView('{$KANBAN_PARENT_MODULE}','VTDevKBView')">
						<b>{vtranslate('Config Kanban', 'VTDevKBView')}</b></a>
				</div>
			</div>

        </div>
		<div class="pull-left" style="margin-top: 7px;">
			<div class="dropdown">
				<select class="select2" style="min-width: 200px;" id ="selectModule">
					<option value="Contacts" {if $smarty.request.source_module eq "Contacts"} selected{/if}>Form Leads</option>
					<option value="PBXManager" {if $smarty.request.source_module eq "PBXManager"} selected{/if}>Call Leads</option>
				</select>
			</div>

		</div>
        <div class="col-lg-5 col-md-5 pull-right">

        </div>
        {if $FIELDS_INFO neq null}
            <script type="text/javascript">
                var uimeta = (function () {
                    var fieldInfo = {$FIELDS_INFO};
                    return {
                        field: {
                            get: function (name, property) {
                                if (name && property === undefined) {
                                    return fieldInfo[name];
                                }
                                if (name && property) {
                                    return fieldInfo[name][property]
                                }
                            },
                            isMandatory: function (name) {
                                if (fieldInfo[name]) {
                                    return fieldInfo[name].mandatory;
                                }
                                return false;
                            },
                            getType: function (name) {
                                if (fieldInfo[name]) {
                                    return fieldInfo[name].type
                                }
                                return false;
                            }
                        },
                    };
                })();
            </script>
        {/if}
		<script type="text/javascript">
			$("body").delegate("#selectModule", "change", function(){
				var selectedModule = $(this).val();
				var kbSourceModule = $("#kbSourceModule").val();
				if(selectedModule != kbSourceModule){
					window.location.href = "index.php?module=VTDevKBView&view=Index&source_module=" + selectedModule;
				}
			})
		</script>
        {/strip}
