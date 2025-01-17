<?php
//headerNop

class CustomDashboards_DashBoard_Model extends Vtiger_Base_Model
{
    private $ownerReport = "";
    public $dashboardTabLimit = 1000;
    /**
     * Function to get Module instance
     * @return <Vtiger_Module_Model>
     */
    public function getModule()
    {
        return $this->module;
    }
    /**
     * Function to set the module instance
     * @param <Vtiger_Module_Model> $moduleInstance - module model
     * @return Vtiger_DetailView_Model>
     */
    public function setModule($moduleInstance)
    {
        $this->module = $moduleInstance;
        return $this;
    }
    /**
     *  Function to get the module name
     *  @return <String> - name of the module
     */
    public function getModuleName()
    {
        return $this->getModule()->get("name");
    }
    /**
     * Function returns the list of Widgets
     * @return <Array of CustomDashboards_Widget_Model>
     */
    public function getSelectableDashboard()
    {
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $moduleModel = $this->getModule();
        $dashBoardTabId = $this->get("tabid");
        $dashBoardTabInfo = $this->getTabInfo($dashBoardTabId);
        $moduleTabIdList = array($moduleModel->getId());
        if (!empty($dashBoardTabInfo["appname"])) {
            $allVisibleModules = Settings_MenuEditor_Module_Model::getAllVisibleModules();
            $appVisibleModules = $allVisibleModules[$dashBoardTabInfo["appname"]];
            if (is_array($appVisibleModules)) {
                $moduleTabIdList = array();
                foreach ($appVisibleModules as $moduleInstance) {
                    $moduleTabIdList[] = $moduleInstance->getId();
                }
            }
        }
        $sql = "SELECT * FROM vtiger_links WHERE  linktype = ? AND tabid IN (" . generateQuestionMarks($moduleTabIdList) . ")";
        $params = array("DASHBOARDWIDGET");
        $params = array_merge($params, $moduleTabIdList);
        $sql .= " UNION SELECT * FROM vtiger_links WHERE tabid = ? AND (linklabel IN (?) OR linklabel IN (?) OR linklabel IN (?) OR linklabel IN (?))";
        $sql .= " ORDER BY linklabel ASC";
        $params[] = $moduleModel->getId();
        $params[] = "Mini List CustomDashboards";
        $params[] = "Key Metrics";
        $params[] = "History";
        $params[] = "Gauge";
        $result = $db->pquery($sql, $params);
        $widgets = array();
        $dashBoardTabId = $_REQUEST["tabid"] ? $_REQUEST["tabid"] : 1;
        for ($i = 0; $i < $db->num_rows($result); $i++) {
            $row = $db->query_result_rowdata($result, $i);
            if ($row["linklabel"] == "Tag Cloud") {
                $isTagCloudExists = getTagCloudView($currentUser->getId());
                if ($isTagCloudExists == "false") {
                    continue;
                }
            }
            if ($this->checkModulePermission($row)) {
                $linkUrl = $row["linkurl"];
                $row["is_show"] = false;
                preg_match("/reportid=\\w*/", $linkUrl, $matches);
                if ($matches) {
                    $reportId = str_replace("reportid=", "", $matches[0]);
                    $query = "SELECT 1 FROM vtiger_module_customdashboarddashboard_widgets WHERE reportid = ? AND userid = ? AND dashboardtabid = ?";
                    $param = array($reportId, $currentUser->getId(), $dashBoardTabId);
                    $resultReportId = $db->pquery($query, $param);
                    $numOfRows = $db->num_rows($resultReportId);
                    if (0 < $numOfRows) {
                        $row["is_show"] = true;
                        $row["reportid"] = $reportId;
                    }
                    $queryFolder = "SELECT vtiger_customdashboardfolder.folderid, vtiger_customdashboardfolder.foldername, vtiger_customdashboard.reportid, vtiger_customdashboard.reportname FROM vtiger_customdashboard\n                          INNER JOIN vtiger_customdashboardfolder ON vtiger_customdashboardfolder.folderid = vtiger_customdashboard.folderid WHERE vtiger_customdashboard.reportid = ?";
                    $resultFolder = $db->pquery($queryFolder, array($reportId));
                    $folderId = $db->query_result($resultFolder, 0, "folderid");
                    $linklabel = $db->query_result($resultFolder, 0, "reportname");
                    if ($folderId) {
                        $row["folderid"] = $folderId;
                        $row["linklabel"] = $linklabel;
                    }
                }
                preg_match("/action.+(?=Actions)/", $linkUrl, $matches);
                $typeReport = explode("=", $matches[0]);
                $typeReport = $typeReport[1];
                if ($typeReport == "Tabular") {
                    $widgets["detail"][] = CustomDashboards_Widget_Model::getInstanceFromValues($row, $typeReport);
                } else {
                    if (in_array($row["linklabel"], array("Mini List CustomDashboards", "Key Metrics", "History", "Gauge"))) {
                        $widgets["other"][] = CustomDashboards_Widget_Model::getInstanceFromValues($row, $typeReport);
                    } else {
                        if ($currentUser->getId() == $this->ownerReport) {
                            $widgets["myWidget"][] = CustomDashboards_Widget_Model::getInstanceFromValues($row, $typeReport);
                        } else {
                            $widgets["share"][] = CustomDashboards_Widget_Model::getInstanceFromValues($row, $typeReport);
                        }
                    }
                }
            }
        }
        return $widgets;
    }
    /**
     * Function returns List of User's selected Dashboard Widgets
     * @return <Array of CustomDashboards_Widget_Model>
     */
    public function checkNotificationDynamic($tabid)
    {
        global $adb;
        $result = $adb->pquery("SELECT 1 \n                                        FROM vtiger_customdashboarddashboard_tabs \n                                        LEFT JOIN vtiger_crmentity ON vtiger_customdashboarddashboard_tabs.dynamic_filter_account = vtiger_crmentity.crmid\n                                        WHERE id = ?  AND  vtiger_crmentity.deleted = 0\n                                        AND (dynamic_filter_account IS NOT NULL OR dynamic_filter_assignedto IS NOT NULL)\n                                         LIMIT 1", array($tabid));
        if (0 < $adb->num_rows($result)) {
            return 1;
        }
        return "";
    }
    public function getDashboards($moduleDashboard)
    {
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $currentUserPrivilegeModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $moduleModel = $this->getModule();
        $rsCheck = $db->pquery("select boardname from vtiger_customdashboarddashboard_boards \n INNER JOIN vtiger_customdashboarddashboard_tabs on vtiger_customdashboarddashboard_tabs.boardid = vtiger_customdashboarddashboard_boards.id where vtiger_customdashboarddashboard_tabs.id = ?", array($this->get("tabid")));
        if ($rsCheck && ($boardName = $db->query_result($rsCheck, 0, "boardname"))) {
            if ($boardName == "Default") {
                $sql = "SELECT vtiger_links.*, vtiger_module_customdashboarddashboard_widgets.userid, vtiger_module_customdashboarddashboard_widgets.filterid, vtiger_module_customdashboarddashboard_widgets.data, vtiger_module_customdashboarddashboard_widgets.id as widgetid, vtiger_module_customdashboarddashboard_widgets.position as position,vtiger_module_customdashboarddashboard_widgets.sizeWidth,\n\tvtiger_module_customdashboarddashboard_widgets.sizeHeight, vtiger_links.linkid as id, vtiger_module_customdashboarddashboard_widgets.min_height, vtiger_module_customdashboarddashboard_widgets.max_height,vtiger_module_customdashboarddashboard_widgets.widthPx FROM vtiger_links " . " INNER JOIN vtiger_module_customdashboarddashboard_widgets ON vtiger_links.linkid=vtiger_module_customdashboarddashboard_widgets.linkid" . " WHERE vtiger_module_customdashboarddashboard_widgets.userid = ? AND linktype = ? AND (linklabel = 'Mini List CustomDashboards' OR linklabel = 'Key Metrics' OR linklabel = 'History' OR linklabel = 'Gauge') AND tabid = ?";
                $params = array($currentUser->getId(), "DASHBOARDWIDGET", $moduleModel->getId());
            } else {
                $sql = "SELECT vtiger_links.*, vtiger_module_customdashboarddashboard_widgets.userid, vtiger_module_customdashboarddashboard_widgets.filterid, vtiger_module_customdashboarddashboard_widgets.data, vtiger_module_customdashboarddashboard_widgets.id as widgetid, vtiger_module_customdashboarddashboard_widgets.position as position,vtiger_module_customdashboarddashboard_widgets.sizeWidth,\n\tvtiger_module_customdashboarddashboard_widgets.sizeHeight, vtiger_links.linkid as id, vtiger_module_customdashboarddashboard_widgets.min_height, vtiger_module_customdashboarddashboard_widgets.max_height,vtiger_module_customdashboarddashboard_widgets.widthPx FROM vtiger_links " . " INNER JOIN vtiger_module_customdashboarddashboard_widgets ON vtiger_links.linkid=vtiger_module_customdashboarddashboard_widgets.linkid" . " WHERE linktype = ? AND (linklabel = 'Mini List CustomDashboards' OR linklabel = 'Key Metrics' OR linklabel = 'History' OR linklabel = 'Gauge') AND tabid = ?";
                $params = array("DASHBOARDWIDGET", $moduleModel->getId());
            }
            if ($this->get("tabid")) {
                $sql .= " AND dashboardtabid = ?";
                array_push($params, $this->get("tabid"));
            }
            $result = $db->pquery($sql, $params);
            $widgets = array();
            $i = 0;
            for ($len = $db->num_rows($result); $i < $len; $i++) {
                $row = $db->query_result_rowdata($result, $i);
                $data = json_decode(decode_html($row["data"]), true);
                $sourceModule = $data["module"];
                if (!empty($sourceModule) && !vtlib_isModuleActive($sourceModule) && $row["linklabel"] == "Mini List CustomDashboards") {
                    continue;
                }
                $row["linkid"] = $row["id"];
                if ($this->checkModulePermission($row)) {
                    $moduleData = json_decode(html_entity_decode($row["data"]))->module;
                    if ($row["linklabel"] == "History" || $row["linklabel"] == "Gauge" || $row["linklabel"] == "Key Metrics") {
                        $moduleData = $this->getModuleName();
                    }
                    $filters = CustomView_Record_Model::getAll($moduleData);
                    $filtersId = array();
                    foreach ($filters as $key => $filter) {
                        $filtersId[] = $filter->get("cvid");
                    }
                    if (in_array($row["filterid"], $filtersId) || !$row["filterid"]) {
                        $widgets[] = CustomDashboards_Widget_Model::getInstanceFromValues($row);
                    }
                }
            }
            foreach ($widgets as $index => $widget) {
                $label = $widget->get("linklabel");
                if ($label == "Tag Cloud") {
                    $isTagCloudExists = getTagCloudView($currentUser->getId());
                    if ($isTagCloudExists === "false") {
                        unset($widgets[$index]);
                    }
                }
            }
            $sql = "SELECT vtiger_module_customdashboarddashboard_widgets.reportid,vtiger_customdashboard.reporttype FROM vtiger_module_customdashboarddashboard_widgets \n                INNER JOIN vtiger_customdashboard ON vtiger_module_customdashboarddashboard_widgets.reportid = vtiger_customdashboard.reportid \n                WHERE  dashboardtabid= ? AND vtiger_customdashboard.reportid IS NOT NULL";
            $params = array($this->get("tabid"));
            if ($this->get("tabid")) {
                $sql .= " AND dashboardtabid = ?";
                array_push($params, $this->get("tabid"));
            }
            $result = $db->pquery($sql, $params);
            $i = 0;
            for ($len = $db->num_rows($result); $i < $len; $i++) {
                $row = $db->query_result_rowdata($result, $i);
                $chartReportModel = CustomDashboards_Record_Model::getInstanceById($row["reportid"]);
                $reportType = $row["reporttype"];
                if ($moduleDashboard == "CustomDashboards" || $moduleDashboard == $chartReportModel->getPrimaryModule()) {
                    $tabId = getTabid($chartReportModel->getPrimaryModule());
                    if ($reportType == "sql") {
                        $reportModel = CustomDashboards_Record_Model::getCleanInstance($row["reportid"]);
                        $currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
                        $owner = $reportModel->get("owner");
                        $this->ownerReport = $owner;
                        $sharingType = $reportModel->get("sharingtype");
                        if ($currentUserPriviligesModel->id != $owner && $sharingType == "Private") {
                            if ($reportModel->isRecordHasViewAccess($sharingType)) {
                                $widgets[] = CustomDashboards_Widget_Model::getInstanceWithReportId($row["reportid"], $this->get("tabid"));
                            }
                        } else {
                            $widgets[] = CustomDashboards_Widget_Model::getInstanceWithReportId($row["reportid"], $this->get("tabid"));
                        }
                    } else {
                        if ($tabId && $currentUserPrivilegeModel->hasModulePermission($tabId)) {
                            $widgets[] = CustomDashboards_Widget_Model::getInstanceWithReportId($row["reportid"], $this->get("tabid"));
                        }
                    }
                }
            }
            return $widgets;
        } else {
            return false;
        }
    }
    public function loadDefaultBoard()
    {
        global $adb;
        global $current_user;
        $userId = $current_user->id;
        $roleId = $current_user->roleid;
        $user_role = fetchUserRole($userId);
        $subRoles = getRoleSubordinates($user_role);
        $group = getCurrentUserGroupList();
        $query = "SELECT vtiger_customdashboarddashboard_tabs.id AS tabid, vtiger_customdashboarddashboard_tabs.boardid FROM vtiger_customdashboarddashboard_tabs\n                                    INNER JOIN vtiger_customdashboarddashboard_boards ON vtiger_customdashboarddashboard_tabs.boardid = vtiger_customdashboarddashboard_boards.id\n                                    WHERE vtiger_customdashboarddashboard_boards.shared_to like '%Default%' AND \n                                    (vtiger_customdashboarddashboard_boards.shared_to LIKE '%Users:" . $userId . "%' OR \n                                     vtiger_customdashboarddashboard_boards.shared_to LIKE '%Roles:" . $roleId . "%' ";
        $tempQuery1 = "";
        foreach ($subRoles as $subRolesId) {
            $tempQuery1 .= " vtiger_customdashboarddashboard_boards.shared_to LIKE '%RoleAndSubordinates:" . $subRolesId . "%' OR";
        }
        if ($tempQuery1 != "") {
            $query .= "OR " . trim($tempQuery1, "OR");
        }
        $tempQuery2 = "";
        foreach ($group as $groupId) {
            $tempQuery2 .= " vtiger_customdashboarddashboard_boards.shared_to LIKE '%Groups:" . $groupId . "%' OR";
        }
        if ($tempQuery2 != "") {
            $query .= "OR " . trim($tempQuery2, "OR");
        }
        $query .= ") ORDER BY vtiger_customdashboarddashboard_tabs.sequence ASC";
        $result = $adb->pquery($query, array());
        $boardId = $adb->query_result($result, 0, "boardid");
        $tabId = $adb->query_result($result, 0, "tabid");
        $data = array("boardid" => $boardId, "tabid" => $tabId);
        return $data;
    }
    public function getActiveTabs($boardId = 0)
    {
        global $default_charset;
        $currentUserPrivilagesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $appTabs = array("MARKETING", "SALES", "INVENTORY", "SUPPORT", "PROJECT");
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $params = array();
        $params[] = $currentUser->getId();
        if ($boardId === false || $boardId === 0) {
            $query = "SELECT id,tabname,sequence,isdefault,appname,modulename FROM vtiger_customdashboarddashboard_tabs WHERE userid=? OR userid = 0 ORDER BY sequence ASC ";
        } else {
            $query = "SELECT tabs.id,tabname,sequence,isdefault,appname,modulename FROM vtiger_customdashboarddashboard_tabs tabs INNER JOIN vtiger_customdashboarddashboard_boards boards on tabs.boardid = boards.id WHERE (boards.shared_to is not null OR tabs.userid = 0 OR tabs.userid = ? ) AND boardid = ? ORDER BY sequence ASC ";
            array_push($params, $boardId);
        }
        $result = $db->pquery($query, $params);
        $tabs = array();
        $num_rows = $db->num_rows($result);
        for ($i = 0; $i < $num_rows; $i++) {
            $row = $db->fetchByAssoc($result, $i);
            $tabName = html_entity_decode(trim($row["tabname"]), ENT_QUOTES, $default_charset);
            $appName = $row["appname"];
            $moduleName = $row["modulename"];
            if (in_array($tabName, $appTabs)) {
                $tabName = vtranslate("LBL_" . $tabName);
            }
            $tabs[$i] = array("id" => $row["id"], "tabname" => $tabName, "sequence" => $row["sequence"], "isdefault" => $row["isdefault"], "appname" => $row["appname"]);
        }
        return $tabs;
    }
    public function getAllBoards($mode = "", $boardId = 0)
    {
        global $default_charset;
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $userId = $currentUser->id;
        $roleId = $currentUser->roleid;
        $user_role = fetchUserRole($userId);
        $subRoles = getRoleSubordinates($user_role);
        $group = getCurrentUserGroupList();
        $tempSubRoles = array();
        foreach ($subRoles as $subRolesId) {
            $tempSubRoles[] = $subRolesId;
        }
        $tempGroup = array();
        foreach ($group as $groupId) {
            $tempGroup[] = $groupId;
        }
        if ($mode == "getAll") {
            $result = $db->pquery("select * from vtiger_customdashboarddashboard_boards ORDER BY boardname ASC");
        } else {
            $result = $db->pquery("select * from vtiger_customdashboarddashboard_boards WHERE id = ?", array($boardId));
            if ($db->num_rows($result) < 1) {
                return false;
            }
        }
        while ($result && ($data = $db->fetchByAssoc($result))) {
            $data["boardname"] = html_entity_decode(trim($data["boardname"]), ENT_QUOTES, $default_charset);
            if ($data["shared_to"] || $data["shared_to"] != 0) {
                $userId = array();
                $share_to = $data["shared_to"];
                $share_to = explode("|##|", $share_to);
                if ((in_array("ShareAll:1", $share_to) || in_array("Default", $share_to)) && $currentUser->getId() == $data["userid"]) {
                    if ($mode == "getExist") {
                        return true;
                    }
                    $instances[] = $data;
                    continue;
                }
                if ((in_array("ShareAll:1", $share_to) || in_array("Default", $share_to) && (count(array_intersect($share_to, $tempSubRoles)) == 0 || count(array_intersect($share_to, $tempGroup)) == 0)) && $currentUser->getId() != $data["userid"] && in_array("Users:" . $currentUser->getId(), $share_to)) {
                    if ($mode == "getExist") {
                        return true;
                    }
                    $data["shared"] = true;
                    $instances[] = $data;
                    continue;
                }
                foreach ($share_to as $key => $member) {
                    $member = explode(":", $member);
                    list($typeMember, $idMember) = $member;
                    if ($typeMember == "Users") {
                        $userId[] = $idMember;
                    } else {
                        if ($typeMember == "Roles") {
                            $sql = "select * from vtiger_user2role where roleid='" . $idMember . "'";
                            $rs = $db->pquery($sql);
                            while ($dataRole = $db->fetch_array($rs)) {
                                $userId[] = $dataRole["userid"];
                            }
                        } else {
                            if ($typeMember == "Groups") {
                                $sql = "select * from vtiger_users2group where groupid='" . $idMember . "'";
                                $rs = $db->pquery($sql);
                                while ($dataGroup = $db->fetch_array($rs)) {
                                    $userId[] = $dataGroup["userid"];
                                }
                            } else {
                                if ($typeMember == "RoleAndSubordinates") {
                                    $rsRoles = $db->pquery("select roleid from vtiger_role where parentrole like '%" . $idMember . "%'");
                                    while ($dataRoles = $db->fetch_array($rsRoles)) {
                                        $sql = "select * from vtiger_user2role where roleid='" . $dataRoles["roleid"] . "'";
                                        $rs = $db->pquery($sql);
                                        while ($dataRole = $db->fetch_array($rs)) {
                                            $userId[] = $dataRole["userid"];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if (0 < count($userId)) {
                    if (in_array($currentUser->getId(), $userId) && $currentUser->getId() != $data["userid"]) {
                        if ($mode == "getExist") {
                            return true;
                        }
                        $data["shared"] = true;
                        $instances[] = $data;
                    } else {
                        if ($currentUser->getId() == $data["userid"]) {
                            if ($mode == "getExist") {
                                return true;
                            }
                            $instances[] = $data;
                        } else {
                            if ($mode == "getExist") {
                                return false;
                            }
                            continue;
                        }
                    }
                }
            } else {
                if ($data["userid"] == $currentUser->getId() || $data["userid"] == 0) {
                    if ($mode == "getExist") {
                        return true;
                    }
                    $instances[] = $data;
                } else {
                    if ($mode == "getExist") {
                        return false;
                    }
                    continue;
                }
            }
        }
        if ($mode == "getExist") {
            return false;
        }
        return $instances;
    }
    public function checkBoardIsShared($boardId)
    {
        if ($boardId == 1) {
            return false;
        }
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $result = $db->pquery("SELECT * FROM vtiger_customdashboarddashboard_boards WHERE id = ? and userid = ?", array($boardId, $currentUser->getId()));
        if ($db->num_rows($result) == 0) {
            return true;
        }
        return false;
    }
    public function getBoardsByUser()
    {
        global $default_charset;
        $currentUserPrivilagesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $appTabs = array("MARKETING", "SALES", "INVENTORY", "SUPPORT", "PROJECT");
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $query = "SELECT vtiger_customdashboarddashboard_boards.* FROM vtiger_customdashboarddashboard_boards \nWHERE vtiger_customdashboarddashboard_boards.userid=? ";
        $result = $db->pquery($query, array($currentUser->getId()));
        $tabs = array();
        $num_rows = $db->num_rows($result);
        for ($i = 0; $i < $num_rows; $i++) {
            $row = $db->fetchByAssoc($result, $i);
            $boardName = html_entity_decode(trim($row["boardname"]), ENT_QUOTES, $default_charset);
            $sharedTo = $row["shared_to"];
            $moduleName = $row["modulename"];
            if (in_array($boardName, $appTabs)) {
                $boardName = vtranslate("LBL_" . $boardName);
            }
            $tabs[$i] = array("id" => $row["id"], "boardname" => $boardName, "shared_to" => $sharedTo);
        }
        return $tabs;
    }
    /**
     * To get first tab of the user
     * Purpose : If user added a widget in Vtiger6 then we need add that widget for first tab
     * @param type $userId
     * @return type
     */
    public function getUserDefaultTab($userId)
    {
        $db = PearDatabase::getInstance();
        $query = "SELECT id,tabname,sequence,isdefault FROM vtiger_customdashboarddashboard_tabs WHERE userid=? AND isdefault =?";
        $result = $db->pquery($query, array($userId, 1));
        $row = $db->fetchByAssoc($result, 0);
        $tab = array("id" => $row["id"], "tabname" => $row["tabname"], "sequence" => $row["sequence"], "isdefault" => $row["isdefault"]);
        return $tab;
    }
    public function addTab($tabName, $boardId)
    {
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $result = $db->pquery("SELECT MAX(sequence)+1 AS sequence FROM vtiger_customdashboarddashboard_tabs where boardid = ?", array($boardId));
        $sequence = $db->query_result($result, 0, "sequence");
        if (!$sequence) {
            $sequence = 1;
        }
        $query = "INSERT INTO vtiger_customdashboarddashboard_tabs(tabname, userid,sequence,boardid) VALUES(?,?,?,?)";
        $db->pquery($query, array($tabName, $currentUser->getId(), $sequence, $boardId));
        $tabData = array("tabid" => $db->getLastInsertID(), "tabname" => $tabName, "sequence" => $sequence);
        return $tabData;
    }
    public function duplicateTab($tabName, $boardId, $duplicateTabId)
    {
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $result = $db->pquery("SELECT MAX(sequence)+1 AS sequence FROM vtiger_customdashboarddashboard_tabs where boardid = ?", array($boardId));
        $sequence = $db->query_result($result, 0, "sequence");
        if (!$sequence) {
            $sequence = 1;
        }
        $query = "INSERT INTO vtiger_customdashboarddashboard_tabs(tabname, userid,sequence,boardid) VALUES(?,?,?,?)";
        $db->pquery($query, array($tabName, $currentUser->getId(), $sequence, $boardId));
        $tabData = array("tabid" => $db->getLastInsertID(), "tabname" => $tabName, "sequence" => $sequence);
        $queryDuplicate = "INSERT INTO vtiger_module_customdashboarddashboard_widgets(\n                                linkid ,\n                                userid ,\n                                filterid ,\n                                title ,\n                                `data` ,\n                                `position` ,\n                                reportid ,\n                                dashboardtabid ,\n                                sizeWidth ,\n                                sizeHeight ,\n                                refresh_time ,\n                                pick_color ,\n                                history_type ,\n                                history_type_radio) \n                            SELECT\n                                linkid ,\n                                userid ,\n                                filterid ,\n                                title ,\n                                `data` ,\n                                `position` ,\n                                reportid ,\n                                ? as 'dashboardtabid' ,\n                                sizeWidth ,\n                                sizeHeight ,\n                                refresh_time ,\n                                pick_color ,\n                                history_type ,\n                                history_type_radio \n                            FROM vtiger_module_customdashboarddashboard_widgets WHERE dashboardtabid = ?";
        $resultDuplicate = $db->pquery($queryDuplicate, array($tabData["tabid"], $duplicateTabId));
        return $tabData;
    }
    public function addBoard($boardId, $boardName, $boardSharedTo, $defaultBoard)
    {
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        if ($boardId) {
            $boardSharedTo = implode("|##|", $boardSharedTo);
            if ($defaultBoard == "on") {
                $boardSharedTo .= "|##|Default";
            }
            $query = "UPDATE vtiger_customdashboarddashboard_boards SET boardname =?, shared_to = ? WHERE id = ?";
            $db->pquery($query, array($boardName, $boardSharedTo, $boardId));
            $tabName = "Default-" . $boardName;
            $query = "UPDATE vtiger_customdashboarddashboard_tabs SET tabname =? WHERE boardid = ? AND tabname LIKE 'Default-%'";
            $db->pquery($query, array($tabName, $boardId));
            $boardName = array("boardid" => $boardId, "boardname" => $boardName);
        } else {
            $query = "INSERT INTO vtiger_customdashboarddashboard_boards(boardname,userid) VALUES (?,?)";
            $db->pquery($query, array($boardName, $currentUser->getId()));
            $boardId = $db->getLastInsertID();
            if ($boardId) {
                $tabName = "Default-" . $boardName;
                $sequence = 1;
                $query = "INSERT INTO vtiger_customdashboarddashboard_tabs(tabname, userid,sequence,boardid) VALUES(?,?,?,?)";
                $db->pquery($query, array($tabName, $currentUser->getId(), $sequence, $boardId));
            }
            $boardName = array("boardid" => $boardId, "boardname" => $boardName);
        }
        return $boardName;
    }
    public function deleteTab($tabId)
    {
        $db = PearDatabase::getInstance();
        $query = "DELETE FROM vtiger_customdashboarddashboard_tabs WHERE id=?";
        $db->pquery($query, array($tabId));
        return true;
    }
    public function deleteBoard($boardId)
    {
        $db = PearDatabase::getInstance();
        $query = "DELETE FROM vtiger_customdashboarddashboard_boards WHERE id=?";
        $db->pquery($query, array($boardId));
        return $boardId;
    }
    public function renameTab($tabId, $tabName)
    {
        $db = PearDatabase::getInstance();
        $query = "UPDATE vtiger_customdashboarddashboard_tabs SET tabname=? WHERE id=?";
        $db->pquery($query, array($tabName, $tabId));
        return true;
    }
    public function checkTabExist($tabName, $boardId, $mode = "")
    {
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        if ($mode == "check") {
            $result = $db->pquery("SELECT * FROM `vtiger_customdashboarddashboard_tabs` WHERE id = ? AND tabname = ? AND  isdefault = ?", array(1, "Default", 1));
        } else {
            $result = $db->pquery("SELECT * FROM vtiger_customdashboarddashboard_tabs WHERE tabname=? AND boardid = ? AND userid=?", array($tabName, $boardId, $currentUser->getId()));
        }
        $numRows = $db->num_rows($result);
        if (0 < $numRows) {
            return true;
        }
        return false;
    }
    public function checkBoardExist($boardName, $boardId)
    {
        $db = PearDatabase::getInstance();
        $query = "SELECT * FROM vtiger_customdashboarddashboard_boards WHERE boardname=? and id != ?";
        $result = $db->pquery($query, array($boardName, $boardId));
        $numRows = $db->num_rows($result);
        if (0 < $numRows) {
            return true;
        }
        return false;
    }
    public function addTabDefault()
    {
        global $site_URL;
        global $adb;
        $result = $adb->pquery("SELECT * FROM vtiger_customdashboarddashboard_tabs WHERE id = ?", array(1));
        if (0 < $adb->num_rows($result)) {
            $tabname = $adb->query_result($result, 0, "tabname");
            if ($tabname != "Default") {
                $adb->pquery("UPDATE vtiger_customdashboarddashboard_tabs SET tabname = ? WHERE id = ?", array("Default", 1));
            }
        } else {
            $adb->pquery("INSERT INTO `vtiger_customdashboarddashboard_tabs` (`id`,`tabname`,`isdefault`,`sequence`,`userid`,`boardid`) VALUES (?, ?, ?, ?, ?, ?)", array(1, "Default", 1, 1, 0, 1));
        }
        header("Location: " . $site_URL . "/index.php?module=CustomDashboards&view=DashBoard");
    }
    public function checkTabsLimitExceeded()
    {
        $db = PearDatabase::getInstance();
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $query = "SELECT count(*) AS count FROM vtiger_customdashboarddashboard_tabs WHERE userid=?";
        $result = $db->pquery($query, array($currentUser->getId()));
        $count = $db->query_result($result, 0, "count");
        if ($this->dashboardTabLimit <= $count) {
            return true;
        }
        return false;
    }
    public function updateTabSequence($sequence)
    {
        $db = PearDatabase::getInstance();
        $query = "UPDATE vtiger_customdashboarddashboard_tabs SET sequence = ? WHERE id=?";
        foreach ($sequence as $tabId => $seq) {
            $db->pquery($query, array($seq, $tabId));
        }
        return true;
    }
    public function getTabInfo($tabId)
    {
        $db = PearDatabase::getInstance();
        $query = "SELECT * FROM vtiger_customdashboarddashboard_tabs WHERE id=? ";
        $params = array($tabId);
        $result = $db->pquery($query, $params);
        if ($db->num_rows($result) <= 0) {
            return false;
        }
        return $db->fetchByAssoc($result, 0);
    }
    /**
     * Function to get the default widgets(Deprecated)
     * @return <Array of CustomDashboards_Widget_Model>
     */
    public function getDefaultWidgets()
    {
        $moduleModel = $this->getModule();
        $widgets = array();
        return $widgets;
    }
    public static function getInstance($moduleName)
    {
        $modelClassName = Vtiger_Loader::getComponentClassName("Model", "DashBoard", $moduleName);
        $instance = new $modelClassName();
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        return $instance->setModule($moduleModel);
    }
    /**
     * Function to get the module and check if the module has permission from the query data
     * @param <array> $resultData - Result Data From Query
     * @return <boolean>
     */
    public function checkModulePermission($resultData)
    {
        $result = false;
        $currentUserPrivilegeModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $linkUrl = $resultData["linkurl"];
        $linkLabel = $resultData["linklabel"];
        $filterId = $resultData["filterid"];
        $data = decode_html($resultData["data"]);
        $module = $this->getModuleNameFromLink($linkUrl, $linkLabel);
        $reportId = $this->getReportIdFromLink($linkUrl);
        if ($module == "Home" && !empty($filterId) && !empty($data)) {
            $filterData = Zend_Json::decode($data);
            $module = $filterData["module"];
        }
        if ($currentUserPrivilegeModel->hasModulePermission(getTabid($module)) && !Vtiger_Runtime::isRestricted("modules", $module)) {
            if (!$reportId) {
                $result = true;
            } else {
                $reportModel = CustomDashboards_Record_Model::getCleanInstance($reportId);
                $currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
                $owner = $reportModel->get("owner");
                $this->ownerReport = $owner;
                $sharingType = $reportModel->get("sharingtype");
                $isRecordShared = true;
                if ($currentUserPriviligesModel->id != $owner && $sharingType == "Private") {
                    $isRecordShared = $reportModel->isRecordHasViewAccess($sharingType);
                }
                $result = $isRecordShared;
            }
        }
        return $result;
    }
    public function getAccountDynamicFilter($tabid)
    {
        global $adb;
        $query = $adb->pquery("SELECT `dynamic_filter_account` FROM `vtiger_customdashboarddashboard_tabs` WHERE id = ? LIMIT 1", array($tabid));
        $accountId = $adb->query_result($query, 0, "dynamic_filter_account");
        return $accountId;
    }
    /**
     * Function to get the module name of a widget using linkurl
     * @param <string> $linkUrl
     * @param <string> $linkLabel
     * @return <string> $module - Module Name
     */
    public function getModuleNameFromLink($linkUrl, $linkLabel)
    {
        $urlParts = parse_url($linkUrl);
        parse_str($urlParts["query"], $params);
        $module = $params["module"];
        if ($linkLabel == "Overdue Activities" || $linkLabel == "Upcoming Activities") {
            $module = "Calendar";
        }
        return $module;
    }
    public function getReportIdFromLink($linkUrl)
    {
        preg_match("/reportid=\\w+/", $linkUrl, $matchs);
        $result = preg_replace("/reportid=/", "", $matchs[0]);
        if (count($matchs) == 0) {
            return false;
        }
        return $result;
    }
    public function findMissingLink($mode = "")
    {
        global $adb;
        $moduleInstance = Vtiger_Module_Model::getInstance("CustomDashboards");
        $errorFromLink1 = $adb->pquery("SELECT vtiger_links.* FROM vtiger_links LEFT JOIN \n    vtiger_customdashboard ON vtiger_links.linklabel = vtiger_customdashboard.reportname WHERE vtiger_links.tabid = ?\n    AND linktype = ? AND linklabel NOT IN ('Mini List CustomDashboards','Key Metrics','History','Gauge')", array($moduleInstance->id, "DASHBOARDWIDGET"));
        while ($result = $adb->fetch_array($errorFromLink1)) {
            if ($result["linklabel"] != NULL) {
                $errorLink1[$result["linkid"]] = $result["linklabel"];
                $rawValue1[$result["linkid"]] = $result;
            }
        }
        $errorFromLink2 = $adb->pquery("SELECT vtiger_customdashboard.* FROM vtiger_customdashboard\n\tRIGHT JOIN vtiger_links ON vtiger_links.linklabel = vtiger_customdashboard.reportname\n\tWHERE vtiger_links.tabid = ? AND linktype = ?\n\tAND linklabel NOT IN ('Mini List CustomDashboards','Key Metrics','History','Gauge')", array($moduleInstance->id, "DASHBOARDWIDGET"));
        while ($result = $adb->fetch_array($errorFromLink2)) {
            if ($result["reportname"] != NULL) {
                $errorLink2[$result["reportid"]] = $result["reportname"];
                $rawValue2[$result["reportid"]] = $result;
            }
        }
        $diff = array_diff($errorLink1, $errorLink2);
        foreach ($diff as $errorId => $errorName) {
            if ($rawValue1[$errorId]) {
                $rawValue[$errorId] = $rawValue1[$errorId];
                $rawValue[$errorId]["query"] = $adb->convert2Sql("DELETE FROM vtiger_links WHERE tabid = ? AND linktype = ? AND linklabel = ?", array($moduleInstance->id, "DASHBOARDWIDGET", $errorName));
                if ($mode == "fix") {
                    $adb->pquery($rawValue[$errorId]["query"], array());
                }
            } else {
                if ($rawValue2[$errorId]) {
                    $rawValue[$errorId] = $rawValue2[$errorId];
                    $rawValue[$errorId]["query"] = $adb->convert2Sql("DELETE FROM vtiger_links WHERE tabid = ? AND linktype = ? AND linklabel = ?", array($moduleInstance->id, "DASHBOARDWIDGET", $errorName));
                    if ($mode == "fix") {
                        $adb->pquery($rawValue[$errorId]["query"], array());
                    }
                }
            }
        }
        if ($mode == "find") {
            return array("diff" => array_diff($errorLink1, $errorLink2), "rawValue" => $rawValue);
        }
        if ($mode == "fix") {
            return count(array_diff($errorLink1, $errorLink2));
        }
        return true;
    }
    public function findMissingWidget($mode)
    {
        global $adb;
        $errorFromWidget = $adb->pquery("SELECT vtiger_links.linkid,vtiger_module_customdashboarddashboard_widgets.reportid,\n\t              vtiger_links.linkurl,vtiger_module_customdashboarddashboard_widgets.title FROM vtiger_module_customdashboarddashboard_widgets \n                  INNER JOIN vtiger_links ON vtiger_module_customdashboarddashboard_widgets.linkid = vtiger_links.linkid AND vtiger_links.linklabel \n                  NOT IN ('Mini List CustomDashboards','Key Metrics','History','Gauge')", array());
        while ($result = $adb->fetch_array($errorFromWidget)) {
            $reportTitle = $result["title"];
            preg_match("/.+ReportWidget/", $reportTitle, $matches);
            $typeWidget = $matches[0];
            if (!$typeWidget) {
                $errorWidgetLink[$result["reportid"]]["title"] = $result["title"];
                $errorWidgetLink[$result["reportid"]]["linkurl"] = html_entity_decode($result["linkurl"]);
                $errorWidgetLink[$result["reportid"]]["linkid"] = $result["linkid"];
                $tempVal1 = explode("&", html_entity_decode($result["linkurl"]));
                $tempVal1 = $tempVal1[1];
                $tempVal2 = explode("=", $tempVal1);
                $tempVal2 = $tempVal2[1];
                $typeWidget = str_replace("Actions", "ReportWidget", $tempVal2) . "_" . $result["reportid"];
                $errorWidgetLink[$result["reportid"]]["query"] = $adb->convert2Sql("UPDATE vtiger_module_customdashboarddashboard_widgets SET title = ? WHERE linkid = ?", array($typeWidget, $result["linkid"]));
                if ($mode == "fix") {
                    $adb->pquery($errorWidgetLink[$result["reportid"]]["query"], array());
                }
            }
        }
        if ($mode == "find") {
            return $errorWidgetLink;
        }
        if ($mode == "fix") {
            return count($errorWidgetLink);
        }
        return true;
    }
    public function findEmptyLink($mode)
    {
        global $adb;
        $moduleInstance = Vtiger_Module_Model::getInstance("CustomDashboards");
        $emptyLinkresult = $adb->pquery("SELECT * FROM vtiger_links WHERE tabid = ? AND linktype = ? AND linklabel = ''", array($moduleInstance->id, "DASHBOARDWIDGET"));
        while ($result = $adb->fetch_array($emptyLinkresult)) {
            $emptyLink[] = $result;
        }
        if ($mode == "find") {
            return array("emptyLink" => $emptyLink, "query" => $adb->convert2Sql("DELETE FROM vtiger_links WHERE tabid = ? AND linktype = ? AND linklabel = ''", array($moduleInstance->id, "DASHBOARDWIDGET")));
        }
        if ($mode == "fix") {
            $adb->pquery("DELETE FROM vtiger_links WHERE tabid = ? AND linktype = ? AND linklabel = ''", array($moduleInstance->id, "DASHBOARDWIDGET"));
            return count($emptyLink);
        }
        return true;
    }
    public function findDefaultTab($mode)
    {
        $checkTabExits = $this->checkTabExist(0, 0, "check");
        if ($mode == "find") {
            return $checkTabExits;
        }
        if ($mode == "fix") {
            if (!$checkTabExits) {
                $this->addTabDefault();
            }
        } else {
            return true;
        }
    }
}

?>