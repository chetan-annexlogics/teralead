<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

/**
 * Roles Record Model Class
 */
class CustomDashboards_Member_Model extends Vtiger_Base_Model
{
    public static $groupTables = NULL;
    public static $cvTables = NULL;
    public static $reportTables = NULL;
    const MEMBER_TYPE_USERS = "Users";
    const MEMBER_TYPE_GROUPS = "Groups";
    const MEMBER_TYPE_ROLES = "Roles";
    const MEMBER_TYPE_ROLE_AND_SUBORDINATES = "RoleAndSubordinates";
    const GROUP_MODE = "GROUPS";
    const CUSTOM_VIEW_MODE = "CV";
    const REPORTS_VIEW_MODE = "RP";
    /**
     * Function to get the Qualified Id of the Group Member
     * @return <Number> Id
     */
    public function getId()
    {
        return $this->get("id");
    }
    public function getIdComponents()
    {
        return explode(":", ${$this}->getId());
    }
    public function getMemberType()
    {
        $idComponents = $this->getIdComponents();
        if ($idComponents && 0 < count($idComponents)) {
            return $idComponents[0];
        }
        return false;
    }
    public function getMemberId()
    {
        $idComponents = $this->getIdComponents();
        if ($idComponents && 1 < count($idComponents)) {
            return $idComponents[1];
        }
        return false;
    }
    /**
     * Function to get the Group Name
     * @return <String>
     */
    public function getName()
    {
        return $this->get("name");
    }
    /**
     * Function to get the Group Name
     * @return <String>
     */
    public function getQualifiedName()
    {
        return $this->getMemberType() . " - " . $this->get("name");
    }
    public static function getIdComponentsFromQualifiedId($id)
    {
        return explode(":", $id);
    }
    public static function getQualifiedId($type, $id)
    {
        return $type . ":" . $id;
    }
    public static function getAllByTypeForGroup($groupModel, $type, $mode = self::GROUP_MODE)
    {
        $db = PearDatabase::getInstance();
        $members = array();
        if ($mode == self::GROUP_MODE) {
            $tables = self::$groupTables;
        } else {
            if ($mode == "CV") {
                $tables = self::$cvTables;
            } else {
                if ($mode == "RP") {
                    $tables = self::$reportTables;
                }
            }
        }
        if ($type == self::MEMBER_TYPE_USERS) {
            $tableName = 'vtiger_customdashboard_shareusers';//$tables[self::MEMBER_TYPE_USERS]["table"];
            $tableIndex = 'reportid';//$tables[self::MEMBER_TYPE_USERS]["index"];
            $refIndex = 'userid';//$tables[self::MEMBER_TYPE_USERS]["refIndex"];

            $sql = "SELECT vtiger_users.id, vtiger_users.last_name, vtiger_users.first_name FROM vtiger_users INNER JOIN " . $tableName . " ON " . $tableName . "." . $refIndex . " = vtiger_users.id WHERE " . $tableName . "." . $tableIndex . " = ?";
            $params = array($groupModel->getId());
            $result = $db->pquery($sql, $params);
            $noOfUsers = $db->num_rows($result);
            for ($i = 0; $i < $noOfUsers; $i++) {
                $row = $db->query_result_rowdata($result, $i);
                $userId = $row["id"];
                $qualifiedId = self::getQualifiedId(self::MEMBER_TYPE_USERS, $userId);
                $name = getFullNameFromArray("Users", $row);
                $member = new self();
                $members[$qualifiedId] = $member->set("id", $qualifiedId)->set("name", $name)->set("userId", $userId);
            }
        }
        if ($type == self::MEMBER_TYPE_GROUPS) {
            $tableName = 'vtiger_customdashboard_sharegroups';//$tables[self::MEMBER_TYPE_GROUPS]["table"];
            $tableIndex = 'reportid';//$tables[self::MEMBER_TYPE_GROUPS]["index"];
            $refIndex = 'groupid';//$tables[self::MEMBER_TYPE_GROUPS]["refIndex"];
            $sql = "SELECT vtiger_groups.groupid, vtiger_groups.groupname FROM vtiger_groups\n\t\t\t\t\t\t\tINNER JOIN " . $tableName . " ON " . $tableName . "." . $refIndex . " = vtiger_groups.groupid\n\t\t\t\t\t\t\tWHERE " . $tableName . "." . $tableIndex . " = ?";
            $params = array($groupModel->getId());
            $result = $db->pquery($sql, $params);
            $noOfGroups = $db->num_rows($result);
            for ($i = 0; $i < $noOfGroups; $i++) {
                $row = $db->query_result_rowdata($result, $i);
                $qualifiedId = self::getQualifiedId(self::MEMBER_TYPE_GROUPS, $row["groupid"]);
                $name = $row["groupname"];
                $member = new self();
                $members[$qualifiedId] = $member->set("id", $qualifiedId)->set("name", $name)->set("groupId", $row["groupid"]);
            }
        }
        if ($type == self::MEMBER_TYPE_ROLES) {
            $tableName = 'vtiger_customdashboard_sharerole';//$tables[self::MEMBER_TYPE_ROLES]["table"];
            $tableIndex = 'reportid';//$tables[self::MEMBER_TYPE_ROLES]["index"];
            $refIndex = 'roleid';//$tables[self::MEMBER_TYPE_ROLES]["refIndex"];
            $sql = "SELECT vtiger_role.roleid, vtiger_role.rolename FROM vtiger_role\n\t\t\t\t\t\t\tINNER JOIN " . $tableName . " ON " . $tableName . "." . $refIndex . " = vtiger_role.roleid\n\t\t\t\t\t\t\tWHERE " . $tableName . "." . $tableIndex . " = ?";
            $params = array($groupModel->getId());
            $result = $db->pquery($sql, $params);
            $noOfRoles = $db->num_rows($result);
            for ($i = 0; $i < $noOfRoles; $i++) {
                $row = $db->query_result_rowdata($result, $i);
                $qualifiedId = self::getQualifiedId(self::MEMBER_TYPE_ROLES, $row["roleid"]);
                $name = $row["rolename"];
                $member = new self();
                $members[$qualifiedId] = $member->set("id", $qualifiedId)->set("name", $name)->set("roleId", $row["roleid"]);
            }
        }
        if ($type == self::MEMBER_TYPE_ROLE_AND_SUBORDINATES) {
            $tableName = 'vtiger_customdashboard_sharers';//$tables[self::MEMBER_TYPE_ROLE_AND_SUBORDINATES]["table"];
            $tableIndex = 'reportid';//$tables[self::MEMBER_TYPE_ROLE_AND_SUBORDINATES]["index"];
            $refIndex = 'rsid';//$tables[self::MEMBER_TYPE_ROLE_AND_SUBORDINATES]["refIndex"];
            $sql = "SELECT vtiger_role.roleid, vtiger_role.rolename FROM vtiger_role\n\t\t\t\t\t\t\tINNER JOIN " . $tableName . " ON " . $tableName . "." . $refIndex . " = vtiger_role.roleid\n\t\t\t\t\t\t\tWHERE " . $tableName . "." . $tableIndex . " = ?";
            $params = array($groupModel->getId());
            $result = $db->pquery($sql, $params);
            $noOfRoles = $db->num_rows($result);
            for ($i = 0; $i < $noOfRoles; $i++) {
                $row = $db->query_result_rowdata($result, $i);
                $qualifiedId = self::getQualifiedId(self::MEMBER_TYPE_ROLE_AND_SUBORDINATES, $row["roleid"]);
                $name = $row["rolename"];
                $member = new self();
                $members[$qualifiedId] = $member->set("id", $qualifiedId)->set("name", $name)->set("roleId", $row["roleid"]);
            }
        }
        return $members;
    }
    /**
     * Function to get Detail View Url of this member
     * return <String> url
     */
    public function getDetailViewUrl()
    {
        list($type, $recordId) = self::getIdComponentsFromQualifiedId($this->getId());
        switch ($type) {
            case "Users":
                $recordModel = Users_Record_Model::getCleanInstance($type);
                $recordModel->setId($recordId);
                return $recordModel->getDetailViewUrl();
            case "RoleAndSubordinates":
            case "Roles":
                $recordModel = new Settings_Roles_Record_Model();
                $recordModel->set("roleid", $recordId);
                return $recordModel->getEditViewUrl();
            case "Groups":
                $recordModel = new Settings_Groups_Record_Model();
                $recordModel->setId($recordId);
                return $recordModel->getDetailViewUrl();
        }
    }
    /**
     * Function to get all the groups
     * @return <Array> - Array of Settings_Groups_Record_Model instances
     */
    public static function getAllByGroup($groupModel, $mode = self::GROUP_MODE)
    {
        $db = PearDatabase::getInstance();
        $members = array();
        $members[self::MEMBER_TYPE_USERS] = self::getAllByTypeForGroup($groupModel, self::MEMBER_TYPE_USERS, $mode);
        $members[self::MEMBER_TYPE_GROUPS] = self::getAllByTypeForGroup($groupModel, self::MEMBER_TYPE_GROUPS, $mode);
        $members[self::MEMBER_TYPE_ROLES] = self::getAllByTypeForGroup($groupModel, self::MEMBER_TYPE_ROLES, $mode);
        $members[self::MEMBER_TYPE_ROLE_AND_SUBORDINATES] = self::getAllByTypeForGroup($groupModel, self::MEMBER_TYPE_ROLE_AND_SUBORDINATES, $mode);
        return $members;
    }
    /**
     * Function to get all the groups
     * @return <Array> - Array of Settings_Groups_Record_Model instances
     */
    public static function getAll($onlyActive = true)
    {
        $members = array();
        $allUsers = Users_Record_Model::getAll($onlyActive);
        foreach ($allUsers as $userId => $userModel) {
            $qualifiedId = self::getQualifiedId(self::MEMBER_TYPE_USERS, $userId);
            $member = new self();
            $members[self::MEMBER_TYPE_USERS][$qualifiedId] = $member->set("id", $qualifiedId)->set("name", $userModel->getName());
        }
        $allGroups = Settings_Groups_Record_Model::getAll();
        foreach ($allGroups as $groupId => $groupModel) {
            $qualifiedId = self::getQualifiedId(self::MEMBER_TYPE_GROUPS, $groupId);
            $member = new self();
            $members[self::MEMBER_TYPE_GROUPS][$qualifiedId] = $member->set("id", $qualifiedId)->set("name", $groupModel->getName());
        }
        $allRoles = Settings_Roles_Record_Model::getAll();
        foreach ($allRoles as $roleId => $roleModel) {
            $qualifiedId = self::getQualifiedId(self::MEMBER_TYPE_ROLES, $roleId);
            $member = new self();
            $members[self::MEMBER_TYPE_ROLES][$qualifiedId] = $member->set("id", $qualifiedId)->set("name", $roleModel->getName());
            $qualifiedId = self::getQualifiedId(self::MEMBER_TYPE_ROLE_AND_SUBORDINATES, $roleId);
            $member = new self();
            $members[self::MEMBER_TYPE_ROLE_AND_SUBORDINATES][$qualifiedId] = $member->set("id", $qualifiedId)->set("name", $roleModel->getName());
        }
        return $members;
    }
}

?>