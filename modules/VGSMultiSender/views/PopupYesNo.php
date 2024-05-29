<?php

class VGSMultiSender_PopupYesNo_View extends Vtiger_Index_View
{
    /**
     * @param Vtiger_Request $request
     * @throws Exception
     */
    public function process(Vtiger_Request $request) {
        $qualifiedModuleName = $request->getModule(false);
        $viewer = $this->getViewer($request);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->assign('TITLE', vtranslate('LBL_deletion', $qualifiedModuleName));
        $viewer->assign('QUESTION', vtranslate('LBL_are_you_sure', $qualifiedModuleName));
        $viewer->assign('RECORD_ID', $request->get('record_id'));
        $viewer->assign('CASE1', vtranslate('LBL_yes', $qualifiedModuleName));
        $viewer->assign('CASE2', vtranslate('LBL_no', $qualifiedModuleName));
        $viewer->view('PopupYesNo.tpl', $qualifiedModuleName);
    }
}
