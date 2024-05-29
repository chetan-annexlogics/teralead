<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

/**
 * PHPExcel_Shared_TimeZone
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Shared_TimeZone
{
    protected static $timezone = "UTC";
    /**
     * Validate a Timezone name
     *
     * @param     string        $timezone            Time zone (e.g. 'Europe/London')
     * @return     boolean                        Success or failure
     */
    public static function _validateTimeZone($timezone)
    {
        if (in_array($timezone, DateTimeZone::listIdentifiers())) {
            return true;
        }
        return false;
    }
    /**
     * Set the Default Timezone used for date/time conversions
     *
     * @param     string        $timezone            Time zone (e.g. 'Europe/London')
     * @return     boolean                        Success or failure
     */
    public static function setTimeZone($timezone)
    {
        if (self::_validateTimezone($timezone)) {
            self::$timezone = $timezone;
            return true;
        }
        return false;
    }
    /**
     * Return the Default Timezone used for date/time conversions
     *
     * @return     string        Timezone (e.g. 'Europe/London')
     */
    public static function getTimeZone()
    {
        return self::$timezone;
    }
    /**
     *    Return the Timezone transition for the specified timezone and timestamp
     *
     *    @param        DateTimeZone         $objTimezone    The timezone for finding the transitions
     *    @param        integer                 $timestamp        PHP date/time value for finding the current transition
     *    @return         array                The current transition details
     */
    private static function getTimezoneTransitions($objTimezone, $timestamp)
    {
        $allTransitions = $objTimezone->getTransitions();
        $transitions = array();
        foreach ($allTransitions as $key => $transition) {
            if ($timestamp < $transition["ts"]) {
                $transitions[] = 0 < $key ? $allTransitions[$key - 1] : $transition;
                break;
            }
            if (empty($transitions)) {
                $transitions[] = end($allTransitions);
            }
        }
        return $transitions;
    }
    /**
     *    Return the Timezone offset used for date/time conversions to/from UST
     *    This requires both the timezone and the calculated date/time to allow for local DST
     *
     *    @param        string                 $timezone        The timezone for finding the adjustment to UST
     *    @param        integer                 $timestamp        PHP date/time value
     *    @return         integer                Number of seconds for timezone adjustment
     *    @throws        PHPExcel_Exception
     */
    public static function getTimeZoneAdjustment($timezone, $timestamp)
    {
        if ($timezone !== NULL) {
            if (!self::_validateTimezone($timezone)) {
                throw new PHPExcel_Exception("Invalid timezone " . $timezone);
            }
        } else {
            $timezone = self::$timezone;
        }
        if ($timezone == "UST") {
            return 0;
        }
        $objTimezone = new DateTimeZone($timezone);
        if (0 <= version_compare(PHP_VERSION, "5.3.0")) {
            $transitions = $objTimezone->getTransitions($timestamp, $timestamp);
        } else {
            $transitions = self::getTimezoneTransitions($objTimezone, $timestamp);
        }
        return 0 < count($transitions) ? $transitions[0]["offset"] : 0;
    }
}

?>