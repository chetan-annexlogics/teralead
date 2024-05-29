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
 * PHPExcel_Cell_DataType
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
 * @package    PHPExcel_Cell
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Cell_DataType
{
    /**
     * List of error codes
     *
     * @var array
     */
    private static $errorCodes = array("#NULL!" => 0, "#DIV/0!" => 1, "#VALUE!" => 2, "#REF!" => 3, "#NAME?" => 4, "#NUM!" => 5, "#N/A" => 6);
    const TYPE_STRING2 = "str";
    const TYPE_STRING = "s";
    const TYPE_FORMULA = "f";
    const TYPE_NUMERIC = "n";
    const TYPE_BOOL = "b";
    const TYPE_NULL = "null";
    const TYPE_INLINE = "inlineStr";
    const TYPE_ERROR = "e";
    /**
     * Get list of error codes
     *
     * @return array
     */
    public static function getErrorCodes()
    {
        return self::$errorCodes;
    }
    /**
     * DataType for value
     *
     * @deprecated  Replaced by PHPExcel_Cell_IValueBinder infrastructure, will be removed in version 1.8.0
     * @param       mixed  $pValue
     * @return      string
     */
    public static function dataTypeForValue($pValue = NULL)
    {
        return PHPExcel_Cell_DefaultValueBinder::dataTypeForValue($pValue);
    }
    /**
     * Check a string that it satisfies Excel requirements
     *
     * @param  mixed  Value to sanitize to an Excel string
     * @return mixed  Sanitized value
     */
    public static function checkString($pValue = NULL)
    {
        if ($pValue instanceof PHPExcel_RichText) {
            return $pValue;
        }
        $pValue = PHPExcel_Shared_String::Substring($pValue, 0, 32767);
        $pValue = str_replace(array("\r\n", "\r"), "\n", $pValue);
        return $pValue;
    }
    /**
     * Check a value that it is a valid error code
     *
     * @param  mixed   Value to sanitize to an Excel error code
     * @return string  Sanitized value
     */
    public static function checkErrorCode($pValue = NULL)
    {
        $pValue = (string) $pValue;
        if (!array_key_exists($pValue, self::$errorCodes)) {
            $pValue = "#NULL!";
        }
        return $pValue;
    }
}

?>