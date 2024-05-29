<?php
class VTEWebhookRequests extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'vtiger_vtewebhookrequests';
	var $table_index= 'vtewebhookrequestsid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_vtewebhookrequestscf', 'vtewebhookrequestsid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_vtewebhookrequests', 'vtiger_vtewebhookrequestscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_vtewebhookrequests' => 'vtewebhookrequestsid',
		'vtiger_vtewebhookrequestscf'=>'vtewebhookrequestsid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('vtewebhookrequests', 'name'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'name',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'name';

	/// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('vtewebhookrequests', 'name'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'name',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('name');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'name';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'name';

	// Required Information for enabling Import feature
	var $required_fields = Array ();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('name','assigned_user_id');

	var $default_order_by = 'name';
	var $default_sort_order='ASC';

	function __construct() {
		global $log;
		$this->column_fields = getColumnFields(get_class($this));
		$this->db = new PearDatabase();
		$this->log = $log;
	}
	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type
	 */
	function save_module(){

	}
	function vtlib_handler($moduleName, $eventType) {
		require_once('include/utils/utils.php');
		global $adb;

		if($eventType == 'module.postinstall') {
            $this->addUserSpecificTable();
            $this->addDefaultModuleTypeEntity();
            $this->addWidgetTo();
            $this->fixUI();
		} else if($eventType == 'module.disabled') {
		} else if($eventType == 'module.enabled') {
            $this->addUserSpecificTable();
            $this->addModTrackerforModule();
            $this->addWidgetTo();
            $this->fixUI();
		} else if($eventType == 'module.preuninstall') {
			vtws_deleteWebserviceEntity('VTEWebhookRequests');
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
            $this->addWidgetTo();
            $this->fixUI();
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
            $this->addUserSpecificTable();
            $this->addDefaultModuleTypeEntity();
			$this->addModTrackerforModule();
            $this->addWidgetTo();
            $this->fixUI();
		}
	}
    public function fixUI(){
        global $adb;
        $sql = "UPDATE `vtiger_request_status` SET color = '#d6fcff' WHERE request_status = 'Pending';";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_request_status` SET color = '#b8ffad' WHERE request_status = 'Success';";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_request_status` SET color = '#ffe0e0' WHERE request_status = 'Failed';";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_request_status` SET color = '#ffe5b2' WHERE request_status = 'Retry';";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET presence = 1 WHERE fieldname = 'source' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 1 WHERE fieldname = 'name' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 1 WHERE fieldname = 'method' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 2 WHERE fieldname = 'assigned_user_id' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 2 WHERE fieldname = 'authorization_type' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 3 WHERE fieldname = 'record_url' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 3 WHERE fieldname = 'created_user_id' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 3 WHERE fieldname = 'content_type' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 4 WHERE fieldname = 'authorization_username' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 4 WHERE fieldname = 'vtewebhookrequestsno' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 5 WHERE fieldname = 'source_module' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 5 WHERE fieldname = 'request_status' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 6 WHERE fieldname = 'createdtime' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 6 WHERE fieldname = 'authorization_password' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 7 WHERE fieldname = 'request_date_time' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 7 WHERE fieldname = 'workflow' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 8 WHERE fieldname = 'url' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 8 WHERE fieldname = 'modifiedtime' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 9 WHERE fieldname = 'request' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 9 WHERE fieldname = 'action_title' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_field` SET sequence = 10 WHERE fieldname = 'request_response' AND tabid = (SELECT tabid FROM vtiger_tab WHERE name = 'VTEWebhookRequests');";
        $adb->pquery($sql,array());
        $sourceModule = 'VTEWebhookRequests';
        $moduleModel = Settings_Vtiger_CustomRecordNumberingModule_Model::getInstance($sourceModule);
        $moduleModel->set('prefix', 'WHR');
        $moduleModel->set('sequenceNumber', 1);
        $moduleModel->setModuleSequence();
    }
    private function addWidgetTo() {
         global $adb,$vtiger_current_version,$root_directory;
         $widgetType = 'HEADERCSS';
         $widgetName = 'VTEWebhookRequests';
         if(version_compare($vtiger_current_version, '7.0.0', '<')) {
             $template_folder= "layouts/vlayout";
         }else{
             $template_folder= "layouts/v7";
         }
         $link = $template_folder.'/modules/VTEWebhookRequests/resources/style.css';
         include_once 'vtlib/Vtiger/Module.php';

         $moduleNames = array('VTEWebhookRequests');
         foreach($moduleNames as $moduleName) {
             $module = Vtiger_Module::getInstance($moduleName);
             if($module) {
                 $module->addLink($widgetType, $widgetName, $link);
             }
         }
    }
    public function addDefaultModuleTypeEntity(){
		global $adb;
        // Check entity module
        $rs=$adb->query("SELECT * FROM `vtiger_ws_entity` WHERE `name`='VTEWebhookRequests'");
        if($adb->num_rows($rs) == 0) {
//            $entityId = $adb->getUniqueID("vtiger_ws_entity");
            $res=$adb->query("SELECT MAX(id) as id FROM vtiger_ws_entity");
            while ($row=$adb->fetch_row($res)){
                $entityId=$row['id']+1;
            }
            $adb->pquery("INSERT INTO `vtiger_ws_entity` (`id`, `name`, `handler_path`, `handler_class`, `ismodule`) VALUES (?, ?, ?, ?, ?);",
                array($entityId, 'VTEWebhookRequests', 'include/Webservices/VtigerModuleOperation.php', 'VtigerModuleOperation', '1'));
            $adb->pquery("UPDATE vtiger_ws_entity_seq SET id=?", array($entityId));
        }
    }

	public function addModTrackerforModule()
	{
		require_once('modules/ModTracker/ModTracker.php');
		$moduleInstance = Vtiger_Module::getInstance('VTEWebhookRequests');
		$blockInstance = Vtiger_Block::getInstance('LBL_VTEWEBHOOKREQUESTS_INFORMATION',$moduleInstance);
		//Date Created
		$createTime = Vtiger_Field::getInstance('createdtime',$moduleInstance);
		if($createTime) {
			echo "<li>The createdtime field already exists</li><br> \n";
		} else {
			$createTime = new Vtiger_Field();
			$createTime->label = 'Created Time';
			$createTime->name = 'createdtime';
			$createTime->table = 'vtiger_crmentity';
			$createTime->column = 'createdtime';
			$createTime->uitype = 70;
			$createTime->typeofdata = 'T~O';
			$createTime->displaytype = 2;

			$blockInstance->addField($createTime);
		}

		//Date Modified
		$modifiedTime = Vtiger_Field::getInstance('modifiedtime',$moduleInstance);
		if($modifiedTime) {
			echo "<li>The modifiedtime field already exists</li><br> \n";
		} else {
			$modifiedTime = new Vtiger_Field();
			$modifiedTime->label = 'Modified Time';
			$modifiedTime->name = 'modifiedtime';
			$modifiedTime->table = 'vtiger_crmentity';
			$modifiedTime->column = 'modifiedtime';
			$modifiedTime->uitype = 70;
			$modifiedTime->typeofdata = 'T~O';
			$modifiedTime->displaytype = 2;

			$blockInstance->addField($modifiedTime);
		}
		ModTracker::enableTrackingForModule($moduleInstance->id);
	}
    
    public function addUserSpecificTable(){
        // Add table xyz_user_field
        global $vtiger_current_version;
        if(version_compare($vtiger_current_version, '7.0.0', '<')) {
            // Nothing
        }else{
            $moduleName = 'VTEWebhookRequests';
            $moduleUserSpecificTable = Vtiger_Functions::getUserSpecificTableName($moduleName);
            if (!Vtiger_Utils::CheckTable($moduleUserSpecificTable)) {
                Vtiger_Utils::CreateTable($moduleUserSpecificTable,
                    '(`recordid` INT(19) NOT NULL,
					   `userid` INT(19) NOT NULL,
					   `starred` varchar(100) NULL,
					   Index `record_user_idx` (`recordid`, `userid`)
						)', true);
            }
        }
    }
}
?>