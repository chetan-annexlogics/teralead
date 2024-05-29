<div class="accountsSMTP">
    <div class="accountsSMTP_header">
        <h3 style="padding-bottom: 1em;text-align: center">{vtranslate('Multi SMTP accounts', $MODULE)}</h3>
        <div class="row" style="margin: 1em;">
            <div class="alert alert-warning" style="float: left;margin-left:1em !important; margin-bottom: 0px !important;margin-top: 0px !important;width: 80%; display:none;">
                {vtranslate('notice', $MODULE)}
            </div>
        </div>
    </div>
    <div>
        <div style="width: 100%;margin: auto;">
            <div style="width:100%; height: 1%;">
                <button class="btn pull-right" style="margin-bottom: 0.5em;" onclick="window.location.href='index.php?module={$MODULE}&view=SMTPAddnew';return false;">
                    {vtranslate('Add new from address', $MODULE)}
                </button>
            </div>
            <table class="table table-bordered listViewEntriesTable">
                <thead>
                    <tr class="listViewHeaders">
                        <th> {vtranslate('SMTP Server', $MODULE)} </th>
                        <th> {vtranslate('SMTP User', $MODULE)} </th>
                        <th> {vtranslate('Password', $MODULE)} </th>
                        <th> {vtranslate('From email address', $MODULE)} </th>
                        <th> {vtranslate('From Name', $MODULE)} </th>
                        <th> {vtranslate('Batch Count', $MODULE)} </th>
                        <th> {vtranslate('Batch Delay', $MODULE)} </th>
                        <th> {vtranslate('Requires Authentication', $MODULE)} </th>
                        <th> {vtranslate('Actions', $MODULE)} </th>
                    </tr>
                </thead>
                {foreach item=RMU_FIELDS from=$RMU_FIELDS_ARRAY}
                    <tr class="listViewEntries">
                        <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.server_name}</td>
                        <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.user_name} </td>
                        <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.password} </td>
                        <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.email_from} </td>
                        <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.from_name} </td>
                        <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.batch_count} </td>
                        <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.batch_delay} </td>
                        <td class="listViewEntryValue" nowrap> {if $RMU_FIELDS.smtp_auth}Yes{else}No{/if}</td>
                        <td class="listViewEntryValue" nowrap>
                            <a class="deleteRecordButton" id="{$RMU_FIELDS.id}">
                                <i title="Delete" class="icon-trash alignMiddle"></i>
                            </a>
                        </td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
</div>
<link type='text/css' rel='stylesheet' href='layouts/v7/modules/VGSMultiSender/css/VGSMultiSender.css'>