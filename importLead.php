<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
chdir(dirname(__FILE__));
require_once('include/utils/utils.php');
require_once('includes/runtime/BaseModel.php');
require_once('includes/Loader.php');
require_once('data/CRMEntity.php');
$assignedTo = 57;

$json = file_get_contents('php://input');
$leadInfo = json_decode($json, true);
if(count($leadInfo)  > 0){
    $module = $leadInfo['module'];
    $new_focus = CRMEntity::getInstance($module);
    $new_focus->column_fields['assigned_user_id'] = $assignedTo;
    $new_focus->column_fields['smcreatorid'] = $assignedTo;
    $new_focus->column_fields['modifiedby'] = $assignedTo;
    $new_focus->column_fields['createdtime'] = date("Y-m-d H:i:s");
    foreach ($leadInfo as $key => $value){
        if($key !== "module") $new_focus->column_fields[$key] = $value;
    }
    $new_focus->save($module);
}

?>