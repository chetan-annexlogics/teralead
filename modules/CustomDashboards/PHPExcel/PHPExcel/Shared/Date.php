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
 * PHPExcel_Shared_Date
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Shared_Date
{
    public static $monthNames = array("Jan" => "January", "Feb" => "February", "Mar" => "March", "Apr" => "April", "May" => "May", "Jun" => "June", "Jul" => "July", "Aug" => "August", "Sep" => "September", "Oct" => "October", "Nov" => "November", "Dec" => "December");
    public static $numberSuffixes = array("st", "nd", "rd", "th");
    protected static $excelBaseDate = self::CALENDAR_WINDOWS_1900;
    private static $possibleDateFormatCharacters = "eymdHs";
    const CALENDAR_WINDOWS_1900 = 1900;
    const CALENDAR_MAC_1904 = 1904;
    /**
     * Set the Excel calendar (Windows 1900 or Mac 1904)
     *
     * @param     integer    $baseDate           Excel base date (1900 or 1904)
     * @return    boolean                        Success or failure
     */
    public static function setExcelCalendar($baseDate)
    {
        if ($baseDate == self::CALENDAR_WINDOWS_1900 || $baseDate == self::CALENDAR_MAC_1904) {
            self::$excelBaseDate = $baseDate;
            return true;
        }
        return false;
    }
    /**
     * Return the Excel calendar (Windows 1900 or Mac 1904)
     *
     * @return     integer    Excel base date (1900 or 1904)
     */
    public static function getExcelCalendar()
    {
        return self::$excelBaseDate;
    }
    /**
     *    Convert a date from Excel to PHP
     *
     *    @param        integer        $dateValue            Excel date/time value
     *    @param        boolean        $adjustToTimezone    Flag indicating whether $dateValue should be treated as
     *                                                    a UST timestamp, or adjusted to UST
     *    @param        string         $timezone            The timezone for finding the adjustment from UST
     *    @return       integer        PHP serialized date/time
     */
    public static function ExcelToPHP($dateValue = 0, $adjustToTimezone = false, $timezone = NULL)
    {
        if (self::$excelBaseDate == self::CALENDAR_WINDOWS_1900) {
            $myexcelBaseDate = 25569;
            if ($dateValue < 60) {
                $myexcelBaseDate--;
            }
        } else {
            $myexcelBaseDate = 24107;
        }
        if (1 <= $dateValue) {
            $utcDays = $dateValue - $myexcelBaseDate;
            $returnValue = round($utcDays * 86400);
            if ($returnValue <= PHP_INT_MAX && 0 - PHP_INT_MAX <= $returnValue) {
                $returnValue = (int) $returnValue;
            }
        } else {
            $hours = round($dateValue * 24);
            $mins = round($dateValue * 1440) - round($hours * 60);
            $secs = round($dateValue * 86400) - round($hours * 3600) - round($mins * 60);
            $returnValue = (int) gmmktime($hours, $mins, $secs);
        }
        $timezoneAdjustment = $adjustToTimezone ? PHPExcel_Shared_TimeZone::getTimezoneAdjustment($timezone, $returnValue) : 0;
        return $returnValue + $timezoneAdjustment;
    }
    /**
     * Convert a date from Excel to a PHP Date/Time object
     *
     * @param    integer        $dateValue        Excel date/time value
     * @return    DateTime                    PHP date/time object
     */
    public static function ExcelToPHPObject($dateValue = 0)
    {
        $dateTime = self::ExcelToPHP($dateValue);
        $days = floor($dateTime / 86400);
        $time = round(($dateTime / 86400 - $days) * 86400);
        $hours = round($time / 3600);
        $minutes = round($time / 60) - $hours * 60;
        $seconds = round($time) - $hours * 3600 - $minutes * 60;
        $dateObj = date_create("1-Jan-1970+" . $days . " days");
        $dateObj->setTime($hours, $minutes, $seconds);
        return $dateObj;
    }
    /**
     *    Convert a date from PHP to Excel
     *
     *    @param    mixed        $dateValue            PHP serialized date/time or date object
     *    @param    boolean        $adjustToTimezone    Flag indicating whether $dateValue should be treated as
     *                                                    a UST timestamp, or adjusted to UST
     *    @param    string         $timezone            The timezone for finding the adjustment from UST
     *    @return    mixed        Excel date/time value
     *                            or boolean FALSE on failure
     */
    public static function PHPToExcel($dateValue = 0, $adjustToTimezone = false, $timezone = NULL)
    {
        $saveTimeZone = date_default_timezone_get();
        date_default_timezone_set("UTC");
        $timezoneAdjustment = $adjustToTimezone ? PHPExcel_Shared_TimeZone::getTimezoneAdjustment($timezone ? $timezone : $saveTimeZone, $dateValue) : 0;
        $retValue = false;
        if (is_object($dateValue) && $dateValue instanceof DateTime) {
            $dateValue->add(new DateInterval("PT" . $timezoneAdjustment . "S"));
            $retValue = self::FormattedPHPToExcel($dateValue->format("Y"), $dateValue->format("m"), $dateValue->format("d"), $dateValue->format("H"), $dateValue->format("i"), $dateValue->format("s"));
        } else {
            if (is_numeric($dateValue)) {
                $dateValue += $timezoneAdjustment;
                $retValue = self::FormattedPHPToExcel(date("Y", $dateValue), date("m", $dateValue), date("d", $dateValue), date("H", $dateValue), date("i", $dateValue), date("s", $dateValue));
            } else {
                if (is_string($dateValue)) {
                    $retValue = self::stringToExcel($dateValue);
                }
            }
        }
        date_default_timezone_set($saveTimeZone);
        return $retValue;
    }
    /**
     * FormattedPHPToExcel
     *
     * @param    integer    $year
     * @param    integer    $month
     * @param    integer    $day
     * @param    integer    $hours
     * @param    integer    $minutes
     * @param    integer    $seconds
     * @return   integer    Excel date/time value
     */
    public static function FormattedPHPToExcel($year, $month, $day, $hours = 0, $minutes = 0, $seconds = 0)
    {
        if (self::$excelBaseDate == self::CALENDAR_WINDOWS_1900) {
            $excel1900isLeapYear = true;
            if ($year == 1900 && $month <= 2) {
                $excel1900isLeapYear = false;
            }
            $myexcelBaseDate = 2415020;
        } else {
            $myexcelBaseDate = 2416481;
            $excel1900isLeapYear = false;
        }
        if (2 < $month) {
            $month -= 3;
        } else {
            $month += 9;
            $year--;
        }
        $century = substr($year, 0, 2);
        $decade = substr($year, 2, 2);
        $excelDate = floor(146097 * $century / 4) + floor(1461 * $decade / 4) + floor((153 * $month + 2) / 5) + $day + 1721119 - $myexcelBaseDate + $excel1900isLeapYear;
        $excelTime = ($hours * 3600 + $minutes * 60 + $seconds) / 86400;
        return (double) $excelDate + $excelTime;
    }
    /**
     * Is a given cell a date/time?
     *
     * @param     PHPExcel_Cell    $pCell
     * @return     boolean
     */
    public static function isDateTime(PHPExcel_Cell $pCell)
    {
        return self::isDateTimeFormat($pCell->getWorksheet()->getStyle($pCell->getCoordinate())->getNumberFormat());
    }
    /**
     * Is a given number format a date/time?
     *
     * @param     PHPExcel_Style_NumberFormat    $pFormat
     * @return     boolean
     */
    public static function isDateTimeFormat(PHPExcel_Style_NumberFormat $pFormat)
    {
        return self::isDateTimeFormatCode($pFormat->getFormatCode());
    }
    /**
     * Is a given number format code a date/time?
     *
     * @param     string    $pFormatCode
     * @return     boolean
     */
    public static function isDateTimeFormatCode($pFormatCode = "")
    {
        if (strtolower($pFormatCode) === strtolower(PHPExcel_Style_NumberFormat::FORMAT_GENERAL)) {
            return false;
        }
        if (preg_match("/[0#]E[+-]0/i", $pFormatCode)) {
            return false;
        }
        switch ($pFormatCode) {
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_DMMINUS:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_MYMINUS:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME1:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME2:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME5:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME6:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME7:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME8:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX16:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX17:
            case PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX22:
                return true;
        }
        if (substr($pFormatCode, 0, 1) == "_" || substr($pFormatCode, 0, 2) == "0 ") {
            return false;
        }
        if (preg_match("/(^|\\])[^\\[]*[" . self::$possibleDateFormatCharacters . "]/i", $pFormatCode)) {
            if (strpos($pFormatCode, "\"") !== false) {
                $segMatcher = false;
                foreach (explode("\"", $pFormatCode) as $subVal) {
                    if (($segMatcher = !$segMatcher) && preg_match("/(^|\\])[^\\[]*[" . self::$possibleDateFormatCharacters . "]/i", $subVal)) {
                        return true;
                    }
                }
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    /**
     * Convert a date/time string to Excel time
     *
     * @param    string    $dateValue        Examples: '2009-12-31', '2009-12-31 15:59', '2009-12-31 15:59:10'
     * @return    float|FALSE        Excel date/time serial value
     */
    public static function stringToExcel($dateValue = "")
    {
        if (strlen($dateValue) < 2) {
            return false;
        }
        if (!preg_match("/^(\\d{1,4}[ \\.\\/\\-][A-Z]{3,9}([ \\.\\/\\-]\\d{1,4})?|[A-Z]{3,9}[ \\.\\/\\-]\\d{1,4}([ \\.\\/\\-]\\d{1,4})?|\\d{1,4}[ \\.\\/\\-]\\d{1,4}([ \\.\\/\\-]\\d{1,4})?)( \\d{1,2}:\\d{1,2}(:\\d{1,2})?)?\$/iu", $dateValue)) {
            return false;
        }
        $dateValueNew = PHPExcel_Calculation_DateTime::DATEVALUE($dateValue);
        if ($dateValueNew === PHPExcel_Calculation_Functions::VALUE()) {
            return false;
        }
        if (strpos($dateValue, ":") !== false) {
            $timeValue = PHPExcel_Calculation_DateTime::TIMEVALUE($dateValue);
            if ($timeValue === PHPExcel_Calculation_Functions::VALUE()) {
                return false;
            }
            $dateValueNew += $timeValue;
        }
        return $dateValueNew;
    }
    /**
     * Converts a month name (either a long or a short name) to a month number
     *
     * @param     string    $month    Month name or abbreviation
     * @return    integer|string     Month number (1 - 12), or the original string argument if it isn't a valid month name
     */
    public static function monthStringToNumber($month)
    {
        $monthIndex = 1;
        foreach (self::$monthNames as $shortMonthName => $longMonthName) {
            if ($month === $longMonthName || $month === $shortMonthName) {
                return $monthIndex;
            }
            $monthIndex++;
        }
        return $month;
    }
    /**
     * Strips an ordinal froma numeric value
     *
     * @param     string    $day      Day number with an ordinal
     * @return    integer|string      The integer value with any ordinal stripped, or the original string argument if it isn't a valid numeric
     */
    public static function dayStringToNumber($day)
    {
        $strippedDayValue = str_replace(self::$numberSuffixes, "", $day);
        if (is_numeric($strippedDayValue)) {
            return (int) $strippedDayValue;
        }
        return $day;
    }
}

?>