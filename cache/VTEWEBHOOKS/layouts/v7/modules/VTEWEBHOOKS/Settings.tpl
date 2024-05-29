{*<!--
/* ********************************************************************************
* The content of this file is subject to the Multiple SMTP ("License");
* You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is VTExperts.com
* Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
* All Rights Reserved.
* ****************************************************************************** */
-->*}
<style>
    .web-hook-info{
        border: 1px solid rgb(217, 217, 217);
        border-left: #52a9cd solid 4px;
        max-height: 419px;
        height: 120px;
    }

    .web-hook-info > .label-info{
        color: #52a9cd;
        background-color: white !important;
    }

    .web-hook-info > .content-info{
        resize: none;
        border: none;
        width: 100%;
        color: #9b9997;
        max-height: 140px;
        height: 140px;
    }
</style>
<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3>{vtranslate('VTEWEBHOOKS', 'VTEWEBHOOKS')}</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <div class="web-hook-info col-lg-12 col-sm-12 col-xs-12">
                <div class="label-info">
                    <h5>
                        <span class="glyphicon glyphicon-info-sign"></span> Info
                    </h5>
                </div>
                <span>Webhooks have to be creating using workflows. Please go to Automation > Workflows, create new/edit existing workflow and you see under "Add Tasks" - there will be new option for "Webhooks".</br></br>NOTE: Please refer to the extension user guide for more details. User guide includes detailed explanation of how the extension.</span>
            </div>
        </div>
    </div>
</div>