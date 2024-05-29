<?php
//headerNop

class CustomDashboards_DashBoard_View extends Vtiger_Index_View
{
    protected static $selectable_dashboards = NULL;
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
    public function checkPermission(Vtiger_Request $request)
    {
        $moduleName = $request->getModule();
		 $tabid = getTabid($moduleName);
        $userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        if (!$userPrivilegesModel->hasModulePermission($tabid)) {
            throw new AppException(vtranslate("LBL_PERMISSION_DENIED"));
        }
    }
    public function preProcess(Vtiger_Request $request, $display = true)
    {
        global $site_URL;
        parent::preProcess($request, false);
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $dashBoardModel = CustomDashboards_DashBoard_Model::getInstance($moduleName);
        $moduleModel = Vtiger_Module_Model::getInstance("CustomDashboards");
        $userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
        if ($permission) {
            $boardid = false;
            if (!$dashBoardModel->checkTabExist(0, 0, "check")) {
                $dashBoardModel->addTabDefault();
            }
            if (!$request->get("boardid")) {
                $data = $dashBoardModel->loadDefaultBoard();
                $boardid = $data["boardid"];
                $tabid = $data["tabid"];
                if ($boardid && $tabid) {
                    header("Location: " . $site_URL . "/index.php?module=CustomDashboards&view=DashBoard&boardid=" . $boardid . "&tabid=" . $tabid);
                }
            }
            if ($request->get("boardid")) {
                $boardid = $request->get("boardid");
            }
            $dashboardTabs = $dashBoardModel->getActiveTabs($boardid);
            $dashboardBoards = $dashBoardModel->getAllBoards($mode = "getAll");
            if ($request->get("tabid")) {
                $tabid = $request->get("tabid");
            } else {
                $tabid = $dashboardTabs[0]["id"];
            }
            $orgDynamicFilter = $dashBoardModel->getAccountDynamicFilter($tabid);
            if (!isset($_REQUEST["organization"]) && $orgDynamicFilter != "") {
                if (!isset($_REQUEST["module"])) {
                    $url = $site_URL . "index.php?module=CustomDashboards&view=DashBoard&organization=" . $orgDynamicFilter;
                } else {
                    $protocol = isset($_SERVER["HTTPS"]) ? "https" : "http";
                    $url = $protocol . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "&organization=" . $orgDynamicFilter;
                }
                header("Location: " . $url);
            }
            $dashBoardModel->set("tabid", $tabid);
        }
        $viewer->assign("MODULE_PERMISSION", $permission);
        $viewer->assign("MODULE_NAME", $moduleName);
        $viewer->assign("DASHBOARD_BOARDS", $dashboardBoards);
        $viewer->assign("BOARDID", $boardid);
        if($request->get('source_module')){
            $viewer->assign("SOURCEMODULE", $request->get('source_module'));
            $viewer->assign("VIEWNAME", $request->get('source_module'));
        }
        if ($display) {
            $this->preProcessDisplay($request);
        }
    }
    public function preProcessTplName(Vtiger_Request $request)
    {
        return "dashboards/DashBoardPreProcess.tpl";
    }
    public function process(Vtiger_Request $request)
    {
        global $current_user;
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $dashBoardModel = CustomDashboards_DashBoard_Model::getInstance($moduleName);
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
        if ($permission) {
            $boardid = 1;
            if ($request->get("boardid") && $dashBoardModel->getAllBoards($mode = "getExist", $request->get("boardid"))) {
                $boardid = $request->get("boardid");
                $viewer->assign("IS_SHARED", $dashBoardModel->checkBoardIsShared($boardid));
            }
            $dashboardTabs = $dashBoardModel->getActiveTabs($boardid);
            if ($request->get("tabid")) {
                $tabid = $request->get("tabid");
            } else {
                $tabid = $dashboardTabs[0]["id"];
            }
            $dashBoardModel->set("tabid", $tabid);
            $widgets = $dashBoardModel->getDashboards($moduleName);
            $notificationDynamic = $dashBoardModel->checkNotificationDynamic($tabid);
            $viewer->assign("MODULE_NAME", $moduleName);
            $viewer->assign("WIDGETS", $widgets);
            $viewer->assign("NOTIFICATION_DYNAMIC", $notificationDynamic);
            $viewer->assign("DASHBOARD_TABS", $dashboardTabs);
            $viewer->assign("DASHBOARD_TABS_LIMIT", $dashBoardModel->dashboardTabLimit);
            $viewer->assign("SELECTED_TAB", $tabid);
            $viewer->assign("SELECTED_BOARD", $boardid);
            $viewer->assign("CURRENT_USER", Users_Record_Model::getCurrentUserModel());
            $viewer->assign("TABID", $tabid);
            if($request->get('source_module')){
                $viewer->assign("SOURCEMODULE", $request->get('source_module'));
            }
            $viewer->view("dashboards/DashBoardContents.tpl", $moduleName);
        } else {
            return NULL;
        }
    }
    public function postProcess(Vtiger_Request $request)
    {
        parent::postProcess($request);
    }
    /**
     * Function to get the list of Script models to be included
     * @param Vtiger_Request $request
     * @return <Array> - List of Vtiger_JsScript_Model instances
     */
    public function getHeaderScripts(Vtiger_Request $request)
    {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();
        $jsFileNames = array("~/layouts/v7/modules/CustomDashboards/resources/chartjs/Chart.bundle.min.js", "~/layouts/v7/modules/CustomDashboards/resources/chartjs/Chart.BarFunnel.min.js", "~/layouts/v7/modules/CustomDashboards/resources/chartjs/Chart.Funnel.bundle.min.js", "~/layouts/v7/modules/CustomDashboards/resources/chartjs/utils.js", "~/layouts/v7/modules/CustomDashboards/resources/chartjs/chartjs-piecelabel.js", "~/layouts/v7/modules/CustomDashboards/resources/gridstack/lodash.min.js", "~/layouts/v7/modules/CustomDashboards/resources/gridstack/gridstack.min.js", "~/layouts/v7/modules/CustomDashboards/resources/gridstack/gridstack.jQueryUI.min.js", "~/layouts/v7/modules/CustomDashboards/resources/jbPivot/jbPivot.min.js", "~/layouts/v7/modules/CustomDashboards/resources/CustomDashboardsDashBoard.js", "~/layouts/v7/modules/CustomDashboards/resources/CustomDashboardsButtonDashBoard.js", "~/layouts/v7/modules/CustomDashboards/resources/perfect-scrollbar/js/perfect-scrollbar.jquery.js", "~/libraries/jquery/colorpicker/js/colorpicker.js");
        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
    /**
     * Function to get the list of Css models to be included
     * @param Vtiger_Request $request
     * @return <Array> - List of Vtiger_CssScript_Model instances
     */
    public function getHeaderCss(Vtiger_Request $request)
    {
        $parentHeaderCssScriptInstances = parent::getHeaderCss($request);
        $headerCss = array("~/layouts/v7/modules/CustomDashboards/resources/gridstack/gridstack.min.css", "~/layouts/v7/modules/CustomDashboards/resources/gridstack/gridstack-extra.min.css", "~/layouts/v7/modules/CustomDashboards/resources/jbPivot/jbPivot.css", "~/layouts/v7/modules/CustomDashboards/resources/StyleDashboard.css", "~libraries/jquery/colorpicker/css/colorpicker.css", "~/layouts/v7/modules/CustomDashboards/resources/perfect-scrollbar/css/perfect-scrollbar.css");
        $cssScripts = $this->checkAndConvertCssStyles($headerCss);
        $headerCssScriptInstances = array_merge($parentHeaderCssScriptInstances, $cssScripts);
        return $headerCssScriptInstances;
    }
}

?>