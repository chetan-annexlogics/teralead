<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

chdir("../../../");
require_once "include/utils/utils.php";
require_once "include/utils/CommonUtils.php";
require_once "includes/Loader.php";
vimport("includes.runtime.EntryPoint");
vimport("includes.runtime.Globals");
require_once "modules/CustomDashboards/models/ScheduleReports.php";
CustomDashboards_ScheduleReports_Model::runScheduledCustomDashboards();

?>