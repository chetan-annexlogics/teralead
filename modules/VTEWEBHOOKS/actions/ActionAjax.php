<?php
require_once('modules/com_vtiger_workflow/VTTaskManager.inc');
require_once('modules/VTEWEBHOOKS/workflow/VTEWEBHOOKSTask.php');

require_once 'modules/com_vtiger_workflow/expression_engine/VTParser.inc';
require_once 'modules/com_vtiger_workflow/expression_engine/VTTokenizer.inc';
require_once 'modules/com_vtiger_workflow/expression_engine/VTExpressionEvaluater.inc';
require_once 'modules/com_vtiger_workflow/VTEntityCache.inc';
require_once 'modules/com_vtiger_workflow/VTWorkflowManager.inc';
require_once 'include/Webservices/Retrieve.php';
require_once 'modules/VTEWEBHOOKS/helpers/WebhooksJsonCondition.inc.php';

class VTEWEBHOOKS_ActionAjax_Action extends Vtiger_Action_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->exposeMethod('enableModule');
        $this->exposeMethod('getSettingWF');
        $this->exposeMethod('getValuesForUpdateFieldsWebhooksFormulas');
    }

    function checkPermission(Vtiger_Request $request)
    {
        return;
    }

    function process(Vtiger_Request $request)
    {
        $mode = $request->get('mode');
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
            return;
        }
    }

    public function getSettingWF($request)
    {
        $source = $request->get('source');

        $recordModel = new VTEWEBHOOKS_Record_Model();
        $result['data'] = $recordModel->getWorkflowtasksByModuleName($source);
        $vTELicense = new VTEWEBHOOKS_VTELicense_Model('VTEWEBHOOKS');
        if ($vTELicense->validate()) {
            $result['license'] = true;
        }

        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }

    function enableModule(Vtiger_Request $request) {
        global $adb;
        $value=$request->get('value');
        $adb->pquery("UPDATE `webhooks_settings` SET `enable`=?",array($value));
        if($value==1) {
            // Add workflow task
            $taskType = array("name"=>"VTEWEBHOOKSTask", "label"=>"Field Formulas", "classname"=>"VTEWEBHOOKSTask", "classpath"=>"modules/VTEWEBHOOKS/workflow/VTEWEBHOOKSTask.inc", "templatepath"=>"modules/VTEWEBHOOKS/taskforms/VTEWEBHOOKSTask.tpl", "modules"=>array('include' => array(), 'exclude'=>array()), "sourcemodule"=>'VTEWEBHOOKS');
            VTTaskType::registerTaskType($taskType);
        }else{
            // Remove workflow task
            $adb->pquery('DELETE FROM com_vtiger_workflow_tasktypes WHERE tasktypename=? AND sourcemodule=? AND classname=?', array('VTEWEBHOOKSTask','VTEWEBHOOKS','VTEWEBHOOKSTask'));
        }
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult(array('result'=>'success'));
        $response->emit();
    }

    public function getValuesForUpdateFieldsWebhooksFormulas($request)
    {
		global $adb, $current_user;

        $WebhooksJsonCondition = new WebhooksJsonCondition();
        $moduleName = $request->get('source');
        require_once 'modules/'.$moduleName.'/'.$moduleName.'.php';
        $module = new $moduleName();

        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $moduleFields = $moduleModel->getFields();

		$record = $request->get('record');
        if (!empty($record)) {
			$module->id = $record;
		}
        $entityData = VTEntityData::fromCRMEntity($module);

        $moduleHandler = vtws_getModuleHandlerFromName($moduleName, $current_user);
        $handlerMeta = $moduleHandler->getMeta();
        $moduleFields = $handlerMeta->getModuleFields();
        $focus = CRMEntity::getInstance($moduleName);

        $focus->column_fields->pauseTracking();

        $focus->column_fields = DataTransform::sanitizeDateFieldsForInsert($focus->column_fields, $handlerMeta);
        $focus->column_fields = DataTransform::sanitizeCurrencyFieldsForInsert($focus->column_fields, $handlerMeta);
        $entityFields = $referenceEntityFields = false;
        $focus->column_fields->resumeTracking();


        $data = $request->get('field');
        $oldData = $request->get('store',array());
        $rawData = array();
        foreach ($moduleFields as $fieldName => $fieldParams) {
            if (!empty($data[$fieldName])) {
                $entityData->set($fieldName, strtolower($data[$fieldName]));
                $rawData[$fieldName] = $data[$fieldName];
                $focus->column_fields[$fieldName] = $data[$fieldName];
            }
        }

        $wfs = new VTWorkflowManager($adb);
        $workflows = $wfs->getWorkflowsForModule($entityData->getModuleName());

        $isNew = $entityData->isNew();
		$result = array();
        foreach ($workflows as $workflow) {

            if (!is_a($workflow, 'Workflow'))
                continue;
            switch ($workflow->executionCondition) {
                case VTWorkflowManager::$ON_FIRST_SAVE: {
                    if ($isNew) {
                        $doEvaluate = true;
                    } else {
                        $doEvaluate = false;
                    }
                    break;
                }
                case VTWorkflowManager::$ONCE: {
                    $entity_id = vtws_getIdComponents($entityData->getId());
                    $entity_id = $entity_id[1];
                    if ($workflow->isCompletedForRecord($entity_id)) {
                        $doEvaluate = false;
                    } else {
                        $doEvaluate = true;
                    }
                    break;
                }
                case VTWorkflowManager::$ON_EVERY_SAVE: {
                    $doEvaluate = true;
                    break;
                }
                case VTWorkflowManager::$ON_MODIFY: {
                    $doEvaluate = !($isNew);
                    break;
                }
                case VTWorkflowManager::$MANUAL: {
                    $doEvaluate = false;
                    break;
                }
                case VTWorkflowManager::$ON_SCHEDULE: {
                    $doEvaluate = false;
                    break;
                }
                default: {
                    throw new Exception("Should never come here! Execution Condition:" . $workflow->executionCondition);
                }
            }

            if ($doEvaluate && $WebhooksJsonCondition->evaluate($workflow->test, $entityData, $oldData)) {
                require_once('modules/com_vtiger_workflow/VTTaskManager.inc');
                require_once('modules/com_vtiger_workflow/VTTaskQueue.inc');
                $tm = new VTTaskManager($adb);
                $tasks = $tm->getTasksForWorkflow($workflow->id);

                foreach ($tasks as $task) {
                    if ($task->active && $task->executeImmediately == true) {
                        $fieldValueMapping = Zend_Json::decode($task->field_value_mapping);
                        foreach ($fieldValueMapping as $key => $fieldInfo) {
                            $fieldValue = $fieldInfo['value'];
                            if (!empty($fieldInfo['ActiveWebhooksField'])) {
                                if ($fieldInfo['valuetype'] == 'expression') {
                                    $value = $this->getResultUpdateFieldOfExpresstion($moduleName, $fieldInfo['value'], $rawData);
                                    if ($this->is_date($value)) {
                                        $value = DateTimeField::convertToUserFormat($value);
                                        $value = str_replace('--','',$value);
                                    }
                                    $fieldValueMapping[$key]['value'] = $value;
                                } else if ($fieldInfo['valuetype'] == 'fieldname') {
                                    $fieldValue = $fieldInfo['value'];
                                    $fieldInstance = $moduleFields[$fieldName];
                                    $fieldDataType = $fieldInstance->getFieldDataType();
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

                                            // while getting the focus currency fields are converted to user format
                                            $currencyfieldValueInDB = $referenceFocus->column_fields[$referencedFieldName . "_raw"];
                                            $currencyfieldConvertedValue = $referenceFocus->column_fields[$referencedFieldName . "_raw_converted"];

                                            $rightOperandReferenceModuleHandler = vtws_getModuleHandlerFromName($referencedModule, $current_user);
                                            $rightOperandReferenceHandlerMeta = $rightOperandReferenceModuleHandler->getMeta();
                                            $rightOperandReferenceModuleFields = $rightOperandReferenceHandlerMeta->getModuleFields();
                                            $rightOperandFieldInstance = $rightOperandReferenceModuleFields[$referencedFieldName];
                                            if ($rightOperandFieldInstance) {
                                                $rightOperandFieldType = $rightOperandFieldInstance->getFieldDataType();
                                            }
                                        }
                                    } else {
                                        $rightOperandFieldInstance = $moduleFields[$fieldValue];
                                        if ($rightOperandFieldInstance) {
                                            $rightOperandFieldType = $rightOperandFieldInstance->getFieldDataType();
                                        }

                                        // while getting the focus currency fields are converted to user format
                                        $currencyfieldValueInDB = $focus->column_fields[$fieldValue . "_raw"];
                                        $currencyfieldConvertedValue = $focus->column_fields[$fieldValue . "_raw_converted"];

                                        $fieldValue = $focus->column_fields[$fieldValue];
                                    }
                                    $fieldValueInDB = $fieldValue;

                                    // for currency field value should be in database format
                                    if (!empty($currencyfieldValueInDB) && $fieldDataType == "currency" && $fieldInstance->getUIType() != 72) {
                                        $fieldValueInDB = $currencyfieldValueInDB;
                                        if (!empty($currencyfieldConvertedValue)) {
                                            $fieldValue = $currencyfieldConvertedValue;
                                        }
                                    }

                                    if ($fieldDataType == 'date' && !empty($fieldValue)) {
                                        $dbDateValue = getValidDBInsertDateTimeValue($fieldValue);
                                        //Convert the DB Date Time Format to User Date Time Format
                                        $dateTime = new DateTimeField($dbDateValue);
                                        $fieldValue = $dateTime->getDisplayDateTimeValue();

                                        $date = explode(' ', $fieldValue);
                                        $fieldValue = $date[0];
                                    }
                                    //for Product Unit Price value converted with based product currency
                                    if ($fieldDataType == 'currency' && $fieldName == 'unit_price') {
                                        $fieldValue = $this->calculateProductUnitPrice($fieldValue);
                                    }
                                    // for calendar time_start field db value will be in UTC format, we should convert to user format
                                    if (trim($fieldInfo['value']) == 'time_start' && $moduleName == 'Calendar' && $fieldDataType == 'time' && !empty($fieldValue)) {
                                        $date = new DateTime();
                                        $dateTime = new DateTimeField($date->format('Y-m-d') . ' ' . $fieldValue);
                                        $fieldValue = $dateTime->getDisplayTime();
                                    } else if ($fieldDataType == 'time' && !empty($fieldValue)) {
                                        $fieldValueInstance = $moduleFields[trim($fieldInfo['value'])];
                                        if ($fieldValueInstance) {
                                            $fieldValueDataType = $fieldValueInstance->getFieldDataType();
                                        }
                                        //If time field is updating by datetime field then we have to convert to user format
                                        if ($fieldValueDataType == 'datetime') {
                                            $date = new DateTime();
                                            $dateTime = new DateTimeField($fieldValue);
                                            $fieldValue = $dateTime->getDisplayTime();
                                        }
                                    }

                                    if ($rightOperandFieldType == "reference") {
                                        if (!empty($fieldValue)) {
                                            if (!empty($rightOperandFieldInstance)) {
                                                $referenceList = $rightOperandFieldInstance->getReferenceList();
                                                if ((count($referenceList) == 1) && $referenceList[0] == "Users") {
                                                    $userRecordLabels = Vtiger_Functions::getOwnerRecordLabels($fieldValue);
                                                    $fieldValue = $userRecordLabels[$fieldValue];
                                                } elseif ((count($referenceList) == 1) && $referenceList[0] == "Currency") {
                                                    $fieldValue = getCurrencyName($fieldValue);
                                                } elseif ($rightOperandFieldInstance->getFieldName() == "roleid") {
                                                    $fieldValue = getRoleName($fieldValue);
                                                } else {
                                                    $fieldValue = Vtiger_Util_Helper::getRecordName($fieldValue);
                                                }
                                            } else {
                                                $fieldValue = Vtiger_Util_Helper::getRecordName($fieldValue);
                                            }
                                        } else {
                                            //Not value is there for reference fields . So skip this field mapping
                                            continue;
                                        }
                                    }
                                    $fieldValueMapping[$key]['value'] = $fieldValue;
                                } else {
                                    if (preg_match('/([^:]+):boolean$/', $fieldValue, $match)) {
                                        $fieldValue = $match[1];
                                        if ($fieldValue == 'true') {
                                            $fieldValue = '1';
                                        } else {
                                            $fieldValue = '0';
                                        }
                                    }
                                    //for Product Unit Price value converted with based product currency
                                    if ($fieldInstance && $fieldInstance->getFieldDataType() == 'currency' && $fieldName == 'unit_price') {
                                        $fieldValue = $this->calculateProductUnitPrice($fieldValue);
                                    }
                                    $fieldValueMapping[$key]['value'] = $fieldValue;
                                }
                                $result[] = json_encode($fieldValueMapping);
                            }
                        }
                    }
                }

            }
        }
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }

    public function getResultUpdateFieldOfExpresstion($module, $expresstion, $data)
    {
        global $current_user;
        $cacheData=VTEntityCache::getCachedEntity(0);
        $fieldValue = $expresstion;
        $parser = new VTExpressionParser(new VTExpressionSpaceFilter(new VTExpressionTokenizer($fieldValue)));
        $expression = $parser->expression();

        $exprEvaluater = new VTFieldExpressionEvaluater($expression);

        // have issue
        // can not create new VTWorkflowEntity
        // if current_user have date format not is 'yyyd-mm-dd'
        // it's issue of core vtiger
        // now fix : change to 'yyyy-mm-dd', and revert when done
        $temp_dateformat = 'yyyy-mm-dd';
        if ($current_user->date_format != $temp_dateformat){
            foreach ($data as $key=>$item){
                if($this->is_date($item,$current_user->date_format)){
                    $data[$key] = $this->convertToFormat($item,$current_user->date_format,$temp_dateformat);
                }
            }
        }

        $VTWorkflowEntityClass = new VTWorkflowEntity($current_user, 0);
        $VTWorkflowEntityClass->moduleName = $module;
        $VTWorkflowEntityClass->data = $data;
        $evaluateDataReturn = $exprEvaluater->evaluate($VTWorkflowEntityClass);
        return $evaluateDataReturn;
    }

    private function is_date($date,$dateformat = 'yyyy-mm-dd')
    {
        $date = DateTimeField::convertToInternalFormat($date);
        switch ($dateformat){
            case 'dd-mm-yyyy':
                list($d, $m, $y) = explode('-', $date[0]);
                break;
            case 'mm-dd-yyyy':
                list($m, $d, $y) = explode('-', $date[0]);
                break;
            case 'yyyy-mm-dd':
                list($y, $m, $d) = explode('-', $date[0]);
                break;
        }

        if (checkdate($m, $d, $y)) {
            return true;
        } else {
            return false;
        }
    }

    protected function convertToFormat($date, $current_format,$new_format) {
        $date = DateTimeField::convertToInternalFormat($date);
        switch ($current_format){
            case 'dd-mm-yyyy':
                list($d, $m, $y) = explode('-', $date[0]);
                break;
            case 'mm-dd-yyyy':
                list($m, $d, $y) = explode('-', $date[0]);
                break;
            case 'yyyy-mm-dd':
                list($y, $m, $d) = explode('-', $date[0]);
                break;
        }

        switch ($new_format){
            case 'dd-mm-yyyy':
                $dateWithNewFormat = array($d, $m, $y);
                $dateWithNewFormat = implode('-',$dateWithNewFormat);
                break;
            case 'mm-dd-yyyy':
                $dateWithNewFormat = array($m, $d, $y);
                $dateWithNewFormat = implode('-',$dateWithNewFormat);
                break;
            case 'yyyy-mm-dd':
                $dateWithNewFormat = array($y, $m, $d);
                $dateWithNewFormat = implode('-',$dateWithNewFormat);
                break;
        }


        if ($date[1] != '') {
            $userDate = $dateWithNewFormat . ' ' . $date[1];
        } else {
            $userDate = $dateWithNewFormat;
        }
        return $userDate;
    }
}
