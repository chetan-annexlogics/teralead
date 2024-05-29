<?php
/* ********************************************************************************
 * The content of this file is subject to the VTDevQuickEdit ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VtigerDev.com
 * Portions created by VtigerDev.com. are Copyright(C) VtigerDev.com.
 * All Rights Reserved.
 * ****************************************************************************** */

class Contacts_ShowCustomPopup_View extends Vtiger_QuickCreateAjax_View {
    function __construct() {
        parent::__construct();
    }
    
    public function process(Vtiger_Request $request) {
        global  $adb;
        $viewer = $this->getViewer($request);
        $moduleName = $request->get('module');

        $recordId = $request->get('record');
        $color = $request->get('color');
        
        $arrfields = array("cf_919","cf_921");
        if($color == 'Closed') {
            $arrfields = array("cf_929");
        }

        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);

        $fieldList = $moduleModel->getFields();
        $requestFieldList = array_intersect_key($request->getAll(), $fieldList);
        foreach($requestFieldList as $fieldName => $fieldValue){
            $fieldModel = $fieldList[$fieldName];
            if($fieldModel->isEditable() && in_array($fieldName, $arrfields)) {
                $recordModel->set($fieldName, $fieldModel->getDBInsertValue($fieldValue));
            }
        }
        $picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($moduleName);
        $viewer->assign('PICKIST_DEPENDENCY_DATASOURCE',Vtiger_Functions::jsonEncode($picklistDependencyDatasource));
        $viewer->assign('RECORD_ID',$recordId);
        $viewer->assign('RECORD_MODEL',$recordModel);
        $viewer->assign('ALL_FIELDS',$fieldList);
        $viewer->assign('ADD_FIELDS',$arrfields);
        $viewer->assign('MODULE_NAME',$moduleName);
        $viewer->assign('MODULE',$moduleName);
        $viewer->assign('MODULE_MODEL', $moduleModel);
        $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->assign('SCRIPTS', $this->getHeaderScripts($request));
        $viewer->assign('MAX_UPLOAD_LIMIT_MB', Vtiger_Util_Helper::getMaxUploadSize());
        $viewer->assign('MAX_UPLOAD_LIMIT', vglobal('upload_maxsize'));
        echo $viewer->view('ShowCustomPopup.tpl','Contacts',true);
    }

}