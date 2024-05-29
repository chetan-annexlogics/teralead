<?php
include_once 'include/utils/utils.php';
$adb = PearDatabase::getInstance();
$field_name = 'cf_913';
$field_name = 'cf_913';
$picklist_values = array(
    "All leads",
    "Appointment",
    "Can't reach",
    "No show",
    "Undecided",
    "Closed",
);
$picklist_values_mapping = array(
    "All Leads" =>"All leads",
    "Appointment Booked" => "Appointment",
    "Closed Leads" => "Closed",
);
$table= "vtiger_".$field_name;
$table_seq= "vtiger_".$field_name."_seq";
$sql_1 = "DELETE FROM `$table`";
$res_1 = $adb->query($sql_1);
$sql_1 = "ALTER TABLE $table AUTO_INCREMENT =1;";
$res_1 = $adb->query($sql_1);
$sequence = 0;
if(true) {
    foreach($picklist_values as $value){
        $insertNewValueQuery = "INSERT INTO `$table`(`$field_name`, `sortorderid`) VALUES (?,?)";
        $adb->pquery($insertNewValueQuery,array($value,$sequence));
        $sequence++;
    }
    $updateNewSeqQuery = "UPDATE INTO `$table_seq` set id =?";
    $adb->pquery($updateNewSeqQuery,array($sequence+1));
}

foreach($picklist_values_mapping as $old => $new){
    $updateNewValueQuery = "UPDATE `vtiger_contactscf` SET `$field_name` = ? WHERE `$field_name` = ?";
    $adb->pquery($updateNewValueQuery,array($new,$old));
}
$primary_value = serialize($picklist_values);
//Update kb settings
$module = "Contacts";
$sql = 'UPDATE vtdevkbview_setting SET primary_value = ? WHERE module = ?';
$adb->pquery($sql,array($primary_value,$module));

return true;
// vtiger create fields END