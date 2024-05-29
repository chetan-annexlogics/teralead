<?php
/**
 * VGS Multi FROM Address Module
 *
 *
 * @package        VGSMultiSender Module
 * @author         Conrado Maggi - www.vgsglobal.com
 * @license        vTiger Public License.
 * @version        Release: 1.0
 */

include_once 'vtlib/Vtiger/Mailer.php';
include_once 'include/utils/encryption.php';
//include_once 'modules/VGSMultiSender/models/VGSLicenseManager.php';



class VGSMultiSender_Mailer_Model extends Vtiger_Mailer {

    public static function getInstance() {
        return new self();
    }

    /**
     * Function returns error from phpmailer
     * @return <String>
     */
    function getError() {
        return $this->ErrorInfo;
    }

    /**
     * Initialize this instance
     * @access private
     */
    function initialize() {
        $this->Timeout = 30;
        $this->IsSMTP();
        global $adb;
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $this->Host = '';

        // if (aW8bgzsTs3Xp('VGSMultiSender')) {
            if (isset($_REQUEST['chooseFromEmail']) && vtlib_purify($_REQUEST['chooseFromEmail']) != '') {
                $result = $adb->pquery("SELECT * FROM vtiger_vgsmultisender WHERE user_name=? AND userid=?", Array(vtlib_purify($_REQUEST['chooseFromEmail']), $currentUser->id));
                if ($result && $adb->num_rows($result) > 0) {
                    $encrypt = new Encryption();

                    $this->Host = $adb->query_result($result, 0, 'server_name');
                    $this->Username = decode_html($adb->query_result($result, 0, 'user_name'));
                    $this->Password = $encrypt->decrypt($adb->query_result($result, 0, 'password'));
                    $this->From = $adb->query_result($result, 0, 'email_from');
                    $this->AddReplyTo($adb->query_result($result, 0, 'email_from'));
                    $this->SMTPAuth = $adb->query_result($result, 0, 'smtp_auth');
                    $this->FromName = decode_html($adb->query_result($result, 0, 'from_name'));
                }
            }
        // }

        $this->configureDefault();
        $this->setSecure();
    }


    /**
     * Initialize this instance
     * @access private
     * @param $from_email
     * @throws Exception
     */
    function reconfigure($from_email) {
        $this->Timeout = 30;
        $this->IsSMTP();
        global $adb;
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $this->Host = '';

        // if (aW8bgzsTs3Xp('VGSMultiSender')) {
            if ($from_email != '') {
                $result = $adb->pquery("SELECT * FROM vtiger_vgsmultisender WHERE user_name=?", Array(vtlib_purify($from_email)));
                if ($result && $adb->num_rows($result) > 0) {
                    $encrypt = new Encryption();

                    $this->Host = $adb->query_result($result, 0, 'server_name');
                    $this->Username = decode_html($adb->query_result($result, 0, 'user_name'));
                    $this->Password = $encrypt->decrypt($adb->query_result($result, 0, 'password'));
                    $this->From = $adb->query_result($result, 0, 'email_from');
                    // $this->AddReplyTo($adb->query_result($result, 0, 'email_from'));
                    $this->SMTPAuth = $adb->query_result($result, 0, 'smtp_auth');
                    $this->FromName = decode_html($adb->query_result($result, 0, 'from_name'));
                }
            }
        //}

        $this->configureDefault();
        $this->setSecure();
    }


    function reconfigureFromUser($email = null, $id = null) {
        $this->Timeout = 30;
        $this->IsSMTP();
        global $adb;
        $currentUser = Users_Record_Model::getCurrentUserModel();

        $this->Host = '';
        if ($email != null && $email != ""){
            $adb->query("UPDATE vtiger_emaildetails SET  from_email = '$email' WHERE emailid =" . $id);
            $result = $adb->pquery("SELECT * FROM vtiger_vgsmultisender WHERE email_from = ?", array($email));
        } else {
            $result = $adb->pquery(
                "SELECT * FROM vtiger_vgsmultisender WHERE userid = ?",
                array($currentUser->id)
            );
        }

        if ($result && $adb->num_rows($result) > 0) {

            $encrypt = new Encryption();
            $this->Host = $adb->query_result($result, 0, 'server_name');
            $this->Username = decode_html($adb->query_result($result, 0, 'user_name'));
            $this->Password = $encrypt->decrypt($adb->query_result($result, 0, 'password'));
            $this->From = $adb->query_result($result, 0, 'email_from');
            $this->AddReplyTo($adb->query_result($result, 0, 'email_from'));
            $this->SMTPAuth = $adb->query_result($result, 0, 'smtp_auth');
            $this->FromName = decode_html($adb->query_result($result, 0, 'from_name'));
            $this->BatchCount = decode_html($adb->query_result($result, 0, 'batch_count'));
            $this->BatchDelay = decode_html($adb->query_result($result, 0, 'batch_delay'));

        }

        $this->configureDefault();
        $this->setSecure();

    }

    private function setSecure(){

        if ($this->Host != '') {
            // To support TLS
            $hostinfo = explode("://", $this->Host);
            $smtpsecure = $hostinfo[0];
            if ($smtpsecure == 'tls') {
                $this->SMTPSecure = $smtpsecure;
                $this->Host = $hostinfo[1];
            }
            // End

            if (empty($this->SMTPAuth))
                $this->SMTPAuth = false;

            $this->_serverConfigured = true;
        }

    }

