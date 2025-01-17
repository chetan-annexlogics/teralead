<?php
/* ********************************************************************************
 * The content of this file is subject to the Custom Header/Bills ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VtigerDev.com
 * Portions created by VtigerDev.com. are Copyright(C) VtigerDev.com.
 * All Rights Reserved.
 * ****************************************************************************** */
require_once realpath(dirname(__FILE__) . '/../vendor/autoload.php');
use Lazzard\FtpClient\Connection\FtpSSLConnection;
use Lazzard\FtpClient\Config\FtpConfig;
use Lazzard\FtpClient\FtpClient;
use Lazzard\FtpClient\FtpWrapper;
include_once "modules/Contacts/ContactsHandler.php";
class CRMManager_ActionAjax_Action extends Vtiger_Action_Controller
{

    function checkPermission(Vtiger_Request $request)
    {
        return true;
    }

    function __construct()
    {
        parent::__construct();
        $this->exposeMethod('managerChildCrm');
        $this->exposeMethod('updateChildCrm');
        $this->exposeMethod('pushDataToChildCrm');
    }

    function process(Vtiger_Request $request)
    {
        $mode = $request->get('mode');
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
            return;
        }
    }

    public function managerChildCrm(Vtiger_Request $request)
    {
        global $core_crm_file_path;
        $response = new Vtiger_Response();
        $recordId = $request->get('recordId');
        $moduleName = $request->get('module');
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleModel);
        $host = $recordModel->get('cmmanager_cf_951');
        $user = $recordModel->get('cmmanager_cf_955');
        $pass = $recordModel->get('cmmanager_cf_957');
        $remotePath = $recordModel->get('cmmanager_cf_953');
        $vtigerCrm = $recordModel->get('cmmanager_cf_945');
        $extractScriptFile = "extract.php";
        try {
            if (!extension_loaded('ftp')) {
                //throw new \RuntimeException("FTP extension not loaded.");
                $result = array("success" => false, "message" => "FTP extension not loaded.");
            }
            else{
                $connection = new FtpSSLConnection($host, $user, $pass);
                $connection->open();
                $config = new FtpConfig($connection);
                //$config->setPassive(true);
                $client = new FtpClient($connection);
                // upload a remote file asynchronously
                $localZipFile = $core_crm_file_path;
                $remoteFile = $remotePath."/child_crm.zip";
                //$dir_details = $client->listDirDetails($remotePath);
                $client->upload($localZipFile, $remoteFile);
                //Upload script extract zipfile
                $localScriptFile = realpath(dirname(__FILE__) . '/../extract.php');
                $remoteScriptFile = $remotePath."/".$extractScriptFile;
                if($client ->isExists($remoteScriptFile)){
                    $client->removeFile($remoteScriptFile);
                }
                $client->upload($localScriptFile, $remoteScriptFile);
                $connection->close();
                //Unzip file
                $this->executeRemoteFile($vtigerCrm.$extractScriptFile);
                //Mark record is created
                $recordModel->set('mode','edit');
                $recordModel->set('cmmanager_cf_959',1);
                $recordModel->save();
                $result = array("success" => true, "message" => "Child CRM had been created successfully","setup_crm_path" =>$vtigerCrm . "setup.php");
            }
        } catch (Throwable $ex) {
            $response->setError($ex->getMessage());
            $result = array("success" => false, "message" => $ex->getMessage());
        }
        $response->setResult($result);
        $response->emit();
    }
    public function updateChildCrm(Vtiger_Request $request)
    {
        $response = new Vtiger_Response();
        $updateType = $request->get('type');
        if($updateType === "All"){
            $allChildCrmId = $this->getAllChilds();
            foreach ($allChildCrmId as $key => $recordId){
                $result = $this ->updateChildCrmById($recordId);
            }
            $result = array("success" => true, "message" => "All Child CRM had been updated successfully");
        }
        else{
            $recordId = $request->get('recordId');
            $result = $this ->updateChildCrmById($recordId);
        }
        $response->setResult($result);
        $response->emit();
    }
    public function pushDataToChildCrm(Vtiger_Request $request)
    {
        $response = new Vtiger_Response();
        $recordId = $request->get('recordId');
        $childList = $this->getChildLeadByCrmId($recordId);
        foreach ($childList as $key => $childCrmId){
            $recordModel = Vtiger_Record_Model::getInstanceById($childCrmId);
            if($recordModel){
                CreateChildCrm($recordModel);
            }
        }
        $result = array("success" => true, "message" => "All data transfer successful to child.");
        $response->setResult($result);
        $response->emit();
    }
    function getAllChilds(){
        global $adb;
        $return = array();
        $sql = "SELECT crmmanagerid FROM vtiger_crmmanager c 
            INNER JOIN vtiger_crmentity e ON c.crmmanagerid = e.crmid
            WHERE e.deleted = 0";
        $result = $adb ->pquery($sql,array());
        while($row = $adb->fetch_array($result))
        {
            $return[] = $row['crmmanagerid'];
        }
        return $return;
    }
    function getChildLeadByCrmId($recordId){
        global $adb;
        $return = array();
        $moduleName = "CRMManager";
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId,$moduleName);
        $crmUrl = $recordModel ->get('cmmanager_cf_945');
        //PBX
        $sql = "SELECT p.pbxmanagerid
                FROM
                    vtiger_pbxmanager p
                    INNER JOIN vtiger_crmentity c ON p.pbxmanagerid = c.crmid
                    INNER JOIN vtiger_users u ON c.smownerid = u.id 
                WHERE
                    p.callsessionid != '' AND c.deleted = 0 AND INSTR(?, user_name ) > 0";
        $result = $adb ->pquery($sql,array($crmUrl));
        while($row = $adb->fetch_array($result))
        {
            $return[] = $row['pbxmanagerid'];
        }
        //Contact
        $sql = "SELECT ct.contactid
                FROM
                    vtiger_contactdetails ct
                    INNER JOIN vtiger_crmentity c ON ct.contactid = c.crmid
                    INNER JOIN vtiger_users u ON c.smownerid = u.id 
                WHERE
                    ct.contactid != '' AND c.deleted = 0 AND INSTR(?, user_name ) > 0";
        $result = $adb ->pquery($sql,array($crmUrl));
        while($row = $adb->fetch_array($result))
        {
            $return[] = $row['contactid'];
        }
        return $return;
    }
    function executeRemoteFile($remoteUrlFile){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remoteUrlFile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
    }
    function updateChildCrmById($recordId){
        global $update_crm_file_path;
        $moduleName = "CRMManager";
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleModel);
        $host = $recordModel->get('cmmanager_cf_951');
        $user = $recordModel->get('cmmanager_cf_955');
        $pass = $recordModel->get('cmmanager_cf_957');
        $remotePath = $recordModel->get('cmmanager_cf_953');
        $vtigerCrm = $recordModel->get('cmmanager_cf_945');
        $extractScriptFile = "extract.php";
        try {
            if (!extension_loaded('ftp')) {
                //throw new \RuntimeException("FTP extension not loaded.");
                $result = array("success" => false, "message" => "FTP extension not loaded.");
            }
            else{
                $connection = new FtpSSLConnection($host, $user, $pass);
                $connection->open();
                //$config->setPassive(true);
                $client = new FtpClient($connection);
                // upload a remote file asynchronously
                $localZipFile = $update_crm_file_path;
                $remoteFile = $remotePath."/child_crm_update.zip";
                $client->upload($localZipFile, $remoteFile);
                //Upload script extract zipfile
//                $localScriptFile = realpath(dirname(__FILE__) . '/../extract.php');
//                $remoteScriptFile = $remotePath."/".$extractScriptFile;
//                if($client ->isExists($remoteScriptFile)){
//                    $client->removeFile($remoteScriptFile);
//                }
//                $client->upload($localScriptFile, $remoteScriptFile);

                //Unzip file
                $this->executeRemoteFile($vtigerCrm.$extractScriptFile."?action=update");
                //Run add script or insert db
                $addDbScriptFile = "addDb.php";
                $addDbScriptFilePath = $remotePath."/".$addDbScriptFile;
                $this->executeRemoteFile($vtigerCrm.$addDbScriptFile);
                $client->removeFile($addDbScriptFilePath);
                //Mark record is created
                $connection->close();
                $result = array("success" => true, "message" => "Child CRM had been updated successfully");
            }
        } catch (Throwable $ex) {
            //$response->setError($ex->getMessage());
            $result = array("success" => false, "message" => $ex->getMessage());
        }
        return $result;
    }
//End class
}