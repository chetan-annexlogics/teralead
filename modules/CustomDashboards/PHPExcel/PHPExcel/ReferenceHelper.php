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
 * PHPExcel_ReferenceHelper (Singleton)
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
class PHPExcel_ReferenceHelper
{
    /**
     * Instance of this class
     *
     * @var PHPExcel_ReferenceHelper
     */
    private static $instance = NULL;
    const REFHELPER_REGEXP_CELLREF = "((\\w*|'[^!]*')!)?(?<![:a-z\\\$])(\\\$?[a-z]{1,3}\\\$?\\d+)(?=[^:!\\d'])";
    const REFHELPER_REGEXP_CELLRANGE = "((\\w*|'[^!]*')!)?(\\\$?[a-z]{1,3}\\\$?\\d+):(\\\$?[a-z]{1,3}\\\$?\\d+)";
    const REFHELPER_REGEXP_ROWRANGE = "((\\w*|'[^!]*')!)?(\\\$?\\d+):(\\\$?\\d+)";
    const REFHELPER_REGEXP_COLRANGE = "((\\w*|'[^!]*')!)?(\\\$?[a-z]{1,3}):(\\\$?[a-z]{1,3})";
    /**
     * Get an instance of this class
     *
     * @return PHPExcel_ReferenceHelper
     */
    public static function getInstance()
    {
        if (!isset($instance) || self::$instance === NULL) {
            self::$instance = new PHPExcel_ReferenceHelper();
        }
        return self::$instance;
    }
    /**
     * Create a new PHPExcel_ReferenceHelper
     */
    protected function __construct()
    {
    }
    /**
     * Compare two column addresses
     * Intended for use as a Callback function for sorting column addresses by column
     *
     * @param   string   $a  First column to test (e.g. 'AA')
     * @param   string   $b  Second column to test (e.g. 'Z')
     * @return  integer
     */
    public static function columnSort($a, $b)
    {
        return strcasecmp(strlen($a) . $a, strlen($b) . $b);
    }
    /**
     * Compare two column addresses
     * Intended for use as a Callback function for reverse sorting column addresses by column
     *
     * @param   string   $a  First column to test (e.g. 'AA')
     * @param   string   $b  Second column to test (e.g. 'Z')
     * @return  integer
     */
    public static function columnReverseSort($a, $b)
    {
        return 1 - strcasecmp(strlen($a) . $a, strlen($b) . $b);
    }
    /**
     * Compare two cell addresses
     * Intended for use as a Callback function for sorting cell addresses by column and row
     *
     * @param   string   $a  First cell to test (e.g. 'AA1')
     * @param   string   $b  Second cell to test (e.g. 'Z1')
     * @return  integer
     */
    public static function cellSort($a, $b)
    {
        sscanf($a, "%[A-Z]%d", $ac, $ar);
        sscanf($b, "%[A-Z]%d", $bc, $br);
        if ($ar == $br) {
            return strcasecmp(strlen($ac) . $ac, strlen($bc) . $bc);
        }
        return $ar < $br ? -1 : 1;
    }
    /**
     * Compare two cell addresses
     * Intended for use as a Callback function for sorting cell addresses by column and row
     *
     * @param   string   $a  First cell to test (e.g. 'AA1')
     * @param   string   $b  Second cell to test (e.g. 'Z1')
     * @return  integer
     */
    public static function cellReverseSort($a, $b)
    {
        sscanf($a, "%[A-Z]%d", $ac, $ar);
        sscanf($b, "%[A-Z]%d", $bc, $br);
        if ($ar == $br) {
            return 1 - strcasecmp(strlen($ac) . $ac, strlen($bc) . $bc);
        }
        return $ar < $br ? 1 : -1;
    }
    /**
     * Test whether a cell address falls within a defined range of cells
     *
     * @param   string     $cellAddress        Address of the cell we're testing
     * @param   integer    $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer    $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     * @param   integer    $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer    $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @return  boolean
     */
    private static function cellAddressInDeleteRange($cellAddress, $beforeRow, $pNumRows, $beforeColumnIndex, $pNumCols)
    {
        list($cellColumn, $cellRow) = PHPExcel_Cell::coordinateFromString($cellAddress);
        $cellColumnIndex = PHPExcel_Cell::columnIndexFromString($cellColumn);
        if ($pNumRows < 0 && $beforeRow + $pNumRows <= $cellRow && $cellRow < $beforeRow) {
            return true;
        }
        if ($pNumCols < 0 && $beforeColumnIndex + $pNumCols <= $cellColumnIndex && $cellColumnIndex < $beforeColumnIndex) {
            return true;
        }
        return false;
    }
    /**
     * Update page breaks when inserting/deleting rows/columns
     *
     * @param   PHPExcel_Worksheet  $pSheet             The worksheet that we're editing
     * @param   string              $pBefore            Insert/Delete before this cell address (e.g. 'A1')
     * @param   integer             $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer             $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer             $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     */
    protected function adjustPageBreaks(PHPExcel_Worksheet $pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows)
    {
        $aBreaks = $pSheet->getBreaks();
        0 < $pNumCols || 0 < $pNumRows ? uksort($aBreaks, array("PHPExcel_ReferenceHelper", "cellReverseSort")) : uksort($aBreaks, array("PHPExcel_ReferenceHelper", "cellSort"));
        foreach ($aBreaks as $key => $value) {
            if (self::cellAddressInDeleteRange($key, $beforeRow, $pNumRows, $beforeColumnIndex, $pNumCols)) {
                $pSheet->setBreak($key, PHPExcel_Worksheet::BREAK_NONE);
            } else {
                $newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);
                if ($key != $newReference) {
                    $pSheet->setBreak($newReference, $value)->setBreak($key, PHPExcel_Worksheet::BREAK_NONE);
                }
            }
        }
    }
    /**
     * Update cell comments when inserting/deleting rows/columns
     *
     * @param   PHPExcel_Worksheet  $pSheet             The worksheet that we're editing
     * @param   string              $pBefore            Insert/Delete before this cell address (e.g. 'A1')
     * @param   integer             $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer             $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer             $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     */
    protected function adjustComments($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows)
    {
        $aComments = $pSheet->getComments();
        $aNewComments = array();
        foreach ($aComments as $key => &$value) {
            if (!self::cellAddressInDeleteRange($key, $beforeRow, $pNumRows, $beforeColumnIndex, $pNumCols)) {
                $newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);
                $aNewComments[$newReference] = $value;
            }
        }
        $pSheet->setComments($aNewComments);
    }
    /**
     * Update hyperlinks when inserting/deleting rows/columns
     *
     * @param   PHPExcel_Worksheet  $pSheet             The worksheet that we're editing
     * @param   string              $pBefore            Insert/Delete before this cell address (e.g. 'A1')
     * @param   integer             $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer             $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer             $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     */
    protected function adjustHyperlinks($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows)
    {
        $aHyperlinkCollection = $pSheet->getHyperlinkCollection();
        0 < $pNumCols || 0 < $pNumRows ? uksort($aHyperlinkCollection, array("PHPExcel_ReferenceHelper", "cellReverseSort")) : uksort($aHyperlinkCollection, array("PHPExcel_ReferenceHelper", "cellSort"));
        foreach ($aHyperlinkCollection as $key => $value) {
            $newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);
            if ($key != $newReference) {
                $pSheet->setHyperlink($newReference, $value);
                $pSheet->setHyperlink($key, NULL);
            }
        }
    }
    /**
     * Update data validations when inserting/deleting rows/columns
     *
     * @param   PHPExcel_Worksheet  $pSheet             The worksheet that we're editing
     * @param   string              $pBefore            Insert/Delete before this cell address (e.g. 'A1')
     * @param   integer             $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer             $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer             $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     */
    protected function adjustDataValidations($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows)
    {
        $aDataValidationCollection = $pSheet->getDataValidationCollection();
        0 < $pNumCols || 0 < $pNumRows ? uksort($aDataValidationCollection, array("PHPExcel_ReferenceHelper", "cellReverseSort")) : uksort($aDataValidationCollection, array("PHPExcel_ReferenceHelper", "cellSort"));
        foreach ($aDataValidationCollection as $key => $value) {
            $newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);
            if ($key != $newReference) {
                $pSheet->setDataValidation($newReference, $value);
                $pSheet->setDataValidation($key, NULL);
            }
        }
    }
    /**
     * Update merged cells when inserting/deleting rows/columns
     *
     * @param   PHPExcel_Worksheet  $pSheet             The worksheet that we're editing
     * @param   string              $pBefore            Insert/Delete before this cell address (e.g. 'A1')
     * @param   integer             $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer             $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer             $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     */
    protected function adjustMergeCells($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows)
    {
        $aMergeCells = $pSheet->getMergeCells();
        $aNewMergeCells = array();
        foreach ($aMergeCells as $key => &$value) {
            $newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);
            $aNewMergeCells[$newReference] = $newReference;
        }
        $pSheet->setMergeCells($aNewMergeCells);
    }
    /**
     * Update protected cells when inserting/deleting rows/columns
     *
     * @param   PHPExcel_Worksheet  $pSheet             The worksheet that we're editing
     * @param   string              $pBefore            Insert/Delete before this cell address (e.g. 'A1')
     * @param   integer             $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer             $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer             $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     */
    protected function adjustProtectedCells($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows)
    {
        $aProtectedCells = $pSheet->getProtectedCells();
        0 < $pNumCols || 0 < $pNumRows ? uksort($aProtectedCells, array("PHPExcel_ReferenceHelper", "cellReverseSort")) : uksort($aProtectedCells, array("PHPExcel_ReferenceHelper", "cellSort"));
        foreach ($aProtectedCells as $key => $value) {
            $newReference = $this->updateCellReference($key, $pBefore, $pNumCols, $pNumRows);
            if ($key != $newReference) {
                $pSheet->protectCells($newReference, $value, true);
                $pSheet->unprotectCells($key);
            }
        }
    }
    /**
     * Update column dimensions when inserting/deleting rows/columns
     *
     * @param   PHPExcel_Worksheet  $pSheet             The worksheet that we're editing
     * @param   string              $pBefore            Insert/Delete before this cell address (e.g. 'A1')
     * @param   integer             $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer             $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer             $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     */
    protected function adjustColumnDimensions($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows)
    {
        $aColumnDimensions = array_reverse($pSheet->getColumnDimensions(), true);
        if (!empty($aColumnDimensions)) {
            foreach ($aColumnDimensions as $objColumnDimension) {
                $newReference = $this->updateCellReference($objColumnDimension->getColumnIndex() . "1", $pBefore, $pNumCols, $pNumRows);
                list($newReference) = PHPExcel_Cell::coordinateFromString($newReference);
                if ($objColumnDimension->getColumnIndex() != $newReference) {
                    $objColumnDimension->setColumnIndex($newReference);
                }
            }
            $pSheet->refreshColumnDimensions();
        }
    }
    /**
     * Update row dimensions when inserting/deleting rows/columns
     *
     * @param   PHPExcel_Worksheet  $pSheet             The worksheet that we're editing
     * @param   string              $pBefore            Insert/Delete before this cell address (e.g. 'A1')
     * @param   integer             $beforeColumnIndex  Index number of the column we're inserting/deleting before
     * @param   integer             $pNumCols           Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $beforeRow          Number of the row we're inserting/deleting before
     * @param   integer             $pNumRows           Number of rows to insert/delete (negative values indicate deletion)
     */
    protected function adjustRowDimensions($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows)
    {
        $aRowDimensions = array_reverse($pSheet->getRowDimensions(), true);
        if (!empty($aRowDimensions)) {
            foreach ($aRowDimensions as $objRowDimension) {
                $newReference = $this->updateCellReference("A" . $objRowDimension->getRowIndex(), $pBefore, $pNumCols, $pNumRows);
                list($newReference) = PHPExcel_Cell::coordinateFromString($newReference);
                if ($objRowDimension->getRowIndex() != $newReference) {
                    $objRowDimension->setRowIndex($newReference);
                }
            }
            $pSheet->refreshRowDimensions();
            $copyDimension = $pSheet->getRowDimension($beforeRow - 1);
            for ($i = $beforeRow; $i <= $beforeRow - 1 + $pNumRows; $i++) {
                $newDimension = $pSheet->getRowDimension($i);
                $newDimension->setRowHeight($copyDimension->getRowHeight());
                $newDimension->setVisible($copyDimension->getVisible());
                $newDimension->setOutlineLevel($copyDimension->getOutlineLevel());
                $newDimension->setCollapsed($copyDimension->getCollapsed());
            }
        }
    }
    /**
     * Insert a new column or row, updating all possible related data
     *
     * @param   string              $pBefore    Insert before this cell address (e.g. 'A1')
     * @param   integer             $pNumCols   Number of columns to insert/delete (negative values indicate deletion)
     * @param   integer             $pNumRows   Number of rows to insert/delete (negative values indicate deletion)
     * @param   PHPExcel_Worksheet  $pSheet     The worksheet that we're editing
     * @throws  PHPExcel_Exception
     */
    public function insertNewBefore($pBefore = "A1", $pNumCols = 0, $pNumRows = 0, PHPExcel_Worksheet $pSheet = NULL)
    {
        $remove = $pNumCols < 0 || $pNumRows < 0;
        $aCellCollection = $pSheet->getCellCollection();
        $beforeColumn = "A";
        $beforeRow = 1;
        list($beforeColumn, $beforeRow) = PHPExcel_Cell::coordinateFromString($pBefore);
        $beforeColumnIndex = PHPExcel_Cell::columnIndexFromString($beforeColumn);
        $highestColumn = $pSheet->getHighestColumn();
        $highestRow = $pSheet->getHighestRow();
        if ($pNumCols < 0 && 0 < $beforeColumnIndex - 2 + $pNumCols) {
            for ($i = 1; $i <= $highestRow - 1; $i++) {
                for ($j = $beforeColumnIndex - 1 + $pNumCols; $j <= $beforeColumnIndex - 2; $j++) {
                    $coordinate = PHPExcel_Cell::stringFromColumnIndex($j) . $i;
                    $pSheet->removeConditionalStyles($coordinate);
                    if ($pSheet->cellExists($coordinate)) {
                        $pSheet->getCell($coordinate)->setValueExplicit("", PHPExcel_Cell_DataType::TYPE_NULL);
                        $pSheet->getCell($coordinate)->setXfIndex(0);
                    }
                }
            }
        }
        if ($pNumRows < 0 && 0 < $beforeRow - 1 + $pNumRows) {
            for ($i = $beforeColumnIndex - 1; $i <= PHPExcel_Cell::columnIndexFromString($highestColumn) - 1; $i++) {
                for ($j = $beforeRow + $pNumRows; $j <= $beforeRow - 1; $j++) {
                    $coordinate = PHPExcel_Cell::stringFromColumnIndex($i) . $j;
                    $pSheet->removeConditionalStyles($coordinate);
                    if ($pSheet->cellExists($coordinate)) {
                        $pSheet->getCell($coordinate)->setValueExplicit("", PHPExcel_Cell_DataType::TYPE_NULL);
                        $pSheet->getCell($coordinate)->setXfIndex(0);
                    }
                }
            }
        }
        if ($remove) {
            $aCellCollection = array_reverse($aCellCollection);
        }
        while ($cellID = array_pop($aCellCollection)) {
            $cell = $pSheet->getCell($cellID);
            $cellIndex = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
            if ($cellIndex - 1 + $pNumCols < 0) {
                continue;
            }
            $newCoordinates = PHPExcel_Cell::stringFromColumnIndex($cellIndex - 1 + $pNumCols) . ($cell->getRow() + $pNumRows);
            if ($beforeColumnIndex <= $cellIndex && $beforeRow <= $cell->getRow()) {
                $pSheet->getCell($newCoordinates)->setXfIndex($cell->getXfIndex());
                if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
                    $pSheet->getCell($newCoordinates)->setValue($this->updateFormulaReferences($cell->getValue(), $pBefore, $pNumCols, $pNumRows, $pSheet->getTitle()));
                } else {
                    $pSheet->getCell($newCoordinates)->setValue($cell->getValue());
                }
                $pSheet->getCellCacheController()->deleteCacheData($cellID);
            } else {
                if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
                    $cell->setValue($this->updateFormulaReferences($cell->getValue(), $pBefore, $pNumCols, $pNumRows, $pSheet->getTitle()));
                }
            }
        }
        $highestColumn = $pSheet->getHighestColumn();
        $highestRow = $pSheet->getHighestRow();
        if (0 < $pNumCols && 0 < $beforeColumnIndex - 2) {
            for ($i = $beforeRow; $i <= $highestRow - 1; $i++) {
                $coordinate = PHPExcel_Cell::stringFromColumnIndex($beforeColumnIndex - 2) . $i;
                if ($pSheet->cellExists($coordinate)) {
                    $xfIndex = $pSheet->getCell($coordinate)->getXfIndex();
                    $conditionalStyles = $pSheet->conditionalStylesExists($coordinate) ? $pSheet->getConditionalStyles($coordinate) : false;
                    for ($j = $beforeColumnIndex - 1; $j <= $beforeColumnIndex - 2 + $pNumCols; $j++) {
                        $pSheet->getCellByColumnAndRow($j, $i)->setXfIndex($xfIndex);
                        if ($conditionalStyles) {
                            $cloned = array();
                            foreach ($conditionalStyles as $conditionalStyle) {
                                $cloned[] = clone $conditionalStyle;
                            }
                            $pSheet->setConditionalStyles(PHPExcel_Cell::stringFromColumnIndex($j) . $i, $cloned);
                        }
                    }
                }
            }
        }
        if (0 < $pNumRows && 0 < $beforeRow - 1) {
            for ($i = $beforeColumnIndex - 1; $i <= PHPExcel_Cell::columnIndexFromString($highestColumn) - 1; $i++) {
                $coordinate = PHPExcel_Cell::stringFromColumnIndex($i) . ($beforeRow - 1);
                if ($pSheet->cellExists($coordinate)) {
                    $xfIndex = $pSheet->getCell($coordinate)->getXfIndex();
                    $conditionalStyles = $pSheet->conditionalStylesExists($coordinate) ? $pSheet->getConditionalStyles($coordinate) : false;
                    for ($j = $beforeRow; $j <= $beforeRow - 1 + $pNumRows; $j++) {
                        $pSheet->getCell(PHPExcel_Cell::stringFromColumnIndex($i) . $j)->setXfIndex($xfIndex);
                        if ($conditionalStyles) {
                            $cloned = array();
                            foreach ($conditionalStyles as $conditionalStyle) {
                                $cloned[] = clone $conditionalStyle;
                            }
                            $pSheet->setConditionalStyles(PHPExcel_Cell::stringFromColumnIndex($i) . $j, $cloned);
                        }
                    }
                }
            }
        }
        $this->adjustColumnDimensions($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows);
        $this->adjustRowDimensions($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows);
        $this->adjustPageBreaks($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows);
        $this->adjustComments($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows);
        $this->adjustHyperlinks($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows);
        $this->adjustDataValidations($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows);
        $this->adjustMergeCells($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows);
        $this->adjustProtectedCells($pSheet, $pBefore, $beforeColumnIndex, $pNumCols, $beforeRow, $pNumRows);
        $autoFilter = $pSheet->getAutoFilter();
        $autoFilterRange = $autoFilter->getRange();
        if (!empty($autoFilterRange)) {
            if ($pNumCols != 0) {
                $autoFilterColumns = array_keys($autoFilter->getColumns());
                if (0 < count($autoFilterColumns)) {
                    sscanf($pBefore, "%[A-Z]%d", $column, $row);
                    $columnIndex = PHPExcel_Cell::columnIndexFromString($column);
                    list($rangeStart, $rangeEnd) = PHPExcel_Cell::rangeBoundaries($autoFilterRange);
                    if ($columnIndex <= $rangeEnd[0]) {
                        if ($pNumCols < 0) {
                            $deleteColumn = $columnIndex + $pNumCols - 1;
                            $deleteCount = abs($pNumCols);
                            for ($i = 1; $i <= $deleteCount; $i++) {
                                if (in_array(PHPExcel_Cell::stringFromColumnIndex($deleteColumn), $autoFilterColumns)) {
                                    $autoFilter->clearColumn(PHPExcel_Cell::stringFromColumnIndex($deleteColumn));
                                }
                                $deleteColumn++;
                            }
                        }
                        $startCol = $rangeStart[0] < $columnIndex ? $columnIndex : $rangeStart[0];
                        if (0 < $pNumCols) {
                            $startColID = PHPExcel_Cell::stringFromColumnIndex($startCol - 1);
                            $toColID = PHPExcel_Cell::stringFromColumnIndex($startCol + $pNumCols - 1);
                            $endColID = PHPExcel_Cell::stringFromColumnIndex($rangeEnd[0]);
                            $startColRef = $startCol;
                            $endColRef = $rangeEnd[0];
                            $toColRef = $rangeEnd[0] + $pNumCols;
                            do {
                                $autoFilter->shiftColumn(PHPExcel_Cell::stringFromColumnIndex($endColRef - 1), PHPExcel_Cell::stringFromColumnIndex($toColRef - 1));
                                $endColRef--;
                                $toColRef--;
                            } while ($startColRef <= $endColRef);
                        } else {
                            $startColID = PHPExcel_Cell::stringFromColumnIndex($startCol - 1);
                            $toColID = PHPExcel_Cell::stringFromColumnIndex($startCol + $pNumCols - 1);
                            $endColID = PHPExcel_Cell::stringFromColumnIndex($rangeEnd[0]);
                            do {
                                $autoFilter->shiftColumn($startColID, $toColID);
                                $startColID++;
                                $toColID++;
                            } while ($startColID != $endColID);
                        }
                    }
                }
            }
            $pSheet->setAutoFilter($this->updateCellReference($autoFilterRange, $pBefore, $pNumCols, $pNumRows));
        }
        if ($pSheet->getFreezePane() != "") {
            $pSheet->freezePane($this->updateCellReference($pSheet->getFreezePane(), $pBefore, $pNumCols, $pNumRows));
        }
        if ($pSheet->getPageSetup()->isPrintAreaSet()) {
            $pSheet->getPageSetup()->setPrintArea($this->updateCellReference($pSheet->getPageSetup()->getPrintArea(), $pBefore, $pNumCols, $pNumRows));
        }
        $aDrawings = $pSheet->getDrawingCollection();
        foreach ($aDrawings as $objDrawing) {
            $newReference = $this->updateCellReference($objDrawing->getCoordinates(), $pBefore, $pNumCols, $pNumRows);
            if ($objDrawing->getCoordinates() != $newReference) {
                $objDrawing->setCoordinates($newReference);
            }
        }
        if (0 < count($pSheet->getParent()->getNamedRanges())) {
            foreach ($pSheet->getParent()->getNamedRanges() as $namedRange) {
                if ($namedRange->getWorksheet()->getHashCode() == $pSheet->getHashCode()) {
                    $namedRange->setRange($this->updateCellReference($namedRange->getRange(), $pBefore, $pNumCols, $pNumRows));
                }
            }
        }
        $pSheet->garbageCollect();
    }
    /**
     * Update references within formulas
     *
     * @param    string    $pFormula    Formula to update
     * @param    int        $pBefore    Insert before this one
     * @param    int        $pNumCols    Number of columns to insert
     * @param    int        $pNumRows    Number of rows to insert
     * @param   string  $sheetName  Worksheet name/title
     * @return    string    Updated formula
     * @throws    PHPExcel_Exception
     */
    public function updateFormulaReferences($pFormula = "", $pBefore = "A1", $pNumCols = 0, $pNumRows = 0, $sheetName = "")
    {
        $formulaBlocks = explode("\"", $pFormula);
        $i = false;
        foreach ($formulaBlocks as &$formulaBlock) {
            if ($i = !$i) {
                $adjustCount = 0;
                $newCellTokens = $cellTokens = array();
                $matchCount = preg_match_all("/" . self::REFHELPER_REGEXP_ROWRANGE . "/i", " " . $formulaBlock . " ", $matches, PREG_SET_ORDER);
                if (0 < $matchCount) {
                    foreach ($matches as $match) {
                        $fromString = "" < $match[2] ? $match[2] . "!" : "";
                        $fromString .= $match[3] . ":" . $match[4];
                        $modified3 = substr($this->updateCellReference("\$A" . $match[3], $pBefore, $pNumCols, $pNumRows), 2);
                        $modified4 = substr($this->updateCellReference("\$A" . $match[4], $pBefore, $pNumCols, $pNumRows), 2);
                        if ($match[3] . ":" . $match[4] !== $modified3 . ":" . $modified4 && ($match[2] == "" || trim($match[2], "'") == $sheetName)) {
                            $toString = "" < $match[2] ? $match[2] . "!" : "";
                            $toString .= $modified3 . ":" . $modified4;
                            $column = 100000;
                            $row = 10000000 + trim($match[3], "\$");
                            $cellIndex = $column . $row;
                            $newCellTokens[$cellIndex] = preg_quote($toString);
                            $cellTokens[$cellIndex] = "/(?<!\\d\\\$\\!)" . preg_quote($fromString) . "(?!\\d)/i";
                            $adjustCount++;
                        }
                    }
                }
                $matchCount = preg_match_all("/" . self::REFHELPER_REGEXP_COLRANGE . "/i", " " . $formulaBlock . " ", $matches, PREG_SET_ORDER);
                if (0 < $matchCount) {
                    foreach ($matches as $match) {
                        $fromString = "" < $match[2] ? $match[2] . "!" : "";
                        $fromString .= $match[3] . ":" . $match[4];
                        $modified3 = substr($this->updateCellReference($match[3] . "\$1", $pBefore, $pNumCols, $pNumRows), 0, -2);
                        $modified4 = substr($this->updateCellReference($match[4] . "\$1", $pBefore, $pNumCols, $pNumRows), 0, -2);
                        if ($match[3] . ":" . $match[4] !== $modified3 . ":" . $modified4 && ($match[2] == "" || trim($match[2], "'") == $sheetName)) {
                            $toString = "" < $match[2] ? $match[2] . "!" : "";
                            $toString .= $modified3 . ":" . $modified4;
                            $column = PHPExcel_Cell::columnIndexFromString(trim($match[3], "\$")) + 100000;
                            $row = 10000000;
                            $cellIndex = $column . $row;
                            $newCellTokens[$cellIndex] = preg_quote($toString);
                            $cellTokens[$cellIndex] = "/(?<![A-Z\\\$\\!])" . preg_quote($fromString) . "(?![A-Z])/i";
                            $adjustCount++;
                        }
                    }
                }
                $matchCount = preg_match_all("/" . self::REFHELPER_REGEXP_CELLRANGE . "/i", " " . $formulaBlock . " ", $matches, PREG_SET_ORDER);
                if (0 < $matchCount) {
                    foreach ($matches as $match) {
                        $fromString = "" < $match[2] ? $match[2] . "!" : "";
                        $fromString .= $match[3] . ":" . $match[4];
                        $modified3 = $this->updateCellReference($match[3], $pBefore, $pNumCols, $pNumRows);
                        $modified4 = $this->updateCellReference($match[4], $pBefore, $pNumCols, $pNumRows);
                        if ($match[3] . $match[4] !== $modified3 . $modified4 && ($match[2] == "" || trim($match[2], "'") == $sheetName)) {
                            $toString = "" < $match[2] ? $match[2] . "!" : "";
                            $toString .= $modified3 . ":" . $modified4;
                            list($column, $row) = PHPExcel_Cell::coordinateFromString($match[3]);
                            $column = PHPExcel_Cell::columnIndexFromString(trim($column, "\$")) + 100000;
                            $row = trim($row, "\$") + 10000000;
                            $cellIndex = $column . $row;
                            $newCellTokens[$cellIndex] = preg_quote($toString);
                            $cellTokens[$cellIndex] = "/(?<![A-Z]\\\$\\!)" . preg_quote($fromString) . "(?!\\d)/i";
                            $adjustCount++;
                        }
                    }
                }
                $matchCount = preg_match_all("/" . self::REFHELPER_REGEXP_CELLREF . "/i", " " . $formulaBlock . " ", $matches, PREG_SET_ORDER);
                if (0 < $matchCount) {
                    foreach ($matches as $match) {
                        $fromString = "" < $match[2] ? $match[2] . "!" : "";
                        $fromString .= $match[3];
                        $modified3 = $this->updateCellReference($match[3], $pBefore, $pNumCols, $pNumRows);
                        if ($match[3] !== $modified3 && ($match[2] == "" || trim($match[2], "'") == $sheetName)) {
                            $toString = "" < $match[2] ? $match[2] . "!" : "";
                            $toString .= $modified3;
                            list($column, $row) = PHPExcel_Cell::coordinateFromString($match[3]);
                            $column = PHPExcel_Cell::columnIndexFromString(trim($column, "\$")) + 100000;
                            $row = trim($row, "\$") + 10000000;
                            $cellIndex = $row . $column;
                            $newCellTokens[$cellIndex] = preg_quote($toString);
                            $cellTokens[$cellIndex] = "/(?<![A-Z\\\$\\!])" . preg_quote($fromString) . "(?!\\d)/i";
                            $adjustCount++;
                        }
                    }
                }
                if (0 < $adjustCount) {
                    if (0 < $pNumCols || 0 < $pNumRows) {
                        krsort($cellTokens);
                        krsort($newCellTokens);
                    } else {
                        ksort($cellTokens);
                        ksort($newCellTokens);
                    }
                    $formulaBlock = str_replace("\\", "", preg_replace($cellTokens, $newCellTokens, $formulaBlock));
                }
            }
        }
        unset($formulaBlock);
        return implode("\"", $formulaBlocks);
    }
    /**
     * Update cell reference
     *
     * @param    string    $pCellRange            Cell range
     * @param    int        $pBefore            Insert before this one
     * @param    int        $pNumCols            Number of columns to increment
     * @param    int        $pNumRows            Number of rows to increment
     * @return    string    Updated cell range
     * @throws    PHPExcel_Exception
     */
    public function updateCellReference($pCellRange = "A1", $pBefore = "A1", $pNumCols = 0, $pNumRows = 0)
    {
        if (strpos($pCellRange, "!") !== false) {
            return $pCellRange;
        }
        if (strpos($pCellRange, ":") === false && strpos($pCellRange, ",") === false) {
            return $this->updateSingleCellReference($pCellRange, $pBefore, $pNumCols, $pNumRows);
        }
        if (strpos($pCellRange, ":") !== false || strpos($pCellRange, ",") !== false) {
            return $this->updateCellRange($pCellRange, $pBefore, $pNumCols, $pNumRows);
        }
        return $pCellRange;
    }
    /**
     * Update named formulas (i.e. containing worksheet references / named ranges)
     *
     * @param PHPExcel $pPhpExcel    Object to update
     * @param string $oldName        Old name (name to replace)
     * @param string $newName        New name
     */
    public function updateNamedFormulas(PHPExcel $pPhpExcel, $oldName = "", $newName = "")
    {
        if ($oldName == "") {
            return NULL;
        }
        foreach ($pPhpExcel->getWorksheetIterator() as $sheet) {
            foreach ($sheet->getCellCollection(false) as $cellID) {
                $cell = $sheet->getCell($cellID);
                if ($cell !== NULL && $cell->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
                    $formula = $cell->getValue();
                    if (strpos($formula, $oldName) !== false) {
                        $formula = str_replace("'" . $oldName . "'!", "'" . $newName . "'!", $formula);
                        $formula = str_replace($oldName . "!", $newName . "!", $formula);
                        $cell->setValueExplicit($formula, PHPExcel_Cell_DataType::TYPE_FORMULA);
                    }
                }
            }
        }
    }
    /**
     * Update cell range
     *
     * @param    string    $pCellRange            Cell range    (e.g. 'B2:D4', 'B:C' or '2:3')
     * @param    int        $pBefore            Insert before this one
     * @param    int        $pNumCols            Number of columns to increment
     * @param    int        $pNumRows            Number of rows to increment
     * @return    string    Updated cell range
     * @throws    PHPExcel_Exception
     */
    private function updateCellRange($pCellRange = "A1:A1", $pBefore = "A1", $pNumCols = 0, $pNumRows = 0)
    {
        if (strpos($pCellRange, ":") !== false || strpos($pCellRange, ",") !== false) {
            $range = PHPExcel_Cell::splitRange($pCellRange);
            $ic = count($range);
            for ($i = 0; $i < $ic; $i++) {
                $jc = count($range[$i]);
                for ($j = 0; $j < $jc; $j++) {
                    if (ctype_alpha($range[$i][$j])) {
                        $r = PHPExcel_Cell::coordinateFromString($this->updateSingleCellReference($range[$i][$j] . "1", $pBefore, $pNumCols, $pNumRows));
                        $range[$i][$j] = $r[0];
                    } else {
                        if (ctype_digit($range[$i][$j])) {
                            $r = PHPExcel_Cell::coordinateFromString($this->updateSingleCellReference("A" . $range[$i][$j], $pBefore, $pNumCols, $pNumRows));
                            $range[$i][$j] = $r[1];
                        } else {
                            $range[$i][$j] = $this->updateSingleCellReference($range[$i][$j], $pBefore, $pNumCols, $pNumRows);
                        }
                    }
                }
            }
            return PHPExcel_Cell::buildRange($range);
        }
        throw new PHPExcel_Exception("Only cell ranges may be passed to this method.");
    }
    /**
     * Update single cell reference
     *
     * @param    string    $pCellReference        Single cell reference
     * @param    int        $pBefore            Insert before this one
     * @param    int        $pNumCols            Number of columns to increment
     * @param    int        $pNumRows            Number of rows to increment
     * @return    string    Updated cell reference
     * @throws    PHPExcel_Exception
     */
    private function updateSingleCellReference($pCellReference = "A1", $pBefore = "A1", $pNumCols = 0, $pNumRows = 0)
    {
        if (strpos($pCellReference, ":") === false && strpos($pCellReference, ",") === false) {
            list($beforeColumn, $beforeRow) = PHPExcel_Cell::coordinateFromString($pBefore);
            list($newColumn, $newRow) = PHPExcel_Cell::coordinateFromString($pCellReference);
            $updateColumn = $newColumn[0] != "\$" && $beforeColumn[0] != "\$" && PHPExcel_Cell::columnIndexFromString($beforeColumn) <= PHPExcel_Cell::columnIndexFromString($newColumn);
            $updateRow = $newRow[0] != "\$" && $beforeRow[0] != "\$" && $beforeRow <= $newRow;
            if ($updateColumn) {
                $newColumn = PHPExcel_Cell::stringFromColumnIndex(PHPExcel_Cell::columnIndexFromString($newColumn) - 1 + $pNumCols);
            }
            if ($updateRow) {
                $newRow = $newRow + $pNumRows;
            }
            return $newColumn . $newRow;
        }
        throw new PHPExcel_Exception("Only single cell references may be passed to this method.");
    }
    /**
     * __clone implementation. Cloning should not be allowed in a Singleton!
     *
     * @throws    PHPExcel_Exception
     */
    public final function __clone()
    {
        throw new PHPExcel_Exception("Cloning a Singleton is not allowed!");
    }
}

?>