<?php
/* ********************************************************************************
 * The content of this file is subject to the Documents Upload Anywhere ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

class VTEWEBHOOKS_Module_Model extends Vtiger_Module_Model {

    function getSettingLinks() {
        $settingsLinks[] = array(
            'linktype' => 'MODULESETTING',
            'linklabel' => 'Settings',
            'linkurl' => 'index.php?module=VTEWEBHOOKS&parent=Settings&view=Settings',
            'linkicon' => ''
        );

        $vTELicense = new VTEWEBHOOKS_VTELicense_Model('VTEWEBHOOKS');
        if (!$vTELicense->validate()){
            $settingsLinks[] = array(
                'linktype' => 'MODULESETTING',
                'linklabel' => 'Active License',
                'linkurl' => 'index.php?module=VTEWEBHOOKS&parent=Settings&view=ActiveLicense',
                'linkicon' => ''
            );
        }
        $settingsLinks[] = array(
            'linktype' => 'MODULESETTING',
            'linklabel' => 'Uninstall',
            'linkurl' => 'index.php?module=VTEWEBHOOKS&parent=Settings&view=Uninstall',
            'linkicon' => ''
        );
        return $settingsLinks;
    }
}