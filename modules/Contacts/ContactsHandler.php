<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

function Contacts_sendCustomerPortalLoginDetails($entityData)
{
    $adb = PearDatabase::getInstance();
    $moduleName = $entityData->getModuleName();
    $wsId = $entityData->getId();
    $parts = explode('x', $wsId);
    $entityId = $parts[1];
    $entityDelta = new VTEntityDelta();
    $email = $entityData->get('email');

    $isEmailChanged = $entityDelta->hasChanged($moduleName, $entityId, 'email') && $email;//changed and not empty
    $isPortalEnabled = $entityData->get('portal') == 'on' || $entityData->get('portal') == '1';

    if ($isPortalEnabled) {
        //If portal enabled / disabled, then trigger following actions
        $sql = "SELECT id, user_name, user_password, isactive FROM vtiger_portalinfo WHERE id=?";
        $result = $adb->pquery($sql, array($entityId));

        $insert = true;
        $update = false;
        if ($adb->num_rows($result)) {
            $insert = false;
            $dbusername = $adb->query_result($result, 0, 'user_name');
            $isactive = $adb->query_result($result, 0, 'isactive');
            if ($email == $dbusername && $isactive == 1 && !$entityData->isNew()) {
                $update = false;
            } else if ($isPortalEnabled) {
                $sql = "UPDATE vtiger_portalinfo SET user_name=?, isactive=? WHERE id=?";
                $adb->pquery($sql, array($email, 1, $entityId));
                $update = true;
            } else {
                $sql = "UPDATE vtiger_portalinfo SET user_name=?, isactive=? WHERE id=?";
                $adb->pquery($sql, array($email, 0, $entityId));
                $update = false;
            }
        }

        //generate new password
        $password = makeRandomPassword();
        $enc_password = Vtiger_Functions::generateEncryptedPassword($password);

        //create new portal user
        $sendEmail = false;
        if ($insert) {
            $sql = "INSERT INTO vtiger_portalinfo(id,user_name,user_password,cryptmode,type,isactive) VALUES(?,?,?,?,?,?)";
            $params = array($entityId, $email, $enc_password, 'CRYPT', 'C', 1);
            $adb->pquery($sql, $params);
            $sendEmail = true;
        }

        //update existing portal user password
        if ($update && $isEmailChanged) {
            $sql = "UPDATE vtiger_portalinfo SET user_password=?, cryptmode=? WHERE id=?";
            $params = array($enc_password, 'CRYPT', $entityId);
            $adb->pquery($sql, $params);
            $sendEmail = true;
        }

        //trigger send email
        if ($sendEmail && $entityData->get('emailoptout') == 0) {
            global $current_user, $HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME;
            require_once("modules/Emails/mail.php");
            $emailData = Contacts::getPortalEmailContents($entityData, $password, 'LoginDetails');
            $subject = $emailData['subject'];
            if (empty($subject)) {
                $subject = 'Customer Portal Login Details';
            }

            $contents = $emailData['body'];
            $contents = decode_html(getMergedDescription($contents, $entityId, 'Contacts'));
            if (empty($contents)) {
                require_once 'config.inc.php';
                global $PORTAL_URL;
                $contents = 'LoginDetails';
                $contents .= "<br><br> User ID : $email";
                $contents .= "<br> Password: " . $password;
                $portalURL = vtranslate('Please ', $moduleName) . '<a href="' . $PORTAL_URL . '" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;">' . vtranslate('click here', $moduleName) . '</a>';
                $contents .= "<br>" . $portalURL;
            }
            $subject = decode_html(getMergedDescription($subject, $entityId, 'Contacts'));
            send_mail('Contacts', $email, $HELPDESK_SUPPORT_NAME, $HELPDESK_SUPPORT_EMAIL_ID, $subject, $contents, '', '', '', '', '', true);
        }
    } else {
        $sql = "UPDATE vtiger_portalinfo SET user_name=?,isactive=0 WHERE id=?";
        $adb->pquery($sql, array($email, $entityId));
    }
}

function SendEmailToChangeAppointmentStatus($entityData)
{
    global $adb, $site_URL, $current_user, $HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME;

    $contactId = $entityData->getId();
    $contactId = explode('x', $contactId);
    $contactId = $contactId[1];
    $firstName = $entityData->get('firstname');
    $lastName = $entityData->get('lastname');
    $appointmentDate = $entityData->get('cf_919');
    $appointmentTime = $entityData->get('cf_921');
    $treatment = $entityData->get('cf_853');
    $randomParam = date('Y_m_d_h_i_s');
    $urlConfirmAppointment = $site_URL . "ActionChangeAppointmentStatus.php?contact_id=" . $contactId . "&appointment_status=Confimed%20Appointment&random=" . $randomParam;
    $urlRescheduleAppointment = $site_URL . "ActionChangeAppointmentStatus.php?contact_id=" . $contactId . "&appointment_status=Reschedule%20Appointment&random=" . $randomParam;

    $emailTo = $entityData->get('email');
    $emailSubject = "Confirm your appointment";
    $emailContent = "Hey $firstName $lastName, <br><br>
	Appointment Date & Time: $appointmentDate $appointmentTime<br>
	Treatment: $treatment<br><br>
	Kindly confirm your appointment.
	<br><br>
	<html>
	<head>
		<style>
			#confirm_appointment {
				background-color: #ffa726;
				border: 1px solid #ffa726;
				color: #fff;
				border-radius: 5px;
				height: 2.5rem;
			}
			#confirm_appointment a {
				color: #fff;
				height: 100%;
				line-height: 2.5em;
				align-items: center;
				text-decoration: none;
				font-weight: bold;
				font-size: 14px;
			}

			#reschedule_appointment {
				background-color: #9a9a99;
				border: 1px solid #9a9a99;
				color: #fff;
				border-radius: 5px;
				height: 2.5rem;
				margin-left: 10px;
			}

			#reschedule_appointment a {
				color: #fff;
				height: 100%;
    			display: flex;
				line-height: 2.5em;
				text-decoration: none;
				font-weight: bold;
				font-size: 14px;
			}
		</style>
	</head>
	<body>
		<button id='confirm_appointment'><a style='display: flex; align-items: center;' href='$urlConfirmAppointment'>Confirm Appointment</a></button>
		<button id='reschedule_appointment'><a style='display: flex; align-items: center;' href='$urlRescheduleAppointment'>Reschedule Appointment</a></button>
	</body>
	</html>
	";
    $result = send_mail('Users', $emailTo, $HELPDESK_SUPPORT_NAME, $HELPDESK_SUPPORT_EMAIL_ID, $emailSubject, $emailContent, '', '', '', '', '', true);
}

