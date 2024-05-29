<?php
/* ********************************************************************************
 * The content of this file is subject to the Webhooks ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

require_once('data/CRMEntity.php');
require_once('data/Tracker.php');
require_once 'vtlib/Vtiger/Module.php';
require_once('modules/com_vtiger_workflow/include.inc');

class VTEWEBHOOKS extends CRMEntity {
    /**
     * Invoked when special actions are performed on the module.
     * @param String Module name
     * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
     */
    function vtlib_handler($modulename, $event_type) {
        global $adb;
        if($event_type == 'module.postinstall') {
			self::checkEnable();
            self::addSettings();
            self::addWidgetTo();
            self::installWorkflow();
            self::resetValid();
            self::createScheduler();
        } else if($event_type == 'module.disabled') {
            // TODO Handle actions when this module is disabled.
            self::removeSettings();
            self::removeWorkflows();
            self::removeWidgetTo();
            self::deactiveScheduler();
        } else if($event_type == 'module.enabled') {
            // TODO Handle actions when this module is enabled.
            self::addSettings();
            self::addWidgetTo();
            self::installWorkflow();
            self::createScheduler();
        } else if($event_type == 'module.preuninstall') {
            // TODO Handle actions when this module is about to be deleted.
            self::removeSettings();
            self::removeWorkflows();
            self::deleteScheduler();
            self::removeValid();
        } else if($event_type == 'module.preupdate') {
            // TODO Handle actions before this module is updated.
            self::removeWidgetTo();
        } else if($event_type == 'module.postupdate') {
            self::removeSettings();
            self::removeWidgetTo();
			self::checkEnable();
            self::addWidgetTo();
            self::addSettings();
            self::installWorkflow();
            self::resetValid();
            self::deleteScheduler();
            self::createScheduler();
        }
    }
    
    static function resetValid() {
        global $adb;
        $adb->pquery("DELETE FROM `vte_modules` WHERE module=?;",array('VTEWEBHOOKS'));
        $adb->pquery("INSERT INTO `vte_modules` (`module`, `valid`) VALUES (?, ?);",array('VTEWEBHOOKS','0'));
    }
    
    static function removeValid() {
        global $adb;
        $adb->pquery("DELETE FROM `vte_modules` WHERE module=?;",array('VTEWEBHOOKS'));
    }
	
	static function checkEnable() {
        global $adb;
        $rs=$adb->pquery("SELECT `enable` FROM `webhooks_settings`;",array());
        if($adb->num_rows($rs)==0) {
            $adb->pquery("INSERT INTO `webhooks_settings` (`enable`) VALUES ('1');",array());
        }
    }

    static function addSettings() {
        global $adb;

        $max_id=$adb->getUniqueID('vtiger_settings_field');
        $adb->pquery("INSERT INTO `vtiger_settings_field` (`fieldid`, `blockid`, `name`, `description`, `linkto`, `sequence`) VALUES (?, ?, ?, ?, ?, ?)",array($max_id, '4', 'Webhooks', 'Settings area for VTEWEBHOOKS', 'index.php?module=Workflows&parent=Settings&view=List', $max_id));
    }    
    
    static function removeSettings() {
        global $adb;
        $adb->pquery("DELETE FROM vtiger_settings_field WHERE `name` = ?",array('Webhooks'));
    }

    static function installWorkflow() {
        global $adb;
        $name='VTEWEBHOOKSTask';
        $dest1 = "modules/com_vtiger_workflow/tasks/".$name.".inc";
        $source1 = "modules/VTEWEBHOOKS/workflow/".$name.".inc";

        if (file_exists($dest1)) {
            $file_exist1 = true;
        } else {
            if(copy($source1, $dest1)) {
                $file_exist1 = true;
            }
        }

        $template_folder= "layouts/v7";
        $dest2 = $template_folder."/modules/Settings/Workflows/Tasks/".$name.".tpl";
        $source2 = $template_folder."/modules/VTEWEBHOOKS/taskforms/".$name.".tpl";

        if (file_exists($dest2)) {
            $file_exist2 = true;
        } else {
            if(copy($source2, $dest2)) {
                $file_exist2 = true;
            }
        }

        if ($file_exist1 && $file_exist2) {
       	 	$name='VTEWEBHOOKS';
            $sql1 = "SELECT * FROM com_vtiger_workflow_tasktypes WHERE tasktypename = ?";
            $result1 = $adb->pquery($sql1,array($name));

            if ($adb->num_rows($result1) == 0) {
                // Add workflow task
                $taskType = array("name"=>"VTEWEBHOOKSTask", "label"=>"Webhooks", "classname"=>"VTEWEBHOOKSTask", "classpath"=>"modules/VTEWEBHOOKS/workflow/VTEWEBHOOKSTask.inc", "templatepath"=>"modules/VTEWEBHOOKS/taskforms/VTEWEBHOOKSTask.tpl", "modules"=>array('include' => array(), 'exclude'=>array()), "sourcemodule"=>'VTEWEBHOOKS');
                VTTaskType::registerTaskType($taskType);
            }
		}
    }

    static function removeWorkflows() {
        global $adb;
        $sql1 = "DELETE FROM com_vtiger_workflow_tasktypes WHERE sourcemodule = ?";
        $adb->pquery($sql1, array('VTEWEBHOOKS'));

        $sql2 = "DELETE FROM com_vtiger_workflowtasks WHERE task LIKE ?";
        $adb->pquery($sql2,array('%:"VTEWEBHOOKSTask":%'));
		
        @shell_exec('rm -f modules/com_vtiger_workflow/tasks/VTEWEBHOOKSTask.inc');
        @shell_exec('rm -f layouts/vlayout/modules/Settings/Workflows/Tasks/VTEWEBHOOKSTask.inc');
    }

    static function addWidgetTo() {
        $widgetType = 'HEADERSCRIPT';
        $widgetName = 'VTEWEBHOOKSJS';
        $link = 'layouts/v7/modules/VTEWEBHOOKS/resources/VTEWEBHOOKSJS.js';
        include_once 'vtlib/Vtiger/Module.php';

        $moduleNames = array('VTEWEBHOOKS');
        foreach($moduleNames as $moduleName) {
            $module = Vtiger_Module::getInstance($moduleName);
            if($module) {
                $module->addLink($widgetType, $widgetName, $link);
            }
        }

    }

    static function removeWidgetTo() {
        $widgetType = 'HEADERSCRIPT';
        $widgetName = 'VTEWEBHOOKSJS';
        $link = 'layouts/v7/modules/VTEWEBHOOKS/resources/VTEWEBHOOKSJS.js';
        include_once 'vtlib/Vtiger/Module.php';

        $moduleNames = array('VTEWEBHOOKS');
        foreach($moduleNames as $moduleName) {
            $module = Vtiger_Module::getInstance($moduleName);
            if($module) {
                $module->deleteLink($widgetType, $widgetName, $link);
            }
        }
    }
    private function createScheduler() {
        $adb = PearDatabase::getInstance();
        $sql = "SELECT id FROM `vtiger_cron_task` WHERE `module` = 'VTEWEBHOOKS'";
        $res = $adb->pquery($sql,array());
        if(!$adb->num_rows($res)) {
            $adb->pquery("INSERT INTO `vtiger_cron_task` (`name`, `handler_file`, `frequency`, `status`, `module`, `sequence`) VALUES ('Webhook Execution', 'modules/VTEWEBHOOKS/cron/VTEWEBHOOKS.service', '60', '1', 'VTEWEBHOOKS', '101')",array());
        }
    }

    private function deactiveScheduler() {
        $adb = PearDatabase::getInstance();
        $adb->pquery("UPDATE `vtiger_cron_task` SET `status`='0' WHERE (`module`='VTEWEBHOOKS')",array());
    }

    private function deleteScheduler() {
        $adb = PearDatabase::getInstance();
        $adb->pquery("DELETE FROM `vtiger_cron_task` WHERE (`module`='VTEWEBHOOKS')",array());
    }
}
?>