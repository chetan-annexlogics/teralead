<?php
require_once('modules/VTDevKBView/VTDevKBView.php');
class VTDevKBView_Module_Model extends Vtiger_Module_Model
{
    function getSettingLinks()
    {
        $settingsLinks[] = array('linktype' => 'MODULESETTING', 'linklabel' => 'Settings', 'linkurl' => 'index.php?module=VTDevKBView&parent=Settings&view=Settings', 'linkicon' => '');
        $settingsLinks[] = array('linktype' => 'MODULESETTING', 'linklabel' => 'Uninstall', 'linkurl' => 'index.php?module=VTDevKBView&parent=Settings&view=Uninstall', 'linkicon' => '');
        return $settingsLinks;
    }
    function getPrimaryFields($module)
    {
        global $adb;
        $primaryFields = array();
        $sql = "SELECT fieldid,fieldlabel,fieldname FROM vtiger_field
                INNER JOIN vtiger_tab ON vtiger_field.tabid = vtiger_tab.tabid
                WHERE uitype IN (15,16) AND vtiger_tab.`name` = ? AND (vtiger_field.presence = 0 OR vtiger_field.presence = 2) AND vtiger_field.block > 0";
        $rs = $adb->pquery($sql, array($module));
        if ($adb->num_rows($rs) > 0) {
            for ($i = 0; $i < $adb->num_rows($rs); $i++) {
                $primaryFields[$i]['fieldid'] = $adb->query_result($rs, $i, 'fieldid');
                $primaryFields[$i]['fieldlabel'] = $adb->query_result($rs, $i, 'fieldlabel');
                $primaryFields[$i]['fieldname'] = $adb->query_result($rs, $i, 'fieldname');
            }
        }
        return $primaryFields;
    }
    function removeFieldForVTDevKBViewSetting($request)
    {
        global $adb;
        $module = $request->get('targetModule');
        $primary_field = $request->get('primaryFieldId');
        $primary_field_value = $request->get('primaryFieldValue');
        if (!empty($primary_field)) {
            $sql = 'SELECT * FROM vtdevkbview_setting WHERE vtdevkbview_setting.module = ? AND primary_field = ?';
            $rs = $adb->pquery($sql, array($module, $primary_field));
            if ($adb->num_rows($rs) > 0) {
                $primary_value_setting = unserialize(decode_html($adb->query_result($rs, 0, 'primary_value')));
                $key = array_search($primary_field_value, $primary_value_setting);
                if (false !== $key) {
                    unset($primary_value_setting[$key]);
                    $sql = 'UPDATE vtdevkbview_setting SET primary_value = ? WHERE primary_field = ? AND module = ?';
                    $adb->pquery($sql, array(serialize($primary_value_setting), $primary_field, $module));
                    return true;
                }
            }
        }
        return false;
    }
    function updateVTDevKBViewSetting($request)
    {
        global $adb;
        $userModel = Users_Record_Model::getCurrentUserModel();
        $userName = $userModel->get('user_name');
        if (!empty($userName)) {
            $module = $request->get('source_module');
            $primary_field = $request->get('primaryField');
            $primary_value = serialize($request->get('primaryFieldValue'));
            $other_field = serialize($request->get('otherField'));
            $isDefaultPage = $request->get('isDefaultPage');
            $moduleModel = Vtiger_Module_Model::getInstance($module);
            $colorField = Vtiger_Field_Model::getInstance('vtdevkb_color', $moduleModel);
            if (!$colorField) {
                VTDevKBView::createFields($module);
            }
            if ($this->isUpdate($module, $userName)) {
                $sql = 'UPDATE vtdevkbview_setting SET primary_field = ?, primary_value = ?, other_field = ?, is_default_page = ? WHERE username = ? AND module = ?';
                $adb->pquery($sql, array($primary_field, $primary_value, $other_field, $isDefaultPage, $userName, $module));
                return true;
            } else {
                $sql = 'INSERT INTO vtdevkbview_setting (primary_field,primary_value,other_field,module,username,is_default_page) VALUES (?,?,?,?,?,?)';
                $adb->pquery($sql, array($primary_field, $primary_value, $other_field, $module, $userName, $isDefaultPage));
                return true;
            }
        }
        return false;
    }
    function isUpdate($module, $userName)
    {
        global $adb;
        $sql = 'SELECT * FROM vtdevkbview_setting WHERE vtdevkbview_setting.module = ? AND username = ?';
        $rs = $adb->pquery($sql, array($module, $userName));
        if ($adb->num_rows($rs) > 0) {
            return true;
        } else {
            return false;
        }
    }
    function getVTDevKBviewSetting($module)
    {
        global $adb;
        $primaryFieldSettings = array();
        $userModel = Users_Record_Model::getCurrentUserModel();
        $username = $userModel->get('user_name');
        if (!empty($username)) {
            $sql = 'SELECT * FROM vtdevkbview_setting WHERE vtdevkbview_setting.module = ? AND username = ?';
            $rs = $adb->pquery($sql, array($module, $username));
            if ($adb->num_rows($rs) > 0) {
                $primaryFieldSettings['primary_field'] = $adb->query_result($rs, 0, 'primary_field');
                $primaryFieldSettings['primary_value_setting'] = unserialize(decode_html($adb->query_result($rs, 0, 'primary_value')));
                $primaryFieldSettings['other_field'] = unserialize(decode_html($adb->query_result($rs, 0, 'other_field')));
                $primaryFieldSettings['is_default_page'] = $adb->query_result($rs, 0, 'is_default_page');
                if (!$primaryFieldSettings['primary_value_setting']) {
                    $primaryFieldSettings['primary_value_setting'] = array();
                }
                if (!$primaryFieldSettings['other_field']) {
                    $primaryFieldSettings['other_field'] = array();
                }
            }
        }
        return $primaryFieldSettings;
    }

