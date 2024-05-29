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
 * PHPExcel_Shared_String
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
class PHPExcel_Shared_String
{
    /**
     * Control characters array
     *
     * @var string[]
     */
    private static $controlCharacters = array();
    /**
     * SYLK Characters array
     *
     * $var array
     */
    private static $SYLKCharacters = array();
    /**
     * Decimal separator
     *
     * @var string
     */
    private static $decimalSeparator = NULL;
    /**
     * Thousands separator
     *
     * @var string
     */
    private static $thousandsSeparator = NULL;
    /**
     * Currency code
     *
     * @var string
     */
    private static $currencyCode = NULL;
    /**
     * Is mbstring extension avalable?
     *
     * @var boolean
     */
    private static $isMbstringEnabled = NULL;
    /**
     * Is iconv extension avalable?
     *
     * @var boolean
     */
    private static $isIconvEnabled = NULL;
    const STRING_REGEXP_FRACTION = "(-?)(\\d+)\\s+(\\d+\\/\\d+)";
    /**
     * Build control characters array
     */
    private static function buildControlCharacters()
    {
        for ($i = 0; $i <= 31; $i++) {
            if ($i != 9 && $i != 10 && $i != 13) {
                $find = "_x" . sprintf("%04s", strtoupper(dechex($i))) . "_";
                $replace = chr($i);
                self::$controlCharacters[$find] = $replace;
            }
        }
    }
    /**
     * Build SYLK characters array
     */
    private static function buildSYLKCharacters()
    {
        self::$SYLKCharacters = array("\33 0" => chr(0), "\33 1" => chr(1), "\33 2" => chr(2), "\33 3" => chr(3), "\33 4" => chr(4), "\33 5" => chr(5), "\33 6" => chr(6), "\33 7" => chr(7), "\33 8" => chr(8), "\33 9" => chr(9), "\33 :" => chr(10), "\33 ;" => chr(11), "\33 <" => chr(12), "\33 :" => chr(13), "\33 >" => chr(14), "\33 ?" => chr(15), "\33!0" => chr(16), "\33!1" => chr(17), "\33!2" => chr(18), "\33!3" => chr(19), "\33!4" => chr(20), "\33!5" => chr(21), "\33!6" => chr(22), "\33!7" => chr(23), "\33!8" => chr(24), "\33!9" => chr(25), "\33!:" => chr(26), "\33!;" => chr(27), "\33!<" => chr(28), "\33!=" => chr(29), "\33!>" => chr(30), "\33!?" => chr(31), "\33'?" => chr(127), "\33(0" => "€", "\33(2" => "‚", "\33(3" => "ƒ", "\33(4" => "„", "\33(5" => "…", "\33(6" => "†", "\33(7" => "‡", "\33(8" => "ˆ", "\33(9" => "‰", "\33(:" => "Š", "\33(;" => "‹", "\33Nj" => "Œ", "\33(>" => "Ž", "\33)1" => "‘", "\33)2" => "’", "\33)3" => "“", "\33)4" => "”", "\33)5" => "•", "\33)6" => "–", "\33)7" => "—", "\33)8" => "˜", "\33)9" => "™", "\33):" => "š", "\33);" => "›", "\33Nz" => "œ", "\33)>" => "ž", "\33)?" => "Ÿ", "\33*0" => " ", "\33N!" => "¡", "\33N\"" => "¢", "\33N#" => "£", "\33N(" => "¤", "\33N%" => "¥", "\33*6" => "¦", "\33N'" => "§", "\33NH " => "¨", "\33NS" => "©", "\33Nc" => "ª", "\33N+" => "«", "\33*<" => "¬", "\33*=" => "­", "\33NR" => "®", "\33*?" => "¯", "\33N0" => "°", "\33N1" => "±", "\33N2" => "²", "\33N3" => "³", "\33NB " => "´", "\33N5" => "µ", "\33N6" => "¶", "\33N7" => "·", "\33+8" => "¸", "\33NQ" => "¹", "\33Nk" => "º", "\33N;" => "»", "\33N<" => "¼", "\33N=" => "½", "\33N>" => "¾", "\33N?" => "¿", "\33NAA" => "À", "\33NBA" => "Á", "\33NCA" => "Â", "\33NDA" => "Ã", "\33NHA" => "Ä", "\33NJA" => "Å", "\33Na" => "Æ", "\33NKC" => "Ç", "\33NAE" => "È", "\33NBE" => "É", "\33NCE" => "Ê", "\33NHE" => "Ë", "\33NAI" => "Ì", "\33NBI" => "Í", "\33NCI" => "Î", "\33NHI" => "Ï", "\33Nb" => "Ð", "\33NDN" => "Ñ", "\33NAO" => "Ò", "\33NBO" => "Ó", "\33NCO" => "Ô", "\33NDO" => "Õ", "\33NHO" => "Ö", "\33-7" => "×", "\33Ni" => "Ø", "\33NAU" => "Ù", "\33NBU" => "Ú", "\33NCU" => "Û", "\33NHU" => "Ü", "\33-=" => "Ý", "\33Nl" => "Þ", "\33N{" => "ß", "\33NAa" => "à", "\33NBa" => "á", "\33NCa" => "â", "\33NDa" => "ã", "\33NHa" => "ä", "\33NJa" => "å", "\33Nq" => "æ", "\33NKc" => "ç", "\33NAe" => "è", "\33NBe" => "é", "\33NCe" => "ê", "\33NHe" => "ë", "\33NAi" => "ì", "\33NBi" => "í", "\33NCi" => "î", "\33NHi" => "ï", "\33Ns" => "ð", "\33NDn" => "ñ", "\33NAo" => "ò", "\33NBo" => "ó", "\33NCo" => "ô", "\33NDo" => "õ", "\33NHo" => "ö", "\33/7" => "÷", "\33Ny" => "ø", "\33NAu" => "ù", "\33NBu" => "ú", "\33NCu" => "û", "\33NHu" => "ü", "\33/=" => "ý", "\33N|" => "þ", "\33NHy" => "ÿ");
    }
    /**
     * Get whether mbstring extension is available
     *
     * @return boolean
     */
    public static function getIsMbstringEnabled()
    {
        if (isset($isMbstringEnabled)) {
            return self::$isMbstringEnabled;
        }
        self::$isMbstringEnabled = function_exists("mb_convert_encoding") ? true : false;
        return self::$isMbstringEnabled;
    }
    /**
     * Get whether iconv extension is available
     *
     * @return boolean
     */
    public static function getIsIconvEnabled()
    {
        if (isset($isIconvEnabled)) {
            return self::$isIconvEnabled;
        }
        if (!function_exists("iconv")) {
            self::$isIconvEnabled = false;
            return false;
        }
        if (!@iconv("UTF-8", "UTF-16LE", "x")) {
            self::$isIconvEnabled = false;
            return false;
        }
        if (!@iconv_substr("A", 0, 1, "UTF-8")) {
            self::$isIconvEnabled = false;
            return false;
        }
        if (defined("PHP_OS") && @stristr(PHP_OS, "AIX") && defined("ICONV_IMPL") && @strcasecmp(ICONV_IMPL, "unknown") == 0 && defined("ICONV_VERSION") && @strcasecmp(ICONV_VERSION, "unknown") == 0) {
            self::$isIconvEnabled = false;
            return false;
        }
        self::$isIconvEnabled = true;
        return true;
    }
    public static function buildCharacterSets()
    {
        if (empty($controlCharacters)) {
            self::buildControlCharacters();
        }
        if (empty($SYLKCharacters)) {
            self::buildSYLKCharacters();
        }
    }
    /**
     * Convert from OpenXML escaped control character to PHP control character
     *
     * Excel 2007 team:
     * ----------------
     * That's correct, control characters are stored directly in the shared-strings table.
     * We do encode characters that cannot be represented in XML using the following escape sequence:
     * _xHHHH_ where H represents a hexadecimal character in the character's value...
     * So you could end up with something like _x0008_ in a string (either in a cell value (<v>)
     * element or in the shared string <t> element.
     *
     * @param     string    $value    Value to unescape
     * @return     string
     */
    public static function ControlCharacterOOXML2PHP($value = "")
    {
        return str_replace(array_keys(self::$controlCharacters), array_values(self::$controlCharacters), $value);
    }
    /**
     * Convert from PHP control character to OpenXML escaped control character
     *
     * Excel 2007 team:
     * ----------------
     * That's correct, control characters are stored directly in the shared-strings table.
     * We do encode characters that cannot be represented in XML using the following escape sequence:
     * _xHHHH_ where H represents a hexadecimal character in the character's value...
     * So you could end up with something like _x0008_ in a string (either in a cell value (<v>)
     * element or in the shared string <t> element.
     *
     * @param     string    $value    Value to escape
     * @return     string
     */
    public static function ControlCharacterPHP2OOXML($value = "")
    {
        return str_replace(array_values(self::$controlCharacters), array_keys(self::$controlCharacters), $value);
    }
    /**
     * Try to sanitize UTF8, stripping invalid byte sequences. Not perfect. Does not surrogate characters.
     *
     * @param string $value
     * @return string
     */
    public static function SanitizeUTF8($value)
    {
        if (self::getIsIconvEnabled()) {
            $value = @iconv("UTF-8", "UTF-8", $value);
            return $value;
        }
        if (self::getIsMbstringEnabled()) {
            $value = mb_convert_encoding($value, "UTF-8", "UTF-8");
            return $value;
        }
        return $value;
    }
    /**
     * Check if a string contains UTF8 data
     *
     * @param string $value
     * @return boolean
     */
    public static function IsUTF8($value = "")
    {
        return $value === "" || preg_match("/^./su", $value) === 1;
    }
    /**
     * Formats a numeric value as a string for output in various output writers forcing
     * point as decimal separator in case locale is other than English.
     *
     * @param mixed $value
     * @return string
     */
    public static function FormatNumber($value)
    {
        if (is_float($value)) {
            return str_replace(",", ".", $value);
        }
        return (string) $value;
    }
    /**
     * Converts a UTF-8 string into BIFF8 Unicode string data (8-bit string length)
     * Writes the string using uncompressed notation, no rich text, no Asian phonetics
     * If mbstring extension is not available, ASCII is assumed, and compressed notation is used
     * although this will give wrong results for non-ASCII strings
     * see OpenOffice.org's Documentation of the Microsoft Excel File Format, sect. 2.5.3
     *
     * @param string  $value    UTF-8 encoded string
     * @param mixed[] $arrcRuns Details of rich text runs in $value
     * @return string
     */
    public static function UTF8toBIFF8UnicodeShort($value, $arrcRuns = array())
    {
        $ln = self::CountCharacters($value, "UTF-8");
        if (empty($arrcRuns)) {
            $opt = self::getIsIconvEnabled() || self::getIsMbstringEnabled() ? 1 : 0;
            $data = pack("CC", $ln, $opt);
            $data .= self::ConvertEncoding($value, "UTF-16LE", "UTF-8");
        } else {
            $data = pack("vC", $ln, 9);
            $data .= pack("v", count($arrcRuns));
            $data .= self::ConvertEncoding($value, "UTF-16LE", "UTF-8");
            foreach ($arrcRuns as $cRun) {
                $data .= pack("v", $cRun["strlen"]);
                $data .= pack("v", $cRun["fontidx"]);
            }
        }
        return $data;
    }
    /**
     * Converts a UTF-8 string into BIFF8 Unicode string data (16-bit string length)
     * Writes the string using uncompressed notation, no rich text, no Asian phonetics
     * If mbstring extension is not available, ASCII is assumed, and compressed notation is used
     * although this will give wrong results for non-ASCII strings
     * see OpenOffice.org's Documentation of the Microsoft Excel File Format, sect. 2.5.3
     *
     * @param string $value UTF-8 encoded string
     * @return string
     */
    public static function UTF8toBIFF8UnicodeLong($value)
    {
        $ln = self::CountCharacters($value, "UTF-8");
        $opt = self::getIsIconvEnabled() || self::getIsMbstringEnabled() ? 1 : 0;
        $chars = self::ConvertEncoding($value, "UTF-16LE", "UTF-8");
        $data = pack("vC", $ln, $opt) . $chars;
        return $data;
    }
    /**
     * Convert string from one encoding to another. First try mbstring, then iconv, finally strlen
     *
     * @param string $value
     * @param string $to Encoding to convert to, e.g. 'UTF-8'
     * @param string $from Encoding to convert from, e.g. 'UTF-16LE'
     * @return string
     */
    public static function ConvertEncoding($value, $to, $from)
    {
        if (self::getIsIconvEnabled()) {
            return iconv($from, $to, $value);
        }
        if (self::getIsMbstringEnabled()) {
            return mb_convert_encoding($value, $to, $from);
        }
        if ($from == "UTF-16LE") {
            return self::utf16_decode($value, false);
        }
        if ($from == "UTF-16BE") {
            return self::utf16_decode($value);
        }
        return $value;
    }
    /**
     * Decode UTF-16 encoded strings.
     *
     * Can handle both BOM'ed data and un-BOM'ed data.
     * Assumes Big-Endian byte order if no BOM is available.
     * This function was taken from http://php.net/manual/en/function.utf8-decode.php
     * and $bom_be parameter added.
     *
     * @param   string  $str  UTF-16 encoded data to decode.
     * @return  string  UTF-8 / ISO encoded data.
     * @access  public
     * @version 0.2 / 2010-05-13
     * @author  Rasmus Andersson {@link http://rasmusandersson.se/}
     * @author vadik56
     */
    public static function utf16_decode($str, $bom_be = true)
    {
        if (strlen($str) < 2) {
            return $str;
        }
        $c0 = ord($str[0]);
        $c1 = ord($str[1]);
        if ($c0 == 254 && $c1 == 255) {
            $str = substr($str, 2);
        } else {
            if ($c0 == 255 && $c1 == 254) {
                $str = substr($str, 2);
                $bom_be = false;
            }
        }
        $len = strlen($str);
        $newstr = "";
        $i = 0;
        while ($i < $len) {
            if ($bom_be) {
                $val = ord($str[$i]) << 4;
                $val += ord($str[$i + 1]);
            } else {
                $val = ord($str[$i + 1]) << 4;
                $val += ord($str[$i]);
            }
            $newstr .= $val == 552 ? "\n" : chr($val);
            $i += 2;
        }
        return $newstr;
    }
    /**
     * Get character count. First try mbstring, then iconv, finally strlen
     *
     * @param string $value
     * @param string $enc Encoding
     * @return int Character count
     */
    public static function CountCharacters($value, $enc = "UTF-8")
    {
        if (self::getIsMbstringEnabled()) {
            return mb_strlen($value, $enc);
        }
        if (self::getIsIconvEnabled()) {
            return iconv_strlen($value, $enc);
        }
        return strlen($value);
    }
    /**
     * Get a substring of a UTF-8 encoded string. First try mbstring, then iconv, finally strlen
     *
     * @param string $pValue UTF-8 encoded string
     * @param int $pStart Start offset
     * @param int $pLength Maximum number of characters in substring
     * @return string
     */
    public static function Substring($pValue = "", $pStart = 0, $pLength = 0)
    {
        if (self::getIsMbstringEnabled()) {
            return mb_substr($pValue, $pStart, $pLength, "UTF-8");
        }
        if (self::getIsIconvEnabled()) {
            return iconv_substr($pValue, $pStart, $pLength, "UTF-8");
        }
        return substr($pValue, $pStart, $pLength);
    }
    /**
     * Convert a UTF-8 encoded string to upper case
     *
     * @param string $pValue UTF-8 encoded string
     * @return string
     */
    public static function StrToUpper($pValue = "")
    {
        if (function_exists("mb_convert_case")) {
            return mb_convert_case($pValue, MB_CASE_UPPER, "UTF-8");
        }
        return strtoupper($pValue);
    }
    /**
     * Convert a UTF-8 encoded string to lower case
     *
     * @param string $pValue UTF-8 encoded string
     * @return string
     */
    public static function StrToLower($pValue = "")
    {
        if (function_exists("mb_convert_case")) {
            return mb_convert_case($pValue, MB_CASE_LOWER, "UTF-8");
        }
        return strtolower($pValue);
    }
    /**
     * Convert a UTF-8 encoded string to title/proper case
     *    (uppercase every first character in each word, lower case all other characters)
     *
     * @param string $pValue UTF-8 encoded string
     * @return string
     */
    public static function StrToTitle($pValue = "")
    {
        if (function_exists("mb_convert_case")) {
            return mb_convert_case($pValue, MB_CASE_TITLE, "UTF-8");
        }
        return ucwords($pValue);
    }
    public static function mb_is_upper($char)
    {
        return mb_strtolower($char, "UTF-8") != $char;
    }
    public static function mb_str_split($string)
    {
        return preg_split("/(?<!^)(?!\$)/u", $string);
    }
    /**
     * Reverse the case of a string, so that all uppercase characters become lowercase
     *    and all lowercase characters become uppercase
     *
     * @param string $pValue UTF-8 encoded string
     * @return string
     */
    public static function StrCaseReverse($pValue = "")
    {
        if (self::getIsMbstringEnabled()) {
            $characters = self::mb_str_split($pValue);
            foreach ($characters as &$character) {
                if (self::mb_is_upper($character)) {
                    $character = mb_strtolower($character, "UTF-8");
                } else {
                    $character = mb_strtoupper($character, "UTF-8");
                }
            }
            return implode("", $characters);
        } else {
            return strtolower($pValue) ^ strtoupper($pValue) ^ $pValue;
        }
    }
    /**
     * Identify whether a string contains a fractional numeric value,
     *    and convert it to a numeric if it is
     *
     * @param string &$operand string value to test
     * @return boolean
     */
    public static function convertToNumberIfFraction(&$operand)
    {
        if (preg_match("/^" . self::STRING_REGEXP_FRACTION . "\$/i", $operand, $match)) {
            $sign = $match[1] == "-" ? "-" : "+";
            $fractionFormula = "=" . $sign . $match[2] . $sign . $match[3];
            $operand = PHPExcel_Calculation::getInstance()->_calculateFormulaValue($fractionFormula);
            return true;
        }
        return false;
    }
    /**
     * Get the decimal separator. If it has not yet been set explicitly, try to obtain number
     * formatting information from locale.
     *
     * @return string
     */
    public static function getDecimalSeparator()
    {
        if (!isset($decimalSeparator)) {
            $localeconv = localeconv();
            self::$decimalSeparator = $localeconv["decimal_point"] != "" ? $localeconv["decimal_point"] : $localeconv["mon_decimal_point"];
            if (self::$decimalSeparator == "") {
                self::$decimalSeparator = ".";
            }
        }
        return self::$decimalSeparator;
    }
    /**
     * Set the decimal separator. Only used by PHPExcel_Style_NumberFormat::toFormattedString()
     * to format output by PHPExcel_Writer_HTML and PHPExcel_Writer_PDF
     *
     * @param string $pValue Character for decimal separator
     */
    public static function setDecimalSeparator($pValue = ".")
    {
        self::$decimalSeparator = $pValue;
    }
    /**
     * Get the thousands separator. If it has not yet been set explicitly, try to obtain number
     * formatting information from locale.
     *
     * @return string
     */
    public static function getThousandsSeparator()
    {
        if (!isset($thousandsSeparator)) {
            $localeconv = localeconv();
            self::$thousandsSeparator = $localeconv["thousands_sep"] != "" ? $localeconv["thousands_sep"] : $localeconv["mon_thousands_sep"];
            if (self::$thousandsSeparator == "") {
                self::$thousandsSeparator = ",";
            }
        }
        return self::$thousandsSeparator;
    }
    /**
     * Set the thousands separator. Only used by PHPExcel_Style_NumberFormat::toFormattedString()
     * to format output by PHPExcel_Writer_HTML and PHPExcel_Writer_PDF
     *
     * @param string $pValue Character for thousands separator
     */
    public static function setThousandsSeparator($pValue = ",")
    {
        self::$thousandsSeparator = $pValue;
    }
    /**
     *    Get the currency code. If it has not yet been set explicitly, try to obtain the
     *        symbol information from locale.
     *
     * @return string
     */
    public static function getCurrencyCode()
    {
        if (!isset($currencyCode)) {
            $localeconv = localeconv();
            self::$currencyCode = $localeconv["currency_symbol"] != "" ? $localeconv["currency_symbol"] : $localeconv["int_curr_symbol"];
            if (self::$currencyCode == "") {
                self::$currencyCode = "\$";
            }
        }
        return self::$currencyCode;
    }
    /**
     * Set the currency code. Only used by PHPExcel_Style_NumberFormat::toFormattedString()
     *        to format output by PHPExcel_Writer_HTML and PHPExcel_Writer_PDF
     *
     * @param string $pValue Character for currency code
     */
    public static function setCurrencyCode($pValue = "\$")
    {
        self::$currencyCode = $pValue;
    }
    /**
     * Convert SYLK encoded string to UTF-8
     *
     * @param string $pValue
     * @return string UTF-8 encoded string
     */
    public static function SYLKtoUTF8($pValue = "")
    {
        if (strpos($pValue, "\33") === false) {
            return $pValue;
        }
        foreach (self::$SYLKCharacters as $k => $v) {
            $pValue = str_replace($k, $v, $pValue);
        }
        return $pValue;
    }
    /**
     * Retrieve any leading numeric part of a string, or return the full string if no leading numeric
     *    (handles basic integer or float, but not exponent or non decimal)
     *
     * @param    string    $value
     * @return    mixed    string or only the leading numeric part of the string
     */
    public static function testStringAsNumeric($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
        $v = floatval($value);
        return is_numeric(substr($value, 0, strlen($v))) ? $v : $value;
    }
}

?>