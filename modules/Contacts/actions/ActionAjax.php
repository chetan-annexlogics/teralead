<?php
/* ********************************************************************************
 * The content of this file is subject to the Custom Header/Bills ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VtigerDev.com
 * Portions created by VtigerDev.com. are Copyright(C) VtigerDev.com.
 * All Rights Reserved.
 * ****************************************************************************** */
class Contacts_ActionAjax_Action extends Vtiger_Action_Controller {

    function checkPermission(Vtiger_Request $request) {
        return true;
    }

    function __construct() {
        parent::__construct();
        $this->exposeMethod('doUpdateFields');
        $this->exposeMethod('GetColor');
        $this->exposeMethod('checkDefaultListView');
    }

    function process(Vtiger_Request $request) {
        $mode = $request->get('mode');
        if(!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
            return;
        }
    }
    public function doUpdateFields(Vtiger_Request $request) {
        global $adb;
        $recordId = $request->get('record');
        $moduleName = $request->get('source_module');
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleModel);
        $recordModel->set('id', $recordId);
        $recordModel->set('mode', 'edit');
        $_REQUEST['ajxaction'] = 'DETAILVIEW';
        $fieldModelList = $moduleModel->getFields();
        foreach ($fieldModelList as $fieldName => $fieldModel) {
            //For not converting createdtime and modified time to user format
            $uiType = $fieldModel->get('uitype');
            if ($uiType == 70) {
                $fieldValue = $recordModel->get($fieldName);
            } else {
                $fieldValue = $fieldModel->getUITypeModel()->getUserRequestValue($recordModel->get($fieldName));
            }

            // To support Inline Edit in Vtiger7
            if($request->has($fieldName)){
                $fieldValue = $request->get($fieldName,null);
            }else if($fieldName === $request->get('field')){
                $fieldValue = $request->get('value');
            }

            $fieldDataType = $fieldModel->getFieldDataType();
            if ($fieldDataType == 'time') {
                $fieldValue = Vtiger_Time_UIType::getTimeValueWithSeconds($fieldValue);
            }
            if ($fieldValue !== null) {
                if (!is_array($fieldValue)) {
                    $fieldValue = trim($fieldValue);
                }
                $recordModel->set($fieldName, $fieldValue);
            }
            $recordModel->set($fieldName, $fieldValue);
        }
        $recordModel->save();
        //self::autoUpdate($request);

        $response = new Vtiger_Response();
        $response->setResult('success');
        $response->emit();
    }

    public function GetColor(Vtiger_Request $request) {
        $recordId = $request->get('record');
        $moduleName = $request->get('module');
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleModel);
        $color = $recordModel->get('cf_913');
        if($color === "Appointment Booked"){
            $appointmentDate = $recordModel -> get('cf_919');
            $appointmentTime = $recordModel -> get('cf_921');
            if(!empty($appointmentDate) && !empty($appointmentTime)){
                $color = '';
            }
        }
        elseif($color === "Closed Leads"){
            $paymentRecieved = $recordModel -> get('cf_929');
            if(!empty($paymentRecieved)){
                $color = '';
            }
        }
        $response = new Vtiger_Response();
        $response->setResult($color);
        $response->emit();
    }
    public function checkDefaultListView(Vtiger_Request $request){
        global $adb,$current_user;
        $return = array("default" => false);
        $sql = "SELECT is_default_page FROM vtdevkbview_setting s
                    INNER JOIN vtiger_users u ON s.username = u.user_name
                    WHERE u.user_name = ?";
        $result = $adb -> pquery($sql,array($current_user->user_name));
        if($adb->num_rows($result)) {
            $is_default_page = $adb->query_result($result,0,"is_default_page");
            if($is_default_page == 1)  $return = array("default" => true);
        }

        $response = new Vtiger_Response();
        $response->setResult($return);
        $response->emit();
    }
//End class
}