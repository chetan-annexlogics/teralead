<div class="accountsSMTP">
    <div class="accountsSMTP_header">
        <h3 style="text-align: center">{vtranslate('Multi SMTP accounts', $MODULE)}</h3>
        <input type="hidden" id="parent_view" value="{$PARENT_MODULE}">
        <div class="row" style="margin: 1em;">
            <div class="alert alert-warning">
                {vtranslate('notice', $MODULE)}
            </div>
        </div>
    </div>
    <div>
        <div style="width: 100%;margin: auto;">
            <div style="width:100%; height: 1%;" class="clearfix">
                <button class="btn pull-right btn-success btn-sm" onclick="window.location.href='index.php?module={$MODULE}&view=SettingsAddNew&parent=Settings';return false;">
                    {vtranslate('Add New', $MODULE)}
                </button>
            </div>
            <div class="accountsSMTP_over">
                <table class="table table-bordered listViewEntriesTable">
                    <thead>
                    <tr class="listViewHeaders">
                        <th> {vtranslate('User Name', $MODULE)} </th>
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
                            <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.user_name}</td>
                            <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.server_name}</td>
                            <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.smtpuser} </td>
                            <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.password} </td>
                            <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.email_from} </td>
                            <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.from_name} </td>
                            <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.batch_count} </td>
                            <td class="listViewEntryValue" nowrap> {$RMU_FIELDS.batch_delay} </td>
                            <td class="listViewEntryValue" nowrap> {if $RMU_FIELDS.smtp_auth}Yes{else}No{/if}</td>
                            <td class="listViewEntryValue" nowrap>
                                <a class="lockRecordButton" id="{$RMU_FIELDS.id}" data-locked="{$RMU_FIELDS.locked}">
                                    {if $RMU_FIELDS.locked}
                                        <i title="Locked" class="fas fa-lock"></i>
                                    {else}
                                        <i title="Unlocked" class="fas fa-lock-open"></i>
                                    {/if}
                                </a>
                                <a class="editRecordButton" id="{$RMU_FIELDS.id}"
                                   href="?module=VGSMultiSender&view=SettingsAddNew&parent=Settings&id={$RMU_FIELDS.id}">
                                    <i title="Edit" class="fa fa-pencil"></i>
                                </a>
                                <a  class="deleteRecordButton" id="{$RMU_FIELDS.id}">
                                    <i title="Delete" class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>
        </div>
    </div>
</div>
<link type='text/css' rel='stylesheet' href='layouts/v7/modules/VGSMultiSender/css/VGSMultiSender.css'>