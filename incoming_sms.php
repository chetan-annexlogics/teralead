<?php
chdir(dirname(__FILE__));
//ini_set('display_errors','on'); error_reporting(E_ALL);
$json = file_get_contents('php://input');
$action = json_decode($json, true);

$logFile = fopen(dirname(__FILE__)."/action_incomming.log", 'a');
fwrite($logFile, print_r($action, true));
fwrite($logFile, "\n");
fclose($logFile);
