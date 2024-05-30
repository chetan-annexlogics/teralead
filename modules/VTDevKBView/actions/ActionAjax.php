<?php


class VTDevKBView_ActionAjax_Action extends Vtiger_Action_Controller
{
    function checkPermission(Vtiger_Request $request)
    {
        return;
    }
    function __construct()
    {
        parent::__construct();
        $this->exposeMethod('getPrimaryValues');
        $this->exposeMethod('checkVTDevKBViewEnable');
        $this->exposeMethod('updatePrimaryFieldValue');
        $this->exposeMethod('enableModule');
        $this->exposeMethod('checkEnable');
        //$this->vtdevLicense();
    }
    function vtdevLicense()
    {
        $vTELicense = VTDevKBView_VTDEVLicense_Model::validate();
        if (!$vTELicense['valid']) {
            echo "Invalid license";
            die();
        }
    }
    function process(Vtiger_Request $request)
    {
        $mode = $request->get('mode');
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
            return;
        }
    }
    function getPrimaryValues(Vtiger_Request $request)
    {
        $primaryFieldSelect = $request->get('primaryField');
        $source_module = $request->get('source_module');
        $recordModel = Vtiger_Record_Model::getCleanInstance($source_module);
        $recordStructureModel = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel);
        foreach ($recordStructureModel->getStructure() as $block) {
            foreach ($block as $field) {
                if ($field->getId() == $primaryFieldSelect) {
                    $primaryFieldValues = $field->getPicklistValues();
                    break;
                }
            }
        }
        $response = new Vtiger_Response();
        $response->setResult($primaryFieldValues);
        $response->emit();
    }
    function checkVTDevKBViewEnable(Vtiger_Request $request)
    {
                              
        global $adb;
        $targetModule = $request->get('source_module');
        $allModules = array_keys(Vtiger_Module_Model::getSearchableModules());
        $sql = "SELECT fieldid,fieldlabel,fieldname FROM vtiger_field
                INNER JOIN vtiger_tab ON vtiger_field.tabid = vtiger_tab.tabid
                WHERE uitype IN (15,16) AND vtiger_tab.name = ? and block > 0";
        $rs = $adb->pquery($sql, array($targetModule));
        $numRow = $adb->num_rows($rs);
        $isConfig = false;
        $isDefaultPage = 0;
        if ($this->checkEnable() && in_array($targetModule, $allModules) && $numRow > 0) {
            $isEnable = true;
            $userModel = Users_Record_Model::getCurrentUserModel();
            $username = $userModel->get('user_name');
            $resultSetting = $adb->pquery("SELECT is_default_page FROM vtdevkbview_setting WHERE module = ? AND username = ?", array($targetModule, $username));
            if ($adb->num_rows($resultSetting) > 0) {
                $isConfig = true;
                $isDefaultPage = $adb->query_result($resultSetting, 0, "is_default_page");
            }
        } else {
            $isEnable = false;
        }
        $result = array('isConfig' => $isConfig, 'isEnable' => $isEnable, 'isDefaultPage' => $isDefaultPage);
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
    function updatePrimaryFieldValue(Vtiger_Request $request)
    {
        global $adb;
        $kanbanModel = new VTDevKBView_Module_Model();
        $recordId = $request->get('recordId');
        $primaryFieldName = $request->get('primaryFieldName');
        $primaryFieldId = $request->get('primaryFieldId');
        $primaryValue = $request->get('primaryValue');
        $nextRecordId = $request->get('nextRecordId');
        $prevRecordId = $request->get('prevRecordId');
        $source_module = $request->get('source_module');
        $userModel = Users_Record_Model::getCurrentUserModel();
        $username = $userModel->get('user_name');
        $curentNumSeq = $kanbanModel->getCurrentSequence($recordId, $username);
        if ($nextRecordId > 0) {
            echo $curentNumSeq;
            $nextSeq = $kanbanModel->getCurrentSequence($nextRecordId, $username);
            if ($curentNumSeq > $nextSeq) {
                echo "1";
                $adb->pquery("update vtdevkb_sequence set sequence = (sequence + 1) where module = ? AND  sequence < $curentNumSeq AND sequence >= $nextSeq AND username = ?", array($source_module, $username));
                $adb->pquery("update vtdevkb_sequence set sequence = ?, primary_field_value = ? where crmid = ? AND username = ?", array($nextSeq, $primaryValue, $recordId, $username));
            } elseif ($curentNumSeq < $nextSeq) {
                echo "2";
                $adb->pquery("update vtdevkb_sequence set sequence = (sequence - 1) where module = ? AND  sequence > $curentNumSeq AND sequence < $nextSeq AND username = ?", array($source_module, $username));
                $adb->pquery("update vtdevkb_sequence set sequence = ?,primary_field_value = ? where crmid = ? AND username = ?", array($nextSeq - 1, $primaryValue, $recordId, $username));
            }
        } elseif ($nextRecordId = -1 && $prevRecordId > 0) {
            $prevSeq = $kanbanModel->getCurrentSequence($prevRecordId, $username);
            if ($curentNumSeq > $prevSeq) {
                $adb->pquery("update vtdevkb_sequence set sequence = (sequence + 1) where module = ? AND  sequence < $curentNumSeq AND sequence > $prevSeq AND username = ?", array($source_module, $username));
                $adb->pquery("update vtdevkb_sequence set sequence = ?,primary_field_value = ? where crmid = ? AND username = ?", array($prevSeq + 1, $primaryValue, $recordId, $username));
            } elseif ($curentNumSeq < $prevSeq) {
                $adb->pquery("update vtdevkb_sequence set sequence = (sequence - 1) where module = ? AND  sequence > $curentNumSeq AND sequence <= $prevSeq AND username = ?", array($source_module, $username));
                $adb->pquery("update vtdevkb_sequence set sequence = ?,primary_field_value = ? where crmid = ? AND username = ?", array($prevSeq, $primaryValue, $recordId, $username));
            }
        } elseif ($nextRecordId = -1 && $prevRecordId = -1) {
            $rsMaxSeqColumn = $adb->pquery("SELECT MAX(sequence) as max_id FROM vtdevkb_sequence WHERE module =? AND primary_field_id = ? AND primary_field_value = ? AND username = ?", array($source_module, $primaryFieldId, $primaryValue, $username));
            $maxSeqComumNum = $adb->query_result($rsMaxSeqColumn, 0, 'max_id');
            if ($maxSeqComumNum > 0) {
                if ($curentNumSeq > $maxSeqComumNum) {
                    $adb->pquery("update vtdevkb_sequence set sequence = (sequence + 1) where module = ? AND  sequence < $curentNumSeq AND sequence > $maxSeqComumNum AND username = ?", array($source_module, $username));
                    $adb->pquery("update vtdevkb_sequence set sequence = ?, primary_field_value = ? where crmid = ? AND username = ?", array($maxSeqComumNum + 1, $primaryValue, $recordId, $username));
                } else {
                    $adb->pquery("update vtdevkb_sequence set sequence = (sequence - 1) where module = ? AND  sequence > $curentNumSeq AND sequence <= $maxSeqComumNum AND username = ?", array($source_module, $username));
                    $adb->pquery("update vtdevkb_sequence set sequence = ?, primary_field_value = ? where crmid = ? AND username = ?", array($maxSeqComumNum, $primaryValue, $recordId, $username));
                }
            } else {
                $rsMaxSeqModule = $adb->pquery("SELECT MAX(sequence) as max_id FROM vtdevkb_sequence WHERE module =? AND username = ?", array($source_module, $username));
                $maxSeqModuleNum = $adb->query_result($rsMaxSeqModule, 0, 'max_id');
                $adb->pquery("update vtdevkb_sequence set sequence = (sequence - 1) where module = ? AND  sequence > $curentNumSeq AND sequence <= $maxSeqModuleNum AND username = ?", array($source_module, $username));
                $adb->pquery("update vtdevkb_sequence set sequence = ?, primary_field_value = ? where crmid = ? AND username = ?", array($maxSeqModuleNum, $primaryValue, $recordId, $username));
            }
        }
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId);
        $modelData = $recordModel->getData();
        $recordModel->set('id', $recordId);
        $recordModel->set('mode', 'edit');
        $recordModel->set($primaryFieldName, $primaryValue);
        $_REQUEST['ajxaction'] = 'DETAILVIEW';
        $_REQUEST['action'] = 'SaveAjax';
        $result = $recordModel->save();
        $_REQUEST['action'] = 'ActionAjax';
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
    function enableModule(Vtiger_Request $request)
    {
        global $adb;
        $value = $request->get('value');
        $sql = "SELECT * FROM vtdevkb_view_settings";
        $res = $adb->pquery($sql, array());
        if ($adb->num_rows($res) > 0) {
            $adb->pquery("UPDATE `vtdevkb_view_settings` SET `enable`=?", array($value));
        } else {
            $adb->pquery("INSERT INTO `vtdevkb_view_settings`(`enable`) VALUES (?)", array($value));
        }
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult(array('result' => 'success'));
        $response->emit();
    }
    function checkEnable()
    {
        global $adb;
        $rs = $adb->pquery("SELECT `enable` FROM `vtdevkb_view_settings`;", array());
        $enable = $adb->query_result($rs, 0, 'enable');
        return $enable;
    }
}
