<?php


include_once 'modules/VGSMultiSender/models/Mailer.php';

class VGSMultiSender_MassSaveAjax_View extends Vtiger_Footer_View {

    function __construct() {
        parent::__construct();
        $this->exposeMethod('massSave');
    }

    public function checkPermission(Vtiger_Request $request) {
        $moduleName = 'Emails';

        if (!Users_Privileges_Model::isPermitted($moduleName, 'Save')) {
            throw new AppException(vtranslate($moduleName) . ' ' . vtranslate('LBL_NOT_ACCESSIBLE'));
        }
    }

    public function process(Vtiger_Request $request) {
        $mode = $request->getMode();
        if (!empty($mode)) {
            echo $this->invokeExposedMethod($mode, $request);
            return;
        }
    }

    /**
     * Function Sends/Saves mass emails
     * @param <Vtiger_Request> $request
     */
    public function massSave(Vtiger_Request $request) {
        global $upload_badext;
        $adb = PearDatabase::getInstance();

        $moduleName = 'Emails';
        $currentUserModel = Users_Record_Model::getCurrentUserModel();
        $recordIds = $this->getRecordsListFromRequest($request);
        $documentIds = $request->get('documentids');

        // This is either SENT or SAVED
        $flag = $request->get('flag');

        $result = Vtiger_Util_Helper::transformUploadedFiles($_FILES, true);
        $_FILES = $result['file'];

        $recordId = $request->get('record');

        if (!empty($recordId)) {
            $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
            $recordModel->set('mode', 'edit');
        } else {
            $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
            $recordModel->set('mode', '');
        }


        $parentEmailId = $request->get('parent_id', null);
        $attachmentsWithParentEmail = array();
        if (!empty($parentEmailId) && !empty($recordId)) {
            $parentEmailModel = Vtiger_Record_Model::getInstanceById($parentEmailId);
            $attachmentsWithParentEmail = $parentEmailModel->getAttachmentDetails();
        }
        $existingAttachments = $request->get('attachments', array());
        if (empty($recordId)) {
            if (is_array($existingAttachments)) {
                foreach ($existingAttachments as $index => $existingAttachInfo) {
                    $existingAttachInfo['tmp_name'] = $existingAttachInfo['name'];
                    $existingAttachments[$index] = $existingAttachInfo;
                    if (array_key_exists('docid', $existingAttachInfo)) {
                        $documentIds[] = $existingAttachInfo['docid'];
                        unset($existingAttachments[$index]);
                    }
                }
            }
        } else {
            //If it is edit view unset the exising attachments
            //remove the exising attachments if it is in edit view

            $attachmentsToUnlink = array();
            $documentsToUnlink = array();


            foreach ($attachmentsWithParentEmail as $i => $attachInfo) {
                $found = false;
                foreach ($existingAttachments as $index => $existingAttachInfo) {
                    if ($attachInfo['fileid'] == $existingAttachInfo['fileid']) {
                        $found = true;
                        break;
                    }
                }
                //Means attachment is deleted
                if (!$found) {
                    if (array_key_exists('docid', $attachInfo)) {
                        $documentsToUnlink[] = $attachInfo['docid'];
                    } else {
                        $attachmentsToUnlink[] = $attachInfo;
                    }
                }
                unset($attachmentsWithParentEmail[$i]);
            }
            //Make the attachments as empty for edit view since all the attachments will already be there
            $existingAttachments = array();
            if (!empty($documentsToUnlink)) {
                $recordModel->deleteDocumentLink($documentsToUnlink);
            }

            if (!empty($attachmentsToUnlink)) {
                $recordModel->deleteAttachment($attachmentsToUnlink);
            }
        }


        // This will be used for sending mails to each individual
        $toMailInfo = $request->get('toemailinfo');

        $to = $request->get('to');
        if (is_array($to)) {
            $to = implode(',', $to);
        }


        $recordModel->set('description', $request->get('description'));
        $recordModel->set('subject', $request->get('subject'));
        $recordModel->set('toMailNamesList', $request->get('toMailNamesList'));
        $recordModel->set('saved_toid', $to);
        $recordModel->set('ccmail', $request->get('cc'));
        $recordModel->set('bccmail', $request->get('bcc'));
        $recordModel->set('assigned_user_id', $currentUserModel->getId());
        $recordModel->set('email_flag', $flag);
        $recordModel->set('documentids', $documentIds);

        $recordModel->set('toemailinfo', $toMailInfo);
        foreach ($toMailInfo as $recordId => $emailValueList) {
            if ($recordModel->getEntityType($recordId) == 'Users') {
                $parentIds .= $recordId . '@-1|';
            } else {
                $parentIds .= $recordId . '@1|';
            }
        }
        $recordModel->set('parent_id', $parentIds);

        //save_module still depends on the $_REQUEST, need to clean it up
        $_REQUEST['parent_id'] = $parentIds;

        $success = false;
        $viewer = $this->getViewer($request);
        if ($recordModel->checkUploadSize($documentIds)) {
            $recordModel->save();

            //To Handle existing attachments
            $current_user = Users_Record_Model::getCurrentUserModel();
            $ownerId = $recordModel->get('assigned_user_id');
            $date_var = date("Y-m-d H:i:s");
            if (is_array($existingAttachments)) {
                foreach ($existingAttachments as $index => $existingAttachInfo) {
                    $file_name = $existingAttachInfo['attachment'];
                    $path = $existingAttachInfo['path'];
                    $fileId = $existingAttachInfo['fileid'];

                    $oldFileName = $file_name;
                    //SEND PDF mail will not be having file id
                    if (!empty($fileId)) {
                        $oldFileName = $existingAttachInfo['fileid'] . '_' . $file_name;
                    }
                    $oldFilePath = $path . '/' . $oldFileName;

                    $binFile = sanitizeUploadFileName($file_name, $upload_badext);

                    $current_id = $adb->getUniqueID("vtiger_crmentity");

                    $filename = ltrim(basename(" " . $binFile)); //allowed filename like UTF-8 characters
                    $filetype = $existingAttachInfo['type'];
                    $filesize = $existingAttachInfo['size'];

                    //get the file path inwhich folder we want to upload the file
                    $upload_file_path = decideFilePath();
                    $newFilePath = $upload_file_path . $current_id . "_" . $binFile;

                    copy($oldFilePath, $newFilePath);

                    $sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)";
                    $params1 = array($current_id, $current_user->getId(), $ownerId, $moduleName . " Attachment", $recordModel->get('description'), $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
                    $adb->pquery($sql1, $params1);

                    $sql2 = "insert into vtiger_attachments(attachmentsid, name, description, type, path) values(?, ?, ?, ?, ?)";
                    $params2 = array($current_id, $filename, $recordModel->get('description'), $filetype, $upload_file_path);
                    $result = $adb->pquery($sql2, $params2);

                    $sql3 = 'insert into vtiger_seattachmentsrel values(?,?)';
                    $adb->pquery($sql3, array($recordModel->getId(), $current_id));
                }
            }

            //Need to save in attachment rel
            
            $myids = explode("|", $parentIds);  //2@71|
            $actid = $recordModel->getId();
            for ($i = 0; $i < (count($myids) - 1); $i++) {
                $realid = explode("@", $myids[$i]);
                $mycrmid = $realid[0];
                //added to handle the relationship of emails with vtiger_users
                if ($realid[1] == -1) {
                    $del_q = 'delete from vtiger_salesmanactivityrel where smid=? and activityid=?';
                    $adb->pquery($del_q, array($mycrmid, $actid));
                    $mysql = 'insert into vtiger_salesmanactivityrel values(?,?)';
                } else {
                    $del_q = 'delete from vtiger_seactivityrel where crmid=? and activityid=?';
                    $adb->pquery($del_q, array($mycrmid, $actid));
                    $mysql = 'insert into vtiger_seactivityrel values(?,?)';
                }
                $params = array($mycrmid, $actid);
                $adb->pquery($mysql, $params);
            }

            
            $success = true;
            if ($flag == 'SENT') {
                $status = $this->send($recordModel);
                if ($status === true) {
                    // This is needed to set vtiger_email_track table as it is used in email reporting
                    $recordModel->setAccessCountValue();
                } else {
                    $success = false;
                    $message = $status;
                }
            }
        } else {
            $message = vtranslate('LBL_MAX_UPLOAD_SIZE', $moduleName) . ' ' . vtranslate('LBL_EXCEEDED', $moduleName);
        }
        $viewer->assign('SUCCESS', $success);
        $viewer->assign('MESSAGE', $message);
        $loadRelatedList = $request->get('related_load');
        if (!empty($loadRelatedList)) {
            $viewer->assign('RELATED_LOAD', true);
        }
        $viewer->view('SendEmailResult.tpl', $moduleName);
    }
    