function CreateChildCrm($entityData)
{
    global $adb;
    $moduleName = $entityData->getModuleName();
    $assignedTo = $entityData->get('assigned_user_id');
    $extractUserId = explode('x', $assignedTo);
    if(count($extractUserId) > 1) $userId = $extractUserId[1];
    else $userId = $assignedTo;
    $domainEnding = "teraleads.com/importLead.php";
    $userRecordModel = Users_Record_Model::getInstanceById($userId,"Users");
    $userName = $userRecordModel->get('user_name');
    $domainChildName = strtolower(preg_replace('/\s*/', '', $userName));
    //$urlDomainChildName = "https://".$userName.".".$domainEnding;
    $urlDomainChildName = "https://".$userName.".".$domainEnding;
    $recordDetails = $entityData->getData();
    $childCrmData = array('module'=>$moduleName);
    $mapping_fields_name = array(
      'cf_913' => 'cf_ct_lead_status',//Leads Status
      'cf_917' => 'cf_ct_notes',
      'cf_915' => 'cf_ct_status_of_appointment',
      'cf_927' => 'cf_ct_which_arches',
      'cf_929' => 'cf_ct_payment_received',
      'cf_878' => 'cf_ct_form_status',
      'cf_931' => 'cf_ct_location',
      'cf_919' => 'cf_ct_appointment_date',
      'cf_921' => 'cf_ct_appointment_time',
      'cf_925' => 'cf_pbx_lead_status',//for Call Lead
      'cf_901' => 'cf_pbx_notes',//for Call Lead
      'cf_903' => 'cf_pbx_appointment_date',//for Call Lead
      'cf_905' => 'cf_pbx_appointment_time',//for Call Lead
      'cf_907' => 'cf_pbx_status_of_appointment',//for Call Lead
      'cf_909' => 'cf_pbx_which_arches',//for Call Lead
      'cf_923' => 'cf_pbx_payment_received',//for Call Lead
    );
    $ignoreField = array("record_id","id");
    foreach ($recordDetails as $name => $value){
        echo $name."=>".$value."<br />";
        if (!in_array($name, $ignoreField) && strpos($name, '_no') === false) {
            if (array_key_exists($name, $mapping_fields_name)) {
                $childCrmData[$mapping_fields_name[$name]] = $value;
            }
            else $childCrmData[$name] = $value;
        }
    }
    $childCrmData['assigned_user_id'] = 57;
    createChildCrmRecord($urlDomainChildName,$childCrmData);
//    $logFile = fopen(dirname(__FILE__)."/childCreate2.log", 'a');
//    fwrite($logFile, print_r($urlDomainChildName, true));
//    fwrite($logFile, "\n");
//    fwrite($logFile, print_r($childCrmData, true));
//    fwrite($logFile, "\n");
//    fclose($logFile);
    //Update
    //Count all call lead for this user first
    if($moduleName == "Contacts"){
        $formLeadField = 'cf_933';
        $query = "SELECT
                    COUNT( * ) as _count
                FROM
                    vtiger_contactdetails c
                    INNER JOIN vtiger_crmentity e ON c.contactid = e.crmid 
                WHERE
                    e.smownerid = ? 
                    AND DATE( e.createdtime ) = CURDATE()";
        $result = $adb->pquery($query, array($userId));
        $count = $adb ->query_result($result,0,'_count');
        $query = "UPDATE vtiger_crmmanagercf SET ".$formLeadField." = ? WHERE cmmanager_cf_945 like '%".$domainChildName."%'";
        $result = $adb->pquery($query, array($count));
    }
    else if($moduleName == "PBXManager"){
        $callLeadField = 'cf_935';
        $query = "SELECT
                    COUNT( * ) as _count
                FROM
                    vtiger_pbxmanager p
	                INNER JOIN vtiger_crmentity e ON p.pbxmanagerid = e.crmid
                WHERE
                    e.smownerid = ?
                    AND DATE( e.createdtime ) = CURDATE()";
        $result = $adb->pquery($query, array($userId));
        $count = $adb ->query_result($result,0,'_count');
        $query = "UPDATE vtiger_crmmanagercf SET ".$callLeadField." = ? WHERE cmmanager_cf_945 like '%".$domainChildName."%'";
        $result = $adb->pquery($query, array($count));
    }
    //End Update count for callLeadField

}

function createChildCrmRecord($url,$crmData)
{
    // Create a new cURL resource
        $ch = curl_init($url);
        $payload = json_encode($crmData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
}

?>
