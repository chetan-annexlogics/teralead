<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class CustomDashboards_Module_Model extends Vtiger_Module_Model
{
    /**
     * Function deletes report
     * @param Reports_Record_Model $reportModel
     */
    public static function getPicklistColorByValue($fieldName, $recordModel)
    {
        $db = PearDatabase::getInstance();
        $fieldValue = $recordModel->getRaw($fieldName);
        $tableName = "vtiger_" . $fieldName;
        if (Vtiger_Utils::CheckTable($tableName)) {
            $colums = $db->getColumnNames($tableName);
            $fieldValue = decode_html($fieldValue);
            if (in_array("color", $colums)) {
                $query = "SELECT color FROM " . $tableName . " WHERE " . $fieldName . " = ?";
                $result = $db->pquery($query, array($fieldValue));
                if (0 < $db->num_rows($result)) {
                    $color = $db->query_result($result, 0, "color");
                }
            }
        }
        return $color;
    }
    public function deleteRecord($reportModel)
    {
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $subOrdinateUsers = $currentUser->getSubordinateUsers();
        $subOrdinates = array();
        foreach ($subOrdinateUsers as $id => $name) {
            $subOrdinates[] = $id;
        }
        $owner = $reportModel->get("owner");
        if ($currentUser->isAdminUser() || in_array($owner, $subOrdinates) || $owner == $currentUser->getId()) {
            $reportId = $reportModel->getId();
            $db = PearDatabase::getInstance();
            $db->pquery("DELETE FROM vtiger_selectquery WHERE queryid = ?", array($reportId));
            $db->pquery("DELETE FROM vtiger_customdashboard WHERE reportid = ?", array($reportId));
            $db->pquery("DELETE FROM vtiger_schedulecustomdashboards WHERE reportid = ?", array($reportId));
            $db->pquery("DELETE FROM vtiger_customdashboardtype WHERE reportid = ?", array($reportId));
            $result = $db->pquery("SELECT * FROM vtiger_homecustomdashboardchart WHERE reportid = ?", array($reportId));
            $numOfRows = $db->num_rows($result);
            for ($i = 0; $i < $numOfRows; $i++) {
                $homePageChartIdsList[] = $adb->query_result($result, $i, "stuffid");
            }
            if ($homePageChartIdsList) {
                $deleteQuery = "DELETE FROM vtiger_homestuff WHERE stuffid IN (" . implode(",", $homePageChartIdsList) . ")";
                $db->pquery($deleteQuery, array());
            }
            if ($reportModel->get("reporttype") == "chart") {
                CustomDashboards_Widget_Model::deleteChartReportWidgets($reportId);
            }
            return true;
        }
        return false;
    }
    public function getSettingLinks()
    {
        //was9
        $settingsLinks = parent::getSettingLinks();
        $settingsLinks[] = array("linktype" => "MODULESETTING", "linklabel" => "Settings", "linkurl" => "index.php?module=CustomDashboards&parent=Settings&view=Settings", "linkicon" => "");
        $settingsLinks[] = array("linktype" => "MODULESETTING", "linklabel" => "Uninstall", "linkurl" => "index.php?module=CustomDashboards&parent=Settings&view=Uninstall", "linkicon" => "");
        return $settingsLinks;
    }
    /**
     * Function returns quick links for the module
     * @return <Array of Vtiger_Link_Model>
     */
    // public function getSideBarLinks()
    // {
    //     $quickLinks = array(array("linktype" => "SIDEBARLINK", "linklabel" => "LBL_CUSTOMDASHBOARDS", "linkurl" => $this->getListViewUrl(), "linkicon" => ""));
    //     foreach ($quickLinks as $quickLink) {
    //         $links["SIDEBARLINK"][] = Vtiger_Link_Model::getInstanceFromValues($quickLink);
    //     }
    //     $quickWidgets = array(array("linktype" => "SIDEBARWIDGET", "linklabel" => "LBL_RECENTLY_MODIFIED", "linkurl" => "module=" . $this->get("name") . "&view=IndexAjax&mode=showActiveRecords", "linkicon" => ""));
    //     foreach ($quickWidgets as $quickWidget) {
    //         $links["SIDEBARWIDGET"][] = Vtiger_Link_Model::getInstanceFromValues($quickWidget);
    //     }
    //     return $links;
    // }
    /**
     * Function returns the recent created reports
     * @param <Number> $limit
     * @return <Array of Reports_Record_Model>
     */
    public function getRecentRecords($limit = 10)
    {
        $db = PearDatabase::getInstance();
        $result = $db->pquery("SELECT * FROM vtiger_customdashboard \n\t\t\t\t\t\tINNER JOIN vtiger_customdashboardmodules ON vtiger_customdashboardmodules.reportmodulesid = vtiger_customdashboard.reportid\n\t\t\t\t\t\tINNER JOIN vtiger_tab ON vtiger_tab.name = vtiger_customdashboardmodules.primarymodule AND presence = 0\n\t\t\t\t\t\tORDER BY reportid DESC LIMIT ?", array($limit));
        $rows = $db->num_rows($result);
        $recentRecords = array();
        for ($i = 0; $i < $rows; $i++) {
            $row = $db->query_result_rowdata($result, $i);
            $recentRecords[$row["reportid"]] = $this->getRecordFromArray($row);
        }
        return $recentRecords;
    }
    /**
     * Function returns the report folders
     * @return <Array of Reports_Folder_Model>
     */
    public function getFolders()
    {
        return CustomDashboards_Folder_Model::getAll();
    }
    /**
     * Function to get the url for add folder from list view of the module
     * @return <string> - url
     */
    public function getAddFolderUrl()
    {
        return "index.php?module=" . $this->get("name") . "&view=EditFolder";
    }
    /**
     * Function to check if the extension module is permitted for utility action
     * @return <boolean> true
     */
    public function isUtilityActionEnabled()
    {
        return true;
    }
    /**
     * Function is a callback from Vtiger_Link model to check permission for the links
     * @param type $linkData
     */
    public function checkLinkAccess($linkData)
    {
        $privileges = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $reportModuleModel = Vtiger_Module_Model::getInstance("CustomDashboards");
        return $privileges->hasModulePermission($reportModuleModel->getId());
    }
    public function getUtilityActionsNames()
    {
        return array("Export");
    }
	    /**
     * Function parses date into readable format
     * @param <Date Time> $dateTime
     * @return <String>
     */
    public static function formatDateDiffInStrings($dateTime, $isUserFormat = FALSE) {
        try{
            // http://www.php.net/manual/en/datetime.diff.php#101029
            $currentDateTime = date('Y-m-d H:i:s');

            if($isUserFormat) {
                $dateTime = Vtiger_Datetime_UIType::getDBDateTimeValue($dateTime);
            }
            $seconds =  strtotime($currentDateTime) - strtotime($dateTime);

            if ($seconds == 0) return vtranslate('LBL_JUSTNOW');
            if ($seconds > 0) {
                $prefix = '';
                $suffix = ' '. vtranslate('LBL_AGO');
            } else if ($seconds < 0) {
                $prefix = vtranslate('LBL_DUE') . ' ';
                $suffix = '';
                $seconds = -($seconds);
            }

            $minutes = floor($seconds/60);
            $hours = floor($minutes/60);
            $days = floor($hours/24);
            $months = floor($days/30);
            $suffix = "<br /><lable style='opacity: 0.25;'>".$suffix."</lable>";
            if ($seconds < 60)	return $prefix . self::pluralize($seconds,	"LBL_SECOND") . $suffix;
            if ($minutes < 60)	return $prefix . self::pluralize($minutes,	"LBL_MINUTE") . $suffix;
            if ($hours < 24)	return $prefix . self::pluralize($hours,	"LBL_HOUR") . $suffix;
            if ($days < 30)		return $prefix . self::pluralize($days,		"LBL_DAY") . $suffix;
            if ($months < 12)	return $prefix . self::pluralize($months,	"LBL_MONTH") . $suffix;
            if ($months > 11)	return $prefix . self::pluralize(floor($days/365), "LBL_YEAR") . $suffix;
        }catch(Exception $e){
            //Not handling if failed to parse
        }
    }

    /**
     * Function returns singular or plural text
     * @param <Number> $count
     * @param <String> $text
     * @return <String>
     */
    public static function pluralize($count, $text) {
        return $count ." ". (($count == 1) ? vtranslate("$text","CustomDashboards") : vtranslate("${text}S","CustomDashboards"));
    }
}

?>