<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class CustomDashboards_MiniList_Dashboard extends Vtiger_MiniList_Dashboard
{
    public function process(Vtiger_Request $request, $widget = NULL)
    {
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $currentPage = $request->get("currentPage");
        if (empty($currentPage)) {
            $currentPage = 1;
            $nextPage = 1;
        } else {
            $nextPage = $currentPage + 1;
        }
        if ($widget && !$request->has("widgetid")) {
            $widgetId = $widget->get("id");
        } else {
            $widgetId = $request->get("widgetid");
        }
        $widget = CustomDashboards_Widget_Model::getInstanceWithWidgetId($widgetId, $currentUser->getId());
        $minilistWidgetModel = new CustomDashboards_MiniList_Model();
        $minilistWidgetModel->setWidgetModel($widget);
        $minilistWidgetModel->set("nextPage", $nextPage);
        $minilistWidgetModel->set("currentPage", $currentPage);
        $viewer->assign("WIDGET", $widget);
        $viewer->assign("WIDGET_NAME", $request->get("name"));
        $viewer->assign("MODULE_NAME", $moduleName);
        $viewer->assign("SELECTED_MODULE_NAME", $minilistWidgetModel->getTargetModule());
        $viewer->assign("MINILIST_WIDGET_MODEL", $minilistWidgetModel);
        $viewer->assign("BASE_MODULE", $minilistWidgetModel->getTargetModule());
        $viewer->assign("CURRENT_PAGE", $currentPage);
        $viewer->assign("MORE_EXISTS", $minilistWidgetModel->moreRecordExists());
        $viewer->assign("SCRIPTS", $this->getHeaderScripts());
        $viewer->assign("USER_MODEL", Users_Record_Model::getCurrentUserModel());
        $viewer->assign("RECORD_COUNTS", count($minilistWidgetModel->getRecords()));
        $viewer->assign("ALL_RECORD_COUNTS", $minilistWidgetModel->getRecords("count"));
        $pagingModel = new Vtiger_Paging_Model();
        $pageLimit = $pagingModel->getPageLimit();
        $viewer->assign("PAGE_LIMIT", $pageLimit);
        $content = $request->get("content");
        if (!empty($content)) {
            $viewer->view("dashboards/MiniListContents.tpl", $moduleName);
        } else {
            $widget->set("title", $minilistWidgetModel->getTitle());
            $viewer->view("dashboards/MiniList.tpl", $moduleName);
        }
    }
    public function getListViewCount(Vtiger_Request $request)
    {
        $minilistWidgetModel = new CustomDashboards_MiniList_Model();
        $count = count($minilistWidgetModel->getRecords());
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult($count);
        $response->emit();
    }
}

?>