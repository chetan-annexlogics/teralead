<?php
chdir(dirname(__FILE__));
//ini_set('display_errors','on'); error_reporting(E_ALL);
require_once('include/utils/utils.php');
require_once('includes/runtime/BaseModel.php');
require_once('includes/Loader.php');
require_once('data/CRMEntity.php');
$json = file_get_contents('php://input');
$action = json_decode($json, true)['data'];
$number = $action['payload']['from'];
$to = $action['payload']['to'];
$numberWithoutPlus = str_replace('+','',$action['payload']['from']);
$event_type = $action['event_type'];//call.hangup
$sip_hangup_cause = $action['payload']['sip_hangup_cause'];
$callId = $action['payload']['call_control_id'];
$callId = explode('v3:', $callId)[1];
$call_session_id = $action['payload']['call_session_id'];
$disposition = '';// $action['event_type'];
$firstTime=strtotime($action['payload']['start_time']);
$lastTime=strtotime($action['payload']['end_time']);
$totalduration = $lastTime-$firstTime;
$billduration = $lastTime-$firstTime;

//$adb_host = 'localhost';
//$adb_username = 'uofdstgo3gha6';
//$adb_pass = 'gtjg9vmvqagy';
//$adb_name = 'dbnmzu4logsyxp';
global $adb;
if($event_type == 'call.hangup'){
    //$adb = new mysqli($adb_host,$adb_username,$adb_pass,$adb_name);
    $res_crm =$adb->query("SELECT contactid,smownerid FROM `vtiger_contactdetails` INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid WHERE deleted = 0 AND (mobile = '$number' OR mobile = '$numberWithoutPlus')");
    $contactId = '';
    $userid = '1';
    if (getUser($to)) {
        $userid = getUser($to);
    }
    while($rowCrm = $adb->query_result_rowdata($res_crm,0)){
        $contactId = $rowCrm['contactid'];
        $userid = $rowCrm['smownerid'];
    }
    $startTime = date("Y-m-d H:i:s", $firstTime);
    $endTime = date("Y-m-d H:i:s", $lastTime);
    $recordurl = '';//'http://64.227.10.156/monitor/'.$filename;
    if($sip_hangup_cause == '200'){
        $disposition = 'ANSWERED';
    } else if($sip_hangup_cause == '487') {
        $disposition = 'NO ANSWER';
        $billduration = 0;
    } else {
        $disposition = 'VOICE RECORD';
    }
    $module = "PBXManager";
    $platform = "";
    $facebook_numbers = array(
        "+19713390413",
        "+18722348995",
        "+15126411648",
        "+13139989806",
        "+16292750775",
        "+18564438366",
        "+19083810880",
        "+18597887006",
        "+14703696870",
        "+19294040542",
        "+12393459996",
        "+12098979788",
        "+16152359088",
        "+12159148554",
        "+12364992241",
        "+18584658445"
    );
    if (in_array($to,$facebook_numbers)) {
            $platform = "Facebook";
         }
    $fieldInfos = array(
        'direction' => 'inbound',
        'callstatus' => $disposition,
        'starttime' => $startTime,
        'endtime' => $endTime,
        'totalduration' => $totalduration,
        'billduration' => $billduration,
        'recordingurl' => $recordurl,
        'sourceuuid' => '',
        'gateway' => 'PBXManager',
        'customer' => $contactId,
        'user' => $userid,
        'customernumber' => $number,
        'to_number' => $to,
        'customertype' => 'Contacts',
        'callid' => $callId,
        'callsessionid' => $call_session_id,
        'cf_925' => "All Leads",
        'cf_907' => "Not Booked",
        'cf_981' => $platform
    );
    $new_focus = CRMEntity::getInstance($module);
    $new_focus->column_fields['assigned_user_id'] = $userid;
    $new_focus->column_fields['smcreatorid'] = $userid;
    $new_focus->column_fields['modifiedby'] = $userid;
    $new_focus->column_fields['createdtime'] = date("Y-m-d H:i:s");
    foreach ($fieldInfos as $key => $value){
        $new_focus->column_fields[$key] = $value;
    }
    $new_focus->saveentity($module);
    $newRecordId = $new_focus->id;
    $recordurl = 'https://crm3.teraleads.com/index.php?module=PBXManager&view=Detail&record='.$newRecordId .'&app=TOOLS&downloadCall='.$newRecordId;
    $adb->pquery("update vtiger_pbxmanager set recordingurl = ? WHERE pbxmanagerid = ?",array($recordurl, $newRecordId));
    $recordModel = Vtiger_Record_Model::getInstanceById($newRecordId,$module);
    if($recordModel){
        include_once "modules/Contacts/ContactsHandler.php";
        CreateChildCrm($recordModel);
    }
    //echo "done";
}

