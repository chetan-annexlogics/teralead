<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class CustomDashboards_Gauge_Model extends Vtiger_MiniList_Model
{
    public function getListDetailReport()
    {
        global $adb;
        $currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $result = $adb->pquery("SELECT * FROM vtiger_customdashboard WHERE reporttype = ?", array("tabular"));
        if (0 < $adb->num_rows($result)) {
            while ($row = $adb->fetch_array($result)) {
                $isRecordShared = true;
                $reportModel = CustomDashboards_Record_Model::getCleanInstance($row["reportid"]);
                $owner = $reportModel->get("owner");
                $sharingType = $reportModel->get("sharingtype");
                if ($currentUserPriviligesModel->id != $owner && $sharingType == "Private") {
                    $isRecordShared = $reportModel->isRecordHasViewAccess($sharingType);
                }
                if ($isRecordShared) {
                    $report[$row["reportid"]] = $row["reportname"];
                }
            }
        }
        return $report;
    }
    public function getColumnsDetailReport($reportId)
    {
        global $adb;
        $result = $adb->pquery("select vtiger_customdashboardsummary.* from vtiger_customdashboard inner join \r\n                                    vtiger_customdashboardsummary on vtiger_customdashboard.reportid = vtiger_customdashboardsummary.reportsummaryid \r\n                                    where vtiger_customdashboard.reportid = ?", array($reportId));
        if (0 < $adb->num_rows($result)) {
            while ($row = $adb->fetch_array($result)) {
                $listColumn[$row["columnname"]] = $this->getFieldName($row["columnname"]);
            }
        }
        return $listColumn;
    }
    public function getFieldName($fieldInfo)
    {
        global $adb;
        $fieldTable = explode(":", $fieldInfo);
        $fieldTable = $fieldTable[1];
        $fieldName = explode(":", $fieldInfo);
        $fieldName = $fieldName[3];
        $queryGetModule = $adb->pquery("SELECT modulename FROM vtiger_entityname WHERE tablename = ?", array($fieldTable));
        $moduleReportName = $adb->query_result($queryGetModule, 0, "modulename");
        return $fieldName;
    }
    public function calculateGaugeData($data)
    {
        global $adb;
        if (json_decode($data) != NULL) {
            $data = json_decode($data);
            $reportId = $data->targetReport;
            $fieldInfo = $data->dataGauge;
        } else {
            $reportId = $data["targetReport"];
            $fieldInfo = $data["dataGauge"];
        }
        $reportModel = CustomDashboards_Record_Model::getInstanceById($reportId);
        $calculation = $reportModel->getReportCalulationData();
        $fieldTable = explode(":", $fieldInfo);
        $fieldTable = $fieldTable[1];
        $fieldGetData = explode(":", $fieldInfo);
        $fieldGetData = $fieldGetData[3];
        $queryGetModule = $adb->pquery("SELECT vtiger_tab.`name` FROM vtiger_tab INNER JOIN vtiger_field USING(tabid) WHERE vtiger_field.tablename = ? LIMIT 1", array($fieldTable));
        $moduleReportName = $adb->query_result($queryGetModule, 0, 0);
        $gaugeFieldTemp = $moduleReportName . "_" . $fieldGetData;
        $gaugeField = "";
        $gaugeValue = 0;
        foreach ($calculation as $index => $value) {
            foreach ($value as $fieldName => $fieldValue) {
                if ($fieldName == $gaugeFieldTemp) {
                    $gaugeValue = $fieldValue;
                    $gaugeField = $gaugeFieldTemp;
                }
            }
        }
        if ($gaugeValue == "") {
            $gaugeValue = 0;
        }
        return array($gaugeField => $gaugeValue);
    }
    public static function getValueByName($widget, $name)
    {
        $value = json_decode(html_entity_decode($widget->get("data")));
        return $value->{$name};
    }
    public static function formatFinalValue($value, $decimal, $formatLargeNumber)
    {
        $value = floatval(trim(str_replace(",", "", $value), "\$"));
        $symbol = "";
        if ($formatLargeNumber == 1) {
            if (1000000000000.0 < $value) {
                $value = round($value / 1000000000000.0, 2);
                $symbol = " T";
            } else {
                if (1000000000 < $value) {
                    $value = round($value / 1000000000, 2);
                    $symbol = " B";
                } else {
                    if (1000000 < $value) {
                        $value = round($value / 1000000, 2);
                        $symbol = " M";
                    } else {
                        if (1000 < $value) {
                            $value = round($value / 1000, 2);
                            $symbol = " K";
                        }
                    }
                }
            }
        }
        if ($decimal != "") {
            if ($decimal == 0) {
                $value = number_format($value, 0);
            } else {
                if ($decimal == 1) {
                    $value = number_format($value, 1);
                } else {
                    if ($decimal == 2) {
                        $value = number_format($value, 2);
                    } else {
                        if ($decimal == 3) {
                            $value = number_format($value, 3);
                        }
                    }
                }
            }
        }
        return $value . $symbol;
    }
    public function autoUpdateSize($widgetId)
    {
        global $adb;
        $adb->pquery("UPDATE vtiger_module_customdashboarddashboard_widgets SET sizeHeight = ? , sizeWidth = ? WHERE id = ?", array(2, 2, $widgetId));
    }
}

?>