<?php
//headerNop

class CustomDashboards_GaugeWizard_View extends Vtiger_MiniListWizard_View
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
        $widgetName = $request->get("widgetName");
        $viewer = $this->getViewer($request);
        $moduleName = $request->get("module");
        $gaugeModel = new CustomDashboards_Gauge_Model();
        $viewer->assign("GAUGE_WIZARD_STEP", $request->get("step"));
        $viewer->assign("WIDGET_NAME", $widgetName);
        $viewer->assign("WIDGET_MODE", "Settings");
        $viewer->assign("WIDGET_FORM", "Create");
        $viewer->assign("MODULE", $moduleName);
        switch ($request->get("step")) {
            case "step1":
                $detailReports = $gaugeModel->getListDetailReport();
                $viewer->assign("ALL_DETAIL_REPORTS", $detailReports);
                break;
            case "step2":
                $reportId = $request->get("selectedReport");
                $viewer->assign("CALCULATION_FIELDS", $gaugeModel->getColumnsDetailReport($reportId));
                $viewer->assign("WIDGET_MODE", "Create");
                break;
        }
        $viewer->view("dashboards/MiniListWizard.tpl", $moduleName);
    }
}

?>