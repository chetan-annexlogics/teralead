<?php


require_once('data/CRMEntity.php');
require_once('data/Tracker.php');
require_once 'vtlib/Vtiger/Module.php';
class VTDevKBView extends CRMEntity
{
    function vtlib_handler($modulename, $event_type)
    {
        if ($event_type == 'module.postinstall') {
            self::addWidgetTo();
            self::checkEnable();
            self::resetValid();
            self::addExtensionToListView();
        } else if ($event_type == 'module.disabled') {
            self::removeWidgetTo();
            self::removeExtensionToListView();
        } else if ($event_type == 'module.enabled') {
            self::addWidgetTo();
            self::addExtensionToListView();
        } else if ($event_type == 'module.preuninstall') {
            self::removeWidgetTo();
            self::removeField();
            self::removeValid();
            self::removeExtensionToListView();
        } else if ($event_type == 'module.preupdate') {
            self::removeExtensionToListView();
            self::removeWidgetTo();
        } else if ($event_type == 'module.postupdate') {
            self::addExtensionToListView();
            self::removeWidgetTo();
            self::addWidgetTo();
            self::checkEnable();
            self::resetValid();
        }
    }
    static function checkEnable()
    {
        global $adb;
        $rs = $adb->pquery("SELECT `enable` FROM `vtdevkb_view_settings`;", array());
        if ($adb->num_rows($rs) == 0) {
            $adb->pquery("INSERT INTO `vtdevkb_view_settings` (`enable`) VALUES ('0');", array());
        }
    }
    static function addExtensionToListView()
    {
        global $adb;
        $supportedModulesList = Settings_LayoutEditor_Module_Model::getSupportedModules();
        $supportedModulesList = array_keys($supportedModulesList);
        foreach ($supportedModulesList as $value) {
            $moduleInstance = Vtiger_Module_Model::getInstance($value);
            $tabid = $moduleInstance->get("id");
            $var = $adb->pquery("SELECT * FROM vtiger_links where tabid= ? AND linklabel =  ? ", array($tabid, 'VTDevKBView'));
            if ($adb->num_rows($var) == 0) {
                $nameModule = $moduleInstance->get('name');
                $pstemplates_module = Vtiger_Module::getInstance($nameModule);
                $pstemplates_module->addLink('EXTENSIONLINK', 'VTDevKBView', 'javascript:VTDevKBView_Js.initData_VTDevKBView()');
            }
        }
    }
    static function removeExtensionToListView()
    {
        global $adb;
        $supportedModulesList = Settings_LayoutEditor_Module_Model::getSupportedModules();
        $supportedModulesList = array_keys($supportedModulesList);
        foreach ($supportedModulesList as $value) {
            $moduleInstance = Vtiger_Module_Model::getInstance($value);
            $tabid = $moduleInstance->get("id");
            $var = $adb->pquery("SELECT * FROM vtiger_links where tabid= ? AND linklabel =  ? ", array($tabid, 'VTDevKBView'));
            if ($adb->num_rows($var)) {
                $nameModule = $moduleInstance->get('name');
                $pstemplates_module = Vtiger_Module::getInstance($nameModule);
                $pstemplates_module->deleteLink('EXTENSIONLINK', 'VTDevKBView', 'javascript:VTDevKBView_Js.initData_VTDevKBView()');
                $pstemplates_module->deleteLink('EXTENSIONLINK', 'VTDevKBView', 'javascript:void(0)');
            }
        }
    }
    static function addWidgetTo()
    {
        global $adb, $vtiger_current_version;
        $template_folder = "layouts/v7";
        $widgetType = 'HEADERSCRIPT';
        $widgetName = 'VTDevKBJs';
        $link = $template_folder . '/modules/VTDevKBView/resources/VTDevKBView.js';
        include_once 'vtlib/Vtiger/Module.php';
        $moduleNames = array('VTDevKBView');
        foreach ($moduleNames as $moduleName) {
            $module = Vtiger_Module::getInstance($moduleName);
            if ($module) {
                $module->addLink($widgetType, $widgetName, $link);
            }
        }
        $max_id = $adb->getUniqueID('vtiger_settings_field');
        $adb->pquery("INSERT INTO `vtiger_settings_field` (`fieldid`, `blockid`, `name`, `description`, `linkto`, `sequence`) VALUES (?, ?, ?, ?, ?, ?)", array($max_id, '4', 'VTDevKBView', 'Settings area for VTDevKBView', 'index.php?module=VTDevKBView&parent=Settings&view=Settings', $max_id));
        $rs = $adb->pquery("SELECT * FROM `vtiger_ws_entity` WHERE `name` = ?", array($moduleName));
        if ($adb->num_rows($rs) == 0) {
            $adb->pquery("INSERT INTO `vtiger_ws_entity` (`name`, `handler_path`, `handler_class`, `ismodule`)
            VALUES (?, 'include/Webservices/VtigerModuleOperation.php', 'VtigerModuleOperation', '1');", array($moduleName));
            $adb->pquery('UPDATE vtiger_ws_entity_seq SET id=(SELECT MAX(id) FROM vtiger_ws_entity)', array());
        }
    }
    static function removeWidgetTo()
    {
        global $adb, $vtiger_current_version;
        $template_folder = "layouts/v7";
        $vtVersion = 'vt7';
        $widgetType = 'HEADERSCRIPT';
        $widgetName = 'VTDevKBJs';
        $link = $template_folder . '/modules/VTDevKBView/resources/VTDevKBView.js';
        include_once 'vtlib/Vtiger/Module.php';
        $moduleNames = array('VTDevKBView');
        foreach ($moduleNames as $moduleName) {
            $module = Vtiger_Module::getInstance($moduleName);
            if ($module) {
                $module->deleteLink($widgetType, $widgetName, $link);
            }
        }
        $adb->pquery("DELETE FROM vtiger_settings_field WHERE `name` = ?", array('VTDevKBView'));
        $adb->pquery("DELETE FROM vtiger_ws_entity WHERE `name` =?", array('VTDevKBView'));
    }
    static function removeField()
    {
        global $adb;
        $allModules = array_keys(Vtiger_Module_Model::getSearchableModules());
        foreach ($allModules as $moduleName) {
            $sql = "SELECT fieldid,fieldlabel,fieldname,vtiger_tab.tabid FROM vtiger_field
                INNER JOIN vtiger_tab ON vtiger_field.tabid = vtiger_tab.tabid
                WHERE uitype IN (15,16) AND vtiger_tab.`name` = ? AND (vtiger_field.presence = 0 OR vtiger_field.presence = 2)";
            $rs = $adb->pquery($sql, array($moduleName));
            if ($adb->num_rows($rs) > 0) {
                $module = Vtiger_Module::getInstance($moduleName);
                if ($module) {
                    $colorField = Vtiger_Field_Model::getInstance('vtdevkb_color', $module);
                    if ($colorField) {
                        $colorField->delete();
                    }
                }
            }
        }
    }
    static function resetValid()
    {
        global $adb;
        $adb->pquery("DELETE FROM `vtdev_modules` WHERE module=?;", array('VTDevKBView'));
        $adb->pquery("INSERT INTO `vtdev_modules` (`module`, `valid`) VALUES (?, ?);", array('VTDevKBView', '0'));
    }
    static function removeValid()
    {
        global $adb;
        $adb->pquery("DELETE FROM `vtdev_modules` WHERE module=?;", array('VTDevKBView'));
    }
    static function createFields($moduleName)
    {
        global $adb;
        $focus = CRMEntity::getInstance($moduleName);
        $table_name = $focus->table_name;
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $blockObject = Vtiger_Block::getInstance('LBL_CUSTOM_INFORMATION', $moduleModel);
        if (!$blockObject) {
            $blockInstance = new Settings_LayoutEditor_Block_Model();
            $blockInstance->set('label', 'LBL_CUSTOM_INFORMATION');
            $blockInstance->set('iscustom', '1');
            $blockId = $blockInstance->save($moduleModel);
            $blockObject = Vtiger_Block::getInstance('LBL_CUSTOM_INFORMATION', $moduleModel);
        }
        $blockModel = Vtiger_Block_Model::getInstanceFromBlockObject($blockObject);
        $fieldModel = new Vtiger_Field_Model();
        $fieldModel->set('name', 'vtdevkb_color')->set('table', $table_name)->set('generatedtype', 2)->set('uitype', 16)->set('label', 'Color')->set('typeofdata', 'V~O')->set('quickcreate', 0)->set('presence', 2)->set('displaytype', 1)->set('columntype', "varchar(100)");
        $blockModel->addField($fieldModel);
        $pickListValues = array('Red', 'Orange', 'Green', 'Yellow', 'Teal', 'Blue', 'Purple', 'Peru', 'Silver', 'Olive');
        $fieldModel->setPicklistValues($pickListValues);
    }
} ?>
