{*<!--
/* ********************************************************************************
* //ContentNop
* ****************************************************************************** */
-->*}
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3>{vtranslate('CustomDashboards', 'CustomDashboards')}</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="summaryWidgetContainer">
        <div class="row-fluid">
            <h4 style="width: 27%; float: left; margin-top: 0">{vtranslate('LBL_ENABLE_MODULE', 'CustomDashboards')}</h4>
            <input type="checkbox" name="enable_module" id="enable_module" value="1" {if $ENABLE eq '1'}checked="" {/if}/>
        </div>
    </div>
    <div class="clearfix"></div>
    <div>
        <div style="padding: 10px; text-align: justify; font-size: 14px; border: 1px solid #ececec; border-left: 5px solid #2a9bbc; border-radius: 5px; overflow: hidden;">
            <h4 style="color: #2a9bbc; margin: 0px -15px 10px -15px; padding: 0px 15px 8px 15px; border-bottom: 1px solid #ececec;"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;{vtranslate('LBL_INFO_BLOCK', $QUALIFIED_MODULE)}</h4>
            {vtranslate('LBL_INFO_BLOCK_ON_SETTING_PAGE', $QUALIFIED_MODULE)}
        </div>
    </div>
    <div class="clearfix"></div>
    <div style="margin-top: 20px;">
        <button id="phpiniWarnings" name="phpiniWarnings" class="btn btn-danger" style="margin-right:5px;">Find Error</button>
    </div>
</div>