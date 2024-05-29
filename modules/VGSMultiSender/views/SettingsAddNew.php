<?php
require_once 'modules/VGSMultiSender/models/SMTPModule.php';

class VGSMultiSender_SettingsAddNew_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {

        $viewer = $this->getViewer($request);
        $id = $request->get('id');
        if ($id) {
            $record = SMTPModule::getRecordData($id);
            $record['password'] =
                substr(str_repeat("*", strlen($record['password'])),0,20);
            $viewer->assign('RECORD', $record);
        }
        $viewer->assign('PARENT_MODULE', 'Settings');
        $viewer->assign('USER_LIST', $this->getUserList());
        $viewer->view('SMTPAddNew.tpl',$request->get('module'));
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

    function getUserList(){
        return getAllUserName();
    }
}
