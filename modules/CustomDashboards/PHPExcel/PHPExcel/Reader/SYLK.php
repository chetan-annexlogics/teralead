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
 * PHPExcel_Reader_SYLK
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
 * @package    PHPExcel_Reader
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Reader_SYLK extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
{
    /**
     * Input encoding
     *
     * @var string
     */
    private $inputEncoding = "ANSI";
    /**
     * Sheet index to read
     *
     * @var int
     */
    private $sheetIndex = 0;
    /**
     * Formats
     *
     * @var array
     */
    private $formats = array();
    /**
     * Format Count
     *
     * @var int
     */
    private $format = 0;
    /**
     * Create a new PHPExcel_Reader_SYLK
     */
    public function __construct()
    {
        $this->readFilter = new PHPExcel_Reader_DefaultReadFilter();
    }
    /**
     * Validate that the current file is a SYLK file
     *
     * @return boolean
     */
    protected function isValidFormat()
    {
        $data = fread($this->fileHandle, 2048);
        $delimiterCount = substr_count($data, ";");
        if ($delimiterCount < 1) {
            return false;
        }
        $lines = explode("\n", $data);
        if (substr($lines[0], 0, 4) != "ID;P") {
            return false;
        }
        return true;
    }
    /**
     * Set input encoding
     *
     * @param string $pValue Input encoding
     */
    public function setInputEncoding($pValue = "ANSI")
    {
        $this->inputEncoding = $pValue;
        return $this;
    }
    /**
     * Get input encoding
     *
     * @return string
     */
    public function getInputEncoding()
    {
        return $this->inputEncoding;
    }
    /**
     * Return worksheet info (Name, Last Column Letter, Last Column Index, Total Rows, Total Columns)
     *
     * @param   string     $pFilename
     * @throws   PHPExcel_Reader_Exception
     */
    public function listWorksheetInfo($pFilename)
    {
        $this->openFile($pFilename);
        if (!$this->isValidFormat()) {
            fclose($this->fileHandle);
            throw new PHPExcel_Reader_Exception($pFilename . " is an Invalid Spreadsheet file.");
        }
        $fileHandle = $this->fileHandle;
        rewind($fileHandle);
        $worksheetInfo = array();
        $worksheetInfo[0]["worksheetName"] = "Worksheet";
        $worksheetInfo[0]["lastColumnLetter"] = "A";
        $worksheetInfo[0]["lastColumnIndex"] = 0;
        $worksheetInfo[0]["totalRows"] = 0;
        $worksheetInfo[0]["totalColumns"] = 0;
        $rowData = array();
        $rowIndex = 0;
        while (($rowData = fgets($fileHandle)) !== false) {
            $columnIndex = 0;
            $rowData = PHPExcel_Shared_String::SYLKtoUTF8($rowData);
            $rowData = explode("\t", str_replace("¤", ";", str_replace(";", "\t", str_replace(";;", "¤", rtrim($rowData)))));
            $dataType = array_shift($rowData);
            if ($dataType == "C") {
                foreach ($rowData as $rowDatum) {
                    switch ($rowDatum[0]) {
                        case "C":
                        case "X":
                            $columnIndex = substr($rowDatum, 1) - 1;
                            break;
                        case "R":
                        case "Y":
                            $rowIndex = substr($rowDatum, 1);
                            break;
                    }
                    $worksheetInfo[0]["totalRows"] = max($worksheetInfo[0]["totalRows"], $rowIndex);
                    $worksheetInfo[0]["lastColumnIndex"] = max($worksheetInfo[0]["lastColumnIndex"], $columnIndex);
                }
            }
        }
        $worksheetInfo[0]["lastColumnLetter"] = PHPExcel_Cell::stringFromColumnIndex($worksheetInfo[0]["lastColumnIndex"]);
        $worksheetInfo[0]["totalColumns"] = $worksheetInfo[0]["lastColumnIndex"] + 1;
        fclose($fileHandle);
        return $worksheetInfo;
    }
    /**
     * Loads PHPExcel from file
     *
     * @param     string         $pFilename
     * @return     PHPExcel
     * @throws     PHPExcel_Reader_Exception
     */
    public function load($pFilename)
    {
        $objPHPExcel = new PHPExcel();
        return $this->loadIntoExisting($pFilename, $objPHPExcel);
    }
    /**
     * Loads PHPExcel from file into PHPExcel instance
     *
     * @param     string         $pFilename
     * @param    PHPExcel    $objPHPExcel
     * @return     PHPExcel
     * @throws     PHPExcel_Reader_Exception
     */
    public function loadIntoExisting($pFilename, PHPExcel $objPHPExcel)
    {
        $this->openFile($pFilename);
        if (!$this->isValidFormat()) {
            fclose($this->fileHandle);
            throw new PHPExcel_Reader_Exception($pFilename . " is an Invalid Spreadsheet file.");
        }
        $fileHandle = $this->fileHandle;
        rewind($fileHandle);
        while ($objPHPExcel->getSheetCount() <= $this->sheetIndex) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($this->sheetIndex);
        $fromFormats = array("\\-", "\\ ");
        $toFormats = array("-", " ");
        $rowData = array();
        $column = $row = "";
        while (($rowData = fgets($fileHandle)) !== false) {
            $rowData = PHPExcel_Shared_String::SYLKtoUTF8($rowData);
            $rowData = explode("\t", str_replace("¤", ";", str_replace(";", "\t", str_replace(";;", "¤", rtrim($rowData)))));
            $dataType = array_shift($rowData);
            if ($dataType == "P") {
                $formatArray = array();
                foreach ($rowData as $rowDatum) {
                    switch ($rowDatum[0]) {
                        case "P":
                            $formatArray["numberformat"]["code"] = str_replace($fromFormats, $toFormats, substr($rowDatum, 1));
                            break;
                        case "E":
                        case "F":
                            $formatArray["font"]["name"] = substr($rowDatum, 1);
                            break;
                        case "L":
                            $formatArray["font"]["size"] = substr($rowDatum, 1);
                            break;
                        case "S":
                            $styleSettings = substr($rowDatum, 1);
                            for ($i = 0; $i < strlen($styleSettings); $i++) {
                                switch ($styleSettings[$i]) {
                                    case "I":
                                        $formatArray["font"]["italic"] = true;
                                        break;
                                    case "D":
                                        $formatArray["font"]["bold"] = true;
                                        break;
                                    case "T":
                                        $formatArray["borders"]["top"]["style"] = PHPExcel_Style_Border::BORDER_THIN;
                                        break;
                                    case "B":
                                        $formatArray["borders"]["bottom"]["style"] = PHPExcel_Style_Border::BORDER_THIN;
                                        break;
                                    case "L":
                                        $formatArray["borders"]["left"]["style"] = PHPExcel_Style_Border::BORDER_THIN;
                                        break;
                                    case "R":
                                        $formatArray["borders"]["right"]["style"] = PHPExcel_Style_Border::BORDER_THIN;
                                        break;
                                }
                            }
                            break;
                    }
                }
                $this->formats["P" . $this->format++] = $formatArray;
            } else {
                if ($dataType == "C") {
                    $hasCalculatedValue = false;
                    $cellData = $cellDataFormula = "";
                    foreach ($rowData as $rowDatum) {
                        switch ($rowDatum[0]) {
                            case "C":
                            case "X":
                                $column = substr($rowDatum, 1);
                                break;
                            case "R":
                            case "Y":
                                $row = substr($rowDatum, 1);
                                break;
                            case "K":
                                $cellData = substr($rowDatum, 1);
                                break;
                            case "E":
                                $cellDataFormula = "=" . substr($rowDatum, 1);
                                $temp = explode("\"", $cellDataFormula);
                                $key = false;
                                foreach ($temp as &$value) {
                                    if ($key = !$key) {
                                        preg_match_all("/(R(\\[?-?\\d*\\]?))(C(\\[?-?\\d*\\]?))/", $value, $cellReferences, PREG_SET_ORDER + PREG_OFFSET_CAPTURE);
                                        $cellReferences = array_reverse($cellReferences);
                                        foreach ($cellReferences as $cellReference) {
                                            $rowReference = $cellReference[2][0];
                                            if ($rowReference == "") {
                                                $rowReference = $row;
                                            }
                                            if ($rowReference[0] == "[") {
                                                $rowReference = $row + trim($rowReference, "[]");
                                            }
                                            $columnReference = $cellReference[4][0];
                                            if ($columnReference == "") {
                                                $columnReference = $column;
                                            }
                                            if ($columnReference[0] == "[") {
                                                $columnReference = $column + trim($columnReference, "[]");
                                            }
                                            $A1CellReference = PHPExcel_Cell::stringFromColumnIndex($columnReference - 1) . $rowReference;
                                            $value = substr_replace($value, $A1CellReference, $cellReference[0][1], strlen($cellReference[0][0]));
                                        }
                                    }
                                }
                                unset($value);
                                $cellDataFormula = implode("\"", $temp);
                                $hasCalculatedValue = true;
                                break;
                        }
                    }
                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($column - 1);
                    $cellData = PHPExcel_Calculation::unwrapResult($cellData);
                    $objPHPExcel->getActiveSheet()->getCell($columnLetter . $row)->setValue($hasCalculatedValue ? $cellDataFormula : $cellData);
                    if ($hasCalculatedValue) {
                        $cellData = PHPExcel_Calculation::unwrapResult($cellData);
                        $objPHPExcel->getActiveSheet()->getCell($columnLetter . $row)->setCalculatedValue($cellData);
                    }
                } else {
                    if ($dataType == "F") {
                        $formatStyle = $columnWidth = $styleSettings = "";
                        $styleData = array();
                        foreach ($rowData as $rowDatum) {
                            switch ($rowDatum[0]) {
                                case "C":
                                case "X":
                                    $column = substr($rowDatum, 1);
                                    break;
                                case "R":
                                case "Y":
                                    $row = substr($rowDatum, 1);
                                    break;
                                case "P":
                                    $formatStyle = $rowDatum;
                                    break;
                                case "W":
                                    list($startCol, $endCol, $columnWidth) = explode(" ", substr($rowDatum, 1));
                                    break;
                                case "S":
                                    $styleSettings = substr($rowDatum, 1);
                                    for ($i = 0; $i < strlen($styleSettings); $i++) {
                                        switch ($styleSettings[$i]) {
                                            case "I":
                                                $styleData["font"]["italic"] = true;
                                                break;
                                            case "D":
                                                $styleData["font"]["bold"] = true;
                                                break;
                                            case "T":
                                                $styleData["borders"]["top"]["style"] = PHPExcel_Style_Border::BORDER_THIN;
                                                break;
                                            case "B":
                                                $styleData["borders"]["bottom"]["style"] = PHPExcel_Style_Border::BORDER_THIN;
                                                break;
                                            case "L":
                                                $styleData["borders"]["left"]["style"] = PHPExcel_Style_Border::BORDER_THIN;
                                                break;
                                            case "R":
                                                $styleData["borders"]["right"]["style"] = PHPExcel_Style_Border::BORDER_THIN;
                                                break;
                                        }
                                    }
                                    break;
                            }
                        }
                        if ("" < $formatStyle && "" < $column && "" < $row) {
                            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($column - 1);
                            if (isset($this->formats[$formatStyle])) {
                                $objPHPExcel->getActiveSheet()->getStyle($columnLetter . $row)->applyFromArray($this->formats[$formatStyle]);
                            }
                        }
                        if (!empty($styleData) && "" < $column && "" < $row) {
                            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($column - 1);
                            $objPHPExcel->getActiveSheet()->getStyle($columnLetter . $row)->applyFromArray($styleData);
                        }
                        if ("" < $columnWidth) {
                            if ($startCol == $endCol) {
                                $startCol = PHPExcel_Cell::stringFromColumnIndex($startCol - 1);
                                $objPHPExcel->getActiveSheet()->getColumnDimension($startCol)->setWidth($columnWidth);
                            } else {
                                $startCol = PHPExcel_Cell::stringFromColumnIndex($startCol - 1);
                                $endCol = PHPExcel_Cell::stringFromColumnIndex($endCol - 1);
                                $objPHPExcel->getActiveSheet()->getColumnDimension($startCol)->setWidth($columnWidth);
                                do {
                                    $objPHPExcel->getActiveSheet()->getColumnDimension(++$startCol)->setWidth($columnWidth);
                                } while ($startCol != $endCol);
                            }
                        }
                    } else {
                        foreach ($rowData as $rowDatum) {
                            switch ($rowDatum[0]) {
                                case "C":
                                case "X":
                                    $column = substr($rowDatum, 1);
                                    break;
                                case "R":
                                case "Y":
                                    $row = substr($rowDatum, 1);
                                    break;
                            }
                        }
                    }
                }
            }
        }
        fclose($fileHandle);
        return $objPHPExcel;
    }
    /**
     * Get sheet index
     *
     * @return int
     */
    public function getSheetIndex()
    {
        return $this->sheetIndex;
    }
    /**
     * Set sheet index
     *
     * @param    int        $pValue        Sheet index
     * @return PHPExcel_Reader_SYLK
     */
    public function setSheetIndex($pValue = 0)
    {
        $this->sheetIndex = $pValue;
        return $this;
    }
}

?>