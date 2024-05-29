<?php

class SMTPModule
{
    public static function getRecordData($id)
    {
        $row = array();
        $adb = PearDatabase::getInstance();
        $ret = $adb->pquery(
            "SELECT *
                FROM vtiger_vgsmultisender
                WHERE id = ?",
            array($id)
        );
        if ($adb->num_rows($ret) > 0) {
            try {
                $row = $adb->query_result_rowdata($ret, 0);
            } catch (Exception $e) {
            }
        }
        return $row;
    }

    public static function saveRecord($id, $params) {
        $adb = PearDatabase::getInstance();
        $set = '';
        foreach ($params as $key => $value) {
            $set .= "{$key} = ?,";
        }
        $set = trim($set, ',');
        $val = array_merge(array_values($params), array($id));
        $adb->dieOnError = true;
        $adb->pquery(
            "UPDATE vtiger_vgsmultisender
                    SET {$set}
                    WHERE id = ?",
            $val
        );
    }
}
