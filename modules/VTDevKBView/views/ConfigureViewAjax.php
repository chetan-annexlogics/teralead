<?php

class VTDevKBView_ConfigureViewAjax_View extends Vtiger_Index_View
{
    function checkPermission(Vtiger_Request $request)
    {
        return true;
    }
    function __construct()
    {
        parent::__construct();
        $this->vtdevLicense();
    }
    function vtdevLicense()
    {
        $vTELicense = VTDevKBView_VTDEVLicense_Model::validate();
        if (!$vTELicense['valid']) {
            echo "Invalid license";
            die();
        }
    }
    function process(Vtiger_Request $request)
    {
        $kanbanViewModel = new VTDevKBView_Module_Model();
        $sourceModule = $request->get('source_module');
        $primaryFields = $kanbanViewModel->getPrimaryFields($sourceModule);
        $primaryFieldSetting = $kanbanViewModel->getVTDevKBviewSetting($sourceModule);
        if (empty($primaryFieldSetting)) {
            $primaryFieldSetting['primary_field'] = $primaryFields[0]['fieldid'];
            $primaryFieldSetting['primary_value_setting'] = array();
            $primaryFieldSetting['other_field'] = array();
            $primaryFieldSetting['value'] = array();
            $primaryFieldSetting['is_default_page'] = 0;
        }
        $recordModel = Vtiger_Record_Model::getCleanInstance($sourceModule);
        $recordStructureModel = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel);
        foreach ($recordStructureModel->getStructure() as $block) {
            foreach ($block as $field) {
                if ($field->getId() == $primaryFieldSetting['primary_field']) {
                    $picklistValues = $field->getPicklistValues();
                    if ($primaryFieldSetting['primary_value_setting']) {
                        $tmp = array();
                        foreach ($primaryFieldSetting['primary_value_setting'] as $primary_value_setting) {
                            $tmp[$primary_value_setting] = $picklistValues[$primary_value_setting];
                        }
                        foreach ($picklistValues as $key => $value) {
                            if (!array_key_exists($value, $primaryFieldSetting['primary_value_setting'])) {
                                $tmp = array_merge($tmp, array($key => $value));
                            }
                        }
                        $primaryFieldSetting['value'] = $tmp;
                    } else {
                        $primaryFieldSetting['value'] = $picklistValues;
                    }
                    break;
                }
            }
        }
        $viewer = $this->getViewer($request);
        $viewer->assign('PRIMARY_FIELDS', $primaryFields);
        $viewer->assign('PRIMARY_SETTING', $primaryFieldSetting);
        $viewer->assign('RECORD_STRUCTURE', $recordStructureModel->getStructure());
        $viewer->assign('MODULE', 'VTDevKBView');
        $viewer->assign('SOURCE_MODULE', $sourceModule);
        echo $viewer->view('ConfigureViewAjax.tpl', 'VTDevKBView', true);
    }
}