    private function configureDefault(){
        global $adb, $current_user;
        if ($this->Host == '') {
            $result = $adb->pquery("SELECT * FROM vtiger_systems WHERE server_type=?", Array('email'));

            if ($adb->num_rows($result)) {
                $this->Host = $adb->query_result($result, 0, 'server');
                $this->Username = decode_html($adb->query_result($result, 0, 'server_username'));
                $this->Password = decode_html($adb->query_result($result, 0, 'server_password'));
                $this->SMTPAuth = $adb->query_result($result, 0, 'smtp_auth');
                $this->FromName = decode_html($adb->query_result($result, 0, 'server_username'));
                $FromEmail = decode_html($adb->query_result($result, 0, 'from_email_field'));
                if (empty($FromEmail)) {
                    $this->FromName = $current_user->first_name.' '.$current_user->last_name;
                    $this->From = $current_user->email1;
                } else {
                    $this->FromName = decode_html($adb->query_result($result, 0, 'server_username'));
                    $this->From = decode_html($adb->query_result($result, 0, 'from_email_field'));
                }
            }
        }
    }


    /**
     * Function to replace space with %20 to make image url as valid
     * @param type $htmlContent
     * @return type
     */
    public function makeImageURLValid($htmlContent) {
        $doc = new DOMDocument();
        $imageUrls = array();
        if (!empty($htmlContent)) {
            @$doc->loadHTML($htmlContent);
            $tags = $doc->getElementsByTagName('img');
            foreach ($tags as $tag) {
                $imageUrl = $tag->getAttribute('src');
                $imageUrls[$imageUrl] = str_replace(" ", "%20", $imageUrl);
            }
        }
        foreach ($imageUrls as $key => $value) {
            $htmlContent = str_replace($key, $value, $htmlContent);
        }
        return $htmlContent;
    }

    public static function convertCssToInline($content) {
        if (preg_match('/<style[^>]+>(?<css>[^<]+)<\/style>/s', $content)) {
            $instyle = new InStyle();
            $convertedContent = $instyle->convert($content);
            if ($convertedContent) {
                return $convertedContent;
            }
        }

        return $content;
    }

    public static function retrieveMessageIdFromMailroom($crmId) {
        $db = PearDatabase::getInstance();
        $result = $db->pquery('SELECT messageid FROM vtiger_mailscanner_ids WHERE crmid=?', array($crmId));
        return $db->query_result($result, 'messageid', 0);
    }

    /**
     * Function generates randomId with host details
     * @return type
     */
    public static function generateMessageID() {
        $generateId = sprintf("<%s.%s@%s>", base_convert(microtime(), 10, 36), base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36), gethostname());
        return $generateId;
    }

    /**
     * Function inserts new message for a crmid which was not present in
     * below table
     * @param type $crmId
     */
    public static function updateMessageIdByCrmId($messageId, $crmId) {
        $db = PearDatabase::getInstance();
        $existingResult = array();
        //Get existing refids for a given crm id and update new refids to the crmid
        $existingResultObject = $db->pquery("SELECT refids FROM vtiger_mailscanner_ids WHERE crmid=? AND refids != 'null'", array($crmId));
        $num_rows = $db->num_rows($existingResultObject);
        if ($num_rows > 0) {
            $existingResult = json_decode($db->query_result($existingResultObject, 'refids', 0), true);
            // Checking if first parameter is not an array
            if (is_array($existingResult)) {
                $existingResultValue = array_merge($existingResult, array($messageId));
                $refIds = json_encode($existingResultValue);
                $db->pquery("UPDATE vtiger_mailscanner_ids SET refids=? WHERE crmid=? ", array($refIds, $crmId));
            }
        } else {
            $db->pquery("INSERT INTO vtiger_mailscanner_ids (messageid, crmid) VALUES(?,?)", array($messageId, $crmId));
        }
    }

    public function convertToValidURL($htmlContent) {
        if (!$this->dom) {
            $this->dom = new DOMDocument();
            @$this->dom->loadHTML($htmlContent);
        }
        $anchorElements = $this->dom->getElementsByTagName('a');
        $urls = array();
        foreach ($anchorElements as $anchorElement) {
            $url = $anchorElement->getAttribute('href');
            if (!empty($url)) {
                //If url start with mailto:,tel:,#,news: then skip those urls 
                if (!preg_match("~^(?:f|ht)tps?://~i", $url) && (strpos('$', $url[0]) !== 0) && (strpos($url, 'mailto:') !== 0 ) && (strpos($url, 'tel:') !== 0 ) && $url[0] !== '#' && !preg_match("/news:\/\//i", $url)) {
                    $url = "http://" . $url;
                    $urls[$anchorElement->getAttribute('href')] = $url;
                    $htmlContent = $this->replaceURLWithValidURLInContent($htmlContent, $anchorElement->getAttribute('href'), $url);
                }
            }
        }
        return $htmlContent;
    }

    public function replaceURLWithValidURLInContent($htmlContent, $searchURL, $replaceWithURL) {
        $search = '"' . $searchURL . '"';
        $toReplace = '"' . $replaceWithURL . '"';
        $pos = strpos($htmlContent, $search);
        if ($pos != false) {
            $replacedContent = substr_replace($htmlContent, $toReplace, $pos) . substr($htmlContent, $pos + strlen($search));
            return $replacedContent;
        }
        return $htmlContent;
    }

    public static function getProcessedContent($content) {
        // remove script tags from whole html content
        $processedContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
        return $processedContent;
    }

    /**
     * Function to Convert an UTF-8 string to Ascii string
     * @param <string> $content - string containing utf-8 characters
     * @param <string> $subst_chr - if the character is not found it replaces with this value
     * @return <string> Ascii String
     */
    public static function convertToAscii($content, $subst_chr = '') {
        return ToAscii::convertToAscii($content, $subst_chr);
    }
}
