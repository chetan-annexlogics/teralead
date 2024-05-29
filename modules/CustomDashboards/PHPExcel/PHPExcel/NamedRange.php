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
 * PHPExcel_NamedRange
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
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_NamedRange
{
    /**
     * Range name
     *
     * @var string
     */
    private $name = NULL;
    /**
     * Worksheet on which the named range can be resolved
     *
     * @var PHPExcel_Worksheet
     */
    private $worksheet = NULL;
    /**
     * Range of the referenced cells
     *
     * @var string
     */
    private $range = NULL;
    /**
     * Is the named range local? (i.e. can only be used on $this->worksheet)
     *
     * @var bool
     */
    private $localOnly = NULL;
    /**
     * Scope
     *
     * @var PHPExcel_Worksheet
     */
    private $scope = NULL;
    /**
     * Create a new NamedRange
     *
     * @param string $pName
     * @param PHPExcel_Worksheet $pWorksheet
     * @param string $pRange
     * @param bool $pLocalOnly
     * @param PHPExcel_Worksheet|null $pScope    Scope. Only applies when $pLocalOnly = true. Null for global scope.
     * @throws PHPExcel_Exception
     */
    public function __construct($pName = NULL, PHPExcel_Worksheet $pWorksheet, $pRange = "A1", $pLocalOnly = false, $pScope = NULL)
    {
        if ($pName === NULL || $pWorksheet === NULL || $pRange === NULL) {
            throw new PHPExcel_Exception("Parameters can not be null.");
        }
        $this->name = $pName;
        $this->worksheet = $pWorksheet;
        $this->range = $pRange;
        $this->localOnly = $pLocalOnly;
        $this->scope = $pLocalOnly == true ? $pScope == NULL ? $pWorksheet : $pScope : NULL;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set name
     *
     * @param string $value
     * @return PHPExcel_NamedRange
     */
    public function setName($value = NULL)
    {
        if ($value !== NULL) {
            $oldTitle = $this->name;
            if ($this->worksheet !== NULL) {
                $this->worksheet->getParent()->removeNamedRange($this->name, $this->worksheet);
            }
            $this->name = $value;
            if ($this->worksheet !== NULL) {
                $this->worksheet->getParent()->addNamedRange($this);
            }
            $newTitle = $this->name;
            PHPExcel_ReferenceHelper::getInstance()->updateNamedFormulas($this->worksheet->getParent(), $oldTitle, $newTitle);
        }
        return $this;
    }
    /**
     * Get worksheet
     *
     * @return PHPExcel_Worksheet
     */
    public function getWorksheet()
    {
        return $this->worksheet;
    }
    /**
     * Set worksheet
     *
     * @param PHPExcel_Worksheet $value
     * @return PHPExcel_NamedRange
     */
    public function setWorksheet(PHPExcel_Worksheet $value = NULL)
    {
        if ($value !== NULL) {
            $this->worksheet = $value;
        }
        return $this;
    }
    /**
     * Get range
     *
     * @return string
     */
    public function getRange()
    {
        return $this->range;
    }
    /**
     * Set range
     *
     * @param string $value
     * @return PHPExcel_NamedRange
     */
    public function setRange($value = NULL)
    {
        if ($value !== NULL) {
            $this->range = $value;
        }
        return $this;
    }
    /**
     * Get localOnly
     *
     * @return bool
     */
    public function getLocalOnly()
    {
        return $this->localOnly;
    }
    /**
     * Set localOnly
     *
     * @param bool $value
     * @return PHPExcel_NamedRange
     */
    public function setLocalOnly($value = false)
    {
        $this->localOnly = $value;
        $this->scope = $value ? $this->worksheet : NULL;
        return $this;
    }
    /**
     * Get scope
     *
     * @return PHPExcel_Worksheet|null
     */
    public function getScope()
    {
        return $this->scope;
    }
    /**
     * Set scope
     *
     * @param PHPExcel_Worksheet|null $value
     * @return PHPExcel_NamedRange
     */
    public function setScope(PHPExcel_Worksheet $value = NULL)
    {
        $this->scope = $value;
        $this->localOnly = $value == NULL ? false : true;
        return $this;
    }
    /**
     * Resolve a named range to a regular cell range
     *
     * @param string $pNamedRange Named range
     * @param PHPExcel_Worksheet|null $pSheet Scope. Use null for global scope
     * @return PHPExcel_NamedRange
     */
    public static function resolveRange($pNamedRange = "", PHPExcel_Worksheet $pSheet)
    {
        return $pSheet->getParent()->getNamedRange($pNamedRange, $pSheet);
    }
    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                $this->{$key} = clone $value;
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

?>