<?php
require_once 'modules/VGSMultiSender/models/SMTPModule.php';

/**
 * VGS Multi FROM Address Module
 *
 *
 * @package        VGSMultiSender Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */
include_once 'include/utils/encryption.php';

class VGSMultiSender_SaveSMTPConfig_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {
        return true;
    }

    public function process(Vtiger_Request $request) {
        global $current_user;
        $db = PearDatabase::getInstance();

        switch ($request->get('mode')) {
            case 'addLock':
                $id = $request->get('record_id');
                $forSave = array(
                    'locked' => $request->get('locked'),
                );
                SMTPModule::saveRecord($id, $forSave);
                $fieldsResponse['result'] = 'ok';
                $response = new Vtiger_Response();
                $response->setResult($fieldsResponse);
                $response->emit();
                break;
            case 'editRecord':
                $this->editRecord($request);
                break;
            case 'deleteRecord':
                $db->pquery("DELETE FROM vtiger_vgsmultisender WHERE id=?", array($request->get('record_id')));
                $fieldsResponse['result'] = 'ok';
                $response = new Vtiger_Response();
                $response->setResult($fieldsResponse);
                $response->emit();
                break;
            case 'getUserSMTPs';
                $result = $db->pquery("SELECT * FROM vtiger_vgsmultisender WHERE userid=?", array($current_user->id));
                if ($db->num_rows($result) > 0) {
                    while ($row = $db->fetch_array($result)) {
                        $smtps[$row["user_name"]] = $row["email_from"];
                    }
                    $fieldsResponse['result'] = 'ok';
                    $fieldsResponse['smtps'] = $smtps;
                    $response = new Vtiger_Response();
                    $response->setResult($fieldsResponse);
                    $response->emit();
                } else {
                    return false;
                }
                break;
            default:
                $encrypt = new Encryption();

                $params = Array(
                    $request->get('server_name'),
                    $request->get('user_name'),
                    $encrypt->encrypt($request->get('password')),
                    $request->get('from_name'),
                    $request->get('email_from'),
                    $request->get('smtp_auth'),
                    $request->get('user_id'),
                    $request->get('batch_count'),
                    $request->get('batch_delay'),
                );

                if($request->get('user_id') != ''){
                    $testEmail = $this->testEmailSettings($request);
                    $fieldsResponse['result'] = $testEmail;
                }  else {
                    $fieldsResponse['result'] = 'fail';
                    $fieldsResponse['message'] = 'Please choose the user name';
                }

                if ($testEmail == 'ok') {
                    $result = $db->pquery("SELECT * FROM vtiger_vgsmultisender WHERE server_name=? AND user_name=? AND password=? AND from_name=? AND email_from=? AND smtp_auth=? AND userid=? AND batch_count=? AND batch_delay=?", $params);

                    if ($db->num_rows($result) > 0) {
                        $fieldsResponse['result'] = 'fail';
                        $fieldsResponse['message'] = vtranslate('ALREADY_EXISTS', 'Users');
                    } else {
                        try {
                            array_push($params, $current_user->id);
                            array_pop($params);
                            $result = $db->pquery("INSERT INTO vtiger_vgsmultisender (server_name,user_name,password,from_name, email_from,smtp_auth,userid,batch_count,batch_delay) VALUES (?,?,?,?,?,?,?,?,?)", $params);
                            if ($db->getAffectedRowCount($result) == 1) {
                                $fieldsResponse['result'] = 'ok';
                            } else {
                                $fieldsResponse['result'] = 'fail';
                                $fieldsResponse['message'] = vtranslate('LBL_DB_INSERT_FAIL', 'Users');
                            }
                        } catch (Exception $exc) {
                            $fieldsResponse['result'] = 'fail';
                            $fieldsResponse['message'] = 'fail';
                        }
                    }
                } else if($testEmail != '' && $request->get('user_id') != '') {
                    $fieldsResponse['result'] = 'fail';
                    $fieldsResponse['message'] = $testEmail;
                }

                $response = new Vtiger_Response();
                $response->setResult($fieldsResponse);
                $response->emit();
                break;
        }
    }

    function testEmailSettings(Vtiger_Request $request) {
        $currentUser = Users_Record_Model::getCurrentUserModel();

        $to_email = $this->getUserEmailId('id', $currentUser->getId());

        $subject = vtranslate('Test email subject', 'VGSMultiSender');
        $description = vtranslate('Test email body. Use language file for translations', 'VGSMultiSender');

        $mailer = new VGSMultiSender_Mailer_Model();
        $mailer->IsHTML(true);
        $mailer->Host = $request->get('server_name');

        if(substr($mailer->Host,0,6) == 'tls://'){
            $mailer->SMTPSecure = 'tls';
            $mailer->Port = '587';
            $mailer->Host = str_replace('tls://','',$mailer->Host);
            $mailer->Host = str_replace(':587','',$mailer->Host);
        }

        $mailer->Username = decode_html($request->get('user_name'));
        $mailer->Password = $request->get('password');
        $mailer->From = $request->get('email_from');
        $mailer->AddReplyTo($request->get('email_from'));
        $mailer->SMTPAuth = $request->get('smtp_auth');
        $mailer->FromName = decode_html($request->get('from_name'));

        if ($mailer->From == '') {
            $recordModel = new Emails_Record_Model();
            $fromEmail = $recordModel->getFromEmailAddress();
            $replyTo = $currentUser->get('email1');
            $userName = $currentUser->getName();
            $mailer->ConfigSenderInfo($fromEmail, $userName, $replyTo);
        }

        $mailer->Body = $description;

        $mailer->Subject = $subject;
        $mailer->AddAddress($to_email);
        $mailer->_serverConfigured = true;
        $status = $mailer->Send(true);


        if (!$status) {
            return $mailer->ErrorInfo;
        } else {
            return 'ok';

        }
    }

    function getUserEmailId($name, $val) {
        global $adb;
        $adb->println("Inside the function getUserEmailId. --- ".$name." = '".$val."'");
        if ($val != '') {
            //done to resolve the PHP5 specific behaviour
            $sql = "SELECT email1, email2, secondaryemail  from vtiger_users WHERE status='Active' AND ".$adb->sql_escape_string($name)." = ?";
            $res = $adb->pquery($sql, array($val));
            $email = $adb->query_result($res, 0, 'email1');
            if ($email == '') {
                $email = $adb->query_result($res, 0, 'email2');
                if ($email == '') {
                    $email = $adb->query_result($res, 0, 'secondaryemail ');
                }
            }
            $adb->println("Email id is selected  => '".$email."'");
            return $email;
        } else {
            $adb->println("User id is empty. so return value is ''");
            return '';
        }
    }

    private function editRecord(Vtiger_Request $request)
    {
        $response = new Vtiger_Response();
        $fieldsResponse = array();
        $id = $request->get('id');
        $params = Array(
            'server_name'   => $request->get('server_name'),
            'user_name'     => $request->get('user_name'),
            'from_name'     => $request->get('from_name'),
            'email_from'    => $request->get('email_from'),
            'smtp_auth'     => $request->get('smtp_auth'),
            'userid'        => $request->get('user_id'),
            'batch_count'   => $request->get('batch_count'),
            'batch_delay'   => $request->get('batch_delay'),
        );
        $password = $request->get('password');
        if (!empty($password)) {
            $encrypt = new Encryption();
            $params['password'] = $encrypt->encrypt($password);
        }
        if (!empty(SMTPModule::getRecordData($id))) {
            SMTPModule::saveRecord($id, $params);
            $fieldsResponse['result'] = 'ok';
        } else {
            $fieldsResponse['result'] = 'fail';
            $fieldsResponse['message'] = 'Record don`t exist';
        }
        $response->setResult($fieldsResponse);
        $response->emit();
        exit();
    }
}
