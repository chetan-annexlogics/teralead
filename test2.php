<?php
$json = file_get_contents('php://input');
$action = json_decode($json, true);
echo file_put_contents('./logsCall2.txt', var_dump($action) , FILE_APPEND);