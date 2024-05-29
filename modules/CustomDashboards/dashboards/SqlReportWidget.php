<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class CustomDashboards_SqlReportWidget_Dashboard extends CustomDashboards_TabularReportWidget_Dashboard
{
    const REPORT_LIMIT = 500;
    public function process(Vtiger_Request $request)
    {
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $recordId = $request->get("reportid");
        $tabId = $request->get("tabid");
        $detailViewModel = CustomDashboards_DetailView_Model::getInstance($moduleName, $recordId);
        $reportModel = $detailViewModel->getRecord();
        $viewer->assign("REPORT_NAME", $reportModel->getName());
        $page = $request->get("page");
        $reportModel->setModule("CustomDashboards");
        $pagingModel = new Vtiger_Paging_Model();
        $pagingModel->set("page", $page);
        $pagingModel->set("limit", self::REPORT_LIMIT);
        $reportData = $reportModel->getReportData($pagingModel);
        $data = $reportData["data"];
        $count = $reportData["count"];
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $widget = CustomDashboards_Widget_Model::getInstanceWithReportId($recordId, $tabId);
        $widget->set("title", $reportModel->getName());
        $viewer->assign("WIDGET", $widget);
        $viewer->assign("REPORT_TYPE", "detail");
        $viewer->assign("DATA", $data);
        $viewer->assign("RECORD_ID", $recordId);
        $viewer->assign("PAGING_MODEL", $pagingModel);
        $viewer->assign("REPORT_MODEL", $reportModel);
        $viewer->assign("COUNT", $count);
        $viewer->assign("MODULE", $moduleName);
        $viewer->assign("REPORT_RUN_INSTANCE", CustomDashboardRun::getInstance($recordId));
        if (self::REPORT_LIMIT < count($data)) {
            $viewer->assign("LIMIT_EXCEEDED", true);
        }
        $content = $request->get("content");
        if (!empty($content)) {
            $viewer->view("dashboards/DashBoardWidgetContents.tpl", $moduleName);
        } else {
            $viewer->view("dashboards/DashBoardWidget.tpl", $moduleName);
        }
    }
}

?>