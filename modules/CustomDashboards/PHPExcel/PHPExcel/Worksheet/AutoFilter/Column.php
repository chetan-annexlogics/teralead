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
 * PHPExcel_Worksheet_AutoFilter_Column
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
 * @category    PHPExcel
 * @package        PHPExcel_Worksheet
 * @copyright    Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license        http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version        ##VERSION##, ##DATE##
 */
class PHPExcel_Worksheet_AutoFilter_Column
{
    /**
     * Types of autofilter rules
     *
     * @var string[]
     */
    private static $filterTypes = NULL;
    /**
     * Join options for autofilter rules
     *
     * @var string[]
     */
    private static $ruleJoins = NULL;
    /**
     * Autofilter
     *
     * @var PHPExcel_Worksheet_AutoFilter
     */
    private $parent = NULL;
    /**
     * Autofilter Column Index
     *
     * @var string
     */
    private $columnIndex = "";
    /**
     * Autofilter Column Filter Type
     *
     * @var string
     */
    private $filterType = self::AUTOFILTER_FILTERTYPE_FILTER;
    /**
     * Autofilter Multiple Rules And/Or
     *
     * @var string
     */
    private $join = self::AUTOFILTER_COLUMN_JOIN_OR;
    /**
     * Autofilter Column Rules
     *
     * @var array of PHPExcel_Worksheet_AutoFilter_Column_Rule
     */
    private $ruleset = array();
    /**
     * Autofilter Column Dynamic Attributes
     *
     * @var array of mixed
     */
    private $attributes = array();
    const AUTOFILTER_FILTERTYPE_FILTER = "filters";
    const AUTOFILTER_FILTERTYPE_CUSTOMFILTER = "customFilters";
    const AUTOFILTER_FILTERTYPE_DYNAMICFILTER = "dynamicFilter";
    const AUTOFILTER_FILTERTYPE_TOPTENFILTER = "top10";
    const AUTOFILTER_COLUMN_JOIN_AND = "and";
    const AUTOFILTER_COLUMN_JOIN_OR = "or";
    /**
     * Create a new PHPExcel_Worksheet_AutoFilter_Column
     *
     *    @param    string                           $pColumn        Column (e.g. A)
     *    @param    PHPExcel_Worksheet_AutoFilter  $pParent        Autofilter for this column
     */
    public function __construct($pColumn, PHPExcel_Worksheet_AutoFilter $pParent = NULL)
    {
        $this->columnIndex = $pColumn;
        $this->parent = $pParent;
    }
    /**
     * Get AutoFilter Column Index
     *
     * @return string
     */
    public function getColumnIndex()
    {
        return $this->columnIndex;
    }
    /**
     *    Set AutoFilter Column Index
     *
     *    @param    string        $pColumn        Column (e.g. A)
     *    @throws    PHPExcel_Exception
     *    @return PHPExcel_Worksheet_AutoFilter_Column
     */
    public function setColumnIndex($pColumn)
    {
        $pColumn = strtoupper($pColumn);
        if ($this->parent !== NULL) {
            $this->parent->testColumnInRange($pColumn);
        }
        $this->columnIndex = $pColumn;
        return $this;
    }
    /**
     * Get this Column's AutoFilter Parent
     *
     * @return PHPExcel_Worksheet_AutoFilter
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Set this Column's AutoFilter Parent
     *
     * @param PHPExcel_Worksheet_AutoFilter
     * @return PHPExcel_Worksheet_AutoFilter_Column
     */
    public function setParent(PHPExcel_Worksheet_AutoFilter $pParent = NULL)
    {
        $this->parent = $pParent;
        return $this;
    }
    /**
     * Get AutoFilter Type
     *
     * @return string
     */
    public function getFilterType()
    {
        return $this->filterType;
    }
    /**
     *    Set AutoFilter Type
     *
     *    @param    string        $pFilterType
     *    @throws    PHPExcel_Exception
     *    @return PHPExcel_Worksheet_AutoFilter_Column
     */
    public function setFilterType($pFilterType = self::AUTOFILTER_FILTERTYPE_FILTER)
    {
        if (!in_array($pFilterType, self::$filterTypes)) {
            throw new PHPExcel_Exception("Invalid filter type for column AutoFilter.");
        }
        $this->filterType = $pFilterType;
        return $this;
    }
    /**
     * Get AutoFilter Multiple Rules And/Or Join
     *
     * @return string
     */
    public function getJoin()
    {
        return $this->join;
    }
    /**
     *    Set AutoFilter Multiple Rules And/Or
     *
     *    @param    string        $pJoin        And/Or
     *    @throws    PHPExcel_Exception
     *    @return PHPExcel_Worksheet_AutoFilter_Column
     */
    public function setJoin($pJoin = self::AUTOFILTER_COLUMN_JOIN_OR)
    {
        $pJoin = strtolower($pJoin);
        if (!in_array($pJoin, self::$ruleJoins)) {
            throw new PHPExcel_Exception("Invalid rule connection for column AutoFilter.");
        }
        $this->join = $pJoin;
        return $this;
    }
    /**
     *    Set AutoFilter Attributes
     *
     *    @param    string[]        $pAttributes
     *    @throws    PHPExcel_Exception
     *    @return PHPExcel_Worksheet_AutoFilter_Column
     */
    public function setAttributes($pAttributes = array())
    {
        $this->attributes = $pAttributes;
        return $this;
    }
    /**
     *    Set An AutoFilter Attribute
     *
     *    @param    string        $pName        Attribute Name
     *    @param    string        $pValue        Attribute Value
     *    @throws    PHPExcel_Exception
     *    @return PHPExcel_Worksheet_AutoFilter_Column
     */
    public function setAttribute($pName, $pValue)
    {
        $this->attributes[$pName] = $pValue;
        return $this;
    }
    /**
     * Get AutoFilter Column Attributes
     *
     * @return string
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    /**
     * Get specific AutoFilter Column Attribute
     *
     *    @param    string        $pName        Attribute Name
     * @return string
     */
    public function getAttribute($pName)
    {
        if (isset($this->attributes[$pName])) {
            return $this->attributes[$pName];
        }
    }
    /**
     * Get all AutoFilter Column Rules
     *
     * @throws    PHPExcel_Exception
     * @return array of PHPExcel_Worksheet_AutoFilter_Column_Rule
     */
    public function getRules()
    {
        return $this->ruleset;
    }
    /**
     * Get a specified AutoFilter Column Rule
     *
     * @param    integer    $pIndex        Rule index in the ruleset array
     * @return    PHPExcel_Worksheet_AutoFilter_Column_Rule
     */
    public function getRule($pIndex)
    {
        if (!isset($this->ruleset[$pIndex])) {
            $this->ruleset[$pIndex] = new PHPExcel_Worksheet_AutoFilter_Column_Rule($this);
        }
        return $this->ruleset[$pIndex];
    }
    /**
     * Create a new AutoFilter Column Rule in the ruleset
     *
     * @return    PHPExcel_Worksheet_AutoFilter_Column_Rule
     */
    public function createRule()
    {
        $this->ruleset[] = new PHPExcel_Worksheet_AutoFilter_Column_Rule($this);
        return end($this->ruleset);
    }
    /**
     * Add a new AutoFilter Column Rule to the ruleset
     *
     * @param    PHPExcel_Worksheet_AutoFilter_Column_Rule    $pRule
     * @param    boolean    $returnRule     Flag indicating whether the rule object or the column object should be returned
     * @return    PHPExcel_Worksheet_AutoFilter_Column|PHPExcel_Worksheet_AutoFilter_Column_Rule
     */
    public function addRule(PHPExcel_Worksheet_AutoFilter_Column_Rule $pRule, $returnRule = true)
    {
        $pRule->setParent($this);
        $this->ruleset[] = $pRule;
        return $returnRule ? $pRule : $this;
    }
    /**
     * Delete a specified AutoFilter Column Rule
     *    If the number of rules is reduced to 1, then we reset And/Or logic to Or
     *
     * @param    integer    $pIndex        Rule index in the ruleset array
     * @return    PHPExcel_Worksheet_AutoFilter_Column
     */
    public function deleteRule($pIndex)
    {
        if (isset($this->ruleset[$pIndex])) {
            unset($this->ruleset[$pIndex]);
            if (count($this->ruleset) <= 1) {
                $this->setJoin(self::AUTOFILTER_COLUMN_JOIN_OR);
            }
        }
        return $this;
    }
    /**
     * Delete all AutoFilter Column Rules
     *
     * @return    PHPExcel_Worksheet_AutoFilter_Column
     */
    public function clearRules()
    {
        $this->ruleset = array();
        $this->setJoin(self::AUTOFILTER_COLUMN_JOIN_OR);
        return $this;
    }
    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                if ($key == "parent") {
                    $this->{$key} = NULL;
                } else {
                    $this->{$key} = clone $value;
                }
            } else {
                if (is_array($value) && $key == "ruleset") {
                    $this->{$key} = array();
                    foreach ($value as $k => $v) {
                        $this->{$key[$k]} = clone $v;
                        $this->{$key[$k]}->setParent($this);
                    }
                } else {
                    $this->{$key} = $value;
                }
            }
        }
    }
}

?>