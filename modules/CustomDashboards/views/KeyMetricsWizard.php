<?php
//headerNop

class CustomDashboards_KeyMetricsWizard_View extends Vtiger_MiniListWizard_View
{
    public function __construct()
    {
        parent::__construct();
        //wasvlic1
    }
    public function smpLicense()
    {
        //was9
        //was5
    }
    public function process(Vtiger_Request $request)
    {
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $widgetName = $request->get("widgetName");
        $viewer = $this->getViewer($request);
        $modules = Vtiger_Module_Model::getSearchableModules();
        $moduleName = $request->get("module");
        $widget = CustomDashboards_Widget_Model::getInstanceWithWidgetId($request->get("record"), $currentUser->getId());
        $allarrayKey = array();
        $widget_1 = new CustomDashboards_KeyMetrics_Model();
        $allkeymetrics = $widget_1->getKeyMetricsWithCount("", $mode = "add");
        foreach ($allkeymetrics as $allkey => $allvalue) {
            $allarrayKey[$allvalue["module"]][$allvalue["id"]] = $allvalue["name"];
        }
        $viewer->assign("ALL_KEY_METRICS_LIST", $allarrayKey);
        $viewer->assign("MODULES", $modules);
        $viewer->assign("RECORD_ID", $widget->get("id"));
        $viewer->assign("WIDGET", $widget);
        $viewer->assign("WIDGET_NAME", $widgetName);
        $viewer->assign("WIDGET_MODE", "Settings");
        $viewer->assign("WIDGET_FORM", "Create");
        $viewer->view("dashboards/MiniListWizard.tpl", $moduleName);
    }
}

?>