    public function getVTDevKBviewAllLeadField($module){
        global $adb;
        $primaryFieldSettings = array();
        $username = "crm";
        if (!empty($username)) {
            $sql = 'SELECT * FROM vtdevkbview_setting WHERE vtdevkbview_setting.module = ? AND username = ?';
            $rs = $adb->pquery($sql, array($module, $username));
            if ($adb->num_rows($rs) > 0) {
                $primaryFieldSettings['primary_field'] = $adb->query_result($rs, 0, 'primary_field');
                $primaryFieldSettings['primary_value_setting'] = unserialize(decode_html($adb->query_result($rs, 0, 'primary_value')));
                $primaryFieldSettings['other_field'] = unserialize(decode_html($adb->query_result($rs, 0, 'other_field')));
                $primaryFieldSettings['is_default_page'] = $adb->query_result($rs, 0, 'is_default_page');
                if (!$primaryFieldSettings['primary_value_setting']) {
                    $primaryFieldSettings['primary_value_setting'] = array();
                }
                if (!$primaryFieldSettings['other_field']) {
                    $primaryFieldSettings['other_field'] = array();
                }
            }
        }
        return $primaryFieldSettings; 
    }
    function getRecordIdSequence($listRecordId, $module)
    {
        global $adb;
        $listRecordIdSeq = array();
        $resultMaxId = $adb->pquery("SELECT MAX(sequence) as max_id FROM vtdevkb_sequence WHERE module =?", array($module));
        $maxId = 1;
        if ($adb->num_rows($resultMaxId) > 0) {
            $maxId = $adb->query_result($resultMaxId, 0, 'max_id');
        }
        foreach ($listRecordId as $recordId) {
            $sql = "SELECT crmid FROM vtdevkb_sequence WHERE crmid = ? ";
            $rs = $adb->pquery($sql, array($recordId));
            if ($adb->num_rows($rs) == 0) {
                $maxId++;
                $adb->pquery("INSERT INTO vtdevkb_sequence(crmid,module,sequence) VALUES(?,?,?);", array($recordId, $module, $maxId));
            }
        }
        $recordCondition = implode(',', $listRecordId);
        $rsSequence = $adb->pquery("SELECT crmid FROM vtdevkb_sequence WHERE module = ? AND crmid IN ($recordCondition) ORDER BY sequence ASC", array($module));
        if ($adb->num_rows($rsSequence) > 0) {
            for ($i = 0; $i < $adb->num_rows($rsSequence); $i++) {
                $listRecordIdSeq[] = $adb->query_result($rsSequence, $i, 'crmid');
            }
        }
        return $listRecordIdSeq;
    }
    function getSequence($crmId, $module, $primary_field, $primary_value)
    {
        global $adb;
        $userModel = Users_Record_Model::getCurrentUserModel();
        $username = $userModel->get('user_name');
        $rsRecord = $adb->pquery("SELECT * FROM vtdevkb_sequence WHERE crmid = $crmId AND username = ?", array($username));
        if ($adb->num_rows($rsRecord) > 0) {
            return $adb->query_result($rsRecord, 0, 'sequence');
        } else {
            $seqNum = 1;
            $rsMaxSeq = $adb->pquery("SELECT MAX(sequence) as max_id FROM vtdevkb_sequence WHERE module =? AND username = ?", array($module, $username));
            $maxSeq = $adb->query_result($rsMaxSeq, 0, 'max_id');
            if ($maxSeq) {
                $seqNum = $maxSeq + 1;
            }
            $adb->pquery("INSERT INTO vtdevkb_sequence(crmid,module,sequence,`primary_field_id`,`primary_field_value`,`username`) VALUES (?,?,?,?,?,?);", array($crmId, $module, $seqNum, $primary_field, $primary_value, $username));
            return $seqNum;
        }
    }
    function getCurrentSequence($crmId, $username)
    {
        global $adb;
        $seqNum = -1;
        $rs = $adb->pquery("SELECT * FROM vtdevkb_sequence WHERE crmid = ? AND username = ?", array($crmId, $username));
        if ($adb->num_rows($rs) > 0) {
            $seqNum = $adb->query_result($rs, 0, 'sequence');
        }
        return $seqNum;
    }
    function setFontColor($recordModel)
    {
        $groupColor1 = array('Red', 'Green', 'Teal', 'Blue', 'Purple', 'Olive');
        $groupColor2 = array('Yellow', 'Orange', 'Peru', 'Silver');
        $bgColorCard = $recordModel->get('vtdevkb_color');
        if (in_array($bgColorCard, $groupColor1)) {
            $recordModel->set('font_color', 'White');
        } elseif (in_array($bgColorCard, $groupColor2)) {
            $recordModel->set('font_color', 'Black');
        }
        return $recordModel;
    }
}
