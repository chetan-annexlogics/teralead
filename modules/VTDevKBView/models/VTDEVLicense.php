<?php


class VTDevKBView_VTDEVLicense_Model
{
    public static function validate()
    {
        global $site_URL;
        if (strpos($site_URL, "teraleads") !== false) return array('valid' => true, "notInstalled" => false, "message" => "");
        else return array('valid' => true, "notInstalled" => false, "message" => "");
    }
}
