<?php


/**
 * VGS Multi FROM Address Module
 *
 *
 * @package        VGSMultiSender Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */

include_once 'modules/Vtiger/CRMEntity.php';
include_once 'include/utils/utils.php';

class VGSMultiSender extends Vtiger_CRMEntity {

    public function __construct() {
        
    }

    function vtlib_handler($moduleName, $eventType) {
        $adb = PearDatabase::getInstance();
        if ($eventType == 'module.postinstall') {

            $otherSettingsBlock = $adb->pquery('SELECT * FROM vtiger_settings_blocks WHERE label=?', array('LBL_OTHER_SETTINGS'));
            $otherSettingsBlockCount = $adb->num_rows($otherSettingsBlock);

            if ($otherSettingsBlockCount > 0) {
                $blockid = $adb->query_result($otherSettingsBlock, 0, 'blockid');
                $sequenceResult = $adb->pquery("SELECT max(sequence) as sequence FROM vtiger_settings_blocks WHERE blockid=?", array($blockid));
                if ($adb->num_rows($sequenceResult)) {
                    $sequence = $adb->query_result($sequenceResult, 0, 'sequence');
                }
            }

            $fieldid = $adb->getUniqueID('vtiger_settings_field');
            $adb->pquery("INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence, active) 
                        VALUES(?,?,?,?,?,?,?,?)", array($fieldid, $blockid, 'VGS Multi Sender', '', 'VGS Multi Sender', 'index.php?module=VGSMultiSender&view=SettingsIndex&parent=Settings', $sequence++, 0));


            require_once('vtlib/Vtiger/Link.php');

            $EmailsTabid = getTabId("Emails");
            $UsersTabid = getTabId("Users");

            Vtiger_Link::addLink($EmailsTabid, 'HEADERSCRIPT', 'SMTP4Emails', 'layouts/v7/modules/VGSMultiSender/resources/VGSMultiSenderHeader.js', '', 0, '');
            Vtiger_Link::addLink($UsersTabid, 'HEADERSCRIPT', 'SMTP4Users', 'layouts/v7/modules/VGSMultiSender/resources/VGSMultiSenderHeader.js', '', 0, '');

            $this->createColumns();
        } else if ($eventType == 'module.disabled') {
            require_once('vtlib/Vtiger/Link.php');

            $EmailsTabid = getTabId("Emails");
            $UsersTabid = getTabId("Users");

            Vtiger_Link::deleteLink($EmailsTabid, 'HEADERSCRIPT', 'SMTP4Emails');
            Vtiger_Link::deleteLink($UsersTabid, 'HEADERSCRIPT', 'SMTP4Users');
            
        } else if ($eventType == 'module.enabled') {
            require_once('vtlib/Vtiger/Link.php');

            $EmailsTabid = getTabId("Emails");
            $UsersTabid = getTabId("Users");

            Vtiger_Link::addLink($EmailsTabid, 'HEADERSCRIPT', 'SMTP4Emails', 'layouts/v7/modules/VGSMultiSender/resources/VGSMultiSenderHeader.js', '', 0, '');
            Vtiger_Link::addLink($UsersTabid, 'HEADERSCRIPT', 'SMTP4Users', 'layouts/v7/modules/VGSMultiSender/resources/VGSMultiSenderHeader.js', '', 0, '');
           
        } else if ($eventType == 'module.preuninstall') {
            $adb = PearDatabase::getInstance();
            $adb->pquery("DELETE FROM vtiger_settings_field WHERE name=?", array('VGS Multi Sender'));
        } else if ($eventType == 'module.preupdate') {
            
        } else if ($eventType == 'module.postupdate') {
            $this->createColumns();
        }
    }

    private function createColumns() {
        //add column "locked"
        $adb = PearDatabase::getInstance();
        $table = 'vtiger_vgsmultisender';
        $new_columns = array(
            "locked" => "int(1) DEFAULT 0 NOT NULL",
        );
        foreach($new_columns as $name => $params) {
            $query = $adb->pquery(
                "SELECT COLUMN_DEFAULT
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME = ?
                      AND COLUMN_NAME = ?
                      AND TABLE_SCHEMA = ?",
                array($table, $name, $adb->database->databaseName)
            );
            if ($adb->num_rows($query) < 1) {
                $adb->pquery(
                    "ALTER TABLE {$table}
                        ADD COLUMN {$name} {$params}"
                );
            }
        }
    }
}
