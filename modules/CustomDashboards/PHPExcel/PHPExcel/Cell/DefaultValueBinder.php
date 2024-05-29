<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

if (!defined("PHPEXCEL_ROOT")) {
    define("PHPEXCEL_ROOT", dirname(__FILE__) . "/../../");
    require PHPEXCEL_ROOT . "PHPExcel/Autoloader.php";
}
/**
 * PHPExcel_Cell_DefaultValueBinder
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
class PHPExcel_Cell_DefaultValueBinder implements PHPExcel_Cell_IValueBinder
{
    /**
     * Bind value to a cell
     *
     * @param  PHPExcel_Cell  $cell   Cell to bind value to
     * @param  mixed          $value  Value to bind in cell
     * @return boolean
     */
    public function bindValue(PHPExcel_Cell $cell, $value = NULL)
    {
        if (is_string($value)) {
            $value = PHPExcel_Shared_String::SanitizeUTF8($value);
        } else {
            if (is_object($value)) {
                if ($value instanceof DateTime) {
                    $value = $value->format("Y-m-d H:i:s");
                } else {
                    if (!$value instanceof PHPExcel_RichText) {
                        $value = (string) $value;
                    }
                }
            }
        }
        $cell->setValueExplicit($value, self::dataTypeForValue($value));
        return true;
    }
    /**
     * DataType for value
     *
     * @param   mixed  $pValue
     * @return  string
     */
    public static function dataTypeForValue($pValue = NULL)
    {
        if ($pValue === NULL) {
            return PHPExcel_Cell_DataType::TYPE_NULL;
        }
        if ($pValue === "") {
            return PHPExcel_Cell_DataType::TYPE_STRING;
        }
        if ($pValue instanceof PHPExcel_RichText) {
            return PHPExcel_Cell_DataType::TYPE_INLINE;
        }
        if ($pValue[0] === "=" && 1 < strlen($pValue)) {
            return PHPExcel_Cell_DataType::TYPE_FORMULA;
        }
        if (is_bool($pValue)) {
            return PHPExcel_Cell_DataType::TYPE_BOOL;
        }
        if (is_float($pValue) || is_int($pValue)) {
            return PHPExcel_Cell_DataType::TYPE_NUMERIC;
        }
        if (preg_match("/^[\\+\\-]?([0-9]+\\.?[0-9]*|[0-9]*\\.?[0-9]+)([Ee][\\-\\+]?[0-2]?\\d{1,3})?\$/", $pValue)) {
            $tValue = ltrim($pValue, "+-");
            if (is_string($pValue) && $tValue[0] === "0" && 1 < strlen($tValue) && $tValue[1] !== ".") {
                return PHPExcel_Cell_DataType::TYPE_STRING;
            }
            if (strpos($pValue, ".") === false && PHP_INT_MAX < $pValue) {
                return PHPExcel_Cell_DataType::TYPE_STRING;
            }
            return PHPExcel_Cell_DataType::TYPE_NUMERIC;
        }
        if (is_string($pValue) && array_key_exists($pValue, PHPExcel_Cell_DataType::getErrorCodes())) {
            return PHPExcel_Cell_DataType::TYPE_ERROR;
        }
        return PHPExcel_Cell_DataType::TYPE_STRING;
    }
}

?>