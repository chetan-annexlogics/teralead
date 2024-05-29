<?php
/* ********************************************************************************
 * The content of this file is subject to the Documents Upload Anywhere ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

class VTEWebhookRequests_Module_Model extends Vtiger_Module_Model {

    public function getModuleIcon() {
        $moduleName = $this->getName();
        $lowerModuleName = strtolower($moduleName);
        $title = vtranslate($moduleName, $moduleName);
        $moduleIcon = "<i class='vicon-$lowerModuleName' title='$title'></i>";

        return $moduleIcon;
    }
}