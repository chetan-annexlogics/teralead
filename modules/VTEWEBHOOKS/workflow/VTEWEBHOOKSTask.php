<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once('modules/com_vtiger_workflow/VTEntityCache.inc');
require_once('modules/com_vtiger_workflow/VTWorkflowUtils.php');

class VTEWEBHOOKSTask extends VTTask {

    public $executeImmediately = true;
    //array which contains the focus instances of reference fields
    private $referenceFieldFocusList = array();

    public function getFieldNames() {
        return array('field_value_mapping');
    }

    public function getModuleFields($module){
        $selectedModuleName = $module;
        $selectedModule = Vtiger_Module_Model::getInstance($selectedModuleName);
        $workflowModel = Settings_Workflows_Record_Model::getCleanInstance($selectedModuleName);

        $taskModel = Settings_Workflows_TaskRecord_Model::getCleanInstance($workflowModel, 'VTUpdateFieldsTask');

        $recordStructureInstance = Settings_Workflows_RecordStructure_Model::getInstanceForWorkFlowModule($workflowModel,Settings_Workflows_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDITTASK);
        $recordStructureInstance->setTaskRecordModel($taskModel);

        return $recordStructureInstance->getStructure();
    }

    public function checkExecuteDemand($taskId){
        $taskModel = Settings_Workflows_TaskRecord_Model::getInstance($taskId);
        $taskObject = $taskModel->getTaskObject();
        $taskObject = Zend_Json::decode($taskObject->field_value_mapping);
        return $taskObject[0]['ActiveRealTimeField'];
    }
    public function getWebhookTaskFieldValue($taskId,$fieldName){
        $taskModel = Settings_Workflows_TaskRecord_Model::getInstance($taskId);
        $taskObject = $taskModel->getTaskObject();
        $taskObject = Zend_Json::decode($taskObject->field_value_mapping);
        return $taskObject[0][$fieldName];
    }

