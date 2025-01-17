<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class CustomDashboards_KeyMetrics_Dashboard extends Vtiger_KeyMetrics_Dashboard
{
    public function process(Vtiger_Request $request, $oldWidget = NULL)
    {
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $linkId = $request->get("linkid");
        if ($request->get("fields")) {
            $fields = "{\"fields\":[\"" . implode("\",\"", explode(",", urldecode($request->get("fields")))) . "\"]}";
        }
        $widgetid = $request->get("widgetid");
        if (!$linkId) {
            $linkId = CustomDashboards_Widget_Model::getLinkId($moduleName, "Key Metrics");
        }
        if ($widgetid != "") {
            $widget = CustomDashboards_Widget_Model::getInstanceKeyMetrics($linkId, $widgetid);
        } else {
            $widget = new CustomDashboards_Widget_Model();
            $widget->set("linkid", $linkId);
            $widget->set("pick_color", $request->get("color"));
            $widget->set("refresh_time", $request->get("time"));
            $widget->set("showemptyval", $request->get("showemptyval"));
            $widget->set("userid", $currentUser->getId());
            $widget->set("tabid", $request->get("tabid"));
            $widget->set("filterid", $request->get("filterid", NULL));
        }
        if (!$widget->get("id")) {
            $widget = $oldWidget;
            $widget->set("pick_color", $widget->get("color"));
        }
        $keyMetricsModel = new CustomDashboards_KeyMetrics_Model();
        if ($fields) {
            $keyMetrics = $keyMetricsModel->getKeyMetricsWithCount($fields);
        } else {
            $keyMetrics = $keyMetricsModel->getKeyMetricsWithCount($widget);
        }
        if ($widget->get("userid") !== $currentUser->getId()) {
            $widget->set("shared", true);
        }
        $viewer->assign("WIDGET", $widget);
        $viewer->assign("TITLE", "Key Metrics");
        $viewer->assign("MODULE_NAME", $moduleName);
        $viewer->assign("KEYMETRICS", $keyMetrics);
        $content = $request->get("content");
        if (!empty($content)) {
            $viewer->view("dashboards/KeyMetricsContents.tpl", $moduleName);
        } else {
            $viewer->view("dashboards/KeyMetrics.tpl", $moduleName);
        }
    }
}

?>