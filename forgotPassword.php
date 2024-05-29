<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ********************************************************************************** */

require_once 'includes/main/WebUI.php';
require_once 'include/utils/utils.php';
require_once 'include/utils/VtlibUtils.php';
require_once 'modules/Vtiger/helpers/ShortURL.php';
require_once 'vtlib/Vtiger/Mailer.php';

global $adb, $HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME;
$adb = PearDatabase::getInstance();

if (isset($_REQUEST['username']) && isset($_REQUEST['emailId'])) {
	$username = vtlib_purify($_REQUEST['username']);
	$result = $adb->pquery('select email1 from vtiger_users where user_name= ? ', array($username));
	if ($adb->num_rows($result) > 0) {
		$email = $adb->query_result($result, 0, 'email1');
	}

	if (vtlib_purify($_REQUEST['emailId']) == $email) {
		$time = time();
		$options = array(
			'handler_path' => 'modules/Users/handlers/ForgotPassword.php',
			'handler_class' => 'Users_ForgotPassword_Handler',
			'handler_function' => 'changePassword',
			'handler_data' => array(
				'username' => $username,
				'email' => $email,
				'time' => $time,
				'hash' => md5($username.$time)
			)
		);
		$trackURL = Vtiger_ShortURL_Helper::generateURL($options);
		$content = 'Hi '.$_REQUEST['username'].',<br><br> 
						Did you forget your password?.<br> 
						We recently received a request to change the password on your Teraleads account. If you’d like to reset your password, please click the link below. <br>
 						<a target="_blank" href='.$trackURL.'>here</a>. 
						<br><br> 
						If you didn’t make this request, you can ignore our email. Your login details won’t change unless you click the link above and set a new password.
						<br> 
						Best regards,<br>
						Teraleads Support Team
						';

		$subject = 'Teraleads: Password Reset';

		$mail = new VGSMultiSender_Mailer_Model();
		$mail->IsHTML();
		$mail->Body = $content;
		$mail->Subject = $subject;
		$mail->AddAddress($email);
		if (empty($mail->From)) {
			$mail->reconfigure($HELPDESK_SUPPORT_EMAIL_ID);
			$mail->FromName = $HELPDESK_SUPPORT_NAME;
		}; 
		$status = $mail->Send(true);
		if ($status === 1 || $status === true) {
			header('Location:  index.php?modules=Users&view=Login&mailStatus=success');
		} else {
			header('Location:  index.php?modules=Users&view=Login&error=statusError');
		}
	} else {
		header('Location:  index.php?modules=Users&view=Login&error=fpError');
	}
}
