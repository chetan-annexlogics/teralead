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
 * PHPExcel_Calculation_FormulaToken
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
 * @package    PHPExcel_Calculation
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Calculation_FormulaToken
{
    /**
     * Value
     *
     * @var string
     */
    private $value = NULL;
    /**
     * Token Type (represented by TOKEN_TYPE_*)
     *
     * @var string
     */
    private $tokenType = NULL;
    /**
     * Token SubType (represented by TOKEN_SUBTYPE_*)
     *
     * @var string
     */
    private $tokenSubType = NULL;
    const TOKEN_TYPE_NOOP = "Noop";
    const TOKEN_TYPE_OPERAND = "Operand";
    const TOKEN_TYPE_FUNCTION = "Function";
    const TOKEN_TYPE_SUBEXPRESSION = "Subexpression";
    const TOKEN_TYPE_ARGUMENT = "Argument";
    const TOKEN_TYPE_OPERATORPREFIX = "OperatorPrefix";
    const TOKEN_TYPE_OPERATORINFIX = "OperatorInfix";
    const TOKEN_TYPE_OPERATORPOSTFIX = "OperatorPostfix";
    const TOKEN_TYPE_WHITESPACE = "Whitespace";
    const TOKEN_TYPE_UNKNOWN = "Unknown";
    const TOKEN_SUBTYPE_NOTHING = "Nothing";
    const TOKEN_SUBTYPE_START = "Start";
    const TOKEN_SUBTYPE_STOP = "Stop";
    const TOKEN_SUBTYPE_TEXT = "Text";
    const TOKEN_SUBTYPE_NUMBER = "Number";
    const TOKEN_SUBTYPE_LOGICAL = "Logical";
    const TOKEN_SUBTYPE_ERROR = "Error";
    const TOKEN_SUBTYPE_RANGE = "Range";
    const TOKEN_SUBTYPE_MATH = "Math";
    const TOKEN_SUBTYPE_CONCATENATION = "Concatenation";
    const TOKEN_SUBTYPE_INTERSECTION = "Intersection";
    const TOKEN_SUBTYPE_UNION = "Union";
    /**
     * Create a new PHPExcel_Calculation_FormulaToken
     *
     * @param string    $pValue
     * @param string    $pTokenType     Token type (represented by TOKEN_TYPE_*)
     * @param string    $pTokenSubType     Token Subtype (represented by TOKEN_SUBTYPE_*)
     */
    public function __construct($pValue, $pTokenType = PHPExcel_Calculation_FormulaToken::TOKEN_TYPE_UNKNOWN, $pTokenSubType = PHPExcel_Calculation_FormulaToken::TOKEN_SUBTYPE_NOTHING)
    {
        $this->value = $pValue;
        $this->tokenType = $pTokenType;
        $this->tokenSubType = $pTokenSubType;
    }
    /**
     * Get Value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * Set Value
     *
     * @param string    $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    /**
     * Get Token Type (represented by TOKEN_TYPE_*)
     *
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }
    /**
     * Set Token Type
     *
     * @param string    $value
     */
    public function setTokenType($value = PHPExcel_Calculation_FormulaToken::TOKEN_TYPE_UNKNOWN)
    {
        $this->tokenType = $value;
    }
    /**
     * Get Token SubType (represented by TOKEN_SUBTYPE_*)
     *
     * @return string
     */
    public function getTokenSubType()
    {
        return $this->tokenSubType;
    }
    /**
     * Set Token SubType
     *
     * @param string    $value
     */
    public function setTokenSubType($value = PHPExcel_Calculation_FormulaToken::TOKEN_SUBTYPE_NOTHING)
    {
        $this->tokenSubType = $value;
    }
}

?>