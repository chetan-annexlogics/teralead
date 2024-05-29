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
 * PHPExcel_Style
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
 * @package    PHPExcel_Style
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Style extends PHPExcel_Style_Supervisor implements PHPExcel_IComparable
{
    /**
     * Font
     *
     * @var PHPExcel_Style_Font
     */
    protected $font = NULL;
    /**
     * Fill
     *
     * @var PHPExcel_Style_Fill
     */
    protected $fill = NULL;
    /**
     * Borders
     *
     * @var PHPExcel_Style_Borders
     */
    protected $borders = NULL;
    /**
     * Alignment
     *
     * @var PHPExcel_Style_Alignment
     */
    protected $alignment = NULL;
    /**
     * Number Format
     *
     * @var PHPExcel_Style_NumberFormat
     */
    protected $numberFormat = NULL;
    /**
     * Conditional styles
     *
     * @var PHPExcel_Style_Conditional[]
     */
    protected $conditionalStyles = NULL;
    /**
     * Protection
     *
     * @var PHPExcel_Style_Protection
     */
    protected $protection = NULL;
    /**
     * Index of style in collection. Only used for real style.
     *
     * @var int
     */
    protected $index = NULL;
    /**
     * Use Quote Prefix when displaying in cell editor. Only used for real style.
     *
     * @var boolean
     */
    protected $quotePrefix = false;
    /**
     * Create a new PHPExcel_Style
     *
     * @param boolean $isSupervisor Flag indicating if this is a supervisor or not
     *         Leave this value at default unless you understand exactly what
     *    its ramifications are
     * @param boolean $isConditional Flag indicating if this is a conditional style or not
     *       Leave this value at default unless you understand exactly what
     *    its ramifications are
     */
    public function __construct($isSupervisor = false, $isConditional = false)
    {
        $this->isSupervisor = $isSupervisor;
        $this->conditionalStyles = array();
        $this->font = new PHPExcel_Style_Font($isSupervisor, $isConditional);
        $this->fill = new PHPExcel_Style_Fill($isSupervisor, $isConditional);
        $this->borders = new PHPExcel_Style_Borders($isSupervisor, $isConditional);
        $this->alignment = new PHPExcel_Style_Alignment($isSupervisor, $isConditional);
        $this->numberFormat = new PHPExcel_Style_NumberFormat($isSupervisor, $isConditional);
        $this->protection = new PHPExcel_Style_Protection($isSupervisor, $isConditional);
        if ($isSupervisor) {
            $this->font->bindParent($this);
            $this->fill->bindParent($this);
            $this->borders->bindParent($this);
            $this->alignment->bindParent($this);
            $this->numberFormat->bindParent($this);
            $this->protection->bindParent($this);
        }
    }
    /**
     * Get the shared style component for the currently active cell in currently active sheet.
     * Only used for style supervisor
     *
     * @return PHPExcel_Style
     */
    public function getSharedComponent()
    {
        $activeSheet = $this->getActiveSheet();
        $selectedCell = $this->getActiveCell();
        if ($activeSheet->cellExists($selectedCell)) {
            $xfIndex = $activeSheet->getCell($selectedCell)->getXfIndex();
        } else {
            $xfIndex = 0;
        }
        return $this->parent->getCellXfByIndex($xfIndex);
    }
    /**
     * Get parent. Only used for style supervisor
     *
     * @return PHPExcel
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Build style array from subcomponents
     *
     * @param array $array
     * @return array
     */
    public function getStyleArray($array)
    {
        return array("quotePrefix" => $array);
    }
    /**
     * Apply styles from array
     *
     * <code>
     * $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray(
     *         array(
     *             'font'    => array(
     *                 'name'      => 'Arial',
     *                 'bold'      => true,
     *                 'italic'    => false,
     *                 'underline' => PHPExcel_Style_Font::UNDERLINE_DOUBLE,
     *                 'strike'    => false,
     *                 'color'     => array(
     *                     'rgb' => '808080'
     *                 )
     *             ),
     *             'borders' => array(
     *                 'bottom'     => array(
     *                     'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
     *                     'color' => array(
     *                         'rgb' => '808080'
     *                     )
     *                 ),
     *                 'top'     => array(
     *                     'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
     *                     'color' => array(
     *                         'rgb' => '808080'
     *                     )
     *                 )
     *             ),
     *             'quotePrefix'    => true
     *         )
     * );
     * </code>
     *
     * @param    array    $pStyles    Array containing style information
     * @param     boolean        $pAdvanced    Advanced mode for setting borders.
     * @throws    PHPExcel_Exception
     * @return PHPExcel_Style
     */
    public function applyFromArray($pStyles = NULL, $pAdvanced = true)
    {
        if (is_array($pStyles)) {
            if ($this->isSupervisor) {
                $pRange = $this->getSelectedCells();
                $pRange = strtoupper($pRange);
                if (strpos($pRange, ":") === false) {
                    $rangeA = $pRange;
                    $rangeB = $pRange;
                } else {
                    list($rangeA, $rangeB) = explode(":", $pRange);
                }
                $rangeStart = PHPExcel_Cell::coordinateFromString($rangeA);
                $rangeEnd = PHPExcel_Cell::coordinateFromString($rangeB);
                $rangeStart[0] = PHPExcel_Cell::columnIndexFromString($rangeStart[0]) - 1;
                $rangeEnd[0] = PHPExcel_Cell::columnIndexFromString($rangeEnd[0]) - 1;
                if ($rangeEnd[0] < $rangeStart[0] && $rangeEnd[1] < $rangeStart[1]) {
                    $tmp = $rangeStart;
                    $rangeStart = $rangeEnd;
                    $rangeEnd = $tmp;
                }
                if ($pAdvanced && isset($pStyles["borders"])) {
                    if (isset($pStyles["borders"]["allborders"])) {
                        foreach (array("outline", "inside") as $component) {
                            if (!isset($pStyles["borders"][$component])) {
                                $pStyles["borders"][$component] = $pStyles["borders"]["allborders"];
                            }
                        }
                        unset($pStyles["borders"]["allborders"]);
                    }
                    if (isset($pStyles["borders"]["outline"])) {
                        foreach (array("top", "right", "bottom", "left") as $component) {
                            if (!isset($pStyles["borders"][$component])) {
                                $pStyles["borders"][$component] = $pStyles["borders"]["outline"];
                            }
                        }
                        unset($pStyles["borders"]["outline"]);
                    }
                    if (isset($pStyles["borders"]["inside"])) {
                        foreach (array("vertical", "horizontal") as $component) {
                            if (!isset($pStyles["borders"][$component])) {
                                $pStyles["borders"][$component] = $pStyles["borders"]["inside"];
                            }
                        }
                        unset($pStyles["borders"]["inside"]);
                    }
                    $xMax = min($rangeEnd[0] - $rangeStart[0] + 1, 3);
                    $yMax = min($rangeEnd[1] - $rangeStart[1] + 1, 3);
                    for ($x = 1; $x <= $xMax; $x++) {
                        $colStart = $x == 3 ? PHPExcel_Cell::stringFromColumnIndex($rangeEnd[0]) : PHPExcel_Cell::stringFromColumnIndex($rangeStart[0] + $x - 1);
                        $colEnd = $x == 1 ? PHPExcel_Cell::stringFromColumnIndex($rangeStart[0]) : PHPExcel_Cell::stringFromColumnIndex($rangeEnd[0] - $xMax + $x);
                        for ($y = 1; $y <= $yMax; $y++) {
                            $edges = array();
                            if ($x == 1) {
                                $edges[] = "left";
                            }
                            if ($x == $xMax) {
                                $edges[] = "right";
                            }
                            if ($y == 1) {
                                $edges[] = "top";
                            }
                            if ($y == $yMax) {
                                $edges[] = "bottom";
                            }
                            $rowStart = $y == 3 ? $rangeEnd[1] : $rangeStart[1] + $y - 1;
                            $rowEnd = $y == 1 ? $rangeStart[1] : $rangeEnd[1] - $yMax + $y;
                            $range = $colStart . $rowStart . ":" . $colEnd . $rowEnd;
                            $regionStyles = $pStyles;
                            unset($regionStyles["borders"]["inside"]);
                            $innerEdges = array_diff(array("top", "right", "bottom", "left"), $edges);
                            foreach ($innerEdges as $innerEdge) {
                                switch ($innerEdge) {
                                    case "top":
                                    case "bottom":
                                        if (isset($pStyles["borders"]["horizontal"])) {
                                            $regionStyles["borders"][$innerEdge] = $pStyles["borders"]["horizontal"];
                                        } else {
                                            unset($regionStyles["borders"][$innerEdge]);
                                        }
                                        break;
                                    case "left":
                                    case "right":
                                        if (isset($pStyles["borders"]["vertical"])) {
                                            $regionStyles["borders"][$innerEdge] = $pStyles["borders"]["vertical"];
                                        } else {
                                            unset($regionStyles["borders"][$innerEdge]);
                                        }
                                        break;
                                }
                            }
                            $this->getActiveSheet()->getStyle($range)->applyFromArray($regionStyles, false);
                        }
                    }
                    return $this;
                } else {
                    if (preg_match("/^[A-Z]+1:[A-Z]+1048576\$/", $pRange)) {
                        $selectionType = "COLUMN";
                    } else {
                        if (preg_match("/^A[0-9]+:XFD[0-9]+\$/", $pRange)) {
                            $selectionType = "ROW";
                        } else {
                            $selectionType = "CELL";
                        }
                    }
                    switch ($selectionType) {
                        case "COLUMN":
                            $oldXfIndexes = array();
                            for ($col = $rangeStart[0]; $col <= $rangeEnd[0]; $col++) {
                                $oldXfIndexes[$this->getActiveSheet()->getColumnDimensionByColumn($col)->getXfIndex()] = true;
                            }
                            break;
                        case "ROW":
                            $oldXfIndexes = array();
                            for ($row = $rangeStart[1]; $row <= $rangeEnd[1]; $row++) {
                                if ($this->getActiveSheet()->getRowDimension($row)->getXfIndex() == NULL) {
                                    $oldXfIndexes[0] = true;
                                } else {
                                    $oldXfIndexes[$this->getActiveSheet()->getRowDimension($row)->getXfIndex()] = true;
                                }
                            }
                            break;
                        case "CELL":
                            $oldXfIndexes = array();
                            for ($col = $rangeStart[0]; $col <= $rangeEnd[0]; $col++) {
                                for ($row = $rangeStart[1]; $row <= $rangeEnd[1]; $row++) {
                                    $oldXfIndexes[$this->getActiveSheet()->getCellByColumnAndRow($col, $row)->getXfIndex()] = true;
                                }
                            }
                            break;
                    }
                    $workbook = $this->getActiveSheet()->getParent();
                    foreach ($oldXfIndexes as $oldXfIndex => $dummy) {
                        $style = $workbook->getCellXfByIndex($oldXfIndex);
                        $newStyle = clone $style;
                        $newStyle->applyFromArray($pStyles);
                        if ($existingStyle = $workbook->getCellXfByHashCode($newStyle->getHashCode())) {
                            $newXfIndexes[$oldXfIndex] = $existingStyle->getIndex();
                        } else {
                            $workbook->addCellXf($newStyle);
                            $newXfIndexes[$oldXfIndex] = $newStyle->getIndex();
                        }
                    }
                    switch ($selectionType) {
                        case "COLUMN":
                            for ($col = $rangeStart[0]; $col <= $rangeEnd[0]; $col++) {
                                $columnDimension = $this->getActiveSheet()->getColumnDimensionByColumn($col);
                                $oldXfIndex = $columnDimension->getXfIndex();
                                $columnDimension->setXfIndex($newXfIndexes[$oldXfIndex]);
                            }
                            break;
                        case "ROW":
                            for ($row = $rangeStart[1]; $row <= $rangeEnd[1]; $row++) {
                                $rowDimension = $this->getActiveSheet()->getRowDimension($row);
                                $oldXfIndex = $rowDimension->getXfIndex() === NULL ? 0 : $rowDimension->getXfIndex();
                                $rowDimension->setXfIndex($newXfIndexes[$oldXfIndex]);
                            }
                            break;
                        case "CELL":
                            for ($col = $rangeStart[0]; $col <= $rangeEnd[0]; $col++) {
                                for ($row = $rangeStart[1]; $row <= $rangeEnd[1]; $row++) {
                                    $cell = $this->getActiveSheet()->getCellByColumnAndRow($col, $row);
                                    $oldXfIndex = $cell->getXfIndex();
                                    $cell->setXfIndex($newXfIndexes[$oldXfIndex]);
                                }
                            }
                            break;
                    }
                }
            } else {
                if (array_key_exists("fill", $pStyles)) {
                    $this->getFill()->applyFromArray($pStyles["fill"]);
                }
                if (array_key_exists("font", $pStyles)) {
                    $this->getFont()->applyFromArray($pStyles["font"]);
                }
                if (array_key_exists("borders", $pStyles)) {
                    $this->getBorders()->applyFromArray($pStyles["borders"]);
                }
                if (array_key_exists("alignment", $pStyles)) {
                    $this->getAlignment()->applyFromArray($pStyles["alignment"]);
                }
                if (array_key_exists("numberformat", $pStyles)) {
                    $this->getNumberFormat()->applyFromArray($pStyles["numberformat"]);
                }
                if (array_key_exists("protection", $pStyles)) {
                    $this->getProtection()->applyFromArray($pStyles["protection"]);
                }
                if (array_key_exists("quotePrefix", $pStyles)) {
                    $this->quotePrefix = $pStyles["quotePrefix"];
                }
            }
            return $this;
        }
        throw new PHPExcel_Exception("Invalid style array passed.");
    }
    /**
     * Get Fill
     *
     * @return PHPExcel_Style_Fill
     */
    public function getFill()
    {
        return $this->fill;
    }
    /**
     * Get Font
     *
     * @return PHPExcel_Style_Font
     */
    public function getFont()
    {
        return $this->font;
    }
    /**
     * Set font
     *
     * @param PHPExcel_Style_Font $font
     * @return PHPExcel_Style
     */
    public function setFont(PHPExcel_Style_Font $font)
    {
        $this->font = $font;
        return $this;
    }
    /**
     * Get Borders
     *
     * @return PHPExcel_Style_Borders
     */
    public function getBorders()
    {
        return $this->borders;
    }
    /**
     * Get Alignment
     *
     * @return PHPExcel_Style_Alignment
     */
    public function getAlignment()
    {
        return $this->alignment;
    }
    /**
     * Get Number Format
     *
     * @return PHPExcel_Style_NumberFormat
     */
    public function getNumberFormat()
    {
        return $this->numberFormat;
    }
    /**
     * Get Conditional Styles. Only used on supervisor.
     *
     * @return PHPExcel_Style_Conditional[]
     */
    public function getConditionalStyles()
    {
        return $this->getActiveSheet()->getConditionalStyles($this->getActiveCell());
    }
    /**
     * Set Conditional Styles. Only used on supervisor.
     *
     * @param PHPExcel_Style_Conditional[] $pValue Array of condtional styles
     * @return PHPExcel_Style
     */
    public function setConditionalStyles($pValue = NULL)
    {
        if (is_array($pValue)) {
            $this->getActiveSheet()->setConditionalStyles($this->getSelectedCells(), $pValue);
        }
        return $this;
    }
    /**
     * Get Protection
     *
     * @return PHPExcel_Style_Protection
     */
    public function getProtection()
    {
        return $this->protection;
    }
    /**
     * Get quote prefix
     *
     * @return boolean
     */
    public function getQuotePrefix()
    {
        if ($this->isSupervisor) {
            return $this->getSharedComponent()->getQuotePrefix();
        }
        return $this->quotePrefix;
    }
    /**
     * Set quote prefix
     *
     * @param boolean $pValue
     */
    public function setQuotePrefix($pValue)
    {
        if ($pValue == "") {
            $pValue = false;
        }
        if ($this->isSupervisor) {
            $styleArray = array("quotePrefix" => $pValue);
            $this->getActiveSheet()->getStyle($this->getSelectedCells())->applyFromArray($styleArray);
        } else {
            $this->quotePrefix = (bool) $pValue;
        }
        return $this;
    }
    /**
     * Get hash code
     *
     * @return string Hash code
     */
    public function getHashCode()
    {
        $hashConditionals = "";
        foreach ($this->conditionalStyles as $conditional) {
            $hashConditionals .= $conditional->getHashCode();
        }
        return md5($this->fill->getHashCode() . $this->font->getHashCode() . $this->borders->getHashCode() . $this->alignment->getHashCode() . $this->numberFormat->getHashCode() . $hashConditionals . $this->protection->getHashCode() . ($this->quotePrefix ? "t" : "f") . "PHPExcel_Style");
    }
    /**
     * Get own index in style collection
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }
    /**
     * Set own index in style collection
     *
     * @param int $pValue
     */
    public function setIndex($pValue)
    {
        $this->index = $pValue;
    }
}

?>