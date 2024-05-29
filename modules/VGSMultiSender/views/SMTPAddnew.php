<?php

class VGSMultiSender_SMTPAddnew_View extends Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        $currentUserModel = Users_Record_Model::getCurrentUserModel();

        $viewer = $this->getViewer($request);
        $viewer->assign('CURRENT_USER_ID', $currentUserModel->get('id'));
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
}