        /**
     * Function returns the record Ids selected in the current filter
     * @param Vtiger_Request $request
     * @return integer
     */
    public function getRecordsListFromRequest(Vtiger_Request $request) {
        $cvId = $request->get('viewname');
        $selectedIds = $request->get('selected_ids');
        $excludedIds = $request->get('excluded_ids');

        if(!empty($selectedIds) && $selectedIds != 'all') {
            if(!empty($selectedIds) && count($selectedIds) > 0) {
                return $selectedIds;
            }
        }

        if($selectedIds == 'all'){
            $sourceRecord = $request->get('sourceRecord');
            $sourceModule = $request->get('sourceModule');
            if ($sourceRecord && $sourceModule) {
                $sourceRecordModel = Vtiger_Record_Model::getInstanceById($sourceRecord, $sourceModule);
                return $sourceRecordModel->getSelectedIdsList($request->get('parentModule'), $excludedIds);
            }

            $customViewModel = CustomView_Record_Model::getInstanceById($cvId);
            if($customViewModel) {
                $searchKey = $request->get('search_key');
                $searchValue = $request->get('search_value');
                $operator = $request->get('operator');
                if(!empty($operator)) {
                    $customViewModel->set('operator', $operator);
                    $customViewModel->set('search_key', $searchKey);
                    $customViewModel->set('search_value', $searchValue);
                }
                return $customViewModel->getRecordIds($excludedIds);
            }
        }
        return array();
    }

