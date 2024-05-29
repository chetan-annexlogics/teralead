<?php
class VTEWEBHOOKS_ActiveLicense_View extends Settings_Vtiger_Index_View {
    function __construct() {
        parent::__construct();
    }

    public function preProcess(Vtiger_Request $request) {
        parent::preProcess($request);
        // Check module valid
        $adb = PearDatabase::getInstance();
        $module = $request->getModule();
        $viewer = $this->getViewer($request);
        $viewer->assign('QUALIFIED_MODULE', $module);
        $rs=$adb->pquery("SELECT * FROM `vte_modules` WHERE module=? AND valid='1';",array($module));
        if($adb->num_rows($rs)==0) {
            $viewer->view('InstallerHeader.tpl', $module);
        }
    }

    public function process(Vtiger_Request $request) {
        $module = $request->getModule();
        $adb = PearDatabase::getInstance();
        $vTELicense=new VTEWEBHOOKS_VTELicense_Model($module);
        if(!$vTELicense->validate()){
            $this->step2($request, $vTELicense);
        }else {
            $rs=$adb->pquery("SELECT * FROM `vte_modules` WHERE module=? AND valid='1';",array($module));
            if($adb->num_rows($rs)==0) {
                $this->step3($request);
            }else{
                $mode = $request->getMode();
                if ($mode) {
                    $this->$mode($request);
                } else {
                    header("location:index.php?module=ModuleManager&parent=Settings&view=List");
                }
            }
        }
    }

    function step2(Vtiger_Request $request, $vTELicense) {
        global $site_URL;
        $module = $request->getModule();
        $viewer = $this->getViewer($request);

        $viewer->assign('VTELICENSE', $vTELicense);
        $viewer->assign('SITE_URL', $site_URL);
        $viewer->view('Step2.tpl', $module);
    }

    function step3(Vtiger_Request $request) {
        $module = $request->getModule();
        $viewer = $this->getViewer($request);
        $viewer->view('Step3.tpl', $module);
    }

    /**
     * Function to get the list of Script models to be included
     * @param Vtiger_Request $request
     * @return <Array> - List of Vtiger_JsScript_Model instances
     */
    function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
            "modules.$moduleName.resources.Settings",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
}