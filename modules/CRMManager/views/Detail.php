<?php
class CRMManager_Detail_View extends Vtiger_Detail_View
{

    /**
     * Function to get activities
     * @param Vtiger_Request $request
     * @return <List of activity models>
     */
    public function getActivities(Vtiger_Request $request) {
        $moduleName = 'Calendar';
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);

        $currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        if($currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
            $moduleName = $request->getModule();
            $recordId = $request->get('record');

            $pageNumber = $request->get('page');
            if(empty ($pageNumber)) {
                $pageNumber = 1;
            }
            $pagingModel = new Vtiger_Paging_Model();
            $pagingModel->set('page', $pageNumber);
            $pagingModel->set('limit', 10);

            if(!$this->record) {
                $this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
            }
            $recordModel = $this->record->getRecord();
            $moduleModel = $recordModel->getModule();

            $relatedActivities = $moduleModel->getCalendarActivities('', $pagingModel, 'all', $recordId);

            $viewer = $this->getViewer($request);
            $viewer->assign('RECORD', $recordModel);
            $viewer->assign('MODULE_NAME', $moduleName);
            $viewer->assign('PAGING_MODEL', $pagingModel);
            $viewer->assign('PAGE_NUMBER', $pageNumber);
            $viewer->assign('ACTIVITIES', $relatedActivities);

            return $viewer->view('RelatedActivities.tpl', $moduleName, true);
        }
    }

}