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
    <tr>
        <td colspan="4"><hr></td>
    </tr>
    <tr>
        <td class="col-lg-1"></td>
        <td class="fieldLabel col-lg-4"><label class="pull-right">{vtranslate('LBL_DATA_COLOR','CustomDashboards')}</label></td>
        <td class="fieldValue col-lg-5" data-value="{$SHOW_EMPTY_VAL}">
            <input class="inputElement" type="text" name="dataColor" value="{if $WIDGET}{CustomDashboards_Gauge_Model::getValueByName($WIDGET,'dataColor')}{/if}" placeholder="Data Color (optional)"/>
        </td>
        <td class="col-lg-4"></td>
    </tr>

    <tr>
        <td class="col-lg-1"></td>
        <td class="fieldLabel col-lg-4"><label class="pull-right">{vtranslate('LBL_BACKGROUND_COLOR','CustomDashboards')}</label></td>
        <td class="fieldValue col-lg-5" data-value="{$SHOW_EMPTY_VAL}">
            <input class="inputElement" type="text" name="backgroundColor" value="{if $WIDGET}{CustomDashboards_Gauge_Model::getValueByName($WIDGET,'backgroundColor')}{/if}" placeholder="Background Color (optional)"/>
        </td>
        <td class="col-lg-4"></td>
    </tr>

    <tr>
        <td class="col-lg-1"></td>
        <td class="fieldLabel col-lg-4"><label class="pull-right">{vtranslate('LBL_DECIMAL','CustomDashboards')}</label></td>
        <td class="fieldValue col-lg-5" data-value="{$SHOW_EMPTY_VAL}">
            <input style="padding: 8px" class="inputElement" type="number" name="decimal" min="0" max="3" value="{if $WIDGET}{CustomDashboards_Gauge_Model::getValueByName($WIDGET,'decimal')}{/if}" placeholder="Decimals (optional)"/>
        </td>
        <td class="col-lg-4"></td>
    </tr>

    <tr>
        <td class="col-lg-1"></td>
        <td class="fieldLabel col-lg-4"><label class="pull-right">{vtranslate('LBL_FOTMAT_LARGE_MUNBER','CustomDashboards')}</label></td>
        <td class="fieldValue col-lg-5" data-value="{$SHOW_EMPTY_VAL}">
            {if $WIDGET}
                {assign var="SELECT_VALUE" value=CustomDashboards_Gauge_Model::getValueByName($WIDGET,'formatLargeNumber')}
            {/if}
            <select  class="select2-choice" style="width: 100%" name="formatLargeNumber">
                <option {if $SELECT_VALUE eq '0'} selected{/if} value="0">No</option>
                <option {if $SELECT_VALUE eq '1'} selected{/if} value="1">Yes</option>
            </select>
        </td>
        <td class="col-lg-4"></td>
    </tr>

    <tr>
        <td class="col-lg-1"></td>
        <td class="fieldLabel col-lg-4"><label class="pull-right">{vtranslate('LBL_ICON','CustomDashboards')}</label></td>
        <td class="fieldValue col-lg-5" data-value="{$SHOW_EMPTY_VAL}">
            <input type="text" class="inputElement" name="icon" value="{if $WIDGET}{CustomDashboards_Gauge_Model::getValueByName($WIDGET,'icon')}{/if}" placeholder="Icon (optional)"/>
        </td>
        <td class="col-lg-4"></td>
    </tr>
{/strip}