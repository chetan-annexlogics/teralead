<div style="width: 65%;margin: auto;margin-top: 2em;padding: 2em;">
    <input type="hidden" id="parent_view" value="{$PARENT_MODULE}">
    <h3 style="padding-bottom: 1em;text-align: center">{vtranslate('Multi SMTP accounts', $MODULE)}</h3>

    <div>
        <table class="table table-bordered table-condensed themeTableColor" style="margin-top: 1em;">
            <tbody>
            {if $PARENT_MODULE eq 'Settings'}
                <tr>
                    <td width="50%" colspan="2">
                        <label class="muted pull-right marginRight10px">{vtranslate('User Name', $MODULE)}</label>
                    </td>
                    <td colspan="2" style="border-left: none;">
                        <select name='user_id' class="chzn-select">
                            <option value=""></option>
                            {foreach key=USER_ID item=USER_NAME from=$USER_LIST}
                                {$selected = ''}
                                {if $RECORD['userid'] == $USER_ID}
                                    {$selected = 'selected="selected"'}
                                {/if}
                                <option value="{$USER_ID}" {$selected}>{$USER_NAME}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
            {else}
                <input type="hidden" name='user_id' value="{$CURRENT_USER_ID}">
            {/if}
            {if !isset($RECORD)}
                {$RECORD = array()}
            {/if}
            <input type="hidden" name='id' id="id" value="{$RECORD['id']}">
            <tr>
                <td width="50%" colspan="2">
                    <label class="muted pull-right marginRight10px">{vtranslate('SMTP Server', $MODULE)}</label>
                </td>
                <td colspan="2" style="border-left: none;">
                    <input type="text"
                           name="server_name"
                           id="server_name"
                           value="{$RECORD['server_name']}"
                           style="width:90%;">
                </td>
            </tr>
            <tr>
                <td width="50%" colspan="2">
                    <label class="muted pull-right marginRight10px">{vtranslate('SMTP User', $MODULE)}</label>
                </td>
                <td colspan="2" style="border-left: none;">
                    <input type="text"
                           name="user_name"
                           id="user_name"
                           value="{$RECORD['user_name']}"
                           style="width:90%;">
                </td>
            </tr>
            <tr>
                <td width="50%" colspan="2">
                    <label class="muted pull-right marginRight10px">{vtranslate('Password', $MODULE)}</label>
                </td>
                <td colspan="2" style="border-left: none;">
                    <input type="password"
                           name="password"
                           id="password"
                           placeholder="{$RECORD['password']}"
                           value=""
                           style="width:90%;">
                </td>
            </tr>
            <tr>
                <td width="50%" colspan="2">
                    <label class="muted pull-right marginRight10px">{vtranslate('From Address', $MODULE)}</label>
                </td>
                <td colspan="2" style="border-left: none;">
                    <input type="text"
                           name="email_from"
                           id="email_from"
                           value="{$RECORD['email_from']}"
                           style="width:90%;">
                </td>
            </tr>
            <tr>
                <td width="50%" colspan="2">
                    <label class="muted pull-right marginRight10px">{vtranslate('From Name', $MODULE)}</label>
                </td>
                <td colspan="2" style="border-left: none;">
                    <input type="text"
                           name="from_name"
                           id="from_name"
                           value="{$RECORD['from_name']}"
                           style="width:90%;">
                </td>
            </tr>
            <tr>
                <td width="50%" colspan="2">
                    <label class="muted pull-right marginRight10px">{vtranslate('Batch Count', $MODULE)}</label>
                </td>
                <td colspan="2" style="border-left: none;">
                    <input type="text"
                           name="batch_count"
                           id="batch_count"
                           value="{$RECORD['batch_count']}"
                           style="width:90%;">
                </td>
            </tr>
            <tr>
                <td width="50%" colspan="2">
                    <label class="muted pull-right marginRight10px">{vtranslate('Batch Delay', $MODULE)}</label>
                </td>
                <td colspan="2" style="border-left: none;">
                    <input type="text"
                           name="batch_delay"
                           id="batch_delay"
                           value="{$RECORD['batch_delay']}"
                           style="width:90%;">
                </td>
            </tr>
            <tr>
                <td width="50%" colspan="2">
                    <label class="muted pull-right marginRight10px">{vtranslate('Auth', $MODULE)}</label>
                </td>
                {$checked = ''}
                {if ({$RECORD['smtp_auth']})}
                    {$checked = " checked='checked' "}
                {/if}
                <td colspan="2" style="border-left: none;">
                    <input type="checkbox"
                           name="smtp_auth"
                           id="smtp_auth"
                            {$checked}
                           value="">
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <br>
        <button class="btn pull-right" id="add_entry">
            {vtranslate('Save', $MODULE)}
        </button>
        <button class="btn pull-right" onclick="window.history.back();" style="margin-right: 2%;" >
            {vtranslate('Cancel', $MODULE)}
        </button>
    </div>
</div>
