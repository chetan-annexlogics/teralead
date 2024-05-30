<?php

class VTDevKBView_SaveAjax_Action extends Vtiger_BasicAjax_Action
{
    function __construct()
    {
        parent::__construct();
        $this->exposeMethod('saveVTDevKBViewSetting');
        $this->exposeMethod('removeFieldForVTDevKBViewSetting');
    }
    function process(Vtiger_Request $request)
    {
        $mode = $request->get('mode');
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
            return;
        }
    }
    public function removeFieldForVTDevKBViewSetting(Vtiger_Request $request)
    {
        $kabanviewModel = new VTDevKBView_Module_Model();
        $value = $kabanviewModel->removeFieldForVTDevKBViewSetting($request);
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult($value);
        $response->emit();
    }
    public function saveVTDevKBViewSetting(Vtiger_Request $request)
    {
        global $adb;
        $kabanviewModel = new VTDevKBView_Module_Model();
        $value = $kabanviewModel->updateVTDevKBViewSetting($request);
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult($value);
        $response->emit();
    }
}
