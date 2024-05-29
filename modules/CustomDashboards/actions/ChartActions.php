<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class CustomDashboards_ChartActions_Action extends Vtiger_Action_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->exposeMethod("pinToDashboard");
        $this->exposeMethod("unpinFromDashboard");
        $this->exposeMethod("savePosition");
    }
    public function checkPermission(Vtiger_Request $request)
    {
        $moduleName = $request->getModule();
        $moduleModel = CustomDashboards_Module_Model::getInstance($moduleName);
        $currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        if (!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
            throw new AppException(vtranslate("LBL_PERMISSION_DENIED"));
        }
    }
    public function process(Vtiger_Request $request)
    {
        $mode = $request->get("mode");
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
        }
    }
    /**
     * Function to add the report chart to dashboard
     * @param Vtiger_Request $request
     */
    public function pinToDashboard(Vtiger_Request $request)
    {
        $db = PearDatabase::getInstance();
        $reportid = $request->get("reportid");
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $currentuserid = $currentUser->getId();
        $widgetTitle = $request->get("title");
        $width = $request->get("width");
        $height = $request->get("height");
        if (!$widgetTitle) {
            $widgetTitle = "ChartReportWidget_" . $reportid;
        }
        $linkid = $request->get("linkid");
        $response = new Vtiger_Response();
        $dashBoardTabId = $request->get("dashBoardTabId");
        if (empty($dashBoardTabId)) {
            $dasbBoardModel = CustomDashboards_DashBoard_Model::getInstance("CustomDashboards");
            $defaultTab = $dasbBoardModel->getUserDefaultTab($currentUser->getId());
            $dashBoardTabId = $defaultTab["id"];
        }
        $query = "SELECT 1 FROM vtiger_module_customdashboarddashboard_widgets WHERE reportid = ? AND userid = ? AND dashboardtabid = ?";
        $param = array($reportid, $currentuserid, $dashBoardTabId);
        $result = $db->pquery($query, $param);
        $numOfRows = $db->num_rows($result);
        if (1 <= $numOfRows) {
            $result = array("pinned" => false, "duplicate" => true);
            $response->setResult($result);
            $response->emit();
        } else {
            if (!$linkid) {
                $linkid = CustomDashboards_Record_Model::getLinkId($reportid);
            }
            $query = "INSERT INTO vtiger_module_customdashboarddashboard_widgets (userid,reportid,linkid,title,dashboardtabid,sizeWidth,sizeHeight) VALUES (?,?,?,?,?,?,?)";
            $param = array($currentuserid, $reportid, $linkid, $widgetTitle, $dashBoardTabId, $width, $height);
            $result = $db->pquery($query, $param);
            $widgetRecordModel = CustomDashboards_Widget_Model::getInstanceWithReportId($reportid, $dashBoardTabId);
            $dataUrl["url"] = $widgetRecordModel->getUrl();
            $dataUrl["urlDetail"] = $widgetRecordModel->getUrlReportDetail();
            $dataUrl["urlEdit"] = $widgetRecordModel->getUrlReportEdit();
            $dataUrl["urlDelete"] = $widgetRecordModel->getDeleteUrl();
            $dataUrl["widgetId"] = $widgetRecordModel->get("id");
            $result = array("pinned" => true, "duplicate" => false, "dataUrl" => $dataUrl);
            $response->setResult($result);
            $response->emit();
        }
    }
    public function unpinFromDashboard($request)
    {
        $db = PearDatabase::getInstance();
        $reportid = $request->get("reportid");
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $dashBoardTabId = $request->get("dashBoardTabId");
        if (empty($dashBoardTabId)) {
            $dasbBoardModel = CustomDashboards_DashBoard_Model::getInstance("CustomDashboards");
            $defaultTab = $dasbBoardModel->getUserDefaultTab($currentUser->getId());
            $dashBoardTabId = $defaultTab["id"];
        }
        $widgetInstance = CustomDashboards_Widget_Model::getInstanceWithReportId($reportid, $dashBoardTabId);
        $widgetInstance->remove();
        $response = new Vtiger_Response();
        $response->setResult(array("unpinned" => true));
        $response->emit();
    }
    public function savePosition($request)
    {
        $db = PearDatabase::getInstance();
        $reportid = $request->get("record");
        $position = "{\"x\":\"" . $request->get("x") . "\",\"y\":\"" . $request->get("y") . "\",\"width\":\"" . $request->get("width") . "\",\"height\":\"" . $request->get("height") . "\"}";
        $db->pquery("UPDATE vtiger_customdashboard set position = ? where reportid = ?", array($position, $reportid));
        $response = new Vtiger_Response();
        $response->setResult(array("success" => true));
        $response->emit();
    }
}

?>