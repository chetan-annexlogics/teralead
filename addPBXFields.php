<?php
require_once("vtlib/Vtiger/Module.php");
require_once("vtlib/Vtiger/Block.php");
require_once("vtlib/Vtiger/Field.php");
$blocks = array("Custom Information");
$fields = array(
    "Custom Information" => array(
		"callid" => array("label" => "Call Id", "uitype" => 1),
		"callsessionid" => array("label" => "Call Session Id", "uitype" => 1)
    ),
);
$table= "vtiger_pbxmanager";
$vmodule= Vtiger_Module::getInstance('PBXManager');
if($vmodule){
    foreach($blocks as $blcks){
        $block= Vtiger_Block::getInstance($blcks, $vmodule);
        if(!$block&& $blcks){
            $block= new Vtiger_Block();
            $block->label= $blcks;
            $block->__create($vmodule);
        }
        # else $block->__delete(true);
		$adb = PearDatabase::getInstance();
		$sql_1 = "SELECT sequence FROM `vtiger_field` WHERE block = '".$block->id."' ORDER BY sequence DESC LIMIT 0,1";
		$res_1 = $adb->query($sql_1);
		$sequence = 0;
		if($adb->num_rows($res_1)) {
			$sequence = $adb->query_result($res_1,'sequence',0);
		}
        foreach($fields[$blcks] as $name=> $a_field){
            $field= Vtiger_Field::getInstance($name, $vmodule);
			//remove if existed first
			if($field)  $field->__delete(true);
            if(!$field && $name && $table){
                $sequence++;
                $field= new Vtiger_Field();
                $field->name= $name;
                $field->label= $a_field['label'];
                $field->table= $table;
                $field->uitype= $a_field['uitype'];
                $field->sequence= $sequence;
                $field->__create($block);
                echo "Field ".$name." is created<br />";
            }
            else {
                $field->__delete(true);
                echo "Field ".$name." is removed<br />";
            }
        }
    }
}
// vtiger create fields END