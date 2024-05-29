<?php
$zip = new ZipArchive;
$packageFileName = 'child_crm.zip';
if(isset($_GET['action']) && $_GET['action'] == "update") $packageFileName = "child_crm_update.zip";
$res = $zip->open($packageFileName);
if ($res === TRUE) {
    $zip->extractTo(getcwd());
    $zip->close();
    unlink($packageFileName);
}