    public function doTask($entity) {
        try {
            global $site_URL;
            $tmpFiles = $_FILES;
            unset($_FILES);
            global $adb, $current_user, $default_charset,$default_timezone;
            $referenceModuleUpdated = array();
            $util = new VTWorkflowUtils();
            $util->adminUser();

            $moduleName = $entity->getModuleName();
            $entityId = $entity->getId();
            $recordId = vtws_getIdComponents($entityId);
            $recordId = $recordId[1];

            $moduleHandler = vtws_getModuleHandlerFromName($moduleName, $current_user);
            $handlerMeta = $moduleHandler->getMeta();
            $moduleFields = $handlerMeta->getModuleFields();

            $fieldValueMapping = array();
            if (!empty($this->field_value_mapping)) {
                $fieldValueMapping = Zend_Json::decode($this->field_value_mapping);
            }
            $webhook_task_id = $this->id;
            $referenceFieldFocus = array();
            if (!empty($fieldValueMapping) && count($fieldValueMapping) > 0) {
                require_once('data/CRMEntity.php');
                $focus = CRMEntity::getInstance($moduleName);
                $focus->id = $recordId;
                $focus->mode = 'edit';
                $focus->retrieve_entity_info($recordId, $moduleName);
                $focus->clearSingletonSaveFields();
                $util->loggedInUser();
                $request = array();
                $jsonFromField = '';
                foreach ($fieldValueMapping as $fieldInfo) {
                    if(!empty($fieldInfo['fieldname'])){
                        $fieldName = $fieldInfo['fieldname'];
                        $fieldValue = trim($fieldInfo['value']);
                        if($fieldName == 'json_content_from_field') {
                            $jsonFromField = decode_html($focus->column_fields[$fieldValue]);
                            break;
                        }
                        $fieldValueType = $fieldInfo['valuetype'];
                        $webhook_method = $fieldInfo['webhook_method'];
                        $webhook_content_type = $fieldInfo['webhook_content_type'];
                        $webhook_url = $fieldInfo['webhook_url'];
                        $webhook_authorization = $fieldInfo['webhook_authorization'];
                        $webhook_authorization_username = $fieldInfo['webhook_authorization_username'];
                        $webhook_authorization_password = $fieldInfo['webhook_authorization_password'];
                        $parent = $fieldInfo['parent'];
                        $index = $fieldInfo['index'];

                        if($fieldValueType == 'fieldname'){
                            preg_match('/\((\w+) : \((\w+)\) (\w+)\)/', $fieldValue, $matches1);
                            if (count($matches1) > 0) {
                                $referenceField = $matches1[1];
                                $referencedModule = $matches1[2];
                                $referencedFieldName = $matches1[3];
                                $referenceRecordId = $focus->column_fields[$referenceField];
                                if (empty($referenceRecordId)) {
                                    //if no value exists for the reference field then we dont have to update
                                    continue;
                                } else {
                                    $referenceFocus = $this->getReferenceFieldFocus($referencedModule, $referenceField, $referenceRecordId);
                                    $fieldValue = $referenceFocus->column_fields[$referencedFieldName];
                                }
                            }else{
                                $fieldValue = $focus->column_fields[$fieldValue];
                            }
                        }elseif($fieldValueType == 'expression'){
                            require_once 'modules/com_vtiger_workflow/expression_engine/include.inc';
                            //Added to generate date value in user timezone.
                            date_default_timezone_set($current_user->time_zone);
                            $parser = new VTExpressionParser(new VTExpressionSpaceFilter(new VTExpressionTokenizer($fieldValue)));
                            $expression = $parser->expression();
                            $exprEvaluater = new VTFieldExpressionEvaluater($expression);
                            $fieldValue = $exprEvaluater->evaluate($entity);
                        }else{
                            $fieldValue = $fieldValue;
                        }
                        $request[$index] = array('value'=>$fieldValue,'parent'=>$parent,'name'=>$fieldName,'index'=>$index);
                    }
                }
                $request = $this->mergeToTreeRequest($request);
                $new = array();
                foreach($request as $item){
                    if(!empty($item['value'])){
                        $new[$item['name']] = $item['value'];
                    }else{
                        $sub = array();
                        foreach($item as $k => $val){
                            if(is_array($val)){
                                $sub[]=$val;
                            }
                        }
                        $new[$item['name']] = $sub;
                    }
                }
                $request = json_encode($new);
                if($jsonFromField != '') {
                    $request = $jsonFromField;
                }
                $sql = "SELECT
					wft.summary wft_summary,
					wft.task_id,
					wf.workflowname as wf_summary
				FROM
					`com_vtiger_workflowtasks` wft
				INNER JOIN com_vtiger_workflows wf ON wf.workflow_id = wft.workflow_id
				WHERE wft.task_id = ?
				";
                $results= $adb->pquery($sql,array($webhook_task_id));
                if($adb->num_rows($results)>0) {
                    $wft_summary = $adb->query_result($results, 0, 'wft_summary');
                    $wf_summary = $adb->query_result($results, 0, 'wf_summary');
                }
                $sourceRecordModel = Vtiger_Record_Model::getInstanceById($recordId,$moduleName);
                $record_url = $site_URL.$sourceRecordModel->getDetailViewUrl();
                $sourceRecordName = $sourceRecordModel->getName();
                $web_hook_request = 'VTEWebhookRequests';
                $web_hook_request_record = Vtiger_Record_Model::getCleanInstance($web_hook_request);
                $web_hook_request_record->set('mode','');
                $web_hook_request_record->set('name',$sourceRecordName);
                $web_hook_request_record->set('assigned_user_id',$current_user->id);
                $web_hook_request_record->set('workflow',$wf_summary);
                $web_hook_request_record->set('source_module',$moduleName);
                $web_hook_request_record->set('action_title',$wft_summary);
                $web_hook_request_record->set('record_url',$record_url);
                $web_hook_request_record->set('method',$webhook_method);
                $web_hook_request_record->set('url',$webhook_url);
                $web_hook_request_record->set('content_type',$webhook_content_type);
                $web_hook_request_record->set('authorization_type',$webhook_authorization);
                $web_hook_request_record->set('authorization_username',$webhook_authorization_username);
                $web_hook_request_record->set('authorization_password',$webhook_authorization_password);
                $web_hook_request_record->set('request',$request);
                $web_hook_request_record->set('request_status','Pending');
                $web_hook_request_record->save();
                $web_hook_request_id = $web_hook_request_record->getId();
                $sql = "INSERT INTO `vtewebhookrequest_tasks` (task_id,vtewebhookrequest_id) VALUES(?,?);";
                $adb->pquery($sql,array($webhook_task_id,$web_hook_request_id));
                $sql = "UPDATE `vtiger_vtewebhookrequests` SET source_record = ? WHERE vtewebhookrequestsid = ? ;";
                $adb->pquery($sql,array($recordId,$web_hook_request_id));
                $_REQUEST['file'] = '';
                $_REQUEST['ajxaction'] = '';
                $actionName = $_REQUEST['action'];
                $_REQUEST['action'] = '';
                $_REQUEST['action'] = $actionName;
                $util->revertUser();
            }
            $util->revertUser();
            $_FILES = $tmpFiles;
        }
        catch(Exception $e) {

        }
    }
    /*
     * return array*/
    function mergeToTreeRequest($request){
        foreach($request as $index=>$item){
            $exists_sub = $this->get_exists_sub($index,$request);
            if(count($request) > 0 && $item['parent'] > 0 && !$exists_sub && in_array($index,array_keys($request)) ){
                $itemparent = $item['parent'];
                unset($item['parent']);
                unset($item['index']);
                $item = $this->formatSub($item);
                $request[$itemparent][] = $item;
                unset($request[$index]);
                $request = $this->mergeToTreeRequest($request);
            }
        }
        return $request ;
    }
    function formatSub($arr){
        if(!empty($arr['value'])){
            return array($arr['name']=>$arr['value']);
        }else{
            $sub = array();
            foreach($arr as $key => $item){
                if(is_array($item)){
                    $sub[] = $item;
                }
            }
            return array($arr['name']=>$sub);
        }
    }
    function get_exists_sub($index,$arr){
        $check = false;
        foreach($arr as $item){
            if($index == $item['parent']){
                $check = true;
            }
        }
        return $check;
    }

