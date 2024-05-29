<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class CustomDashboards_MassDelete_Action extends Vtiger_Mass_Action
{
    public function checkPermission(Vtiger_Request $request)
    {
        $moduleName = $request->getModule();
        $moduleModel = CustomDashboards_Module_Model::getInstance($moduleName);
        $currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        if (!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
            throw new AppException(vtranslate("LBL_PERMISSION_DENIED"));
        }
    }
    public function preProcess(Vtiger_Request $request)
    {
        return true;
    }
    public function postProcess(Vtiger_Request $request)
    {
        return true;
    }
    public function process(Vtiger_Request $request)
    {
        global $current_user;
        $parentModule = "CustomDashboards";
        $recordIds = CustomDashboards_Record_Model::getRecordsListFromRequest($request);
        $reportsDeleteDenied = array();
        foreach ($recordIds as $recordId) {
            $recordModel = CustomDashboards_Record_Model::getInstanceById($recordId);
            if (!$recordModel->isDefault() && $recordModel->isEditable() && $recordModel->isEditableBySharing() || $current_user->is_admin) {
                $success = $recordModel->delete();
                if (!$success) {
                    $reportsDeleteDenied[] = vtranslate($recordModel->getName(), $parentModule);
                }
            } else {
                $reportsDeleteDenied[] = vtranslate($recordModel->getName(), $parentModule);
            }
        }
        $response = new Vtiger_Response();
        if (empty($reportsDeleteDenied)) {
            $response->setResult(array(vtranslate("LBL_REPORTS_DELETED_SUCCESSFULLY", $parentModule)));
        } else {
            $response->setError($reportsDeleteDenied, vtranslate("LBL_DENIED_REPORTS", $parentModule));
        }
        $response->emit();
    }
}

?>