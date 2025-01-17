<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class CustomDashboards_CheckDuplicate_Action extends Vtiger_Action_Controller
{
    public function checkPermission(Vtiger_Request $request)
    {
    }
    public function process(Vtiger_Request $request)
    {
        $moduleName = $request->getModule();
        $reportName = $request->get("reportname");
        $record = $request->get("record");
        if ($record) {
            $recordModel = Vtiger_Record_Model::getInstanceById($record, $moduleName);
        } else {
            $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
        }
        $recordModel->set("reportname", $reportName);
        $recordModel->set("reportid", $record);
        $recordModel->set("isDuplicate", $request->get("isDuplicate"));
        if (!$recordModel->checkDuplicate()) {
            $result = array("success" => false);
        } else {
            $result = array("success" => true, "message" => vtranslate("LBL_DUPLICATES_EXIST", $moduleName));
        }
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
}

?>