    //Function use to convert the field value in to current user format
    public function convertValueToUserFormat($fieldObj, $fieldValue) {
        global $current_user;
        if(!empty ($fieldObj)) {
            // handle the case for Date field
            if($fieldObj->getFieldDataType()=="date") {
                if(!empty($fieldValue)) {
                    $dateFieldObj = new DateTimeField($fieldValue);
                    $fieldValue = $dateFieldObj->getDisplayDate($current_user);
                }
            }

            // handle the case for currency field
            if($fieldObj->getFieldDataType()=="currency" && !empty($fieldValue)) {
                if($fieldObj->getUIType() == '71') {
                    $fieldValue = CurrencyField::convertToUserFormat($fieldValue,$current_user,false);
                } else if($fieldObj->getUIType() == '72') {
                    $fieldValue = CurrencyField::convertToUserFormat($fieldValue,$current_user,true);
                }
            }
        }
        return $fieldValue;
    }

    /**
     * Function to calculate Product Unit Price.
     * Product Unit Price value converted with based product currency
     * @param type $fieldValue
     */
    public function calculateProductUnitPrice($fieldValue) {
        $currency_details = getAllCurrencies('all');
        for($i=0;$i<count($currency_details);$i++)  {
            $curid = $currency_details[$i]['curid'];
            $cur_checkname = 'cur_' . $curid . '_check';
            $cur_valuename = 'curname' . $curid;
            if($cur_valuename == $_REQUEST['base_currency'] && ($_REQUEST[$cur_checkname] == 'on' || $_REQUEST[$cur_checkname] == 1)) {
                $fieldValue = $fieldValue * $currency_details[$i]['conversionrate'];
                $_REQUEST[$cur_valuename] = $fieldValue;
            }
        }
        return $fieldValue;
    }

    public function getReferenceFieldFocus($referencedModule,$referenceField,$referenceRecordId){
        global $current_user;
        $referenceRecordFocus = $this->referenceFieldFocusList[$referenceField][$referenceRecordId];
        if(empty($referenceRecordFocus)){
            $referenceRecordFocus = CRMEntity::getInstance($referencedModule);
            $referenceRecordFocus->id = $referenceRecordId;
            $referenceRecordFocus->mode = 'edit';
            if(isRecordExists($referenceRecordId) || $referencedModule=="Users") {
                $referenceRecordFocus->retrieve_entity_info($referenceRecordId, $referencedModule);
            }
            $referenceRecordFocus->clearSingletonSaveFields();

            $referenceModuleHandler = vtws_getModuleHandlerFromName($referencedModule, $current_user);
            $referenceHandlerMeta = $referenceModuleHandler->getMeta();
            $referenceRecordFocus->column_fields->pauseTracking();
            $referenceRecordFocus->column_fields = DataTransform::sanitizeDateFieldsForInsert($referenceRecordFocus->column_fields,$referenceHandlerMeta);
            $referenceRecordFocus->column_fields = DataTransform::sanitizeCurrencyFieldsForInsert($referenceRecordFocus->column_fields,$referenceHandlerMeta);
            $referenceRecordFocus->column_fields->resumeTracking();
            $this->referenceFieldFocusList[$referenceField][$referenceRecordId] = $referenceRecordFocus;
        }
        return $referenceRecordFocus;
    }

}