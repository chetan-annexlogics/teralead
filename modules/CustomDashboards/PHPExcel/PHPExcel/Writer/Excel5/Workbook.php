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
 * PHPExcel_Writer_Excel5_Workbook
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
class PHPExcel_Writer_Excel5_Workbook extends PHPExcel_Writer_Excel5_BIFFwriter
{
    /**
     * Formula parser
     *
     * @var PHPExcel_Writer_Excel5_Parser
     */
    private $parser = NULL;
    /**
     * The BIFF file size for the workbook.
     * @var integer
     * @see calcSheetOffsets()
     */
    private $biffSize = NULL;
    /**
     * XF Writers
     * @var PHPExcel_Writer_Excel5_Xf[]
     */
    private $xfWriters = array();
    /**
     * Array containing the colour palette
     * @var array
     */
    private $palette = NULL;
    /**
     * The codepage indicates the text encoding used for strings
     * @var integer
     */
    private $codepage = NULL;
    /**
     * The country code used for localization
     * @var integer
     */
    private $countryCode = NULL;
    /**
     * Workbook
     * @var PHPExcel
     */
    private $phpExcel = NULL;
    /**
     * Fonts writers
     *
     * @var PHPExcel_Writer_Excel5_Font[]
     */
    private $fontWriters = array();
    /**
     * Added fonts. Maps from font's hash => index in workbook
     *
     * @var array
     */
    private $addedFonts = array();
    /**
     * Shared number formats
     *
     * @var array
     */
    private $numberFormats = array();
    /**
     * Added number formats. Maps from numberFormat's hash => index in workbook
     *
     * @var array
     */
    private $addedNumberFormats = array();
    /**
     * Sizes of the binary worksheet streams
     *
     * @var array
     */
    private $worksheetSizes = array();
    /**
     * Offsets of the binary worksheet streams relative to the start of the global workbook stream
     *
     * @var array
     */
    private $worksheetOffsets = array();
    /**
     * Total number of shared strings in workbook
     *
     * @var int
     */
    private $stringTotal = NULL;
    /**
     * Number of unique shared strings in workbook
     *
     * @var int
     */
    private $stringUnique = NULL;
    /**
     * Array of unique shared strings in workbook
     *
     * @var array
     */
    private $stringTable = NULL;
    /**
     * Color cache
     */
    private $colors = NULL;
    /**
     * Escher object corresponding to MSODRAWINGGROUP
     *
     * @var PHPExcel_Shared_Escher
     */
    private $escher = NULL;
    /**
     * Class constructor
     *
     * @param PHPExcel    $phpExcel        The Workbook
     * @param int        &$str_total        Total number of strings
     * @param int        &$str_unique    Total number of unique strings
     * @param array        &$str_table        String Table
     * @param array        &$colors        Colour Table
     * @param mixed        $parser            The formula parser created for the Workbook
     */
    public function __construct(PHPExcel $phpExcel = NULL, &$str_total, &$str_unique, &$str_table, &$colors, $parser)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->biffSize = 0;
        $this->palette = array();
        $this->countryCode = -1;
        $this->stringTotal =& $str_total;
        $this->stringUnique =& $str_unique;
        $this->stringTable =& $str_table;
        $this->colors =& $colors;
        $this->setPaletteXl97();
        $this->phpExcel = $phpExcel;
        $this->codepage = 1200;
        $countSheets = $phpExcel->getSheetCount();
        for ($i = 0; $i < $countSheets; $i++) {
            $phpSheet = $phpExcel->getSheet($i);
            $this->parser->setExtSheet($phpSheet->getTitle(), $i);
            $supbook_index = 0;
            $ref = pack("vvv", $supbook_index, $i, $i);
            $this->parser->references[] = $ref;
            if ($phpSheet->isTabColorSet()) {
                $this->addColor($phpSheet->getTabColor()->getRGB());
            }
        }
    }
    /**
     * Add a new XF writer
     *
     * @param PHPExcel_Style
     * @param boolean Is it a style XF?
     * @return int Index to XF record
     */
    public function addXfWriter($style, $isStyleXf = false)
    {
        $xfWriter = new PHPExcel_Writer_Excel5_Xf($style);
        $xfWriter->setIsStyleXf($isStyleXf);
        $fontIndex = $this->addFont($style->getFont());
        $xfWriter->setFontIndex($fontIndex);
        $xfWriter->setFgColor($this->addColor($style->getFill()->getStartColor()->getRGB()));
        $xfWriter->setBgColor($this->addColor($style->getFill()->getEndColor()->getRGB()));
        $xfWriter->setBottomColor($this->addColor($style->getBorders()->getBottom()->getColor()->getRGB()));
        $xfWriter->setTopColor($this->addColor($style->getBorders()->getTop()->getColor()->getRGB()));
        $xfWriter->setRightColor($this->addColor($style->getBorders()->getRight()->getColor()->getRGB()));
        $xfWriter->setLeftColor($this->addColor($style->getBorders()->getLeft()->getColor()->getRGB()));
        $xfWriter->setDiagColor($this->addColor($style->getBorders()->getDiagonal()->getColor()->getRGB()));
        if ($style->getNumberFormat()->getBuiltInFormatCode() === false) {
            $numberFormatHashCode = $style->getNumberFormat()->getHashCode();
            if (isset($this->addedNumberFormats[$numberFormatHashCode])) {
                $numberFormatIndex = $this->addedNumberFormats[$numberFormatHashCode];
            } else {
                $numberFormatIndex = 164 + count($this->numberFormats);
                $this->numberFormats[$numberFormatIndex] = $style->getNumberFormat();
                $this->addedNumberFormats[$numberFormatHashCode] = $numberFormatIndex;
            }
        } else {
            $numberFormatIndex = (int) $style->getNumberFormat()->getBuiltInFormatCode();
        }
        $xfWriter->setNumberFormatIndex($numberFormatIndex);
        $this->xfWriters[] = $xfWriter;
        $xfIndex = count($this->xfWriters) - 1;
        return $xfIndex;
    }
    /**
     * Add a font to added fonts
     *
     * @param PHPExcel_Style_Font $font
     * @return int Index to FONT record
     */
    public function addFont(PHPExcel_Style_Font $font)
    {
        $fontHashCode = $font->getHashCode();
        if (isset($this->addedFonts[$fontHashCode])) {
            $fontIndex = $this->addedFonts[$fontHashCode];
        } else {
            $countFonts = count($this->fontWriters);
            $fontIndex = $countFonts < 4 ? $countFonts : $countFonts + 1;
            $fontWriter = new PHPExcel_Writer_Excel5_Font($font);
            $fontWriter->setColorIndex($this->addColor($font->getColor()->getRGB()));
            $this->fontWriters[] = $fontWriter;
            $this->addedFonts[$fontHashCode] = $fontIndex;
        }
        return $fontIndex;
    }
    /**
     * Alter color palette adding a custom color
     *
     * @param string $rgb E.g. 'FF00AA'
     * @return int Color index
     */
    private function addColor($rgb)
    {
        if (!isset($this->colors[$rgb])) {
            if (count($this->colors) < 57) {
                $colorIndex = 8 + count($this->colors);
                $this->palette[$colorIndex] = array(hexdec(substr($rgb, 0, 2)), hexdec(substr($rgb, 2, 2)), hexdec(substr($rgb, 4)), 0);
                $this->colors[$rgb] = $colorIndex;
            } else {
                $colorIndex = 0;
            }
        } else {
            $colorIndex = $this->colors[$rgb];
        }
        return $colorIndex;
    }
    /**
     * Sets the colour palette to the Excel 97+ default.
     *
     * @access private
     */
    private function setPaletteXl97()
    {
        $this->palette = array(8 => array(0, 0, 0, 0), 9 => array(255, 255, 255, 0), 10 => array(255, 0, 0, 0), 11 => array(0, 255, 0, 0), 12 => array(0, 0, 255, 0), 13 => array(255, 255, 0, 0), 14 => array(255, 0, 255, 0), 15 => array(0, 255, 255, 0), 16 => array(128, 0, 0, 0), 17 => array(0, 128, 0, 0), 18 => array(0, 0, 128, 0), 19 => array(128, 128, 0, 0), 20 => array(128, 0, 128, 0), 21 => array(0, 128, 128, 0), 22 => array(192, 192, 192, 0), 23 => array(128, 128, 128, 0), 24 => array(153, 153, 255, 0), 25 => array(153, 51, 102, 0), 26 => array(255, 255, 204, 0), 27 => array(204, 255, 255, 0), 28 => array(102, 0, 102, 0), 29 => array(255, 128, 128, 0), 30 => array(0, 102, 204, 0), 31 => array(204, 204, 255, 0), 32 => array(0, 0, 128, 0), 33 => array(255, 0, 255, 0), 34 => array(255, 255, 0, 0), 35 => array(0, 255, 255, 0), 36 => array(128, 0, 128, 0), 37 => array(128, 0, 0, 0), 38 => array(0, 128, 128, 0), 39 => array(0, 0, 255, 0), 40 => array(0, 204, 255, 0), 41 => array(204, 255, 255, 0), 42 => array(204, 255, 204, 0), 43 => array(255, 255, 153, 0), 44 => array(153, 204, 255, 0), 45 => array(255, 153, 204, 0), 46 => array(204, 153, 255, 0), 47 => array(255, 204, 153, 0), 48 => array(51, 102, 255, 0), 49 => array(51, 204, 204, 0), 50 => array(153, 204, 0, 0), 51 => array(255, 204, 0, 0), 52 => array(255, 153, 0, 0), 53 => array(255, 102, 0, 0), 54 => array(102, 102, 153, 0), 55 => array(150, 150, 150, 0), 56 => array(0, 51, 102, 0), 57 => array(51, 153, 102, 0), 58 => array(0, 51, 0, 0), 59 => array(51, 51, 0, 0), 60 => array(153, 51, 0, 0), 61 => array(153, 51, 102, 0), 62 => array(51, 51, 153, 0), 63 => array(51, 51, 51, 0));
    }
    /**
     * Assemble worksheets into a workbook and send the BIFF data to an OLE
     * storage.
     *
     * @param    array    $pWorksheetSizes    The sizes in bytes of the binary worksheet streams
     * @return    string    Binary data for workbook stream
     */
    public function writeWorkbook($pWorksheetSizes = NULL)
    {
        $this->worksheetSizes = $pWorksheetSizes;
        $total_worksheets = $this->phpExcel->getSheetCount();
        $this->storeBof(5);
        $this->writeCodepage();
        $this->writeWindow1();
        $this->writeDateMode();
        $this->writeAllFonts();
        $this->writeAllNumberFormats();
        $this->writeAllXfs();
        $this->writeAllStyles();
        $this->writePalette();
        $part3 = "";
        if ($this->countryCode != -1) {
            $part3 .= $this->writeCountry();
        }
        $part3 .= $this->writeRecalcId();
        $part3 .= $this->writeSupbookInternal();
        $part3 .= $this->writeExternalsheetBiff8();
        $part3 .= $this->writeAllDefinedNamesBiff8();
        $part3 .= $this->writeMsoDrawingGroup();
        $part3 .= $this->writeSharedStringsTable();
        $part3 .= $this->writeEof();
        $this->calcSheetOffsets();
        for ($i = 0; $i < $total_worksheets; $i++) {
            $this->writeBoundSheet($this->phpExcel->getSheet($i), $this->worksheetOffsets[$i]);
        }
        $this->_data .= $part3;
        return $this->_data;
    }
    /**
     * Calculate offsets for Worksheet BOF records.
     *
     * @access private
     */
    private function calcSheetOffsets()
    {
        $boundsheet_length = 10;
        $offset = $this->_datasize;
        $total_worksheets = count($this->phpExcel->getAllSheets());
        foreach ($this->phpExcel->getWorksheetIterator() as $sheet) {
            $offset += $boundsheet_length + strlen(PHPExcel_Shared_String::UTF8toBIFF8UnicodeShort($sheet->getTitle()));
        }
        for ($i = 0; $i < $total_worksheets; $i++) {
            $this->worksheetOffsets[$i] = $offset;
            $offset += $this->worksheetSizes[$i];
        }
        $this->biffSize = $offset;
    }
    /**
     * Store the Excel FONT records.
     */
    private function writeAllFonts()
    {
        foreach ($this->fontWriters as $fontWriter) {
            $this->append($fontWriter->writeFont());
        }
    }
    /**
     * Store user defined numerical formats i.e. FORMAT records
     */
    private function writeAllNumberFormats()
    {
        foreach ($this->numberFormats as $numberFormatIndex => $numberFormat) {
            $this->writeNumberFormat($numberFormat->getFormatCode(), $numberFormatIndex);
        }
    }
    /**
     * Write all XF records.
     */
    private function writeAllXfs()
    {
        foreach ($this->xfWriters as $xfWriter) {
            $this->append($xfWriter->writeXf());
        }
    }
    /**
     * Write all STYLE records.
     */
    private function writeAllStyles()
    {
        $this->writeStyle();
    }
    /**
     * Write the EXTERNCOUNT and EXTERNSHEET records. These are used as indexes for
     * the NAME records.
     */
    private function writeExternals()
    {
        $countSheets = $this->phpExcel->getSheetCount();
        $this->writeExternalCount($countSheets);
        for ($i = 0; $i < $countSheets; $i++) {
            $this->writeExternalSheet($this->phpExcel->getSheet($i)->getTitle());
        }
    }
    /**
     * Write the NAME record to define the print area and the repeat rows and cols.
     */
    private function writeNames()
    {
        $total_worksheets = $this->phpExcel->getSheetCount();
        for ($i = 0; $i < $total_worksheets; $i++) {
            $sheetSetup = $this->phpExcel->getSheet($i)->getPageSetup();
            if ($sheetSetup->isPrintAreaSet()) {
                $printArea = PHPExcel_Cell::splitRange($sheetSetup->getPrintArea());
                $printArea = $printArea[0];
                $printArea[0] = PHPExcel_Cell::coordinateFromString($printArea[0]);
                $printArea[1] = PHPExcel_Cell::coordinateFromString($printArea[1]);
                $print_rowmin = $printArea[0][1] - 1;
                $print_rowmax = $printArea[1][1] - 1;
                $print_colmin = PHPExcel_Cell::columnIndexFromString($printArea[0][0]) - 1;
                $print_colmax = PHPExcel_Cell::columnIndexFromString($printArea[1][0]) - 1;
                $this->writeNameShort($i, 6, $print_rowmin, $print_rowmax, $print_colmin, $print_colmax);
            }
        }
        for ($i = 0; $i < $total_worksheets; $i++) {
            $sheetSetup = $this->phpExcel->getSheet($i)->getPageSetup();
            if ($sheetSetup->isColumnsToRepeatAtLeftSet() && $sheetSetup->isRowsToRepeatAtTopSet()) {
                $repeat = $sheetSetup->getColumnsToRepeatAtLeft();
                $colmin = PHPExcel_Cell::columnIndexFromString($repeat[0]) - 1;
                $colmax = PHPExcel_Cell::columnIndexFromString($repeat[1]) - 1;
                $repeat = $sheetSetup->getRowsToRepeatAtTop();
                $rowmin = $repeat[0] - 1;
                $rowmax = $repeat[1] - 1;
                $this->writeNameLong($i, 7, $rowmin, $rowmax, $colmin, $colmax);
            } else {
                if ($sheetSetup->isColumnsToRepeatAtLeftSet() || $sheetSetup->isRowsToRepeatAtTopSet()) {
                    if ($sheetSetup->isColumnsToRepeatAtLeftSet()) {
                        $repeat = $sheetSetup->getColumnsToRepeatAtLeft();
                        $colmin = PHPExcel_Cell::columnIndexFromString($repeat[0]) - 1;
                        $colmax = PHPExcel_Cell::columnIndexFromString($repeat[1]) - 1;
                    } else {
                        $colmin = 0;
                        $colmax = 255;
                    }
                    if ($sheetSetup->isRowsToRepeatAtTopSet()) {
                        $repeat = $sheetSetup->getRowsToRepeatAtTop();
                        $rowmin = $repeat[0] - 1;
                        $rowmax = $repeat[1] - 1;
                    } else {
                        $rowmin = 0;
                        $rowmax = 65535;
                    }
                    $this->writeNameShort($i, 7, $rowmin, $rowmax, $colmin, $colmax);
                }
            }
        }
    }
    /**
     * Writes all the DEFINEDNAME records (BIFF8).
     * So far this is only used for repeating rows/columns (print titles) and print areas
     */
    private function writeAllDefinedNamesBiff8()
    {
        $chunk = "";
        if (0 < count($this->phpExcel->getNamedRanges())) {
            $namedRanges = $this->phpExcel->getNamedRanges();
            foreach ($namedRanges as $namedRange) {
                $range = PHPExcel_Cell::splitRange($namedRange->getRange());
                for ($i = 0; $i < count($range); $i++) {
                    $range[$i][0] = "'" . str_replace("'", "''", $namedRange->getWorksheet()->getTitle()) . "'!" . PHPExcel_Cell::absoluteCoordinate($range[$i][0]);
                    if (isset($range[$i][1])) {
                        $range[$i][1] = PHPExcel_Cell::absoluteCoordinate($range[$i][1]);
                    }
                }
                $range = PHPExcel_Cell::buildRange($range);
                try {
                    $error = $this->parser->parse($range);
                    $formulaData = $this->parser->toReversePolish();
                    if (isset($formulaData[0]) && ($formulaData[0] == "z" || $formulaData[0] == "Z")) {
                        $formulaData = ":" . substr($formulaData, 1);
                    }
                    if ($namedRange->getLocalOnly()) {
                        $scope = $this->phpExcel->getIndex($namedRange->getScope()) + 1;
                    } else {
                        $scope = 0;
                    }
                    $chunk .= $this->writeData($this->writeDefinedNameBiff8($namedRange->getName(), $formulaData, $scope, false));
                } catch (PHPExcel_Exception $e) {
                }
            }
        }
        $total_worksheets = $this->phpExcel->getSheetCount();
        for ($i = 0; $i < $total_worksheets; $i++) {
            $sheetSetup = $this->phpExcel->getSheet($i)->getPageSetup();
            if ($sheetSetup->isColumnsToRepeatAtLeftSet() && $sheetSetup->isRowsToRepeatAtTopSet()) {
                $repeat = $sheetSetup->getColumnsToRepeatAtLeft();
                $colmin = PHPExcel_Cell::columnIndexFromString($repeat[0]) - 1;
                $colmax = PHPExcel_Cell::columnIndexFromString($repeat[1]) - 1;
                $repeat = $sheetSetup->getRowsToRepeatAtTop();
                $rowmin = $repeat[0] - 1;
                $rowmax = $repeat[1] - 1;
                $formulaData = pack("Cv", 41, 23);
                $formulaData .= pack("Cvvvvv", 59, $i, 0, 65535, $colmin, $colmax);
                $formulaData .= pack("Cvvvvv", 59, $i, $rowmin, $rowmax, 0, 255);
                $formulaData .= pack("C", 16);
                $chunk .= $this->writeData($this->writeDefinedNameBiff8(pack("C", 7), $formulaData, $i + 1, true));
            } else {
                if ($sheetSetup->isColumnsToRepeatAtLeftSet() || $sheetSetup->isRowsToRepeatAtTopSet()) {
                    if ($sheetSetup->isColumnsToRepeatAtLeftSet()) {
                        $repeat = $sheetSetup->getColumnsToRepeatAtLeft();
                        $colmin = PHPExcel_Cell::columnIndexFromString($repeat[0]) - 1;
                        $colmax = PHPExcel_Cell::columnIndexFromString($repeat[1]) - 1;
                    } else {
                        $colmin = 0;
                        $colmax = 255;
                    }
                    if ($sheetSetup->isRowsToRepeatAtTopSet()) {
                        $repeat = $sheetSetup->getRowsToRepeatAtTop();
                        $rowmin = $repeat[0] - 1;
                        $rowmax = $repeat[1] - 1;
                    } else {
                        $rowmin = 0;
                        $rowmax = 65535;
                    }
                    $formulaData = pack("Cvvvvv", 59, $i, $rowmin, $rowmax, $colmin, $colmax);
                    $chunk .= $this->writeData($this->writeDefinedNameBiff8(pack("C", 7), $formulaData, $i + 1, true));
                }
            }
        }
        for ($i = 0; $i < $total_worksheets; $i++) {
            $sheetSetup = $this->phpExcel->getSheet($i)->getPageSetup();
            if ($sheetSetup->isPrintAreaSet()) {
                $printArea = PHPExcel_Cell::splitRange($sheetSetup->getPrintArea());
                $countPrintArea = count($printArea);
                $formulaData = "";
                for ($j = 0; $j < $countPrintArea; $j++) {
                    $printAreaRect = $printArea[$j];
                    $printAreaRect[0] = PHPExcel_Cell::coordinateFromString($printAreaRect[0]);
                    $printAreaRect[1] = PHPExcel_Cell::coordinateFromString($printAreaRect[1]);
                    $print_rowmin = $printAreaRect[0][1] - 1;
                    $print_rowmax = $printAreaRect[1][1] - 1;
                    $print_colmin = PHPExcel_Cell::columnIndexFromString($printAreaRect[0][0]) - 1;
                    $print_colmax = PHPExcel_Cell::columnIndexFromString($printAreaRect[1][0]) - 1;
                    $formulaData .= pack("Cvvvvv", 59, $i, $print_rowmin, $print_rowmax, $print_colmin, $print_colmax);
                    if (0 < $j) {
                        $formulaData .= pack("C", 16);
                    }
                }
                $chunk .= $this->writeData($this->writeDefinedNameBiff8(pack("C", 6), $formulaData, $i + 1, true));
            }
        }
        for ($i = 0; $i < $total_worksheets; $i++) {
            $sheetAutoFilter = $this->phpExcel->getSheet($i)->getAutoFilter();
            $autoFilterRange = $sheetAutoFilter->getRange();
            if (!empty($autoFilterRange)) {
                $rangeBounds = PHPExcel_Cell::rangeBoundaries($autoFilterRange);
                $name = pack("C", 13);
                $chunk .= $this->writeData($this->writeShortNameBiff8($name, $i + 1, $rangeBounds, true));
            }
        }
        return $chunk;
    }
    /**
     * Write a DEFINEDNAME record for BIFF8 using explicit binary formula data
     *
     * @param    string        $name            The name in UTF-8
     * @param    string        $formulaData    The binary formula data
     * @param    string        $sheetIndex        1-based sheet index the defined name applies to. 0 = global
     * @param    boolean        $isBuiltIn        Built-in name?
     * @return    string    Complete binary record data
     */
    private function writeDefinedNameBiff8($name, $formulaData, $sheetIndex = 0, $isBuiltIn = false)
    {
        $record = 24;
        $options = $isBuiltIn ? 32 : 0;
        $nlen = PHPExcel_Shared_String::CountCharacters($name);
        $name = substr(PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($name), 2);
        $sz = strlen($formulaData);
        $data = pack("vCCvvvCCCC", $options, 0, $nlen, $sz, 0, $sheetIndex, 0, 0, 0, 0) . $name . $formulaData;
        $length = strlen($data);
        $header = pack("vv", $record, $length);
        return $header . $data;
    }
    /**
     * Write a short NAME record
     *
     * @param    string         $name
     * @param    string         $sheetIndex        1-based sheet index the defined name applies to. 0 = global
     * @param    integer[][]  $rangeBounds    range boundaries
     * @param    boolean      $isHidden
     * @return    string    Complete binary record data
     * */
    private function writeShortNameBiff8($name, $sheetIndex = 0, $rangeBounds, $isHidden = false)
    {
        $record = 24;
        $options = $isHidden ? 33 : 0;
        $extra = pack("Cvvvvv", 59, $sheetIndex - 1, $rangeBounds[0][1] - 1, $rangeBounds[1][1] - 1, $rangeBounds[0][0] - 1, $rangeBounds[1][0] - 1);
        $sz = strlen($extra);
        $data = pack("vCCvvvCCCCC", $options, 0, 1, $sz, 0, $sheetIndex, 0, 0, 0, 0, 0) . $name . $extra;
        $length = strlen($data);
        $header = pack("vv", $record, $length);
        return $header . $data;
    }
    /**
     * Stores the CODEPAGE biff record.
     */
    private function writeCodepage()
    {
        $record = 66;
        $length = 2;
        $cv = $this->codepage;
        $header = pack("vv", $record, $length);
        $data = pack("v", $cv);
        $this->append($header . $data);
    }
    /**
     * Write Excel BIFF WINDOW1 record.
     */
    private function writeWindow1()
    {
        $record = 61;
        $length = 18;
        $xWn = 0;
        $yWn = 0;
        $dxWn = 9660;
        $dyWn = 5490;
        $grbit = 56;
        $ctabsel = 1;
        $wTabRatio = 600;
        $itabFirst = 0;
        $itabCur = $this->phpExcel->getActiveSheetIndex();
        $header = pack("vv", $record, $length);
        $data = pack("vvvvvvvvv", $xWn, $yWn, $dxWn, $dyWn, $grbit, $itabCur, $itabFirst, $ctabsel, $wTabRatio);
        $this->append($header . $data);
    }
    /**
     * Writes Excel BIFF BOUNDSHEET record.
     *
     * @param PHPExcel_Worksheet  $sheet Worksheet name
     * @param integer $offset    Location of worksheet BOF
     */
    private function writeBoundSheet($sheet, $offset)
    {
        $sheetname = $sheet->getTitle();
        $record = 133;
        switch ($sheet->getSheetState()) {
            case PHPExcel_Worksheet::SHEETSTATE_VISIBLE:
                $ss = 0;
                break;
            case PHPExcel_Worksheet::SHEETSTATE_HIDDEN:
                $ss = 1;
                break;
            case PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN:
                $ss = 2;
                break;
            default:
                $ss = 0;
                break;
        }
        $st = 0;
        $grbit = 0;
        $data = pack("VCC", $offset, $ss, $st);
        $data .= PHPExcel_Shared_String::UTF8toBIFF8UnicodeShort($sheetname);
        $length = strlen($data);
        $header = pack("vv", $record, $length);
        $this->append($header . $data);
    }
    /**
     * Write Internal SUPBOOK record
     */
    private function writeSupbookInternal()
    {
        $record = 430;
        $length = 4;
        $header = pack("vv", $record, $length);
        $data = pack("vv", $this->phpExcel->getSheetCount(), 1025);
        return $this->writeData($header . $data);
    }
    /**
     * Writes the Excel BIFF EXTERNSHEET record. These references are used by
     * formulas.
     *
     */
    private function writeExternalsheetBiff8()
    {
        $totalReferences = count($this->parser->references);
        $record = 23;
        $length = 2 + 6 * $totalReferences;
        $supbook_index = 0;
        $header = pack("vv", $record, $length);
        $data = pack("v", $totalReferences);
        for ($i = 0; $i < $totalReferences; $i++) {
            $data .= $this->parser->references[$i];
        }
        return $this->writeData($header . $data);
    }
    /**
     * Write Excel BIFF STYLE records.
     */
    private function writeStyle()
    {
        $record = 659;
        $length = 4;
        $ixfe = 32768;
        $BuiltIn = 0;
        $iLevel = 255;
        $header = pack("vv", $record, $length);
        $data = pack("vCC", $ixfe, $BuiltIn, $iLevel);
        $this->append($header . $data);
    }
    /**
     * Writes Excel FORMAT record for non "built-in" numerical formats.
     *
     * @param string  $format Custom format string
     * @param integer $ifmt   Format index code
     */
    private function writeNumberFormat($format, $ifmt)
    {
        $record = 1054;
        $numberFormatString = PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($format);
        $length = 2 + strlen($numberFormatString);
        $header = pack("vv", $record, $length);
        $data = pack("v", $ifmt) . $numberFormatString;
        $this->append($header . $data);
    }
    /**
     * Write DATEMODE record to indicate the date system in use (1904 or 1900).
     */
    private function writeDateMode()
    {
        $record = 34;
        $length = 2;
        $f1904 = PHPExcel_Shared_Date::getExcelCalendar() == PHPExcel_Shared_Date::CALENDAR_MAC_1904 ? 1 : 0;
        $header = pack("vv", $record, $length);
        $data = pack("v", $f1904);
        $this->append($header . $data);
    }
    /**
     * Write BIFF record EXTERNCOUNT to indicate the number of external sheet
     * references in the workbook.
     *
     * Excel only stores references to external sheets that are used in NAME.
     * The workbook NAME record is required to define the print area and the repeat
     * rows and columns.
     *
     * A similar method is used in Worksheet.php for a slightly different purpose.
     *
     * @param integer $cxals Number of external references
     */
    private function writeExternalCount($cxals)
    {
        $record = 22;
        $length = 2;
        $header = pack("vv", $record, $length);
        $data = pack("v", $cxals);
        $this->append($header . $data);
    }
    /**
     * Writes the Excel BIFF EXTERNSHEET record. These references are used by
     * formulas. NAME record is required to define the print area and the repeat
     * rows and columns.
     *
     * A similar method is used in Worksheet.php for a slightly different purpose.
     *
     * @param string $sheetname Worksheet name
     */
    private function writeExternalSheet($sheetname)
    {
        $record = 23;
        $length = 2 + strlen($sheetname);
        $cch = strlen($sheetname);
        $rgch = 3;
        $header = pack("vv", $record, $length);
        $data = pack("CC", $cch, $rgch);
        $this->append($header . $data . $sheetname);
    }
    /**
     * Store the NAME record in the short format that is used for storing the print
     * area, repeat rows only and repeat columns only.
     *
     * @param integer $index  Sheet index
     * @param integer $type   Built-in name type
     * @param integer $rowmin Start row
     * @param integer $rowmax End row
     * @param integer $colmin Start colum
     * @param integer $colmax End column
     */
    private function writeNameShort($index, $type, $rowmin, $rowmax, $colmin, $colmax)
    {
        $record = 24;
        $length = 36;
        $grbit = 32;
        $chKey = 0;
        $cch = 1;
        $cce = 21;
        $ixals = $index + 1;
        $itab = $ixals;
        $cchCustMenu = 0;
        $cchDescription = 0;
        $cchHelptopic = 0;
        $cchStatustext = 0;
        $rgch = $type;
        $unknown03 = 59;
        $unknown04 = 65535 - $index;
        $unknown05 = 0;
        $unknown06 = 0;
        $unknown07 = 4231;
        $unknown08 = 32773;
        $header = pack("vv", $record, $length);
        $data = pack("v", $grbit);
        $data .= pack("C", $chKey);
        $data .= pack("C", $cch);
        $data .= pack("v", $cce);
        $data .= pack("v", $ixals);
        $data .= pack("v", $itab);
        $data .= pack("C", $cchCustMenu);
        $data .= pack("C", $cchDescription);
        $data .= pack("C", $cchHelptopic);
        $data .= pack("C", $cchStatustext);
        $data .= pack("C", $rgch);
        $data .= pack("C", $unknown03);
        $data .= pack("v", $unknown04);
        $data .= pack("v", $unknown05);
        $data .= pack("v", $unknown06);
        $data .= pack("v", $unknown07);
        $data .= pack("v", $unknown08);
        $data .= pack("v", $index);
        $data .= pack("v", $index);
        $data .= pack("v", $rowmin);
        $data .= pack("v", $rowmax);
        $data .= pack("C", $colmin);
        $data .= pack("C", $colmax);
        $this->append($header . $data);
    }
    /**
     * Store the NAME record in the long format that is used for storing the repeat
     * rows and columns when both are specified. This shares a lot of code with
     * writeNameShort() but we use a separate method to keep the code clean.
     * Code abstraction for reuse can be carried too far, and I should know. ;-)
     *
     * @param integer $index Sheet index
     * @param integer $type  Built-in name type
     * @param integer $rowmin Start row
     * @param integer $rowmax End row
     * @param integer $colmin Start colum
     * @param integer $colmax End column
     */
    private function writeNameLong($index, $type, $rowmin, $rowmax, $colmin, $colmax)
    {
        $record = 24;
        $length = 61;
        $grbit = 32;
        $chKey = 0;
        $cch = 1;
        $cce = 46;
        $ixals = $index + 1;
        $itab = $ixals;
        $cchCustMenu = 0;
        $cchDescription = 0;
        $cchHelptopic = 0;
        $cchStatustext = 0;
        $rgch = $type;
        $unknown01 = 41;
        $unknown02 = 43;
        $unknown03 = 59;
        $unknown04 = 65535 - $index;
        $unknown05 = 0;
        $unknown06 = 0;
        $unknown07 = 4231;
        $unknown08 = 32776;
        $header = pack("vv", $record, $length);
        $data = pack("v", $grbit);
        $data .= pack("C", $chKey);
        $data .= pack("C", $cch);
        $data .= pack("v", $cce);
        $data .= pack("v", $ixals);
        $data .= pack("v", $itab);
        $data .= pack("C", $cchCustMenu);
        $data .= pack("C", $cchDescription);
        $data .= pack("C", $cchHelptopic);
        $data .= pack("C", $cchStatustext);
        $data .= pack("C", $rgch);
        $data .= pack("C", $unknown01);
        $data .= pack("v", $unknown02);
        $data .= pack("C", $unknown03);
        $data .= pack("v", $unknown04);
        $data .= pack("v", $unknown05);
        $data .= pack("v", $unknown06);
        $data .= pack("v", $unknown07);
        $data .= pack("v", $unknown08);
        $data .= pack("v", $index);
        $data .= pack("v", $index);
        $data .= pack("v", 0);
        $data .= pack("v", 16383);
        $data .= pack("C", $colmin);
        $data .= pack("C", $colmax);
        $data .= pack("C", $unknown03);
        $data .= pack("v", $unknown04);
        $data .= pack("v", $unknown05);
        $data .= pack("v", $unknown06);
        $data .= pack("v", $unknown07);
        $data .= pack("v", $unknown08);
        $data .= pack("v", $index);
        $data .= pack("v", $index);
        $data .= pack("v", $rowmin);
        $data .= pack("v", $rowmax);
        $data .= pack("C", 0);
        $data .= pack("C", 255);
        $data .= pack("C", 16);
        $this->append($header . $data);
    }
    /**
     * Stores the COUNTRY record for localization
     *
     * @return string
     */
    private function writeCountry()
    {
        $record = 140;
        $length = 4;
        $header = pack("vv", $record, $length);
        $data = pack("vv", $this->countryCode, $this->countryCode);
        return $this->writeData($header . $data);
    }
    /**
     * Write the RECALCID record
     *
     * @return string
     */
    private function writeRecalcId()
    {
        $record = 449;
        $length = 8;
        $header = pack("vv", $record, $length);
        $data = pack("VV", 449, 124519);
        return $this->writeData($header . $data);
    }
    /**
     * Stores the PALETTE biff record.
     */
    private function writePalette()
    {
        $aref = $this->palette;
        $record = 146;
        $length = 2 + 4 * count($aref);
        $ccv = count($aref);
        $data = "";
        foreach ($aref as $color) {
            foreach ($color as $byte) {
                $data .= pack("C", $byte);
            }
        }
        $header = pack("vvv", $record, $length, $ccv);
        $this->append($header . $data);
    }
    /**
     * Handling of the SST continue blocks is complicated by the need to include an
     * additional continuation byte depending on whether the string is split between
     * blocks or whether it starts at the beginning of the block. (There are also
     * additional complications that will arise later when/if Rich Strings are
     * supported).
     *
     * The Excel documentation says that the SST record should be followed by an
     * EXTSST record. The EXTSST record is a hash table that is used to optimise
     * access to SST. However, despite the documentation it doesn't seem to be
     * required so we will ignore it.
     *
     * @return string Binary data
     */
    private function writeSharedStringsTable()
    {
        $continue_limit = 8224;
        $recordDatas = array();
        $recordData = pack("VV", $this->stringTotal, $this->stringUnique);
        foreach (array_keys($this->stringTable) as $string) {
            $headerinfo = unpack("vlength/Cencoding", $string);
            $encoding = $headerinfo["encoding"];
            $finished = false;
            while ($finished === false) {
                if (strlen($recordData) + strlen($string) <= $continue_limit) {
                    $recordData .= $string;
                    if (strlen($recordData) + strlen($string) == $continue_limit) {
                        $recordDatas[] = $recordData;
                        $recordData = "";
                    }
                    $finished = true;
                } else {
                    $space_remaining = $continue_limit - strlen($recordData);
                    $min_space_needed = $encoding == 1 ? 5 : 4;
                    if ($space_remaining < $min_space_needed) {
                        $recordDatas[] = $recordData;
                        $recordData = "";
                    } else {
                        $effective_space_remaining = $space_remaining;
                        if ($encoding == 1 && (strlen($string) - $space_remaining) % 2 == 1) {
                            $effective_space_remaining--;
                        }
                        $recordData .= substr($string, 0, $effective_space_remaining);
                        $string = substr($string, $effective_space_remaining);
                        $recordDatas[] = $recordData;
                        $recordData = pack("C", $encoding);
                    }
                }
            }
        }
        if (0 < strlen($recordData)) {
            $recordDatas[] = $recordData;
        }
        $chunk = "";
        foreach ($recordDatas as $i => $recordData) {
            $record = $i == 0 ? 252 : 60;
            $header = pack("vv", $record, strlen($recordData));
            $data = $header . $recordData;
            $chunk .= $this->writeData($data);
        }
        return $chunk;
    }
    /**
     * Writes the MSODRAWINGGROUP record if needed. Possibly split using CONTINUE records.
     */
    private function writeMsoDrawingGroup()
    {
        if (isset($this->escher)) {
            $writer = new PHPExcel_Writer_Excel5_Escher($this->escher);
            $data = $writer->close();
            $record = 235;
            $length = strlen($data);
            $header = pack("vv", $record, $length);
            return $this->writeData($header . $data);
        }
        return "";
    }
    /**
     * Get Escher object
     *
     * @return PHPExcel_Shared_Escher
     */
    public function getEscher()
    {
        return $this->escher;
    }
    /**
     * Set Escher object
     *
     * @param PHPExcel_Shared_Escher $pValue
     */
    public function setEscher(PHPExcel_Shared_Escher $pValue = NULL)
    {
        $this->escher = $pValue;
    }
}

?>