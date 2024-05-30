<?php



ini_set('display_errors', '0');
class VTDevKBView_Settings_View extends Settings_Vtiger_Index_View
{
    
    public function preProcess(Vtiger_Request $request)
    {
        parent::preProcess($request);
        $adb = PearDatabase::getInstance();
        $module = $request->getModule();
        $viewer = $this->getViewer($request);
        $viewer->assign('QUALIFIED_MODULE', $module);
        $rs = $adb->pquery("SELECT * FROM `vtdev_modules` WHERE module=? AND valid='1';", array($module));
        if ($adb->num_rows($rs) == 0) {
            $viewer->view('InstallerHeader.tpl', $module);
        }
    }
    public function process(Vtiger_Request $request)
    {
        $vtdevLicense = VTDevKBView_VTDEVLicense_Model::validate();
        if (!$vtdevLicense['valid']) $this->showVtdevStoreRequireScreen($request, $vtdevLicense);
        else {
            $mode = $request->getMode();
            if ($mode) {
                $this->$mode($request);
            } else {
                $this->renderSettingsUI($request);
            }
        }
    }
    function showVtdevStoreRequireScreen(Vtiger_Request $request, $vtdevLicense)
    {
        global $site_URL;
        $module = $request->getModule();
        $viewer = $this->getViewer($request);
        $viewer->assign('VTDEVLICENSE', $vtdevLicense);
        $viewer->assign('SITE_URL', $site_URL);
        $viewer->assign('QUALIFIED_MODULE', $module);
        $viewer->view('VtdevStoreRequire.tpl', $module);
    }
    function renderSettingsUI(Vtiger_Request $request)
    {
        $adb = PearDatabase::getInstance();
        $module = $request->getModule();
        $viewer = $this->getViewer($request);
        $rs = $adb->pquery("SELECT `enable` FROM `vtdevkb_view_settings`;", array());
        $enable = $adb->query_result($rs, 0, 'enable');
        $viewer->assign('ENABLE', $enable);
        echo $viewer->view('Settings.tpl', $module, true);
    }
    function getHeaderScripts(Vtiger_Request $request)
    {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();
        $jsFileNames = array("modules.$moduleName.resources.Settings",);
        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
}