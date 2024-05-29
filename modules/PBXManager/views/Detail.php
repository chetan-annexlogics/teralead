<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class PBXManager_Detail_View extends Vtiger_Detail_View{
    
    /**
     * Overrided to disable Ajax Edit option in Detail View of
     * PBXManager Record
     */
    function isAjaxEnabled($recordModel) {
		return true;
	}
 
    /*
     * Overided to convert totalduration to minutes
     */
    function preProcess(Vtiger_Request $request, $display=true) {
		$recordId = $request->get('record');
        $downloadCall = $request->get('downloadCall');
		$moduleName = $request->getModule();
		if(!$this->record){
			$this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
		}
        if(!empty($downloadCall)){
            $url = $this->downloadCallRecord($recordId);
        }
		$recordModel = $this->record->getRecord();
     
       // To show recording link only if callstatus is 'completed' 
        if($recordModel->get('callstatus') == 'ANSWERED' || $recordModel->get('callstatus') == 'VOICE RECORD') { 
            $url = $this->downloadCallRecord($recordId);
            // $audio = str_replace('"', '', html_entity_decode($recordModel->get('recordingurl')));
            $html = "<div><audio controls style='width:250px;height:30px; position:relative;right:50px'><source src='".$url."' type='audio/mpeg'></audio></div>";
            $recordModel->set('recordingurl', $html); 
        } else {
            $recordModel->set('recordingurl', '');
        }
        return parent::preProcess($request, true);
	}

    function downloadCallRecord($recordId){
        global $adb;
        $result = $adb->query("SELECT * FROM vtiger_pbxmanager WHERE pbxmanagerid = '$recordId'");
        $callId=$adb->query_result($result,0,'callid');
        $callsessionid=$adb->query_result($result,0,'callsessionid');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.telnyx.com/v2/recordings?v3='.$callId.'&call_session_id='.$callsessionid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
            //'Authorization: Bearer KEY0186D0AFB8CAF57FC9862F53A5C7D3A9_QMcJAQ9vmS8d0JmfmWiMAf'
            'Authorization: Bearer KEY018CAECAB5F04243A5231D401EB0C956_Po86er3MHN4sW4H16haOMN'
            ),
        ));

        $response = curl_exec($curl);
        $responseData = json_decode($response, true); 
        curl_close($curl);
        return $responseData['data'][0]['download_urls']['mp3'];
        // $file_content = file_get_contents($responseData['data'][0]['download_urls']['mp3']);
        // $filename = 'callrecord.mp3';
        // // Set the appropriate headers for downloading the file
        // header('Content-Type: audio/mpeg');
        // header('Content-Disposition: attachment; filename="' . $filename . '"');
        // header('Content-Length: ' . strlen($file_content));

        // // Output the file content
        // echo $file_content;
        exit;
    }
}