function getUser($number) {
    $listUsers = [
        '+13088009003' => '5', // my test number
        '+15595738634' => '6', // fresnoimplantclinic
        '+16156231656' => '7', // nashville implantclinic
        '+16292750775' => '7', // nashville implantclinic facebook number
        '+14245120223' => '10', // caimplantclinic
        '+16304630924' => '27', // 3dimplantclinic
        '+18584658515' => '12', // sandiegoimplantclinic
        '+18584658445' => '12', // sandiegoimplantclinic facebook number
        '+19166599182' => '13', // thesmileclinics
        '+12792120049' => '13', // thesmileclinics
        '+15302361313' => '13', // thesmileclinics number 2
        '+15308610748' => '13', // thesmileclinics number 3
        '+19169370337' => '14', // fullarchcenter
        '+15128866002' => '14', // fullarchcenter number 2
        '+16232530670' => '15', // azimplantclinic
        '+16234400020' => '15', // azimplantclinic number 2
        '+14808639493' => '15', // phoeniximplantcenter
        '+19087511858' => '16', // njsmileclinic
        '+19083810880' => '16', // njsmileclinic facebook number
        '+13322871012' => '17', // nysmileclinic
        '+19169536368' => '18', // newsmilesacramento
        '+15307309724' => '18', // newsmilesacramento number 2
        '+18564438103' => '19', // njimplantclinic
        '+18564438366' => '19', // njimplantclinic facebook number
        '+16133675052' => '20', // sunsetdentalcentre
        '+12156080035' => '21', // paimplantclinic
        '+12159148554' => '21', // paimplantclinic facebook number
        '+12148170116' => '22', // texasimplantclinic
        '+13463471690' => '24', // newteethclinic
        '+18887130129' => '25', // fullarchclinic
        '+14077988406' => '26', // orlandoimplantclinic
        '+16308661707' => '27', // acedentalcenter
        '+16304630924' => '27', // acedentalcenter number 2
        '+18722348995' => '27', // acedentalcenter facebook number
        '+18722990095' => '27', // chicagoimplantclinic
        '+12099731288' => '28', // stocktonimplantclinic
        '+12393178821' => '29', // naplesimplantclinic
        '+12394230733' => '29', // naplesimplantclinic number 2 and flimplantclinic
        '+12393459996' => '29', // naplesimplantclinic facebook number
        '+19713390413' => '30', // portlandimplantclinic
        '+14125005030' => '31', // pittsburghimplantclinic
        '+18594361191' => '32', // lexingtonimplantclinic
        '+18597887006' => '32', // lexingtonimplantclinic facebook number
        '+19294040008' => '33', // nyimplantclinic
        '+19294040542' => '33', // nyimplantclinic facebook number
        '+17085904441' => '34', // saludfamilydental
        '+18587798883' => '36', // startsmilingsandiego
        '+18586440410' => '36', // startsmilingsandiego number 2
        '+13365303550' => '37', // ncimplantclinic
        '+16514270441' => '38', // mnimplantclinic
        '+14255481122' => '39', // seattlesmileclinic
        '+16576430858' => '40', // ocsmileclinic
        '+17472191466' => '41', // westhillsimplantclinic
        '+16142159210' => '42', // columbusimplantclinic
        '+19526008606' => '43', // mnsmileclinic
        '+15026638830' => '44', // louisvilleimplantclinic
        '+19257239292' => '45', // oaklandimplantclinic
        '+19166020940' => '46', // elkgroveimplantclinic
        '+12097071080' => '47', // modestoimplantclinic
        '+12098979788' => '47', // modestoimplantclinic facebook number
        '+18646260531' => '48', // greenvilleimplantclinic
        '+18546004274' => '49', // charlestonimplantclinic
        '+17072226579' => '50', // napaimplantclinic
        '+19729936787' => '53', // dallasimplantclinics
        '+18599008119' => '54',  // cincinnatismileclinic
        '+16568882232' => '55',  // tampaimplantclinic
        '+19043721882' => '56', // jacksonvilleimplantclinic
        '+14258450005' => '57',  // seattleimplantclinic
        '+15124204145' => '58',  // austinimplantclinic and 3dsmileclinic
        '+15126411648' => '58',  // austinimplantclinic facebook number
        '+18043158993' => '59', // richmondimplantclinic
        '+14703696968' => '60',  // atlantaimplantclinic
        '+14703696870' => '60',  // atlantaimplantclinic facebook number
        '+17169193113' => '61',  // buffaloimplantclinic
        '+16506329882' => '63',  // redwoodcityimplantclinic
        '+14379009880' => '64',   // vaughanimplantclinic
        '+15189183380' => '65',   //  albanyimplantclinic
        '+12364992221' => '66',   //  kelownaimplantclinic
        '+12364992241' => '66',   //  kelownaimplantclinic facebook number
        '+14056531020' => '67',   //  oklahomaimplantclinic
        '+14162739767' => '68',   //  dundaseastdental
        '+17043436620' => '69',  //  charlotteimplantclinic
        '+13135127272' => '70',   //  michiganimplantclinic
        '+13139989806' => '70',   //  michiganimplantclinic facebook number
        '+15139143414' => '71',   //  ohioimplantclinic
        '+15109411919' => '72',   //  fremontimplantclinic
        '+14706801088' => '73',   //  gaimplantclinic
        '+13465006899' => '75',   //  houstonimplantclinic
        '+18597886688' => '76',    //  freesmilecare
        '+12099611161' => '77',    //  modestosmileclinic
        '+16288959599' => '79',    //  sanfranciscoimplantclinic
        '+16156215565' => '80',     //  tnimplantclinic
        '+16152359088' => '80',     //  tnimplantclinic facebook number
        '+17252390909' => '81'      //  lasvegasimplantclinic
    ];




    if (!empty($listUsers[$number])) {
        return $listUsers[$number];
    }
    return $listUsers;
}

