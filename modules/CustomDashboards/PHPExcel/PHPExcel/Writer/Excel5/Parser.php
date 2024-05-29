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
 * PHPExcel_Writer_Excel5_Parser
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
 * @package    PHPExcel_Writer_Excel5
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Writer_Excel5_Parser
{
    /**
     * The index of the character we are currently looking at
     * @var integer
     */
    public $currentCharacter = NULL;
    /**
     * The token we are working on.
     * @var string
     */
    public $currentToken = NULL;
    /**
     * The formula to parse
     * @var string
     */
    private $formula = NULL;
    /**
     * The character ahead of the current char
     * @var string
     */
    public $lookAhead = NULL;
    /**
     * The parse tree to be generated
     * @var string
     */
    private $parseTree = NULL;
    /**
     * Array of external sheets
     * @var array
     */
    private $externalSheets = NULL;
    /**
     * Array of sheet references in the form of REF structures
     * @var array
     */
    public $references = NULL;
    const REGEX_SHEET_TITLE_UNQUOTED = "[^\\*\\:\\/\\\\\\?\\[\\]\\+\\-\\% \\'\\^\\&\\<\\>\\=\\,\\;\\#\\(\\)\\\"\\{\\}]+";
    const REGEX_SHEET_TITLE_QUOTED = "(([^\\*\\:\\/\\\\\\?\\[\\]\\'])+|(\\'\\')+)+";
    /**
     * The class constructor
     *
     */
    public function __construct()
    {
        $this->currentCharacter = 0;
        $this->currentToken = "";
        $this->formula = "";
        $this->lookAhead = "";
        $this->parseTree = "";
        $this->initializeHashes();
        $this->externalSheets = array();
        $this->references = array();
    }
    /**
     * Initialize the ptg and function hashes.
     *
     * @access private
     */
    private function initializeHashes()
    {
        $this->ptg = array("ptgExp" => 1, "ptgTbl" => 2, "ptgAdd" => 3, "ptgSub" => 4, "ptgMul" => 5, "ptgDiv" => 6, "ptgPower" => 7, "ptgConcat" => 8, "ptgLT" => 9, "ptgLE" => 10, "ptgEQ" => 11, "ptgGE" => 12, "ptgGT" => 13, "ptgNE" => 14, "ptgIsect" => 15, "ptgUnion" => 16, "ptgRange" => 17, "ptgUplus" => 18, "ptgUminus" => 19, "ptgPercent" => 20, "ptgParen" => 21, "ptgMissArg" => 22, "ptgStr" => 23, "ptgAttr" => 25, "ptgSheet" => 26, "ptgEndSheet" => 27, "ptgErr" => 28, "ptgBool" => 29, "ptgInt" => 30, "ptgNum" => 31, "ptgArray" => 32, "ptgFunc" => 33, "ptgFuncVar" => 34, "ptgName" => 35, "ptgRef" => 36, "ptgArea" => 37, "ptgMemArea" => 38, "ptgMemErr" => 39, "ptgMemNoMem" => 40, "ptgMemFunc" => 41, "ptgRefErr" => 42, "ptgAreaErr" => 43, "ptgRefN" => 44, "ptgAreaN" => 45, "ptgMemAreaN" => 46, "ptgMemNoMemN" => 47, "ptgNameX" => 57, "ptgRef3d" => 58, "ptgArea3d" => 59, "ptgRefErr3d" => 60, "ptgAreaErr3d" => 61, "ptgArrayV" => 64, "ptgFuncV" => 65, "ptgFuncVarV" => 66, "ptgNameV" => 67, "ptgRefV" => 68, "ptgAreaV" => 69, "ptgMemAreaV" => 70, "ptgMemErrV" => 71, "ptgMemNoMemV" => 72, "ptgMemFuncV" => 73, "ptgRefErrV" => 74, "ptgAreaErrV" => 75, "ptgRefNV" => 76, "ptgAreaNV" => 77, "ptgMemAreaNV" => 78, "ptgMemNoMemN" => 79, "ptgFuncCEV" => 88, "ptgNameXV" => 89, "ptgRef3dV" => 90, "ptgArea3dV" => 91, "ptgRefErr3dV" => 92, "ptgAreaErr3d" => 93, "ptgArrayA" => 96, "ptgFuncA" => 97, "ptgFuncVarA" => 98, "ptgNameA" => 99, "ptgRefA" => 100, "ptgAreaA" => 101, "ptgMemAreaA" => 102, "ptgMemErrA" => 103, "ptgMemNoMemA" => 104, "ptgMemFuncA" => 105, "ptgRefErrA" => 106, "ptgAreaErrA" => 107, "ptgRefNA" => 108, "ptgAreaNA" => 109, "ptgMemAreaNA" => 110, "ptgMemNoMemN" => 111, "ptgFuncCEA" => 120, "ptgNameXA" => 121, "ptgRef3dA" => 122, "ptgArea3dA" => 123, "ptgRefErr3dA" => 124, "ptgAreaErr3d" => 125);
        $this->functions = array("COUNT" => array(0, -1, 0, 0), "IF" => array(1, -1, 1, 0), "ISNA" => array(2, 1, 1, 0), "ISERROR" => array(3, 1, 1, 0), "SUM" => array(4, -1, 0, 0), "AVERAGE" => array(5, -1, 0, 0), "MIN" => array(6, -1, 0, 0), "MAX" => array(7, -1, 0, 0), "ROW" => array(8, -1, 0, 0), "COLUMN" => array(9, -1, 0, 0), "NA" => array(10, 0, 0, 0), "NPV" => array(11, -1, 1, 0), "STDEV" => array(12, -1, 0, 0), "DOLLAR" => array(13, -1, 1, 0), "FIXED" => array(14, -1, 1, 0), "SIN" => array(15, 1, 1, 0), "COS" => array(16, 1, 1, 0), "TAN" => array(17, 1, 1, 0), "ATAN" => array(18, 1, 1, 0), "PI" => array(19, 0, 1, 0), "SQRT" => array(20, 1, 1, 0), "EXP" => array(21, 1, 1, 0), "LN" => array(22, 1, 1, 0), "LOG10" => array(23, 1, 1, 0), "ABS" => array(24, 1, 1, 0), "INT" => array(25, 1, 1, 0), "SIGN" => array(26, 1, 1, 0), "ROUND" => array(27, 2, 1, 0), "LOOKUP" => array(28, -1, 0, 0), "INDEX" => array(29, -1, 0, 1), "REPT" => array(30, 2, 1, 0), "MID" => array(31, 3, 1, 0), "LEN" => array(32, 1, 1, 0), "VALUE" => array(33, 1, 1, 0), "TRUE" => array(34, 0, 1, 0), "FALSE" => array(35, 0, 1, 0), "AND" => array(36, -1, 0, 0), "OR" => array(37, -1, 0, 0), "NOT" => array(38, 1, 1, 0), "MOD" => array(39, 2, 1, 0), "DCOUNT" => array(40, 3, 0, 0), "DSUM" => array(41, 3, 0, 0), "DAVERAGE" => array(42, 3, 0, 0), "DMIN" => array(43, 3, 0, 0), "DMAX" => array(44, 3, 0, 0), "DSTDEV" => array(45, 3, 0, 0), "VAR" => array(46, -1, 0, 0), "DVAR" => array(47, 3, 0, 0), "TEXT" => array(48, 2, 1, 0), "LINEST" => array(49, -1, 0, 0), "TREND" => array(50, -1, 0, 0), "LOGEST" => array(51, -1, 0, 0), "GROWTH" => array(52, -1, 0, 0), "PV" => array(56, -1, 1, 0), "FV" => array(57, -1, 1, 0), "NPER" => array(58, -1, 1, 0), "PMT" => array(59, -1, 1, 0), "RATE" => array(60, -1, 1, 0), "MIRR" => array(61, 3, 0, 0), "IRR" => array(62, -1, 0, 0), "RAND" => array(63, 0, 1, 1), "MATCH" => array(64, -1, 0, 0), "DATE" => array(65, 3, 1, 0), "TIME" => array(66, 3, 1, 0), "DAY" => array(67, 1, 1, 0), "MONTH" => array(68, 1, 1, 0), "YEAR" => array(69, 1, 1, 0), "WEEKDAY" => array(70, -1, 1, 0), "HOUR" => array(71, 1, 1, 0), "MINUTE" => array(72, 1, 1, 0), "SECOND" => array(73, 1, 1, 0), "NOW" => array(74, 0, 1, 1), "AREAS" => array(75, 1, 0, 1), "ROWS" => array(76, 1, 0, 1), "COLUMNS" => array(77, 1, 0, 1), "OFFSET" => array(78, -1, 0, 1), "SEARCH" => array(82, -1, 1, 0), "TRANSPOSE" => array(83, 1, 1, 0), "TYPE" => array(86, 1, 1, 0), "ATAN2" => array(97, 2, 1, 0), "ASIN" => array(98, 1, 1, 0), "ACOS" => array(99, 1, 1, 0), "CHOOSE" => array(100, -1, 1, 0), "HLOOKUP" => array(101, -1, 0, 0), "VLOOKUP" => array(102, -1, 0, 0), "ISREF" => array(105, 1, 0, 0), "LOG" => array(109, -1, 1, 0), "CHAR" => array(111, 1, 1, 0), "LOWER" => array(112, 1, 1, 0), "UPPER" => array(113, 1, 1, 0), "PROPER" => array(114, 1, 1, 0), "LEFT" => array(115, -1, 1, 0), "RIGHT" => array(116, -1, 1, 0), "EXACT" => array(117, 2, 1, 0), "TRIM" => array(118, 1, 1, 0), "REPLACE" => array(119, 4, 1, 0), "SUBSTITUTE" => array(120, -1, 1, 0), "CODE" => array(121, 1, 1, 0), "FIND" => array(124, -1, 1, 0), "CELL" => array(125, -1, 0, 1), "ISERR" => array(126, 1, 1, 0), "ISTEXT" => array(127, 1, 1, 0), "ISNUMBER" => array(128, 1, 1, 0), "ISBLANK" => array(129, 1, 1, 0), "T" => array(130, 1, 0, 0), "N" => array(131, 1, 0, 0), "DATEVALUE" => array(140, 1, 1, 0), "TIMEVALUE" => array(141, 1, 1, 0), "SLN" => array(142, 3, 1, 0), "SYD" => array(143, 4, 1, 0), "DDB" => array(144, -1, 1, 0), "INDIRECT" => array(148, -1, 1, 1), "CALL" => array(150, -1, 1, 0), "CLEAN" => array(162, 1, 1, 0), "MDETERM" => array(163, 1, 2, 0), "MINVERSE" => array(164, 1, 2, 0), "MMULT" => array(165, 2, 2, 0), "IPMT" => array(167, -1, 1, 0), "PPMT" => array(168, -1, 1, 0), "COUNTA" => array(169, -1, 0, 0), "PRODUCT" => array(183, -1, 0, 0), "FACT" => array(184, 1, 1, 0), "DPRODUCT" => array(189, 3, 0, 0), "ISNONTEXT" => array(190, 1, 1, 0), "STDEVP" => array(193, -1, 0, 0), "VARP" => array(194, -1, 0, 0), "DSTDEVP" => array(195, 3, 0, 0), "DVARP" => array(196, 3, 0, 0), "TRUNC" => array(197, -1, 1, 0), "ISLOGICAL" => array(198, 1, 1, 0), "DCOUNTA" => array(199, 3, 0, 0), "USDOLLAR" => array(204, -1, 1, 0), "FINDB" => array(205, -1, 1, 0), "SEARCHB" => array(206, -1, 1, 0), "REPLACEB" => array(207, 4, 1, 0), "LEFTB" => array(208, -1, 1, 0), "RIGHTB" => array(209, -1, 1, 0), "MIDB" => array(210, 3, 1, 0), "LENB" => array(211, 1, 1, 0), "ROUNDUP" => array(212, 2, 1, 0), "ROUNDDOWN" => array(213, 2, 1, 0), "ASC" => array(214, 1, 1, 0), "DBCS" => array(215, 1, 1, 0), "RANK" => array(216, -1, 0, 0), "ADDRESS" => array(219, -1, 1, 0), "DAYS360" => array(220, -1, 1, 0), "TODAY" => array(221, 0, 1, 1), "VDB" => array(222, -1, 1, 0), "MEDIAN" => array(227, -1, 0, 0), "SUMPRODUCT" => array(228, -1, 2, 0), "SINH" => array(229, 1, 1, 0), "COSH" => array(230, 1, 1, 0), "TANH" => array(231, 1, 1, 0), "ASINH" => array(232, 1, 1, 0), "ACOSH" => array(233, 1, 1, 0), "ATANH" => array(234, 1, 1, 0), "DGET" => array(235, 3, 0, 0), "INFO" => array(244, 1, 1, 1), "DB" => array(247, -1, 1, 0), "FREQUENCY" => array(252, 2, 0, 0), "ERROR.TYPE" => array(261, 1, 1, 0), "REGISTER.ID" => array(267, -1, 1, 0), "AVEDEV" => array(269, -1, 0, 0), "BETADIST" => array(270, -1, 1, 0), "GAMMALN" => array(271, 1, 1, 0), "BETAINV" => array(272, -1, 1, 0), "BINOMDIST" => array(273, 4, 1, 0), "CHIDIST" => array(274, 2, 1, 0), "CHIINV" => array(275, 2, 1, 0), "COMBIN" => array(276, 2, 1, 0), "CONFIDENCE" => array(277, 3, 1, 0), "CRITBINOM" => array(278, 3, 1, 0), "EVEN" => array(279, 1, 1, 0), "EXPONDIST" => array(280, 3, 1, 0), "FDIST" => array(281, 3, 1, 0), "FINV" => array(282, 3, 1, 0), "FISHER" => array(283, 1, 1, 0), "FISHERINV" => array(284, 1, 1, 0), "FLOOR" => array(285, 2, 1, 0), "GAMMADIST" => array(286, 4, 1, 0), "GAMMAINV" => array(287, 3, 1, 0), "CEILING" => array(288, 2, 1, 0), "HYPGEOMDIST" => array(289, 4, 1, 0), "LOGNORMDIST" => array(290, 3, 1, 0), "LOGINV" => array(291, 3, 1, 0), "NEGBINOMDIST" => array(292, 3, 1, 0), "NORMDIST" => array(293, 4, 1, 0), "NORMSDIST" => array(294, 1, 1, 0), "NORMINV" => array(295, 3, 1, 0), "NORMSINV" => array(296, 1, 1, 0), "STANDARDIZE" => array(297, 3, 1, 0), "ODD" => array(298, 1, 1, 0), "PERMUT" => array(299, 2, 1, 0), "POISSON" => array(300, 3, 1, 0), "TDIST" => array(301, 3, 1, 0), "WEIBULL" => array(302, 4, 1, 0), "SUMXMY2" => array(303, 2, 2, 0), "SUMX2MY2" => array(304, 2, 2, 0), "SUMX2PY2" => array(305, 2, 2, 0), "CHITEST" => array(306, 2, 2, 0), "CORREL" => array(307, 2, 2, 0), "COVAR" => array(308, 2, 2, 0), "FORECAST" => array(309, 3, 2, 0), "FTEST" => array(310, 2, 2, 0), "INTERCEPT" => array(311, 2, 2, 0), "PEARSON" => array(312, 2, 2, 0), "RSQ" => array(313, 2, 2, 0), "STEYX" => array(314, 2, 2, 0), "SLOPE" => array(315, 2, 2, 0), "TTEST" => array(316, 4, 2, 0), "PROB" => array(317, -1, 2, 0), "DEVSQ" => array(318, -1, 0, 0), "GEOMEAN" => array(319, -1, 0, 0), "HARMEAN" => array(320, -1, 0, 0), "SUMSQ" => array(321, -1, 0, 0), "KURT" => array(322, -1, 0, 0), "SKEW" => array(323, -1, 0, 0), "ZTEST" => array(324, -1, 0, 0), "LARGE" => array(325, 2, 0, 0), "SMALL" => array(326, 2, 0, 0), "QUARTILE" => array(327, 2, 0, 0), "PERCENTILE" => array(328, 2, 0, 0), "PERCENTRANK" => array(329, -1, 0, 0), "MODE" => array(330, -1, 2, 0), "TRIMMEAN" => array(331, 2, 0, 0), "TINV" => array(332, 2, 1, 0), "CONCATENATE" => array(336, -1, 1, 0), "POWER" => array(337, 2, 1, 0), "RADIANS" => array(342, 1, 1, 0), "DEGREES" => array(343, 1, 1, 0), "SUBTOTAL" => array(344, -1, 0, 0), "SUMIF" => array(345, -1, 0, 0), "COUNTIF" => array(346, 2, 0, 0), "COUNTBLANK" => array(347, 1, 0, 0), "ISPMT" => array(350, 4, 1, 0), "DATEDIF" => array(351, 3, 1, 0), "DATESTRING" => array(352, 1, 1, 0), "NUMBERSTRING" => array(353, 2, 1, 0), "ROMAN" => array(354, -1, 1, 0), "GETPIVOTDATA" => array(358, -1, 0, 0), "HYPERLINK" => array(359, -1, 1, 0), "PHONETIC" => array(360, 1, 0, 0), "AVERAGEA" => array(361, -1, 0, 0), "MAXA" => array(362, -1, 0, 0), "MINA" => array(363, -1, 0, 0), "STDEVPA" => array(364, -1, 0, 0), "VARPA" => array(365, -1, 0, 0), "STDEVA" => array(366, -1, 0, 0), "VARA" => array(367, -1, 0, 0), "BAHTTEXT" => array(368, 1, 0, 0));
    }
    /**
     * Convert a token to the proper ptg value.
     *
     * @access private
     * @param mixed $token The token to convert.
     * @return mixed the converted token on success
     */
    private function convert($token)
    {
        if (preg_match("/\"([^\"]|\"\"){0,255}\"/", $token)) {
            return $this->convertString($token);
        }
        if (is_numeric($token)) {
            return $this->convertNumber($token);
        }
        if (preg_match("/^\\\$?([A-Ia-i]?[A-Za-z])\\\$?(\\d+)\$/", $token)) {
            return $this->convertRef2d($token);
        }
        if (preg_match("/^" . self::REGEX_SHEET_TITLE_UNQUOTED . "(\\:" . self::REGEX_SHEET_TITLE_UNQUOTED . ")?\\!\\\$?[A-Ia-i]?[A-Za-z]\\\$?(\\d+)\$/u", $token)) {
            return $this->convertRef3d($token);
        }
        if (preg_match("/^'" . self::REGEX_SHEET_TITLE_QUOTED . "(\\:" . self::REGEX_SHEET_TITLE_QUOTED . ")?'\\!\\\$?[A-Ia-i]?[A-Za-z]\\\$?(\\d+)\$/u", $token)) {
            return $this->convertRef3d($token);
        }
        if (preg_match("/^(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?(\\d+)\\:(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?(\\d+)\$/", $token)) {
            return $this->convertRange2d($token);
        }
        if (preg_match("/^" . self::REGEX_SHEET_TITLE_UNQUOTED . "(\\:" . self::REGEX_SHEET_TITLE_UNQUOTED . ")?\\!\\\$?([A-Ia-i]?[A-Za-z])?\\\$?(\\d+)\\:\\\$?([A-Ia-i]?[A-Za-z])?\\\$?(\\d+)\$/u", $token)) {
            return $this->convertRange3d($token);
        }
        if (preg_match("/^'" . self::REGEX_SHEET_TITLE_QUOTED . "(\\:" . self::REGEX_SHEET_TITLE_QUOTED . ")?'\\!\\\$?([A-Ia-i]?[A-Za-z])?\\\$?(\\d+)\\:\\\$?([A-Ia-i]?[A-Za-z])?\\\$?(\\d+)\$/u", $token)) {
            return $this->convertRange3d($token);
        }
        if (isset($this->ptg[$token])) {
            return pack("C", $this->ptg[$token]);
        }
        if (preg_match("/^#[A-Z0\\/]{3,5}[!?]{1}\$/", $token) || $token == "#N/A") {
            return $this->convertError($token);
        }
        if ($token == "arg") {
            return "";
        }
        throw new PHPExcel_Writer_Exception("Unknown token " . $token);
    }
    /**
     * Convert a number token to ptgInt or ptgNum
     *
     * @access private
     * @param mixed $num an integer or double for conversion to its ptg value
     */
    private function convertNumber($num)
    {
        if (preg_match("/^\\d+\$/", $num) && $num <= 65535) {
            return pack("Cv", $this->ptg["ptgInt"], $num);
        }
        if (PHPExcel_Writer_Excel5_BIFFwriter::getByteOrder()) {
            $num = strrev($num);
        }
        return pack("Cd", $this->ptg["ptgNum"], $num);
    }
    /**
     * Convert a string token to ptgStr
     *
     * @access private
     * @param string $string A string for conversion to its ptg value.
     * @return mixed the converted token on success
     */
    private function convertString($string)
    {
        $string = substr($string, 1, strlen($string) - 2);
        if (255 < strlen($string)) {
            throw new PHPExcel_Writer_Exception("String is too long");
        }
        return pack("C", $this->ptg["ptgStr"]) . PHPExcel_Shared_String::UTF8toBIFF8UnicodeShort($string);
    }
    /**
     * Convert a function to a ptgFunc or ptgFuncVarV depending on the number of
     * args that it takes.
     *
     * @access private
     * @param string  $token    The name of the function for convertion to ptg value.
     * @param integer $num_args The number of arguments the function receives.
     * @return string The packed ptg for the function
     */
    private function convertFunction($token, $num_args)
    {
        $args = $this->functions[$token][1];
        if (0 <= $args) {
            return pack("Cv", $this->ptg["ptgFuncV"], $this->functions[$token][0]);
        }
        if ($args == -1) {
            return pack("CCv", $this->ptg["ptgFuncVarV"], $num_args, $this->functions[$token][0]);
        }
    }
    /**
     * Convert an Excel range such as A1:D4 to a ptgRefV.
     *
     * @access private
     * @param string    $range    An Excel range in the A1:A2
     * @param int        $class
     */
    private function convertRange2d($range, $class = 0)
    {
        if (preg_match("/^(\\\$)?([A-Ia-i]?[A-Za-z])(\\\$)?(\\d+)\\:(\\\$)?([A-Ia-i]?[A-Za-z])(\\\$)?(\\d+)\$/", $range)) {
            list($cell1, $cell2) = explode(":", $range);
            list($row1, $col1) = $this->cellToPackedRowcol($cell1);
            list($row2, $col2) = $this->cellToPackedRowcol($cell2);
            if ($class == 0) {
                $ptgArea = pack("C", $this->ptg["ptgArea"]);
            } else {
                if ($class == 1) {
                    $ptgArea = pack("C", $this->ptg["ptgAreaV"]);
                } else {
                    if ($class == 2) {
                        $ptgArea = pack("C", $this->ptg["ptgAreaA"]);
                    } else {
                        throw new PHPExcel_Writer_Exception("Unknown class " . $class);
                    }
                }
            }
            return $ptgArea . $row1 . $row2 . $col1 . $col2;
        }
        throw new PHPExcel_Writer_Exception("Unknown range separator");
    }
    /**
     * Convert an Excel 3d range such as "Sheet1!A1:D4" or "Sheet1:Sheet2!A1:D4" to
     * a ptgArea3d.
     *
     * @access private
     * @param string $token An Excel range in the Sheet1!A1:A2 format.
     * @return mixed The packed ptgArea3d token on success.
     */
    private function convertRange3d($token)
    {
        list($ext_ref, $range) = explode("!", $token);
        $ext_ref = $this->getRefIndex($ext_ref);
        list($cell1, $cell2) = explode(":", $range);
        if (preg_match("/^(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?(\\d+)\$/", $cell1)) {
            list($row1, $col1) = $this->cellToPackedRowcol($cell1);
            list($row2, $col2) = $this->cellToPackedRowcol($cell2);
        } else {
            list($row1, $col1, $row2, $col2) = $this->rangeToPackedRange($cell1 . ":" . $cell2);
        }
        $ptgArea = pack("C", $this->ptg["ptgArea3d"]);
        return $ptgArea . $ext_ref . $row1 . $row2 . $col1 . $col2;
    }
    /**
     * Convert an Excel reference such as A1, $B2, C$3 or $D$4 to a ptgRefV.
     *
     * @access private
     * @param string $cell An Excel cell reference
     * @return string The cell in packed() format with the corresponding ptg
     */
    private function convertRef2d($cell)
    {
        $cell_array = $this->cellToPackedRowcol($cell);
        list($row, $col) = $cell_array;
        $ptgRef = pack("C", $this->ptg["ptgRefA"]);
        return $ptgRef . $row . $col;
    }
    /**
     * Convert an Excel 3d reference such as "Sheet1!A1" or "Sheet1:Sheet2!A1" to a
     * ptgRef3d.
     *
     * @access private
     * @param string $cell An Excel cell reference
     * @return mixed The packed ptgRef3d token on success.
     */
    private function convertRef3d($cell)
    {
        list($ext_ref, $cell) = explode("!", $cell);
        $ext_ref = $this->getRefIndex($ext_ref);
        list($row, $col) = $this->cellToPackedRowcol($cell);
        $ptgRef = pack("C", $this->ptg["ptgRef3dA"]);
        return $ptgRef . $ext_ref . $row . $col;
    }
    /**
     * Convert an error code to a ptgErr
     *
     * @access    private
     * @param    string    $errorCode    The error code for conversion to its ptg value
     * @return    string                The error code ptgErr
     */
    private function convertError($errorCode)
    {
        switch ($errorCode) {
            case "#NULL!":
                return pack("C", 0);
            case "#DIV/0!":
                return pack("C", 7);
            case "#VALUE!":
                return pack("C", 15);
            case "#REF!":
                return pack("C", 23);
            case "#NAME?":
                return pack("C", 29);
            case "#NUM!":
                return pack("C", 36);
            case "#N/A":
                return pack("C", 42);
        }
        return pack("C", 255);
    }
    /**
     * Convert the sheet name part of an external reference, for example "Sheet1" or
     * "Sheet1:Sheet2", to a packed structure.
     *
     * @access    private
     * @param    string    $ext_ref    The name of the external reference
     * @return    string                The reference index in packed() format
     */
    private function packExtRef($ext_ref)
    {
        $ext_ref = preg_replace("/^'/", "", $ext_ref);
        $ext_ref = preg_replace("/'\$/", "", $ext_ref);
        if (preg_match("/:/", $ext_ref)) {
            list($sheet_name1, $sheet_name2) = explode(":", $ext_ref);
            $sheet1 = $this->getSheetIndex($sheet_name1);
            if ($sheet1 == -1) {
                throw new PHPExcel_Writer_Exception("Unknown sheet name " . $sheet_name1 . " in formula");
            }
            $sheet2 = $this->getSheetIndex($sheet_name2);
            if ($sheet2 == -1) {
                throw new PHPExcel_Writer_Exception("Unknown sheet name " . $sheet_name2 . " in formula");
            }
            if ($sheet2 < $sheet1) {
                list($sheet1, $sheet2) = array($sheet2, $sheet1);
            }
        } else {
            $sheet1 = $this->getSheetIndex($ext_ref);
            if ($sheet1 == -1) {
                throw new PHPExcel_Writer_Exception("Unknown sheet name " . $ext_ref . " in formula");
            }
            $sheet2 = $sheet1;
        }
        $offset = -1 - $sheet1;
        return pack("vdvv", $offset, 0, $sheet1, $sheet2);
    }
    /**
     * Look up the REF index that corresponds to an external sheet name
     * (or range). If it doesn't exist yet add it to the workbook's references
     * array. It assumes all sheet names given must exist.
     *
     * @access private
     * @param string $ext_ref The name of the external reference
     * @return mixed The reference index in packed() format on success
     */
    private function getRefIndex($ext_ref)
    {
        $ext_ref = preg_replace("/^'/", "", $ext_ref);
        $ext_ref = preg_replace("/'\$/", "", $ext_ref);
        $ext_ref = str_replace("''", "'", $ext_ref);
        if (preg_match("/:/", $ext_ref)) {
            list($sheet_name1, $sheet_name2) = explode(":", $ext_ref);
            $sheet1 = $this->getSheetIndex($sheet_name1);
            if ($sheet1 == -1) {
                throw new PHPExcel_Writer_Exception("Unknown sheet name " . $sheet_name1 . " in formula");
            }
            $sheet2 = $this->getSheetIndex($sheet_name2);
            if ($sheet2 == -1) {
                throw new PHPExcel_Writer_Exception("Unknown sheet name " . $sheet_name2 . " in formula");
            }
            if ($sheet2 < $sheet1) {
                list($sheet1, $sheet2) = array($sheet2, $sheet1);
            }
        } else {
            $sheet1 = $this->getSheetIndex($ext_ref);
            if ($sheet1 == -1) {
                throw new PHPExcel_Writer_Exception("Unknown sheet name " . $ext_ref . " in formula");
            }
            $sheet2 = $sheet1;
        }
        $supbook_index = 0;
        $ref = pack("vvv", $supbook_index, $sheet1, $sheet2);
        $totalreferences = count($this->references);
        $index = -1;
        for ($i = 0; $i < $totalreferences; $i++) {
            if ($ref == $this->references[$i]) {
                $index = $i;
                break;
            }
        }
        if ($index == -1) {
            $this->references[$totalreferences] = $ref;
            $index = $totalreferences;
        }
        return pack("v", $index);
    }
    /**
     * Look up the index that corresponds to an external sheet name. The hash of
     * sheet names is updated by the addworksheet() method of the
     * PHPExcel_Writer_Excel5_Workbook class.
     *
     * @access    private
     * @param    string    $sheet_name        Sheet name
     * @return    integer                    The sheet index, -1 if the sheet was not found
     */
    private function getSheetIndex($sheet_name)
    {
        if (!isset($this->externalSheets[$sheet_name])) {
            return -1;
        }
        return $this->externalSheets[$sheet_name];
    }
    /**
     * This method is used to update the array of sheet names. It is
     * called by the addWorksheet() method of the
     * PHPExcel_Writer_Excel5_Workbook class.
     *
     * @access public
     * @see PHPExcel_Writer_Excel5_Workbook::addWorksheet()
     * @param string  $name  The name of the worksheet being added
     * @param integer $index The index of the worksheet being added
     */
    public function setExtSheet($name, $index)
    {
        $this->externalSheets[$name] = $index;
    }
    /**
     * pack() row and column into the required 3 or 4 byte format.
     *
     * @access private
     * @param string $cell The Excel cell reference to be packed
     * @return array Array containing the row and column in packed() format
     */
    private function cellToPackedRowcol($cell)
    {
        $cell = strtoupper($cell);
        list($row, $col, $row_rel, $col_rel) = $this->cellToRowcol($cell);
        if (256 <= $col) {
            throw new PHPExcel_Writer_Exception("Column in: " . $cell . " greater than 255");
        }
        if (65536 <= $row) {
            throw new PHPExcel_Writer_Exception("Row in: " . $cell . " greater than 65536 ");
        }
        $col |= $col_rel << 14;
        $col |= $row_rel << 15;
        $col = pack("v", $col);
        $row = pack("v", $row);
        return array($row, $col);
    }
    /**
     * pack() row range into the required 3 or 4 byte format.
     * Just using maximum col/rows, which is probably not the correct solution
     *
     * @access private
     * @param string $range The Excel range to be packed
     * @return array Array containing (row1,col1,row2,col2) in packed() format
     */
    private function rangeToPackedRange($range)
    {
        preg_match("/(\\\$)?(\\d+)\\:(\\\$)?(\\d+)/", $range, $match);
        $row1_rel = empty($match[1]) ? 1 : 0;
        $row1 = $match[2];
        $row2_rel = empty($match[3]) ? 1 : 0;
        $row2 = $match[4];
        $row1--;
        $row2--;
        $col1 = 0;
        $col2 = 65535;
        if (65536 <= $row1 || 65536 <= $row2) {
            throw new PHPExcel_Writer_Exception("Row in: " . $range . " greater than 65536 ");
        }
        $col1 |= $row1_rel << 15;
        $col2 |= $row2_rel << 15;
        $col1 = pack("v", $col1);
        $col2 = pack("v", $col2);
        $row1 = pack("v", $row1);
        $row2 = pack("v", $row2);
        return array($row1, $col1, $row2, $col2);
    }
    /**
     * Convert an Excel cell reference such as A1 or $B2 or C$3 or $D$4 to a zero
     * indexed row and column number. Also returns two (0,1) values to indicate
     * whether the row or column are relative references.
     *
     * @access private
     * @param string $cell The Excel cell reference in A1 format.
     * @return array
     */
    private function cellToRowcol($cell)
    {
        preg_match("/(\\\$)?([A-I]?[A-Z])(\\\$)?(\\d+)/", $cell, $match);
        $col_rel = empty($match[1]) ? 1 : 0;
        $col_ref = $match[2];
        $row_rel = empty($match[3]) ? 1 : 0;
        $row = $match[4];
        $expn = strlen($col_ref) - 1;
        $col = 0;
        $col_ref_length = strlen($col_ref);
        for ($i = 0; $i < $col_ref_length; $i++) {
            $col += (ord($col_ref[$i]) - 64) * pow(26, $expn);
            $expn--;
        }
        $row--;
        $col--;
        return array($row, $col, $row_rel, $col_rel);
    }
    /**
     * Advance to the next valid token.
     *
     * @access private
     */
    private function advance()
    {
        $i = $this->currentCharacter;
        $formula_length = strlen($this->formula);
        if ($i < $formula_length) {
            while ($this->formula[$i] == " ") {
                $i++;
            }
            if ($i < $formula_length - 1) {
                $this->lookAhead = $this->formula[$i + 1];
            }
            $token = "";
        }
        while ($i < $formula_length) {
            $token .= $this->formula[$i];
            if ($i < $formula_length - 1) {
                $this->lookAhead = $this->formula[$i + 1];
            } else {
                $this->lookAhead = "";
            }
            if ($this->match($token) != "") {
                $this->currentCharacter = $i + 1;
                $this->currentToken = $token;
                return 1;
            }
            if ($i < $formula_length - 2) {
                $this->lookAhead = $this->formula[$i + 2];
            } else {
                $this->lookAhead = "";
            }
            $i++;
        }
    }
    /**
     * Checks if it's a valid token.
     *
     * @access private
     * @param mixed $token The token to check.
     * @return mixed       The checked token or false on failure
     */
    private function match($token)
    {
        switch ($token) {
            case "+":
            case "-":
            case "*":
            case "/":
            case "(":
            case ")":
            case ",":
            case ";":
            case ">=":
            case "<=":
            case "=":
            case "<>":
            case "^":
            case "&":
            case "%":
                return $token;
            case ">":
                if ($this->lookAhead == "=") {
                    break;
                }
                return $token;
            case "<":
                if ($this->lookAhead == "=" || $this->lookAhead == ">") {
                    break;
                }
                return $token;
            default:
                if (preg_match("/^\\\$?[A-Ia-i]?[A-Za-z]\\\$?[0-9]+\$/", $token) && !preg_match("/[0-9]/", $this->lookAhead) && $this->lookAhead != ":" && $this->lookAhead != "." && $this->lookAhead != "!") {
                    return $token;
                }
                if (preg_match("/^" . self::REGEX_SHEET_TITLE_UNQUOTED . "(\\:" . self::REGEX_SHEET_TITLE_UNQUOTED . ")?\\!\\\$?[A-Ia-i]?[A-Za-z]\\\$?[0-9]+\$/u", $token) && !preg_match("/[0-9]/", $this->lookAhead) && $this->lookAhead != ":" && $this->lookAhead != ".") {
                    return $token;
                }
                if (preg_match("/^'" . self::REGEX_SHEET_TITLE_QUOTED . "(\\:" . self::REGEX_SHEET_TITLE_QUOTED . ")?'\\!\\\$?[A-Ia-i]?[A-Za-z]\\\$?[0-9]+\$/u", $token) && !preg_match("/[0-9]/", $this->lookAhead) && $this->lookAhead != ":" && $this->lookAhead != ".") {
                    return $token;
                }
                if (preg_match("/^(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?[0-9]+:(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?[0-9]+\$/", $token) && !preg_match("/[0-9]/", $this->lookAhead)) {
                    return $token;
                }
                if (preg_match("/^" . self::REGEX_SHEET_TITLE_UNQUOTED . "(\\:" . self::REGEX_SHEET_TITLE_UNQUOTED . ")?\\!\\\$?([A-Ia-i]?[A-Za-z])?\\\$?[0-9]+:\\\$?([A-Ia-i]?[A-Za-z])?\\\$?[0-9]+\$/u", $token) && !preg_match("/[0-9]/", $this->lookAhead)) {
                    return $token;
                }
                if (preg_match("/^'" . self::REGEX_SHEET_TITLE_QUOTED . "(\\:" . self::REGEX_SHEET_TITLE_QUOTED . ")?'\\!\\\$?([A-Ia-i]?[A-Za-z])?\\\$?[0-9]+:\\\$?([A-Ia-i]?[A-Za-z])?\\\$?[0-9]+\$/u", $token) && !preg_match("/[0-9]/", $this->lookAhead)) {
                    return $token;
                }
                if (is_numeric($token) && (!is_numeric($token . $this->lookAhead) || $this->lookAhead == "") && $this->lookAhead != "!" && $this->lookAhead != ":") {
                    return $token;
                }
                if (preg_match("/\"([^\"]|\"\"){0,255}\"/", $token) && $this->lookAhead != "\"" && substr_count($token, "\"") % 2 == 0) {
                    return $token;
                }
                if (preg_match("/^#[A-Z0\\/]{3,5}[!?]{1}\$/", $token) || $token == "#N/A") {
                    return $token;
                }
                if (preg_match("/^[A-Z0-9À-Ü\\.]+\$/i", $token) && $this->lookAhead == "(") {
                    return $token;
                }
                if (substr($token, -1) == ")") {
                    return $token;
                }
                return "";
        }
    }
    /**
     * The parsing method. It parses a formula.
     *
     * @access public
     * @param string $formula The formula to parse, without the initial equal
     *                        sign (=).
     * @return mixed true on success
     */
    public function parse($formula)
    {
        $this->currentCharacter = 0;
        $this->formula = $formula;
        $this->lookAhead = isset($formula[1]) ? $formula[1] : "";
        $this->advance();
        $this->parseTree = $this->condition();
        return true;
    }
    /**
     * It parses a condition. It assumes the following rule:
     * Cond -> Expr [(">" | "<") Expr]
     *
     * @access private
     * @return mixed The parsed ptg'd tree on success
     */
    private function condition()
    {
        $result = $this->expression();
        if ($this->currentToken == "<") {
            $this->advance();
            $result2 = $this->expression();
            $result = $this->createTree("ptgLT", $result, $result2);
        } else {
            if ($this->currentToken == ">") {
                $this->advance();
                $result2 = $this->expression();
                $result = $this->createTree("ptgGT", $result, $result2);
            } else {
                if ($this->currentToken == "<=") {
                    $this->advance();
                    $result2 = $this->expression();
                    $result = $this->createTree("ptgLE", $result, $result2);
                } else {
                    if ($this->currentToken == ">=") {
                        $this->advance();
                        $result2 = $this->expression();
                        $result = $this->createTree("ptgGE", $result, $result2);
                    } else {
                        if ($this->currentToken == "=") {
                            $this->advance();
                            $result2 = $this->expression();
                            $result = $this->createTree("ptgEQ", $result, $result2);
                        } else {
                            if ($this->currentToken == "<>") {
                                $this->advance();
                                $result2 = $this->expression();
                                $result = $this->createTree("ptgNE", $result, $result2);
                            } else {
                                if ($this->currentToken == "&") {
                                    $this->advance();
                                    $result2 = $this->expression();
                                    $result = $this->createTree("ptgConcat", $result, $result2);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
    /**
     * It parses a expression. It assumes the following rule:
     * Expr -> Term [("+" | "-") Term]
     *      -> "string"
     *      -> "-" Term : Negative value
     *      -> "+" Term : Positive value
     *      -> Error code
     *
     * @access private
     * @return mixed The parsed ptg'd tree on success
     */
    private function expression()
    {
        if (preg_match("/\"([^\"]|\"\"){0,255}\"/", $this->currentToken)) {
            $tmp = str_replace("\"\"", "\"", $this->currentToken);
            if ($tmp == "\"" || $tmp == "") {
                $tmp = "\"\"";
            }
            $result = $this->createTree($tmp, "", "");
            $this->advance();
            return $result;
        }
        if (preg_match("/^#[A-Z0\\/]{3,5}[!?]{1}\$/", $this->currentToken) || $this->currentToken == "#N/A") {
            $result = $this->createTree($this->currentToken, "ptgErr", "");
            $this->advance();
            return $result;
        }
        if ($this->currentToken == "-") {
            $this->advance();
            $result2 = $this->expression();
            $result = $this->createTree("ptgUminus", $result2, "");
            return $result;
        }
        if ($this->currentToken == "+") {
            $this->advance();
            $result2 = $this->expression();
            $result = $this->createTree("ptgUplus", $result2, "");
            return $result;
        }
        $result = $this->term();
        while ($this->currentToken == "+" || $this->currentToken == "-" || $this->currentToken == "^") {
            if ($this->currentToken == "+") {
                $this->advance();
                $result2 = $this->term();
                $result = $this->createTree("ptgAdd", $result, $result2);
            } else {
                if ($this->currentToken == "-") {
                    $this->advance();
                    $result2 = $this->term();
                    $result = $this->createTree("ptgSub", $result, $result2);
                } else {
                    $this->advance();
                    $result2 = $this->term();
                    $result = $this->createTree("ptgPower", $result, $result2);
                }
            }
        }
        return $result;
    }
    /**
     * This function just introduces a ptgParen element in the tree, so that Excel
     * doesn't get confused when working with a parenthesized formula afterwards.
     *
     * @access private
     * @see fact()
     * @return array The parsed ptg'd tree
     */
    private function parenthesizedExpression()
    {
        $result = $this->createTree("ptgParen", $this->expression(), "");
        return $result;
    }
    /**
     * It parses a term. It assumes the following rule:
     * Term -> Fact [("*" | "/") Fact]
     *
     * @access private
     * @return mixed The parsed ptg'd tree on success
     */
    private function term()
    {
        $result = $this->fact();
        while ($this->currentToken == "*" || $this->currentToken == "/") {
            if ($this->currentToken == "*") {
                $this->advance();
                $result2 = $this->fact();
                $result = $this->createTree("ptgMul", $result, $result2);
            } else {
                $this->advance();
                $result2 = $this->fact();
                $result = $this->createTree("ptgDiv", $result, $result2);
            }
        }
        return $result;
    }
    /**
     * It parses a factor. It assumes the following rule:
     * Fact -> ( Expr )
     *       | CellRef
     *       | CellRange
     *       | Number
     *       | Function
     *
     * @access private
     * @return mixed The parsed ptg'd tree on success
     */
    private function fact()
    {
        if ($this->currentToken == "(") {
            $this->advance();
            $result = $this->parenthesizedExpression();
            if ($this->currentToken != ")") {
                throw new PHPExcel_Writer_Exception("')' token expected.");
            }
            $this->advance();
            return $result;
        }
        if (preg_match("/^\\\$?[A-Ia-i]?[A-Za-z]\\\$?[0-9]+\$/", $this->currentToken)) {
            $result = $this->createTree($this->currentToken, "", "");
            $this->advance();
            return $result;
        }
        if (preg_match("/^" . self::REGEX_SHEET_TITLE_UNQUOTED . "(\\:" . self::REGEX_SHEET_TITLE_UNQUOTED . ")?\\!\\\$?[A-Ia-i]?[A-Za-z]\\\$?[0-9]+\$/u", $this->currentToken)) {
            $result = $this->createTree($this->currentToken, "", "");
            $this->advance();
            return $result;
        }
        if (preg_match("/^'" . self::REGEX_SHEET_TITLE_QUOTED . "(\\:" . self::REGEX_SHEET_TITLE_QUOTED . ")?'\\!\\\$?[A-Ia-i]?[A-Za-z]\\\$?[0-9]+\$/u", $this->currentToken)) {
            $result = $this->createTree($this->currentToken, "", "");
            $this->advance();
            return $result;
        }
        if (preg_match("/^(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?[0-9]+:(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?[0-9]+\$/", $this->currentToken) || preg_match("/^(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?[0-9]+\\.\\.(\\\$)?[A-Ia-i]?[A-Za-z](\\\$)?[0-9]+\$/", $this->currentToken)) {
            $result = $this->createTree($this->currentToken, "", "");
            $this->advance();
            return $result;
        }
        if (preg_match("/^" . self::REGEX_SHEET_TITLE_UNQUOTED . "(\\:" . self::REGEX_SHEET_TITLE_UNQUOTED . ")?\\!\\\$?([A-Ia-i]?[A-Za-z])?\\\$?[0-9]+:\\\$?([A-Ia-i]?[A-Za-z])?\\\$?[0-9]+\$/u", $this->currentToken)) {
            $result = $this->createTree($this->currentToken, "", "");
            $this->advance();
            return $result;
        }
        if (preg_match("/^'" . self::REGEX_SHEET_TITLE_QUOTED . "(\\:" . self::REGEX_SHEET_TITLE_QUOTED . ")?'\\!\\\$?([A-Ia-i]?[A-Za-z])?\\\$?[0-9]+:\\\$?([A-Ia-i]?[A-Za-z])?\\\$?[0-9]+\$/u", $this->currentToken)) {
            $result = $this->createTree($this->currentToken, "", "");
            $this->advance();
            return $result;
        }
        if (is_numeric($this->currentToken)) {
            if ($this->lookAhead == "%") {
                $result = $this->createTree("ptgPercent", $this->currentToken, "");
                $this->advance();
            } else {
                $result = $this->createTree($this->currentToken, "", "");
            }
            $this->advance();
            return $result;
        }
        if (preg_match("/^[A-Z0-9À-Ü\\.]+\$/i", $this->currentToken)) {
            $result = $this->func();
            return $result;
        }
        throw new PHPExcel_Writer_Exception("Syntax error: " . $this->currentToken . ", lookahead: " . $this->lookAhead . ", current char: " . $this->currentCharacter);
    }
    /**
     * It parses a function call. It assumes the following rule:
     * Func -> ( Expr [,Expr]* )
     *
     * @access private
     * @return mixed The parsed ptg'd tree on success
     */
    private function func()
    {
        $num_args = 0;
        $function = strtoupper($this->currentToken);
        $result = "";
        $this->advance();
        $this->advance();
        while ($this->currentToken != ")") {
            if (0 < $num_args) {
                if ($this->currentToken == "," || $this->currentToken == ";") {
                    $this->advance();
                    $result2 = $this->condition();
                    $result = $this->createTree("arg", $result, $result2);
                } else {
                    throw new PHPExcel_Writer_Exception("Syntax error: comma expected in function " . $function . ", arg #" . $num_args);
                }
            } else {
                $result2 = $this->condition();
                $result = $this->createTree("arg", "", $result2);
            }
            $num_args++;
        }
        if (!isset($this->functions[$function])) {
            throw new PHPExcel_Writer_Exception("Function " . $function . "() doesn't exist");
        }
        $args = $this->functions[$function][1];
        if (0 <= $args && $args != $num_args) {
            throw new PHPExcel_Writer_Exception("Incorrect number of arguments in function " . $function . "() ");
        }
        $result = $this->createTree($function, $result, $num_args);
        $this->advance();
        return $result;
    }
    /**
     * Creates a tree. In fact an array which may have one or two arrays (sub-trees)
     * as elements.
     *
     * @access private
     * @param mixed $value The value of this node.
     * @param mixed $left  The left array (sub-tree) or a final node.
     * @param mixed $right The right array (sub-tree) or a final node.
     * @return array A tree
     */
    private function createTree($value, $left, $right)
    {
        return array("value" => $value, "left" => $left, "right" => $right);
    }
    /**
     * Builds a string containing the tree in reverse polish notation (What you
     * would use in a HP calculator stack).
     * The following tree:
     *
     *    +
     *   / \
     *  2   3
     *
     * produces: "23+"
     *
     * The following tree:
     *
     *    +
     *   / \
     *  3   *
     *     / \
     *    6   A1
     *
     * produces: "36A1*+"
     *
     * In fact all operands, functions, references, etc... are written as ptg's
     *
     * @access public
     * @param array $tree The optional tree to convert.
     * @return string The tree in reverse polish notation
     */
    public function toReversePolish($tree = array())
    {
        $polish = "";
        if (empty($tree)) {
            $tree = $this->parseTree;
        }
        if (is_array($tree["left"])) {
            $converted_tree = $this->toReversePolish($tree["left"]);
            $polish .= $converted_tree;
        } else {
            if ($tree["left"] != "") {
                $converted_tree = $this->convert($tree["left"]);
                $polish .= $converted_tree;
            }
        }
        if (is_array($tree["right"])) {
            $converted_tree = $this->toReversePolish($tree["right"]);
            $polish .= $converted_tree;
        } else {
            if ($tree["right"] != "") {
                $converted_tree = $this->convert($tree["right"]);
                $polish .= $converted_tree;
            }
        }
        if (preg_match("/^[A-Z0-9À-Ü\\.]+\$/", $tree["value"]) && !preg_match("/^([A-Ia-i]?[A-Za-z])(\\d+)\$/", $tree["value"]) && !preg_match("/^[A-Ia-i]?[A-Za-z](\\d+)\\.\\.[A-Ia-i]?[A-Za-z](\\d+)\$/", $tree["value"]) && !is_numeric($tree["value"]) && !isset($this->ptg[$tree["value"]])) {
            if ($tree["left"] != "") {
                $left_tree = $this->toReversePolish($tree["left"]);
            } else {
                $left_tree = "";
            }
            return $left_tree . $this->convertFunction($tree["value"], $tree["right"]);
        }
        $converted_tree = $this->convert($tree["value"]);
        $polish .= $converted_tree;
        return $polish;
    }
}

?>