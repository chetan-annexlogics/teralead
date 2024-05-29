<?php



include_once 'modules/VGSMultiSender/models/VGSLicenseManager.php';

class VGSMultiSender_SettingsIndex_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        global $site_URL;

        if (!aW8bgzsTs3Xp($request->getModule())) {
            
            header('Location: index.php?module=' . $request->getModule() . '&view=VGSLicenseSettings&parent=Settings');
        }else{
            $viewer = $this->getViewer($request);
            $viewer->assign('PARENT_MODULE', 'Settings');
            $viewer->assign('RMU_FIELDS_ARRAY', $this->getSMTPSettings());
            $viewer->view('SettingsIndex.tpl', $request->getModule());
        }

        
    }
    
    function getSMTPSettings(){
        $db = PearDatabase::getInstance();
        $smtpSettings = array();
        $sql = "SELECT vtiger_vgsmultisender.*, vtiger_users.user_name, vtiger_vgsmultisender.user_name as smtpuser FROM vtiger_vgsmultisender 
            INNER JOIN vtiger_users ON vtiger_vgsmultisender.userid = vtiger_users.id 
            WHERE status='Active'";
        $result = $db->pquery($sql);
        if($result && $db->num_rows($result) > 0){
            while ($row = $db->fetchByAssoc($result)) {
                $row['password'] = substr(str_repeat("*", strlen($row['password'])),0,20); 
                array_push($smtpSettings, $row);
            }
        }

        return $smtpSettings;
    }
    
    public function getHeaderScripts(Vtiger_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);

        $jsFileNames = array(
            "modules.VGSMultiSender.resources.VGSMultiSenderSettings",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }

}
