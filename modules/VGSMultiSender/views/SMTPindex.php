<?php

class VGSMultiSender_SMTPindex_View extends Vtiger_Index_View {

    public function checkPermission(Vtiger_Request $request) {

            return true;
       
    }


    public function process(Vtiger_Request $request) {
        $smtpConfig = $this->getSMTPConfigs();

        $viewer = $this->getViewer($request);
        $viewer->assign('RMU_FIELDS_ARRAY', $smtpConfig);
        $viewer->assign('IS_VALIDATED', true);
        $viewer->view("SMTPConfig.tpl","VGSMultiSender");
    }

    public function getSMTPConfigs(){
        global $current_user;
        $db = PearDatabase::getInstance();
        $smtpConfigs = Array();
        $result = $db->pquery("SELECT * FROM vtiger_vgsmultisender WHERE userid = ?", array($current_user->id));
        $i = 0;
        while ($row = $db->fetchByAssoc($result)) {
            $smtpConfigs[$i]['id'] = $row['id'];
            $smtpConfigs[$i]['server_name'] = $row['server_name'];
            $smtpConfigs[$i]['user_name'] = $row['user_name'];
            $smtpConfigs[$i]['password'] = substr(str_repeat("*", strlen($row['password'])),0,20); 
            $smtpConfigs[$i]['email_from'] = $row['email_from'];
            $smtpConfigs[$i]['from_name'] = $row['from_name'];
            $smtpConfigs[$i]['smtp_auth'] = $row['smtp_auth'];
            $smtpConfigs[$i]['locked'] = $row['locked'];
            $i++;
        }
        return $smtpConfigs;
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

