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
 * PHPExcel_CachedObjectStorage_CacheBase
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
 * @package    PHPExcel_CachedObjectStorage
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
abstract class PHPExcel_CachedObjectStorage_CacheBase
{
    /**
     * Parent worksheet
     *
     * @var PHPExcel_Worksheet
     */
    protected $parent = NULL;
    /**
     * The currently active Cell
     *
     * @var PHPExcel_Cell
     */
    protected $currentObject = NULL;
    /**
     * Coordinate address of the currently active Cell
     *
     * @var string
     */
    protected $currentObjectID = NULL;
    /**
     * Flag indicating whether the currently active Cell requires saving
     *
     * @var boolean
     */
    protected $currentCellIsDirty = true;
    /**
     * An array of cells or cell pointers for the worksheet cells held in this cache,
     *        and indexed by their coordinate address within the worksheet
     *
     * @var array of mixed
     */
    protected $cellCache = array();
    /**
     * Initialise this new cell collection
     *
     * @param    PHPExcel_Worksheet    $parent        The worksheet for this cell collection
     */
    public function __construct(PHPExcel_Worksheet $parent)
    {
        $this->parent = $parent;
    }
    /**
     * Return the parent worksheet for this cell collection
     *
     * @return    PHPExcel_Worksheet
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Is a value set in the current PHPExcel_CachedObjectStorage_ICache for an indexed cell?
     *
     * @param    string        $pCoord        Coordinate address of the cell to check
     * @return    boolean
     */
    public function isDataSet($pCoord)
    {
        if ($pCoord === $this->currentObjectID) {
            return true;
        }
        return isset($this->cellCache[$pCoord]);
    }
    /**
     * Move a cell object from one address to another
     *
     * @param    string        $fromAddress    Current address of the cell to move
     * @param    string        $toAddress        Destination address of the cell to move
     * @return    boolean
     */
    public function moveCell($fromAddress, $toAddress)
    {
        if ($fromAddress === $this->currentObjectID) {
            $this->currentObjectID = $toAddress;
        }
        $this->currentCellIsDirty = true;
        if (isset($this->cellCache[$fromAddress])) {
            $this->cellCache[$toAddress] =& $this->cellCache[$fromAddress];
            unset($this->cellCache[$fromAddress]);
        }
        return true;
    }
    /**
     * Add or Update a cell in cache
     *
     * @param    PHPExcel_Cell    $cell        Cell to update
     * @return    PHPExcel_Cell
     * @throws    PHPExcel_Exception
     */
    public function updateCacheData(PHPExcel_Cell $cell)
    {
        return $this->addCacheData($cell->getCoordinate(), $cell);
    }
    /**
     * Delete a cell in cache identified by coordinate address
     *
     * @param    string            $pCoord        Coordinate address of the cell to delete
     * @throws    PHPExcel_Exception
     */
    public function deleteCacheData($pCoord)
    {
        if ($pCoord === $this->currentObjectID && !is_null($this->currentObject)) {
            $this->currentObject->detach();
            $this->currentObjectID = $this->currentObject = NULL;
        }
        if (is_object($this->cellCache[$pCoord])) {
            $this->cellCache[$pCoord]->detach();
            unset($this->cellCache[$pCoord]);
        }
        $this->currentCellIsDirty = false;
    }
    /**
     * Get a list of all cell addresses currently held in cache
     *
     * @return    string[]
     */
    public function getCellList()
    {
        return array_keys($this->cellCache);
    }
    /**
     * Sort the list of all cell addresses currently held in cache by row and column
     *
     * @return    string[]
     */
    public function getSortedCellList()
    {
        $sortKeys = array();
        foreach ($this->getCellList() as $coord) {
            sscanf($coord, "%[A-Z]%d", $column, $row);
            $sortKeys[sprintf("%09d%3s", $row, $column)] = $coord;
        }
        ksort($sortKeys);
        return array_values($sortKeys);
    }
    /**
     * Get highest worksheet column and highest row that have cell records
     *
     * @return array Highest column name and highest row number
     */
    public function getHighestRowAndColumn()
    {
        $col = array("A" => "1A");
        $row = array(1);
        foreach ($this->getCellList() as $coord) {
            sscanf($coord, "%[A-Z]%d", $c, $r);
            $row[$r] = $r;
            $col[$c] = strlen($c) . $c;
        }
        if (!empty($row)) {
            $highestRow = max($row);
            $highestColumn = substr(max($col), 1);
        }
        return array("row" => $highestRow, "column" => $highestColumn);
    }
    /**
     * Return the cell address of the currently active cell object
     *
     * @return    string
     */
    public function getCurrentAddress()
    {
        return $this->currentObjectID;
    }
    /**
     * Return the column address of the currently active cell object
     *
     * @return    string
     */
    public function getCurrentColumn()
    {
        sscanf($this->currentObjectID, "%[A-Z]%d", $column, $row);
        return $column;
    }
    /**
     * Return the row address of the currently active cell object
     *
     * @return    integer
     */
    public function getCurrentRow()
    {
        sscanf($this->currentObjectID, "%[A-Z]%d", $column, $row);
        return (int) $row;
    }
    /**
     * Get highest worksheet column
     *
     * @param   string     $row        Return the highest column for the specified row,
     *                                     or the highest column of any row if no row number is passed
     * @return  string     Highest column name
     */
    public function getHighestColumn($row = NULL)
    {
        if ($row == NULL) {
            $colRow = $this->getHighestRowAndColumn();
            return $colRow["column"];
        }
        $columnList = array(1);
        foreach ($this->getCellList() as $coord) {
            sscanf($coord, "%[A-Z]%d", $c, $r);
            if ($r != $row) {
                continue;
            }
            $columnList[] = PHPExcel_Cell::columnIndexFromString($c);
        }
        return PHPExcel_Cell::stringFromColumnIndex(max($columnList) - 1);
    }
    /**
     * Get highest worksheet row
     *
     * @param   string     $column     Return the highest row for the specified column,
     *                                     or the highest row of any column if no column letter is passed
     * @return  int        Highest row number
     */
    public function getHighestRow($column = NULL)
    {
        if ($column == NULL) {
            $colRow = $this->getHighestRowAndColumn();
            return $colRow["row"];
        }
        $rowList = array(0);
        foreach ($this->getCellList() as $coord) {
            sscanf($coord, "%[A-Z]%d", $c, $r);
            if ($c != $column) {
                continue;
            }
            $rowList[] = $r;
        }
        return max($rowList);
    }
    /**
     * Generate a unique ID for cache referencing
     *
     * @return string Unique Reference
     */
    protected function getUniqueID()
    {
        if (function_exists("posix_getpid")) {
            $baseUnique = posix_getpid();
        } else {
            $baseUnique = mt_rand();
        }
        return uniqid($baseUnique, true);
    }
    /**
     * Clone the cell collection
     *
     * @param    PHPExcel_Worksheet    $parent        The new worksheet
     * @return    void
     */
    public function copyCellCollection(PHPExcel_Worksheet $parent)
    {
        $this->currentCellIsDirty;
        $this->storeData();
        $this->parent = $parent;
        if ($this->currentObject !== NULL && is_object($this->currentObject)) {
            $this->currentObject->attach($this);
        }
    }
    /**
     * Remove a row, deleting all cells in that row
     *
     * @param string    $row    Row number to remove
     * @return void
     */
    public function removeRow($row)
    {
        foreach ($this->getCellList() as $coord) {
            sscanf($coord, "%[A-Z]%d", $c, $r);
            if ($r == $row) {
                $this->deleteCacheData($coord);
            }
        }
    }
    /**
     * Remove a column, deleting all cells in that column
     *
     * @param string    $column    Column ID to remove
     * @return void
     */
    public function removeColumn($column)
    {
        foreach ($this->getCellList() as $coord) {
            sscanf($coord, "%[A-Z]%d", $c, $r);
            if ($c == $column) {
                $this->deleteCacheData($coord);
            }
        }
    }
    /**
     * Identify whether the caching method is currently available
     * Some methods are dependent on the availability of certain extensions being enabled in the PHP build
     *
     * @return    boolean
     */
    public static function cacheMethodIsAvailable()
    {
        return true;
    }
}

?>