    public function send($recordModel) {
        $currentUserModel = Users_Record_Model::getCurrentUserModel();
        $rootDirectory = vglobal('root_directory');

        $mailer = new VGSMultiSender_Mailer_Model();
        $mailer->IsHTML(true);

        if($mailer->From == ''){
            $fromEmail = $recordModel->getFromEmailAddress();
            $replyTo = $currentUserModel->get('email1');
            $userName = $currentUserModel->getName();
            $mailer->ConfigSenderInfo($fromEmail, $userName, $replyTo);

        }
        

        // To eliminate the empty value of an array
        $toEmailInfo = array_filter($recordModel->get('toemailinfo'));
        $toMailNamesList = array_filter($recordModel->get('toMailNamesList'));
        foreach ($toMailNamesList as $id => $emailData) {
            foreach ($emailData as $key => $email) {
                if ($toEmailInfo[$id]) {
                    array_push($toEmailInfo[$id], $email['value']);
                }
            }
        }
        
        
        $emailsInfo = array();
        foreach ($toEmailInfo as $id => $emails) {
            foreach ($emails as $key => $value) {
                array_push($emailsInfo, $value);
            }
        }

        $toFieldData = array_diff(explode(',', $recordModel->get('saved_toid')), $emailsInfo);
        $toEmailsData = array();
        $i = 1;
        foreach ($toFieldData as $value) {
            $toEmailInfo['to' . $i++] = array($value);
        }
        $attachments = $recordModel->getAttachmentDetails();
        $status = false;

        // Merge Users module merge tags based on current user.
        $mergedDescription = getMergedDescription($recordModel->get('description'), $currentUserModel->getId(), 'Users');
        $mergedSubject = getMergedDescription($recordModel->get('subject'), $currentUserModel->getId(), 'Users');

        foreach ($toEmailInfo as $id => $emails) {
            $mailer->reinitialize();
                      
            $old_mod_strings = vglobal('mod_strings');
            $description = $recordModel->get('description');
            $subject = $recordModel->get('subject');
            $parentModule = $recordModel->getEntityType($id);
            
            if ($parentModule) {
                $currentLanguage = Vtiger_Language_Handler::getLanguage();
                $moduleLanguageStrings = Vtiger_Language_Handler::getModuleStringsFromFile($currentLanguage, $parentModule);
                vglobal('mod_strings', $moduleLanguageStrings['languageStrings']);

                if ($parentModule != 'Users') {
                    // Apply merge for non-Users module merge tags.
                    $description = getMergedDescription($mergedDescription, $id, $parentModule);
                    $subject = getMergedDescription($mergedSubject, $id, $parentModule);
                } else {
                    // Re-merge the description for user tags based on actual user.
                    $description = getMergedDescription($description, $id, 'Users');
                    $subject = getMergedDescription($mergedSubject, $id, 'Users');
                    vglobal('mod_strings', $old_mod_strings);
                }
            }

            if (strpos($description, '$logo$')) {
                $description = str_replace('$logo$', "<img src='cid:logo' />", $description);
                $logo = true;
            }

            foreach ($emails as $email) {
                $mailer->Body = '';
                if ($parentModule) {
                    $mailer->Body = $recordModel->getTrackImageDetails($id, $recordModel->isEmailTrackEnabled());
                }
                $mailer->Body .= $description;
                $mailer->Signature = str_replace(array('\r\n', '\n'), '<br>', $currentUserModel->get('signature'));
                if ($mailer->Signature != '') {
                    $mailer->Body.= '<br><br>' . decode_html($mailer->Signature);
                }
                $mailer->Subject = $subject;
                $mailer->AddAddress($email);

                //Adding attachments to mail
                if (is_array($attachments)) {
                    foreach ($attachments as $attachment) {
                        $fileNameWithPath = $rootDirectory . $attachment['path'] . $attachment['fileid'] . "_" . $attachment['attachment'];
                        if (is_file($fileNameWithPath)) {
                            $mailer->AddAttachment($fileNameWithPath, $attachment['attachment']);
                        }
                    }
                }
                if ($logo) {
                    //While sending email template and which has '$logo$' then it should replace with company logo
                    $mailer->AddEmbeddedImage(dirname(__FILE__) . '/../../../layouts/v7/skins/images/logo_mail.jpg', 'logo', 'logo.jpg', 'base64', 'image/jpg');
                }

                $ccs = array_filter(explode(',', $recordModel->get('ccmail')));
                $bccs = array_filter(explode(',', $recordModel->get('bccmail')));

                if (!empty($ccs)) {
                    foreach ($ccs as $cc)
                        $mailer->AddCC($cc);
                }
                if (!empty($bccs)) {
                    foreach ($bccs as $bcc)
                        $mailer->AddBCC($bcc);
                }
            }
            $status = $mailer->Send(true);
            if (!$status) {
                $status = $mailer->getError();
            } else {
                $mailString = $mailer->getMailString();
                $mailBoxModel = MailManager_Mailbox_Model::activeInstance();
                $folderName = $mailBoxModel->folder();
                if (!empty($folderName) && !empty($mailString)) {
                    $connector = MailManager_Connector_Connector::connectorWithModel($mailBoxModel, '');
                    imap_append($connector->mBox, $connector->mBoxUrl . $folderName, $mailString, "\\Seen");
                }
            }
        }
        return $status;
    }

    public function validateRequest(Vtiger_Request $request) {
        $request->validateWriteAccess();
    }

}
