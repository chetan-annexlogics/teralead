<?php

class VTEWEBHOOKS_Record_Model extends Vtiger_Record_Model{
    public function getWorkflowtasksByModuleName($module){
        global $adb;
        $sql = "SELECT `com_vtiger_workflows`.`test`,`com_vtiger_workflowtasks`.`task_id`
                FROM `com_vtiger_workflows`
                INNER JOIN `com_vtiger_workflowtasks`
                ON `com_vtiger_workflows`.`workflow_id` = `com_vtiger_workflowtasks`.`workflow_id`
                WHERE `com_vtiger_workflows`.`module_name` = ?
                AND `com_vtiger_workflows`.`status` = 1
                AND `com_vtiger_workflowtasks`.`task` LIKE '%VTEWEBHOOKSTask%'";

        $query = $adb->pquery($sql,array($module));
        $countResult = $adb->num_rows($query);
        $webhookWF = array();

        if ($countResult > 0){
            for($i=0; $i<$countResult; $i++) {
                $dataResult = $adb->query_result_rowdata($query, $i);

                $testRow = $dataResult['test'];
                $testRow = json_decode(html_entity_decode($testRow),true);


                $taskId = $dataResult['task_id'];
                $taskModel = Settings_Workflows_TaskRecord_Model::getInstance($taskId);
                $taskObject = $taskModel->getTaskObject();


                if ($taskObject->active){
                    $taskRow = Zend_Json::decode($taskObject->field_value_mapping);
                    $actions = false;
                    foreach ($taskRow as $key => $item){
                        if ($item['ActiveRealTimeField'] == true && !empty($testRow)){
                            unset($item['ActiveRealTimeField']);
                            $actions[] = $item;
                        }
                    }

                    if ($actions && !empty($testRow)){
                        $condition = false;
                        foreach ($testRow as $key => $item){
                            if ($item['groupjoin'] == 'and'){
                                $condition['and'][] = $item;
                            }else{
                                $condition['any'][] = $item;
                            }
                        }
                        $webhookWF[] = array('condition'=>$condition,'actions'=>$actions);
                    }
                }
            }
        }
        return $webhookWF;
    }
}
