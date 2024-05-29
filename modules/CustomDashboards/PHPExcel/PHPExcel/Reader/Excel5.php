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
 * PHPExcel_Reader_Excel5
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
 * @package    PHPExcel_Reader_Excel5
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Reader_Excel5 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
{
    /**
     * Summary Information stream data.
     *
     * @var string
     */
    private $summaryInformation = NULL;
    /**
     * Extended Summary Information stream data.
     *
     * @var string
     */
    private $documentSummaryInformation = NULL;
    /**
     * User-Defined Properties stream data.
     *
     * @var string
     */
    private $userDefinedProperties = NULL;
    /**
     * Workbook stream data. (Includes workbook globals substream as well as sheet substreams)
     *
     * @var string
     */
    private $data = NULL;
    /**
     * Size in bytes of $this->data
     *
     * @var int
     */
    private $dataSize = NULL;
    /**
     * Current position in stream
     *
     * @var integer
     */
    private $pos = NULL;
    /**
     * Workbook to be returned by the reader.
     *
     * @var PHPExcel
     */
    private $phpExcel = NULL;
    /**
     * Worksheet that is currently being built by the reader.
     *
     * @var PHPExcel_Worksheet
     */
    private $phpSheet = NULL;
    /**
     * BIFF version
     *
     * @var int
     */
    private $version = NULL;
    /**
     * Codepage set in the Excel file being read. Only important for BIFF5 (Excel 5.0 - Excel 95)
     * For BIFF8 (Excel 97 - Excel 2003) this will always have the value 'UTF-16LE'
     *
     * @var string
     */
    private $codepage = NULL;
    /**
     * Shared formats
     *
     * @var array
     */
    private $formats = NULL;
    /**
     * Shared fonts
     *
     * @var array
     */
    private $objFonts = NULL;
    /**
     * Color palette
     *
     * @var array
     */
    private $palette = NULL;
    /**
     * Worksheets
     *
     * @var array
     */
    private $sheets = NULL;
    /**
     * External books
     *
     * @var array
     */
    private $externalBooks = NULL;
    /**
     * REF structures. Only applies to BIFF8.
     *
     * @var array
     */
    private $ref = NULL;
    /**
     * External names
     *
     * @var array
     */
    private $externalNames = NULL;
    /**
     * Defined names
     *
     * @var array
     */
    private $definedname = NULL;
    /**
     * Shared strings. Only applies to BIFF8.
     *
     * @var array
     */
    private $sst = NULL;
    /**
     * Panes are frozen? (in sheet currently being read). See WINDOW2 record.
     *
     * @var boolean
     */
    private $frozen = NULL;
    /**
     * Fit printout to number of pages? (in sheet currently being read). See SHEETPR record.
     *
     * @var boolean
     */
    private $isFitToPages = NULL;
    /**
     * Objects. One OBJ record contributes with one entry.
     *
     * @var array
     */
    private $objs = NULL;
    /**
     * Text Objects. One TXO record corresponds with one entry.
     *
     * @var array
     */
    private $textObjects = NULL;
    /**
     * Cell Annotations (BIFF8)
     *
     * @var array
     */
    private $cellNotes = NULL;
    /**
     * The combined MSODRAWINGGROUP data
     *
     * @var string
     */
    private $drawingGroupData = NULL;
    /**
     * The combined MSODRAWING data (per sheet)
     *
     * @var string
     */
    private $drawingData = NULL;
    /**
     * Keep track of XF index
     *
     * @var int
     */
    private $xfIndex = NULL;
    /**
     * Mapping of XF index (that is a cell XF) to final index in cellXf collection
     *
     * @var array
     */
    private $mapCellXfIndex = NULL;
    /**
     * Mapping of XF index (that is a style XF) to final index in cellStyleXf collection
     *
     * @var array
     */
    private $mapCellStyleXfIndex = NULL;
    /**
     * The shared formulas in a sheet. One SHAREDFMLA record contributes with one value.
     *
     * @var array
     */
    private $sharedFormulas = NULL;
    /**
     * The shared formula parts in a sheet. One FORMULA record contributes with one value if it
     * refers to a shared formula.
     *
     * @var array
     */
    private $sharedFormulaParts = NULL;
    /**
     * The type of encryption in use
     *
     * @var int
     */
    private $encryption = 0;
    /**
     * The position in the stream after which contents are encrypted
     *
     * @var int
     */
    private $encryptionStartPos = false;
    /**
     * The current RC4 decryption object
     *
     * @var PHPExcel_Reader_Excel5_RC4
     */
    private $rc4Key = NULL;
    /**
     * The position in the stream that the RC4 decryption object was left at
     *
     * @var int
     */
    private $rc4Pos = 0;
    /**
     * The current MD5 context state
     *
     * @var string
     */
    private $md5Ctxt = NULL;
    const XLS_BIFF8 = 1536;
    const XLS_BIFF7 = 1280;
    const XLS_WorkbookGlobals = 5;
    const XLS_Worksheet = 16;
    const XLS_TYPE_FORMULA = 6;
    const XLS_TYPE_EOF = 10;
    const XLS_TYPE_PROTECT = 18;
    const XLS_TYPE_OBJECTPROTECT = 99;
    const XLS_TYPE_SCENPROTECT = 221;
    const XLS_TYPE_PASSWORD = 19;
    const XLS_TYPE_HEADER = 20;
    const XLS_TYPE_FOOTER = 21;
    const XLS_TYPE_EXTERNSHEET = 23;
    const XLS_TYPE_DEFINEDNAME = 24;
    const XLS_TYPE_VERTICALPAGEBREAKS = 26;
    const XLS_TYPE_HORIZONTALPAGEBREAKS = 27;
    const XLS_TYPE_NOTE = 28;
    const XLS_TYPE_SELECTION = 29;
    const XLS_TYPE_DATEMODE = 34;
    const XLS_TYPE_EXTERNNAME = 35;
    const XLS_TYPE_LEFTMARGIN = 38;
    const XLS_TYPE_RIGHTMARGIN = 39;
    const XLS_TYPE_TOPMARGIN = 40;
    const XLS_TYPE_BOTTOMMARGIN = 41;
    const XLS_TYPE_PRINTGRIDLINES = 43;
    const XLS_TYPE_FILEPASS = 47;
    const XLS_TYPE_FONT = 49;
    const XLS_TYPE_CONTINUE = 60;
    const XLS_TYPE_PANE = 65;
    const XLS_TYPE_CODEPAGE = 66;
    const XLS_TYPE_DEFCOLWIDTH = 85;
    const XLS_TYPE_OBJ = 93;
    const XLS_TYPE_COLINFO = 125;
    const XLS_TYPE_IMDATA = 127;
    const XLS_TYPE_SHEETPR = 129;
    const XLS_TYPE_HCENTER = 131;
    const XLS_TYPE_VCENTER = 132;
    const XLS_TYPE_SHEET = 133;
    const XLS_TYPE_PALETTE = 146;
    const XLS_TYPE_SCL = 160;
    const XLS_TYPE_PAGESETUP = 161;
    const XLS_TYPE_MULRK = 189;
    const XLS_TYPE_MULBLANK = 190;
    const XLS_TYPE_DBCELL = 215;
    const XLS_TYPE_XF = 224;
    const XLS_TYPE_MERGEDCELLS = 229;
    const XLS_TYPE_MSODRAWINGGROUP = 235;
    const XLS_TYPE_MSODRAWING = 236;
    const XLS_TYPE_SST = 252;
    const XLS_TYPE_LABELSST = 253;
    const XLS_TYPE_EXTSST = 255;
    const XLS_TYPE_EXTERNALBOOK = 430;
    const XLS_TYPE_DATAVALIDATIONS = 434;
    const XLS_TYPE_TXO = 438;
    const XLS_TYPE_HYPERLINK = 440;
    const XLS_TYPE_DATAVALIDATION = 446;
    const XLS_TYPE_DIMENSION = 512;
    const XLS_TYPE_BLANK = 513;
    const XLS_TYPE_NUMBER = 515;
    const XLS_TYPE_LABEL = 516;
    const XLS_TYPE_BOOLERR = 517;
    const XLS_TYPE_STRING = 519;
    const XLS_TYPE_ROW = 520;
    const XLS_TYPE_INDEX = 523;
    const XLS_TYPE_ARRAY = 545;
    const XLS_TYPE_DEFAULTROWHEIGHT = 549;
    const XLS_TYPE_WINDOW2 = 574;
    const XLS_TYPE_RK = 638;
    const XLS_TYPE_STYLE = 659;
    const XLS_TYPE_FORMAT = 1054;
    const XLS_TYPE_SHAREDFMLA = 1212;
    const XLS_TYPE_BOF = 2057;
    const XLS_TYPE_SHEETPROTECTION = 2151;
    const XLS_TYPE_RANGEPROTECTION = 2152;
    const XLS_TYPE_SHEETLAYOUT = 2146;
    const XLS_TYPE_XFEXT = 2173;
    const XLS_TYPE_PAGELAYOUTVIEW = 2187;
    const XLS_TYPE_UNKNOWN = 65535;
    const MS_BIFF_CRYPTO_NONE = 0;
    const MS_BIFF_CRYPTO_XOR = 1;
    const MS_BIFF_CRYPTO_RC4 = 2;
    const REKEY_BLOCK = 1024;
    /**
     * Create a new PHPExcel_Reader_Excel5 instance
     */
    public function __construct()
    {
        $this->readFilter = new PHPExcel_Reader_DefaultReadFilter();
    }
    /**
     * Can the current PHPExcel_Reader_IReader read the file?
     *
     * @param     string         $pFilename
     * @return     boolean
     * @throws PHPExcel_Reader_Exception
     */
    public function canRead($pFilename)
    {
        if (!file_exists($pFilename)) {
            throw new PHPExcel_Reader_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
        }
        try {
            $ole = new PHPExcel_Shared_OLERead();
            $res = $ole->read($pFilename);
            return true;
        } catch (PHPExcel_Exception $e) {
            return false;
        }
    }
    /**
     * Reads names of the worksheets from a file, without parsing the whole file to a PHPExcel object
     *
     * @param     string         $pFilename
     * @throws     PHPExcel_Reader_Exception
     */
    public function listWorksheetNames($pFilename)
    {
        if (!file_exists($pFilename)) {
            throw new PHPExcel_Reader_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
        }
        $worksheetNames = array();
        $this->loadOLE($pFilename);
        $this->dataSize = strlen($this->data);
        $this->pos = 0;
        $this->sheets = array();
        while ($this->pos < $this->dataSize) {
            $code = self::getInt2d($this->data, $this->pos);
            switch ($code) {
                case self::XLS_TYPE_BOF:
                    $this->readBof();
                    break;
                case self::XLS_TYPE_SHEET:
                    $this->readSheet();
                    break;
                case self::XLS_TYPE_EOF:
                    $this->readDefault();
                    break 2;
                default:
                    $this->readDefault();
                    break;
            }
        }
        foreach ($this->sheets as $sheet) {
            if ($sheet["sheetType"] != 0) {
                continue;
            }
            $worksheetNames[] = $sheet["name"];
        }
        return $worksheetNames;
    }
    /**
     * Return worksheet info (Name, Last Column Letter, Last Column Index, Total Rows, Total Columns)
     *
     * @param   string     $pFilename
     * @throws   PHPExcel_Reader_Exception
     */
    public function listWorksheetInfo($pFilename)
    {
        if (!file_exists($pFilename)) {
            throw new PHPExcel_Reader_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
        }
        $worksheetInfo = array();
        $this->loadOLE($pFilename);
        $this->dataSize = strlen($this->data);
        $this->pos = 0;
        $this->sheets = array();
        while ($this->pos < $this->dataSize) {
            $code = self::getInt2d($this->data, $this->pos);
            switch ($code) {
                case self::XLS_TYPE_BOF:
                    $this->readBof();
                    break;
                case self::XLS_TYPE_SHEET:
                    $this->readSheet();
                    break;
                case self::XLS_TYPE_EOF:
                    $this->readDefault();
                    break 2;
                default:
                    $this->readDefault();
                    break;
            }
        }
        foreach ($this->sheets as $sheet) {
            if ($sheet["sheetType"] != 0) {
                continue;
            }
            $tmpInfo = array();
            $tmpInfo["worksheetName"] = $sheet["name"];
            $tmpInfo["lastColumnLetter"] = "A";
            $tmpInfo["lastColumnIndex"] = 0;
            $tmpInfo["totalRows"] = 0;
            $tmpInfo["totalColumns"] = 0;
            $this->pos = $sheet["offset"];
            while ($this->pos <= $this->dataSize - 4) {
                $code = self::getInt2d($this->data, $this->pos);
                switch ($code) {
                    case self::XLS_TYPE_RK:
                    case self::XLS_TYPE_LABELSST:
                    case self::XLS_TYPE_NUMBER:
                    case self::XLS_TYPE_FORMULA:
                    case self::XLS_TYPE_BOOLERR:
                    case self::XLS_TYPE_LABEL:
                        $length = self::getInt2d($this->data, $this->pos + 2);
                        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
                        $this->pos += 4 + $length;
                        $rowIndex = self::getInt2d($recordData, 0) + 1;
                        $columnIndex = self::getInt2d($recordData, 2);
                        $tmpInfo["totalRows"] = max($tmpInfo["totalRows"], $rowIndex);
                        $tmpInfo["lastColumnIndex"] = max($tmpInfo["lastColumnIndex"], $columnIndex);
                        break;
                    case self::XLS_TYPE_BOF:
                        $this->readBof();
                        break;
                    case self::XLS_TYPE_EOF:
                        $this->readDefault();
                        break 2;
                    default:
                        $this->readDefault();
                        break;
                }
            }
            $tmpInfo["lastColumnLetter"] = PHPExcel_Cell::stringFromColumnIndex($tmpInfo["lastColumnIndex"]);
            $tmpInfo["totalColumns"] = $tmpInfo["lastColumnIndex"] + 1;
            $worksheetInfo[] = $tmpInfo;
        }
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
        $this->loadOLE($pFilename);
        $this->phpExcel = new PHPExcel();
        $this->phpExcel->removeSheetByIndex(0);
        if (!$this->readDataOnly) {
            $this->phpExcel->removeCellStyleXfByIndex(0);
            $this->phpExcel->removeCellXfByIndex(0);
        }
        $this->readSummaryInformation();
        $this->readDocumentSummaryInformation();
        $this->dataSize = strlen($this->data);
        $this->pos = 0;
        $this->codepage = "CP1252";
        $this->formats = array();
        $this->objFonts = array();
        $this->palette = array();
        $this->sheets = array();
        $this->externalBooks = array();
        $this->ref = array();
        $this->definedname = array();
        $this->sst = array();
        $this->drawingGroupData = "";
        $this->xfIndex = "";
        $this->mapCellXfIndex = array();
        $this->mapCellStyleXfIndex = array();
        while ($this->pos < $this->dataSize) {
            $code = self::getInt2d($this->data, $this->pos);
            switch ($code) {
                case self::XLS_TYPE_BOF:
                    $this->readBof();
                    break;
                case self::XLS_TYPE_FILEPASS:
                    $this->readFilepass();
                    break;
                case self::XLS_TYPE_CODEPAGE:
                    $this->readCodepage();
                    break;
                case self::XLS_TYPE_DATEMODE:
                    $this->readDateMode();
                    break;
                case self::XLS_TYPE_FONT:
                    $this->readFont();
                    break;
                case self::XLS_TYPE_FORMAT:
                    $this->readFormat();
                    break;
                case self::XLS_TYPE_XF:
                    $this->readXf();
                    break;
                case self::XLS_TYPE_XFEXT:
                    $this->readXfExt();
                    break;
                case self::XLS_TYPE_STYLE:
                    $this->readStyle();
                    break;
                case self::XLS_TYPE_PALETTE:
                    $this->readPalette();
                    break;
                case self::XLS_TYPE_SHEET:
                    $this->readSheet();
                    break;
                case self::XLS_TYPE_EXTERNALBOOK:
                    $this->readExternalBook();
                    break;
                case self::XLS_TYPE_EXTERNNAME:
                    $this->readExternName();
                    break;
                case self::XLS_TYPE_EXTERNSHEET:
                    $this->readExternSheet();
                    break;
                case self::XLS_TYPE_DEFINEDNAME:
                    $this->readDefinedName();
                    break;
                case self::XLS_TYPE_MSODRAWINGGROUP:
                    $this->readMsoDrawingGroup();
                    break;
                case self::XLS_TYPE_SST:
                    $this->readSst();
                    break;
                case self::XLS_TYPE_EOF:
                    $this->readDefault();
                    break 2;
                default:
                    $this->readDefault();
                    break;
            }
        }
        if (!$this->readDataOnly) {
            foreach ($this->objFonts as $objFont) {
                if (isset($objFont->colorIndex)) {
                    $color = PHPExcel_Reader_Excel5_Color::map($objFont->colorIndex, $this->palette, $this->version);
                    $objFont->getColor()->setRGB($color["rgb"]);
                }
            }
            foreach ($this->phpExcel->getCellXfCollection() as $objStyle) {
                $fill = $objStyle->getFill();
                if (isset($fill->startcolorIndex)) {
                    $startColor = PHPExcel_Reader_Excel5_Color::map($fill->startcolorIndex, $this->palette, $this->version);
                    $fill->getStartColor()->setRGB($startColor["rgb"]);
                }
                if (isset($fill->endcolorIndex)) {
                    $endColor = PHPExcel_Reader_Excel5_Color::map($fill->endcolorIndex, $this->palette, $this->version);
                    $fill->getEndColor()->setRGB($endColor["rgb"]);
                }
                $top = $objStyle->getBorders()->getTop();
                $right = $objStyle->getBorders()->getRight();
                $bottom = $objStyle->getBorders()->getBottom();
                $left = $objStyle->getBorders()->getLeft();
                $diagonal = $objStyle->getBorders()->getDiagonal();
                if (isset($top->colorIndex)) {
                    $borderTopColor = PHPExcel_Reader_Excel5_Color::map($top->colorIndex, $this->palette, $this->version);
                    $top->getColor()->setRGB($borderTopColor["rgb"]);
                }
                if (isset($right->colorIndex)) {
                    $borderRightColor = PHPExcel_Reader_Excel5_Color::map($right->colorIndex, $this->palette, $this->version);
                    $right->getColor()->setRGB($borderRightColor["rgb"]);
                }
                if (isset($bottom->colorIndex)) {
                    $borderBottomColor = PHPExcel_Reader_Excel5_Color::map($bottom->colorIndex, $this->palette, $this->version);
                    $bottom->getColor()->setRGB($borderBottomColor["rgb"]);
                }
                if (isset($left->colorIndex)) {
                    $borderLeftColor = PHPExcel_Reader_Excel5_Color::map($left->colorIndex, $this->palette, $this->version);
                    $left->getColor()->setRGB($borderLeftColor["rgb"]);
                }
                if (isset($diagonal->colorIndex)) {
                    $borderDiagonalColor = PHPExcel_Reader_Excel5_Color::map($diagonal->colorIndex, $this->palette, $this->version);
                    $diagonal->getColor()->setRGB($borderDiagonalColor["rgb"]);
                }
            }
        }
        if (!$this->readDataOnly && $this->drawingGroupData) {
            $escherWorkbook = new PHPExcel_Shared_Escher();
            $reader = new PHPExcel_Reader_Excel5_Escher($escherWorkbook);
            $escherWorkbook = $reader->load($this->drawingGroupData);
        }
        foreach ($this->sheets as $sheet) {
            if ($sheet["sheetType"] != 0) {
                continue;
            }
            if (isset($this->loadSheetsOnly) && !in_array($sheet["name"], $this->loadSheetsOnly)) {
                continue;
            }
            $this->phpSheet = $this->phpExcel->createSheet();
            $this->phpSheet->setTitle($sheet["name"], false);
            $this->phpSheet->setSheetState($sheet["sheetState"]);
            $this->pos = $sheet["offset"];
            $this->isFitToPages = false;
            $this->drawingData = "";
            $this->objs = array();
            $this->sharedFormulaParts = array();
            $this->sharedFormulas = array();
            $this->textObjects = array();
            $this->cellNotes = array();
            $this->textObjRef = -1;
            while ($this->pos <= $this->dataSize - 4) {
                $code = self::getInt2d($this->data, $this->pos);
                switch ($code) {
                    case self::XLS_TYPE_BOF:
                        $this->readBof();
                        break;
                    case self::XLS_TYPE_PRINTGRIDLINES:
                        $this->readPrintGridlines();
                        break;
                    case self::XLS_TYPE_DEFAULTROWHEIGHT:
                        $this->readDefaultRowHeight();
                        break;
                    case self::XLS_TYPE_SHEETPR:
                        $this->readSheetPr();
                        break;
                    case self::XLS_TYPE_HORIZONTALPAGEBREAKS:
                        $this->readHorizontalPageBreaks();
                        break;
                    case self::XLS_TYPE_VERTICALPAGEBREAKS:
                        $this->readVerticalPageBreaks();
                        break;
                    case self::XLS_TYPE_HEADER:
                        $this->readHeader();
                        break;
                    case self::XLS_TYPE_FOOTER:
                        $this->readFooter();
                        break;
                    case self::XLS_TYPE_HCENTER:
                        $this->readHcenter();
                        break;
                    case self::XLS_TYPE_VCENTER:
                        $this->readVcenter();
                        break;
                    case self::XLS_TYPE_LEFTMARGIN:
                        $this->readLeftMargin();
                        break;
                    case self::XLS_TYPE_RIGHTMARGIN:
                        $this->readRightMargin();
                        break;
                    case self::XLS_TYPE_TOPMARGIN:
                        $this->readTopMargin();
                        break;
                    case self::XLS_TYPE_BOTTOMMARGIN:
                        $this->readBottomMargin();
                        break;
                    case self::XLS_TYPE_PAGESETUP:
                        $this->readPageSetup();
                        break;
                    case self::XLS_TYPE_PROTECT:
                        $this->readProtect();
                        break;
                    case self::XLS_TYPE_SCENPROTECT:
                        $this->readScenProtect();
                        break;
                    case self::XLS_TYPE_OBJECTPROTECT:
                        $this->readObjectProtect();
                        break;
                    case self::XLS_TYPE_PASSWORD:
                        $this->readPassword();
                        break;
                    case self::XLS_TYPE_DEFCOLWIDTH:
                        $this->readDefColWidth();
                        break;
                    case self::XLS_TYPE_COLINFO:
                        $this->readColInfo();
                        break;
                    case self::XLS_TYPE_DIMENSION:
                        $this->readDefault();
                        break;
                    case self::XLS_TYPE_ROW:
                        $this->readRow();
                        break;
                    case self::XLS_TYPE_DBCELL:
                        $this->readDefault();
                        break;
                    case self::XLS_TYPE_RK:
                        $this->readRk();
                        break;
                    case self::XLS_TYPE_LABELSST:
                        $this->readLabelSst();
                        break;
                    case self::XLS_TYPE_MULRK:
                        $this->readMulRk();
                        break;
                    case self::XLS_TYPE_NUMBER:
                        $this->readNumber();
                        break;
                    case self::XLS_TYPE_FORMULA:
                        $this->readFormula();
                        break;
                    case self::XLS_TYPE_SHAREDFMLA:
                        $this->readSharedFmla();
                        break;
                    case self::XLS_TYPE_BOOLERR:
                        $this->readBoolErr();
                        break;
                    case self::XLS_TYPE_MULBLANK:
                        $this->readMulBlank();
                        break;
                    case self::XLS_TYPE_LABEL:
                        $this->readLabel();
                        break;
                    case self::XLS_TYPE_BLANK:
                        $this->readBlank();
                        break;
                    case self::XLS_TYPE_MSODRAWING:
                        $this->readMsoDrawing();
                        break;
                    case self::XLS_TYPE_OBJ:
                        $this->readObj();
                        break;
                    case self::XLS_TYPE_WINDOW2:
                        $this->readWindow2();
                        break;
                    case self::XLS_TYPE_PAGELAYOUTVIEW:
                        $this->readPageLayoutView();
                        break;
                    case self::XLS_TYPE_SCL:
                        $this->readScl();
                        break;
                    case self::XLS_TYPE_PANE:
                        $this->readPane();
                        break;
                    case self::XLS_TYPE_SELECTION:
                        $this->readSelection();
                        break;
                    case self::XLS_TYPE_MERGEDCELLS:
                        $this->readMergedCells();
                        break;
                    case self::XLS_TYPE_HYPERLINK:
                        $this->readHyperLink();
                        break;
                    case self::XLS_TYPE_DATAVALIDATIONS:
                        $this->readDataValidations();
                        break;
                    case self::XLS_TYPE_DATAVALIDATION:
                        $this->readDataValidation();
                        break;
                    case self::XLS_TYPE_SHEETLAYOUT:
                        $this->readSheetLayout();
                        break;
                    case self::XLS_TYPE_SHEETPROTECTION:
                        $this->readSheetProtection();
                        break;
                    case self::XLS_TYPE_RANGEPROTECTION:
                        $this->readRangeProtection();
                        break;
                    case self::XLS_TYPE_NOTE:
                        $this->readNote();
                        break;
                    case self::XLS_TYPE_TXO:
                        $this->readTextObject();
                        break;
                    case self::XLS_TYPE_CONTINUE:
                        $this->readContinue();
                        break;
                    case self::XLS_TYPE_EOF:
                        $this->readDefault();
                        break 2;
                    default:
                        $this->readDefault();
                        break;
                }
            }
            if (!$this->readDataOnly && $this->drawingData) {
                $escherWorksheet = new PHPExcel_Shared_Escher();
                $reader = new PHPExcel_Reader_Excel5_Escher($escherWorksheet);
                $escherWorksheet = $reader->load($this->drawingData);
                $allSpContainers = $escherWorksheet->getDgContainer()->getSpgrContainer()->getAllSpContainers();
            }
            foreach ($this->objs as $n => $obj) {
                if (isset($allSpContainers[$n + 1]) && is_object($allSpContainers[$n + 1])) {
                    $spContainer = $allSpContainers[$n + 1];
                    if (1 < $spContainer->getNestingLevel()) {
                        continue;
                    }
                    list($startColumn, $startRow) = PHPExcel_Cell::coordinateFromString($spContainer->getStartCoordinates());
                    list($endColumn, $endRow) = PHPExcel_Cell::coordinateFromString($spContainer->getEndCoordinates());
                    $startOffsetX = $spContainer->getStartOffsetX();
                    $startOffsetY = $spContainer->getStartOffsetY();
                    $endOffsetX = $spContainer->getEndOffsetX();
                    $endOffsetY = $spContainer->getEndOffsetY();
                    $width = PHPExcel_Shared_Excel5::getDistanceX($this->phpSheet, $startColumn, $startOffsetX, $endColumn, $endOffsetX);
                    $height = PHPExcel_Shared_Excel5::getDistanceY($this->phpSheet, $startRow, $startOffsetY, $endRow, $endOffsetY);
                    $offsetX = $startOffsetX * PHPExcel_Shared_Excel5::sizeCol($this->phpSheet, $startColumn) / 1024;
                    $offsetY = $startOffsetY * PHPExcel_Shared_Excel5::sizeRow($this->phpSheet, $startRow) / 256;
                    switch ($obj["otObjType"]) {
                        case 25:
                            if (isset($this->cellNotes[$obj["idObjID"]])) {
                                $cellNote = $this->cellNotes[$obj["idObjID"]];
                                if (isset($this->textObjects[$obj["idObjID"]])) {
                                    $textObject = $this->textObjects[$obj["idObjID"]];
                                    $this->cellNotes[$obj["idObjID"]]["objTextData"] = $textObject;
                                }
                            }
                            break;
                        case 8:
                            $BSEindex = $spContainer->getOPT(260);
                            $BSECollection = $escherWorkbook->getDggContainer()->getBstoreContainer()->getBSECollection();
                            $BSE = $BSECollection[$BSEindex - 1];
                            $blipType = $BSE->getBlipType();
                            if ($blip = $BSE->getBlip()) {
                                $ih = imagecreatefromstring($blip->getData());
                                $drawing = new PHPExcel_Worksheet_MemoryDrawing();
                                $drawing->setImageResource($ih);
                                $drawing->setResizeProportional(false);
                                $drawing->setWidth($width);
                                $drawing->setHeight($height);
                                $drawing->setOffsetX($offsetX);
                                $drawing->setOffsetY($offsetY);
                                switch ($blipType) {
                                    case PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_JPEG:
                                        $drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
                                        $drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_JPEG);
                                        break;
                                    case PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_PNG:
                                        $drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
                                        $drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_PNG);
                                        break;
                                }
                                $drawing->setWorksheet($this->phpSheet);
                                $drawing->setCoordinates($spContainer->getStartCoordinates());
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
            if ($this->version == self::XLS_BIFF8) {
                foreach ($this->sharedFormulaParts as $cell => $baseCell) {
                    list($column, $row) = PHPExcel_Cell::coordinateFromString($cell);
                    if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($column, $row, $this->phpSheet->getTitle())) {
                        $formula = $this->getFormulaFromStructure($this->sharedFormulas[$baseCell], $cell);
                        $this->phpSheet->getCell($cell)->setValueExplicit("=" . $formula, PHPExcel_Cell_DataType::TYPE_FORMULA);
                    }
                }
            }
            if (!empty($this->cellNotes)) {
                foreach ($this->cellNotes as $note => $noteDetails) {
                    if (!isset($noteDetails["objTextData"])) {
                        if (isset($this->textObjects[$note])) {
                            $textObject = $this->textObjects[$note];
                            $noteDetails["objTextData"] = $textObject;
                        } else {
                            $noteDetails["objTextData"]["text"] = "";
                        }
                    }
                    $cellAddress = str_replace("\$", "", $noteDetails["cellRef"]);
                    $this->phpSheet->getComment($cellAddress)->setAuthor($noteDetails["author"])->setText($this->parseRichText($noteDetails["objTextData"]["text"]));
                }
            }
        }
        foreach ($this->definedname as $definedName) {
            if ($definedName["isBuiltInName"]) {
                switch ($definedName["name"]) {
                    case pack("C", 6):
                        $ranges = explode(",", $definedName["formula"]);
                        $extractedRanges = array();
                        foreach ($ranges as $range) {
                            $explodes = explode("!", $range);
                            $sheetName = trim($explodes[0], "'");
                            if (count($explodes) == 2) {
                                if (strpos($explodes[1], ":") === false) {
                                    $explodes[1] = $explodes[1] . ":" . $explodes[1];
                                }
                                $extractedRanges[] = str_replace("\$", "", $explodes[1]);
                            }
                        }
                        if ($docSheet = $this->phpExcel->getSheetByName($sheetName)) {
                            $docSheet->getPageSetup()->setPrintArea(implode(",", $extractedRanges));
                        }
                        break;
                    case pack("C", 7):
                        $ranges = explode(",", $definedName["formula"]);
                        foreach ($ranges as $range) {
                            $explodes = explode("!", $range);
                            if (count($explodes) == 2 && ($docSheet = $this->phpExcel->getSheetByName($explodes[0]))) {
                                $extractedRange = $explodes[1];
                                $extractedRange = str_replace("\$", "", $extractedRange);
                                $coordinateStrings = explode(":", $extractedRange);
                                if (count($coordinateStrings) == 2) {
                                    list($firstColumn, $firstRow) = PHPExcel_Cell::coordinateFromString($coordinateStrings[0]);
                                    list($lastColumn, $lastRow) = PHPExcel_Cell::coordinateFromString($coordinateStrings[1]);
                                    if ($firstColumn == "A" && $lastColumn == "IV") {
                                        $docSheet->getPageSetup()->setRowsToRepeatAtTop(array($firstRow, $lastRow));
                                    } else {
                                        if ($firstRow == 1 && $lastRow == 65536) {
                                            $docSheet->getPageSetup()->setColumnsToRepeatAtLeft(array($firstColumn, $lastColumn));
                                        }
                                    }
                                }
                            }
                        }
                        break;
                }
            } else {
                $explodes = explode("!", $definedName["formula"]);
                if (count($explodes) == 2 && (($docSheet = $this->phpExcel->getSheetByName($explodes[0])) || ($docSheet = $this->phpExcel->getSheetByName(trim($explodes[0], "'"))))) {
                    $extractedRange = $explodes[1];
                    $extractedRange = str_replace("\$", "", $extractedRange);
                    $localOnly = $definedName["scope"] == 0 ? false : true;
                    $scope = $definedName["scope"] == 0 ? NULL : $this->phpExcel->getSheetByName($this->sheets[$definedName["scope"] - 1]["name"]);
                    $this->phpExcel->addNamedRange(new PHPExcel_NamedRange((string) $definedName["name"], $docSheet, $extractedRange, $localOnly, $scope));
                }
            }
        }
        $this->data = NULL;
        return $this->phpExcel;
    }
    /**
     * Read record data from stream, decrypting as required
     *
     * @param string $data   Data stream to read from
     * @param int    $pos    Position to start reading from
     * @param int    $length Record data length
     *
     * @return string Record data
     */
    private function readRecordData($data, $pos, $len)
    {
        $data = substr($data, $pos, $len);
        if ($this->encryption == self::MS_BIFF_CRYPTO_NONE || $pos < $this->encryptionStartPos) {
            return $data;
        }
        $recordData = "";
        if ($this->encryption == self::MS_BIFF_CRYPTO_RC4) {
            $oldBlock = floor($this->rc4Pos / self::REKEY_BLOCK);
            $block = floor($pos / self::REKEY_BLOCK);
            $endBlock = floor(($pos + $len) / self::REKEY_BLOCK);
            if ($block != $oldBlock || $pos < $this->rc4Pos || !$this->rc4Key) {
                $this->rc4Key = $this->makeKey($block, $this->md5Ctxt);
                $step = $pos % self::REKEY_BLOCK;
            } else {
                $step = $pos - $this->rc4Pos;
            }
            $this->rc4Key->RC4(str_repeat("", $step));
            while ($block != $endBlock) {
                $step = self::REKEY_BLOCK - $pos % self::REKEY_BLOCK;
                $recordData .= $this->rc4Key->RC4(substr($data, 0, $step));
                $data = substr($data, $step);
                $pos += $step;
                $len -= $step;
                $block++;
                $this->rc4Key = $this->makeKey($block, $this->md5Ctxt);
            }
            $recordData .= $this->rc4Key->RC4(substr($data, 0, $len));
            $this->rc4Pos = $pos + $len;
        } else {
            if ($this->encryption == self::MS_BIFF_CRYPTO_XOR) {
                throw new PHPExcel_Reader_Exception("XOr encryption not supported");
            }
        }
        return $recordData;
    }
    /**
     * Use OLE reader to extract the relevant data streams from the OLE file
     *
     * @param string $pFilename
     */
    private function loadOLE($pFilename)
    {
        $ole = new PHPExcel_Shared_OLERead();
        $res = $ole->read($pFilename);
        $this->data = $ole->getStream($ole->wrkbook);
        $this->summaryInformation = $ole->getStream($ole->summaryInformation);
        $this->documentSummaryInformation = $ole->getStream($ole->documentSummaryInformation);
    }
    /**
     * Read summary information
     */
    private function readSummaryInformation()
    {
        if (!isset($this->summaryInformation)) {
            return NULL;
        }
        $secCount = self::getInt4d($this->summaryInformation, 24);
        $secOffset = self::getInt4d($this->summaryInformation, 44);
        $secLength = self::getInt4d($this->summaryInformation, $secOffset);
        $countProperties = self::getInt4d($this->summaryInformation, $secOffset + 4);
        $codePage = "CP1252";
        for ($i = 0; $i < $countProperties; $i++) {
            $id = self::getInt4d($this->summaryInformation, $secOffset + 8 + 8 * $i);
            $offset = self::getInt4d($this->summaryInformation, $secOffset + 12 + 8 * $i);
            $type = self::getInt4d($this->summaryInformation, $secOffset + $offset);
            $value = NULL;
            switch ($type) {
                case 2:
                    $value = self::getInt2d($this->summaryInformation, $secOffset + 4 + $offset);
                    break;
                case 3:
                    $value = self::getInt4d($this->summaryInformation, $secOffset + 4 + $offset);
                    break;
                case 19:
                    break;
                case 30:
                    $byteLength = self::getInt4d($this->summaryInformation, $secOffset + 4 + $offset);
                    $value = substr($this->summaryInformation, $secOffset + 8 + $offset, $byteLength);
                    $value = PHPExcel_Shared_String::ConvertEncoding($value, "UTF-8", $codePage);
                    $value = rtrim($value);
                    break;
                case 64:
                    $value = PHPExcel_Shared_OLE::OLE2LocalDate(substr($this->summaryInformation, $secOffset + 4 + $offset, 8));
                    break;
                case 71:
                    break;
            }
            switch ($id) {
                case 1:
                    $codePage = PHPExcel_Shared_CodePage::NumberToName($value);
                    break;
                case 2:
                    $this->phpExcel->getProperties()->setTitle($value);
                    break;
                case 3:
                    $this->phpExcel->getProperties()->setSubject($value);
                    break;
                case 4:
                    $this->phpExcel->getProperties()->setCreator($value);
                    break;
                case 5:
                    $this->phpExcel->getProperties()->setKeywords($value);
                    break;
                case 6:
                    $this->phpExcel->getProperties()->setDescription($value);
                    break;
                case 7:
                    break;
                case 8:
                    $this->phpExcel->getProperties()->setLastModifiedBy($value);
                    break;
                case 9:
                    break;
                case 10:
                    break;
                case 11:
                    break;
                case 12:
                    $this->phpExcel->getProperties()->setCreated($value);
                    break;
                case 13:
                    $this->phpExcel->getProperties()->setModified($value);
                    break;
                case 14:
                    break;
                case 15:
                    break;
                case 16:
                    break;
                case 17:
                    break;
                case 18:
                    break;
                case 19:
                    break;
            }
        }
    }
    /**
     * Read additional document summary information
     */
    private function readDocumentSummaryInformation()
    {
        if (!isset($this->documentSummaryInformation)) {
            return NULL;
        }
        $secCount = self::getInt4d($this->documentSummaryInformation, 24);
        $secOffset = self::getInt4d($this->documentSummaryInformation, 44);
        $secLength = self::getInt4d($this->documentSummaryInformation, $secOffset);
        $countProperties = self::getInt4d($this->documentSummaryInformation, $secOffset + 4);
        $codePage = "CP1252";
        for ($i = 0; $i < $countProperties; $i++) {
            $id = self::getInt4d($this->documentSummaryInformation, $secOffset + 8 + 8 * $i);
            $offset = self::getInt4d($this->documentSummaryInformation, $secOffset + 12 + 8 * $i);
            $type = self::getInt4d($this->documentSummaryInformation, $secOffset + $offset);
            $value = NULL;
            switch ($type) {
                case 2:
                    $value = self::getInt2d($this->documentSummaryInformation, $secOffset + 4 + $offset);
                    break;
                case 3:
                    $value = self::getInt4d($this->documentSummaryInformation, $secOffset + 4 + $offset);
                    break;
                case 11:
                    $value = self::getInt2d($this->documentSummaryInformation, $secOffset + 4 + $offset);
                    $value = $value == 0 ? false : true;
                    break;
                case 19:
                    break;
                case 30:
                    $byteLength = self::getInt4d($this->documentSummaryInformation, $secOffset + 4 + $offset);
                    $value = substr($this->documentSummaryInformation, $secOffset + 8 + $offset, $byteLength);
                    $value = PHPExcel_Shared_String::ConvertEncoding($value, "UTF-8", $codePage);
                    $value = rtrim($value);
                    break;
                case 64:
                    $value = PHPExcel_Shared_OLE::OLE2LocalDate(substr($this->documentSummaryInformation, $secOffset + 4 + $offset, 8));
                    break;
                case 71:
                    break;
            }
            switch ($id) {
                case 1:
                    $codePage = PHPExcel_Shared_CodePage::NumberToName($value);
                    break;
                case 2:
                    $this->phpExcel->getProperties()->setCategory($value);
                    break;
                case 3:
                    break;
                case 4:
                    break;
                case 5:
                    break;
                case 6:
                    break;
                case 7:
                    break;
                case 8:
                    break;
                case 9:
                    break;
                case 10:
                    break;
                case 11:
                    break;
                case 12:
                    break;
                case 13:
                    break;
                case 14:
                    $this->phpExcel->getProperties()->setManager($value);
                    break;
                case 15:
                    $this->phpExcel->getProperties()->setCompany($value);
                    break;
                case 16:
                    break;
            }
        }
    }
    /**
     * Reads a general type of BIFF record. Does nothing except for moving stream pointer forward to next record.
     */
    private function readDefault()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $this->pos += 4 + $length;
    }
    /**
     *    The NOTE record specifies a comment associated with a particular cell. In Excel 95 (BIFF7) and earlier versions,
     *        this record stores a note (cell note). This feature was significantly enhanced in Excel 97.
     */
    private function readNote()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->readDataOnly) {
            return NULL;
        }
        $cellAddress = $this->readBIFF8CellAddress(substr($recordData, 0, 4));
        if ($this->version == self::XLS_BIFF8) {
            $noteObjID = self::getInt2d($recordData, 6);
            $noteAuthor = self::readUnicodeStringLong(substr($recordData, 8));
            $noteAuthor = $noteAuthor["value"];
            $this->cellNotes[$noteObjID] = array("cellRef" => $cellAddress, "objectID" => $noteObjID, "author" => $noteAuthor);
        } else {
            $extension = false;
            if ($cellAddress == "\$B\$65536") {
                $row = self::getInt2d($recordData, 0);
                $extension = true;
                $cellAddress = array_pop(array_keys($this->phpSheet->getComments()));
            }
            $cellAddress = str_replace("\$", "", $cellAddress);
            $noteLength = self::getInt2d($recordData, 4);
            $noteText = trim(substr($recordData, 6));
            if ($extension) {
                $comment = $this->phpSheet->getComment($cellAddress);
                $commentText = $comment->getText()->getPlainText();
                $comment->setText($this->parseRichText($commentText . $noteText));
            } else {
                $this->phpSheet->getComment($cellAddress)->setText($this->parseRichText($noteText));
            }
        }
    }
    /**
     *    The TEXT Object record contains the text associated with a cell annotation.
     */
    private function readTextObject()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->readDataOnly) {
            return NULL;
        }
        $grbitOpts = self::getInt2d($recordData, 0);
        $rot = self::getInt2d($recordData, 2);
        $cchText = self::getInt2d($recordData, 10);
        $cbRuns = self::getInt2d($recordData, 12);
        $text = $this->getSplicedRecordData();
        $this->textObjects[$this->textObjRef] = array("text" => substr($text["recordData"], $text["spliceOffsets"][0] + 1, $cchText), "format" => substr($text["recordData"], $text["spliceOffsets"][1], $cbRuns), "alignment" => $grbitOpts, "rotation" => $rot);
    }
    /**
     * Read BOF
     */
    private function readBof()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = substr($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $substreamType = self::getInt2d($recordData, 2);
        switch ($substreamType) {
            case self::XLS_WorkbookGlobals:
                $version = self::getInt2d($recordData, 0);
                if ($version != self::XLS_BIFF8 && $version != self::XLS_BIFF7) {
                    throw new PHPExcel_Reader_Exception("Cannot read this Excel file. Version is too old.");
                }
                $this->version = $version;
                break;
            case self::XLS_Worksheet:
                break;
            default:
                do {
                    $code = self::getInt2d($this->data, $this->pos);
                    $this->readDefault();
                } while ($code != self::XLS_TYPE_EOF && $this->pos < $this->dataSize);
                break;
        }
    }
    /**
     * FILEPASS
     *
     * This record is part of the File Protection Block. It
     * contains information about the read/write password of the
     * file. All record contents following this record will be
     * encrypted.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     *
     * The decryption functions and objects used from here on in
     * are based on the source of Spreadsheet-ParseExcel:
     * http://search.cpan.org/~jmcnamara/Spreadsheet-ParseExcel/
     */
    private function readFilepass()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        if ($length != 54) {
            throw new PHPExcel_Reader_Exception("Unexpected file pass record length");
        }
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->verifyPassword("VelvetSweatshop", substr($recordData, 6, 16), substr($recordData, 22, 16), substr($recordData, 38, 16), $this->md5Ctxt)) {
            throw new PHPExcel_Reader_Exception("Decryption password incorrect");
        }
        $this->encryption = self::MS_BIFF_CRYPTO_RC4;
        $this->encryptionStartPos = $this->pos + self::getInt2d($this->data, $this->pos + 2);
    }
    /**
     * Make an RC4 decryptor for the given block
     *
     * @var int    $block      Block for which to create decrypto
     * @var string $valContext MD5 context state
     *
     * @return PHPExcel_Reader_Excel5_RC4
     */
    private function makeKey($block, $valContext)
    {
        $pwarray = str_repeat("", 64);
        for ($i = 0; $i < 5; $i++) {
            $pwarray[$i] = $valContext[$i];
        }
        $pwarray[5] = chr($block & 255);
        $pwarray[6] = chr($block >> 8 & 255);
        $pwarray[7] = chr($block >> 16 & 255);
        $pwarray[8] = chr($block >> 24 & 255);
        $pwarray[9] = "€";
        $pwarray[56] = "H";
        $md5 = new PHPExcel_Reader_Excel5_MD5();
        $md5->add($pwarray);
        $s = $md5->getContext();
        return new PHPExcel_Reader_Excel5_RC4($s);
    }
    /**
     * Verify RC4 file password
     *
     * @var string $password        Password to check
     * @var string $docid           Document id
     * @var string $salt_data       Salt data
     * @var string $hashedsalt_data Hashed salt data
     * @var string &$valContext     Set to the MD5 context of the value
     *
     * @return bool Success
     */
    private function verifyPassword($password, $docid, $salt_data, $hashedsalt_data, &$valContext)
    {
        $pwarray = str_repeat("", 64);
        for ($i = 0; $i < strlen($password); $i++) {
            $o = ord(substr($password, $i, 1));
            $pwarray[2 * $i] = chr($o & 255);
            $pwarray[2 * $i + 1] = chr($o >> 8 & 255);
        }
        $pwarray[2 * $i] = chr(128);
        $pwarray[56] = chr($i << 4 & 255);
        $md5 = new PHPExcel_Reader_Excel5_MD5();
        $md5->add($pwarray);
        $mdContext1 = $md5->getContext();
        $offset = 0;
        $keyoffset = 0;
        $tocopy = 5;
        $md5->reset();
        while ($offset != 16) {
            if (64 - $offset < 5) {
                $tocopy = 64 - $offset;
            }
            for ($i = 0; $i <= $tocopy; $i++) {
                $pwarray[$offset + $i] = $mdContext1[$keyoffset + $i];
            }
            $offset += $tocopy;
            if ($offset == 64) {
                $md5->add($pwarray);
                $keyoffset = $tocopy;
                $tocopy = 5 - $tocopy;
                $offset = 0;
                continue;
            }
            $keyoffset = 0;
            $tocopy = 5;
            for ($i = 0; $i < 16; $i++) {
                $pwarray[$offset + $i] = $docid[$i];
            }
            $offset += 16;
        }
        $pwarray[16] = "€";
        for ($i = 0; $i < 47; $i++) {
            $pwarray[17 + $i] = "";
        }
        $pwarray[56] = "€";
        $pwarray[57] = "\n";
        $md5->add($pwarray);
        $valContext = $md5->getContext();
        $key = $this->makeKey(0, $valContext);
        $salt = $key->RC4($salt_data);
        $hashedsalt = $key->RC4($hashedsalt_data);
        $salt .= "€" . str_repeat("", 47);
        $salt[56] = "€";
        $md5->reset();
        $md5->add($salt);
        $mdContext2 = $md5->getContext();
        return $mdContext2 == $hashedsalt;
    }
    /**
     * CODEPAGE
     *
     * This record stores the text encoding used to write byte
     * strings, stored as MS Windows code page identifier.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readCodepage()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $codepage = self::getInt2d($recordData, 0);
        $this->codepage = PHPExcel_Shared_CodePage::NumberToName($codepage);
    }
    /**
     * DATEMODE
     *
     * This record specifies the base date for displaying date
     * values. All dates are stored as count of days past this
     * base date. In BIFF2-BIFF4 this record is part of the
     * Calculation Settings Block. In BIFF5-BIFF8 it is
     * stored in the Workbook Globals Substream.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readDateMode()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        PHPExcel_Shared_Date::setExcelCalendar(PHPExcel_Shared_Date::CALENDAR_WINDOWS_1900);
        if (ord($recordData[0]) == 1) {
            PHPExcel_Shared_Date::setExcelCalendar(PHPExcel_Shared_Date::CALENDAR_MAC_1904);
        }
    }
    /**
     * Read a FONT record
     */
    private function readFont()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $objFont = new PHPExcel_Style_Font();
            $size = self::getInt2d($recordData, 0);
            $objFont->setSize($size / 20);
            $isItalic = (2 & self::getInt2d($recordData, 2)) >> 1;
            if ($isItalic) {
                $objFont->setItalic(true);
            }
            $isStrike = (8 & self::getInt2d($recordData, 2)) >> 3;
            if ($isStrike) {
                $objFont->setStrikethrough(true);
            }
            $colorIndex = self::getInt2d($recordData, 4);
            $objFont->colorIndex = $colorIndex;
            $weight = self::getInt2d($recordData, 6);
            switch ($weight) {
                case 700:
                    $objFont->setBold(true);
                    break;
            }
            $escapement = self::getInt2d($recordData, 8);
            switch ($escapement) {
                case 1:
                    $objFont->setSuperScript(true);
                    break;
                case 2:
                    $objFont->setSubScript(true);
                    break;
            }
            $underlineType = ord($recordData[10]);
            switch ($underlineType) {
                case 0:
                    break;
                case 1:
                    $objFont->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
                    break;
                case 2:
                    $objFont->setUnderline(PHPExcel_Style_Font::UNDERLINE_DOUBLE);
                    break;
                case 33:
                    $objFont->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLEACCOUNTING);
                    break;
                case 34:
                    $objFont->setUnderline(PHPExcel_Style_Font::UNDERLINE_DOUBLEACCOUNTING);
                    break;
            }
            if ($this->version == self::XLS_BIFF8) {
                $string = self::readUnicodeStringShort(substr($recordData, 14));
            } else {
                $string = $this->readByteStringShort(substr($recordData, 14));
            }
            $objFont->setName($string["value"]);
            $this->objFonts[] = $objFont;
        }
    }
    /**
     * FORMAT
     *
     * This record contains information about a number format.
     * All FORMAT records occur together in a sequential list.
     *
     * In BIFF2-BIFF4 other records referencing a FORMAT record
     * contain a zero-based index into this list. From BIFF5 on
     * the FORMAT record contains the index itself that will be
     * used by other records.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readFormat()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $indexCode = self::getInt2d($recordData, 0);
            if ($this->version == self::XLS_BIFF8) {
                $string = self::readUnicodeStringLong(substr($recordData, 2));
            } else {
                $string = $this->readByteStringShort(substr($recordData, 2));
            }
            $formatString = $string["value"];
            $this->formats[$indexCode] = $formatString;
        }
    }
    /**
     * XF - Extended Format
     *
     * This record contains formatting information for cells, rows, columns or styles.
     * According to http://support.microsoft.com/kb/147732 there are always at least 15 cell style XF
     * and 1 cell XF.
     * Inspection of Excel files generated by MS Office Excel shows that XF records 0-14 are cell style XF
     * and XF record 15 is a cell XF
     * We only read the first cell style XF and skip the remaining cell style XF records
     * We read all cell XF records.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readXf()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $objStyle = new PHPExcel_Style();
        if (!$this->readDataOnly) {
            if (self::getInt2d($recordData, 0) < 4) {
                $fontIndex = self::getInt2d($recordData, 0);
            } else {
                $fontIndex = self::getInt2d($recordData, 0) - 1;
            }
            $objStyle->setFont($this->objFonts[$fontIndex]);
            $numberFormatIndex = self::getInt2d($recordData, 2);
            if (isset($this->formats[$numberFormatIndex])) {
                $numberformat = array("code" => $this->formats[$numberFormatIndex]);
            } else {
                if (($code = PHPExcel_Style_NumberFormat::builtInFormatCode($numberFormatIndex)) !== "") {
                    $numberformat = array("code" => $code);
                } else {
                    $numberformat = array("code" => "General");
                }
            }
            $objStyle->getNumberFormat()->setFormatCode($numberformat["code"]);
            $xfTypeProt = self::getInt2d($recordData, 4);
            $isLocked = (1 & $xfTypeProt) >> 0;
            $objStyle->getProtection()->setLocked($isLocked ? PHPExcel_Style_Protection::PROTECTION_INHERIT : PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
            $isHidden = (2 & $xfTypeProt) >> 1;
            $objStyle->getProtection()->setHidden($isHidden ? PHPExcel_Style_Protection::PROTECTION_PROTECTED : PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
            $isCellStyleXf = (4 & $xfTypeProt) >> 2;
            $horAlign = (7 & ord($recordData[6])) >> 0;
            switch ($horAlign) {
                case 0:
                    $objStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL);
                    break;
                case 1:
                    $objStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    break;
                case 2:
                    $objStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    break;
                case 3:
                    $objStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    break;
                case 4:
                    $objStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_FILL);
                    break;
                case 5:
                    $objStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
                    break;
                case 6:
                    $objStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS);
                    break;
            }
            $wrapText = (8 & ord($recordData[6])) >> 3;
            switch ($wrapText) {
                case 0:
                    $objStyle->getAlignment()->setWrapText(false);
                    break;
                case 1:
                    $objStyle->getAlignment()->setWrapText(true);
                    break;
            }
            $vertAlign = (112 & ord($recordData[6])) >> 4;
            switch ($vertAlign) {
                case 0:
                    $objStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                    break;
                case 1:
                    $objStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    break;
                case 2:
                    $objStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
                    break;
                case 3:
                    $objStyle->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_JUSTIFY);
                    break;
            }
            if ($this->version == self::XLS_BIFF8) {
                $angle = ord($recordData[7]);
                $rotation = 0;
                if ($angle <= 90) {
                    $rotation = $angle;
                } else {
                    if ($angle <= 180) {
                        $rotation = 90 - $angle;
                    } else {
                        if ($angle == 255) {
                            $rotation = -165;
                        }
                    }
                }
                $objStyle->getAlignment()->setTextRotation($rotation);
                $indent = (15 & ord($recordData[8])) >> 0;
                $objStyle->getAlignment()->setIndent($indent);
                $shrinkToFit = (16 & ord($recordData[8])) >> 4;
                switch ($shrinkToFit) {
                    case 0:
                        $objStyle->getAlignment()->setShrinkToFit(false);
                        break;
                    case 1:
                        $objStyle->getAlignment()->setShrinkToFit(true);
                        break;
                }
                if ($bordersLeftStyle = PHPExcel_Reader_Excel5_Style_Border::lookup((15 & self::getInt4d($recordData, 10)) >> 0)) {
                    $objStyle->getBorders()->getLeft()->setBorderStyle($bordersLeftStyle);
                }
                if ($bordersRightStyle = PHPExcel_Reader_Excel5_Style_Border::lookup((240 & self::getInt4d($recordData, 10)) >> 4)) {
                    $objStyle->getBorders()->getRight()->setBorderStyle($bordersRightStyle);
                }
                if ($bordersTopStyle = PHPExcel_Reader_Excel5_Style_Border::lookup((3840 & self::getInt4d($recordData, 10)) >> 8)) {
                    $objStyle->getBorders()->getTop()->setBorderStyle($bordersTopStyle);
                }
                if ($bordersBottomStyle = PHPExcel_Reader_Excel5_Style_Border::lookup((61440 & self::getInt4d($recordData, 10)) >> 12)) {
                    $objStyle->getBorders()->getBottom()->setBorderStyle($bordersBottomStyle);
                }
                $objStyle->getBorders()->getLeft()->colorIndex = (8323072 & self::getInt4d($recordData, 10)) >> 16;
                $objStyle->getBorders()->getRight()->colorIndex = (1065353216 & self::getInt4d($recordData, 10)) >> 23;
                $diagonalDown = (1073741824 & self::getInt4d($recordData, 10)) >> 30 ? true : false;
                $diagonalUp = (2147483648.0 & self::getInt4d($recordData, 10)) >> 31 ? true : false;
                if ($diagonalUp == false && $diagonalDown == false) {
                    $objStyle->getBorders()->setDiagonalDirection(PHPExcel_Style_Borders::DIAGONAL_NONE);
                } else {
                    if ($diagonalUp == true && $diagonalDown == false) {
                        $objStyle->getBorders()->setDiagonalDirection(PHPExcel_Style_Borders::DIAGONAL_UP);
                    } else {
                        if ($diagonalUp == false && $diagonalDown == true) {
                            $objStyle->getBorders()->setDiagonalDirection(PHPExcel_Style_Borders::DIAGONAL_DOWN);
                        } else {
                            if ($diagonalUp == true && $diagonalDown == true) {
                                $objStyle->getBorders()->setDiagonalDirection(PHPExcel_Style_Borders::DIAGONAL_BOTH);
                            }
                        }
                    }
                }
                $objStyle->getBorders()->getTop()->colorIndex = (127 & self::getInt4d($recordData, 14)) >> 0;
                $objStyle->getBorders()->getBottom()->colorIndex = (16256 & self::getInt4d($recordData, 14)) >> 7;
                $objStyle->getBorders()->getDiagonal()->colorIndex = (2080768 & self::getInt4d($recordData, 14)) >> 14;
                if ($bordersDiagonalStyle = PHPExcel_Reader_Excel5_Style_Border::lookup((31457280 & self::getInt4d($recordData, 14)) >> 21)) {
                    $objStyle->getBorders()->getDiagonal()->setBorderStyle($bordersDiagonalStyle);
                }
                if ($fillType = PHPExcel_Reader_Excel5_Style_FillPattern::lookup((4227858432.0 & self::getInt4d($recordData, 14)) >> 26)) {
                    $objStyle->getFill()->setFillType($fillType);
                }
                $objStyle->getFill()->startcolorIndex = (127 & self::getInt2d($recordData, 18)) >> 0;
                $objStyle->getFill()->endcolorIndex = (16256 & self::getInt2d($recordData, 18)) >> 7;
            } else {
                $orientationAndFlags = ord($recordData[7]);
                $xfOrientation = (3 & $orientationAndFlags) >> 0;
                switch ($xfOrientation) {
                    case 0:
                        $objStyle->getAlignment()->setTextRotation(0);
                        break;
                    case 1:
                        $objStyle->getAlignment()->setTextRotation(-165);
                        break;
                    case 2:
                        $objStyle->getAlignment()->setTextRotation(90);
                        break;
                    case 3:
                        $objStyle->getAlignment()->setTextRotation(-90);
                        break;
                }
                $borderAndBackground = self::getInt4d($recordData, 8);
                $objStyle->getFill()->startcolorIndex = (127 & $borderAndBackground) >> 0;
                $objStyle->getFill()->endcolorIndex = (16256 & $borderAndBackground) >> 7;
                $objStyle->getFill()->setFillType(PHPExcel_Reader_Excel5_Style_FillPattern::lookup((4128768 & $borderAndBackground) >> 16));
                $objStyle->getBorders()->getBottom()->setBorderStyle(PHPExcel_Reader_Excel5_Style_Border::lookup((29360128 & $borderAndBackground) >> 22));
                $objStyle->getBorders()->getBottom()->colorIndex = (4261412864.0 & $borderAndBackground) >> 25;
                $borderLines = self::getInt4d($recordData, 12);
                $objStyle->getBorders()->getTop()->setBorderStyle(PHPExcel_Reader_Excel5_Style_Border::lookup((7 & $borderLines) >> 0));
                $objStyle->getBorders()->getLeft()->setBorderStyle(PHPExcel_Reader_Excel5_Style_Border::lookup((56 & $borderLines) >> 3));
                $objStyle->getBorders()->getRight()->setBorderStyle(PHPExcel_Reader_Excel5_Style_Border::lookup((448 & $borderLines) >> 6));
                $objStyle->getBorders()->getTop()->colorIndex = (65024 & $borderLines) >> 9;
                $objStyle->getBorders()->getLeft()->colorIndex = (8323072 & $borderLines) >> 16;
                $objStyle->getBorders()->getRight()->colorIndex = (1065353216 & $borderLines) >> 23;
            }
            if ($isCellStyleXf) {
                if ($this->xfIndex == 0) {
                    $this->phpExcel->addCellStyleXf($objStyle);
                    $this->mapCellStyleXfIndex[$this->xfIndex] = 0;
                }
            } else {
                $this->phpExcel->addCellXf($objStyle);
                $this->mapCellXfIndex[$this->xfIndex] = count($this->phpExcel->getCellXfCollection()) - 1;
            }
            $this->xfIndex++;
        }
    }
    /**
     *
     */
    private function readXfExt()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $ixfe = self::getInt2d($recordData, 14);
            $cexts = self::getInt2d($recordData, 18);
            $offset = 20;
            while ($offset < $length) {
                $extType = self::getInt2d($recordData, $offset);
                $cb = self::getInt2d($recordData, $offset + 2);
                $extData = substr($recordData, $offset + 4, $cb);
                switch ($extType) {
                    case 4:
                        $xclfType = self::getInt2d($extData, 0);
                        $xclrValue = substr($extData, 4, 4);
                        if ($xclfType == 2) {
                            $rgb = sprintf("%02X%02X%02X", ord($xclrValue[0]), ord($xclrValue[1]), ord($xclrValue[2]));
                            if (isset($this->mapCellXfIndex[$ixfe])) {
                                $fill = $this->phpExcel->getCellXfByIndex($this->mapCellXfIndex[$ixfe])->getFill();
                                $fill->getStartColor()->setRGB($rgb);
                                unset($fill->startcolorIndex);
                            }
                        }
                        break;
                    case 5:
                        $xclfType = self::getInt2d($extData, 0);
                        $xclrValue = substr($extData, 4, 4);
                        if ($xclfType == 2) {
                            $rgb = sprintf("%02X%02X%02X", ord($xclrValue[0]), ord($xclrValue[1]), ord($xclrValue[2]));
                            if (isset($this->mapCellXfIndex[$ixfe])) {
                                $fill = $this->phpExcel->getCellXfByIndex($this->mapCellXfIndex[$ixfe])->getFill();
                                $fill->getEndColor()->setRGB($rgb);
                                unset($fill->endcolorIndex);
                            }
                        }
                        break;
                    case 7:
                        $xclfType = self::getInt2d($extData, 0);
                        $xclrValue = substr($extData, 4, 4);
                        if ($xclfType == 2) {
                            $rgb = sprintf("%02X%02X%02X", ord($xclrValue[0]), ord($xclrValue[1]), ord($xclrValue[2]));
                            if (isset($this->mapCellXfIndex[$ixfe])) {
                                $top = $this->phpExcel->getCellXfByIndex($this->mapCellXfIndex[$ixfe])->getBorders()->getTop();
                                $top->getColor()->setRGB($rgb);
                                unset($top->colorIndex);
                            }
                        }
                        break;
                    case 8:
                        $xclfType = self::getInt2d($extData, 0);
                        $xclrValue = substr($extData, 4, 4);
                        if ($xclfType == 2) {
                            $rgb = sprintf("%02X%02X%02X", ord($xclrValue[0]), ord($xclrValue[1]), ord($xclrValue[2]));
                            if (isset($this->mapCellXfIndex[$ixfe])) {
                                $bottom = $this->phpExcel->getCellXfByIndex($this->mapCellXfIndex[$ixfe])->getBorders()->getBottom();
                                $bottom->getColor()->setRGB($rgb);
                                unset($bottom->colorIndex);
                            }
                        }
                        break;
                    case 9:
                        $xclfType = self::getInt2d($extData, 0);
                        $xclrValue = substr($extData, 4, 4);
                        if ($xclfType == 2) {
                            $rgb = sprintf("%02X%02X%02X", ord($xclrValue[0]), ord($xclrValue[1]), ord($xclrValue[2]));
                            if (isset($this->mapCellXfIndex[$ixfe])) {
                                $left = $this->phpExcel->getCellXfByIndex($this->mapCellXfIndex[$ixfe])->getBorders()->getLeft();
                                $left->getColor()->setRGB($rgb);
                                unset($left->colorIndex);
                            }
                        }
                        break;
                    case 10:
                        $xclfType = self::getInt2d($extData, 0);
                        $xclrValue = substr($extData, 4, 4);
                        if ($xclfType == 2) {
                            $rgb = sprintf("%02X%02X%02X", ord($xclrValue[0]), ord($xclrValue[1]), ord($xclrValue[2]));
                            if (isset($this->mapCellXfIndex[$ixfe])) {
                                $right = $this->phpExcel->getCellXfByIndex($this->mapCellXfIndex[$ixfe])->getBorders()->getRight();
                                $right->getColor()->setRGB($rgb);
                                unset($right->colorIndex);
                            }
                        }
                        break;
                    case 11:
                        $xclfType = self::getInt2d($extData, 0);
                        $xclrValue = substr($extData, 4, 4);
                        if ($xclfType == 2) {
                            $rgb = sprintf("%02X%02X%02X", ord($xclrValue[0]), ord($xclrValue[1]), ord($xclrValue[2]));
                            if (isset($this->mapCellXfIndex[$ixfe])) {
                                $diagonal = $this->phpExcel->getCellXfByIndex($this->mapCellXfIndex[$ixfe])->getBorders()->getDiagonal();
                                $diagonal->getColor()->setRGB($rgb);
                                unset($diagonal->colorIndex);
                            }
                        }
                        break;
                    case 13:
                        $xclfType = self::getInt2d($extData, 0);
                        $xclrValue = substr($extData, 4, 4);
                        if ($xclfType == 2) {
                            $rgb = sprintf("%02X%02X%02X", ord($xclrValue[0]), ord($xclrValue[1]), ord($xclrValue[2]));
                            if (isset($this->mapCellXfIndex[$ixfe])) {
                                $font = $this->phpExcel->getCellXfByIndex($this->mapCellXfIndex[$ixfe])->getFont();
                                $font->getColor()->setRGB($rgb);
                                unset($font->colorIndex);
                            }
                        }
                        break;
                }
                $offset += $cb;
            }
        }
    }
    /**
     * Read STYLE record
     */
    private function readStyle()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $ixfe = self::getInt2d($recordData, 0);
            $xfIndex = (4095 & $ixfe) >> 0;
            $isBuiltIn = (bool) ((32768 & $ixfe) >> 15);
            if ($isBuiltIn) {
                $builtInId = ord($recordData[2]);
                switch ($builtInId) {
                    case 0:
                        break;
                    default:
                        break;
                }
            }
        }
    }
    /**
     * Read PALETTE record
     */
    private function readPalette()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $nm = self::getInt2d($recordData, 0);
            for ($i = 0; $i < $nm; $i++) {
                $rgb = substr($recordData, 2 + 4 * $i, 4);
                $this->palette[] = self::readRGB($rgb);
            }
        }
    }
    /**
     * SHEET
     *
     * This record is  located in the  Workbook Globals
     * Substream  and represents a sheet inside the workbook.
     * One SHEET record is written for each sheet. It stores the
     * sheet name and a stream offset to the BOF record of the
     * respective Sheet Substream within the Workbook Stream.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readSheet()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $rec_offset = self::getInt4d($this->data, $this->pos + 4);
        $this->pos += 4 + $length;
        switch (ord($recordData[4])) {
            case 0:
                $sheetState = PHPExcel_Worksheet::SHEETSTATE_VISIBLE;
                break;
            case 1:
                $sheetState = PHPExcel_Worksheet::SHEETSTATE_HIDDEN;
                break;
            case 2:
                $sheetState = PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN;
                break;
            default:
                $sheetState = PHPExcel_Worksheet::SHEETSTATE_VISIBLE;
                break;
        }
        $sheetType = ord($recordData[5]);
        if ($this->version == self::XLS_BIFF8) {
            $string = self::readUnicodeStringShort(substr($recordData, 6));
            $rec_name = $string["value"];
        } else {
            if ($this->version == self::XLS_BIFF7) {
                $string = $this->readByteStringShort(substr($recordData, 6));
                $rec_name = $string["value"];
            }
        }
        $this->sheets[] = array("name" => $rec_name, "offset" => $rec_offset, "sheetState" => $sheetState, "sheetType" => $sheetType);
    }
    /**
     * Read EXTERNALBOOK record
     */
    private function readExternalBook()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $offset = 0;
        if (4 < strlen($recordData)) {
            $nm = self::getInt2d($recordData, 0);
            $offset += 2;
            $encodedUrlString = self::readUnicodeStringLong(substr($recordData, 2));
            $offset += $encodedUrlString["size"];
            $externalSheetNames = array();
            for ($i = 0; $i < $nm; $i++) {
                $externalSheetNameString = self::readUnicodeStringLong(substr($recordData, $offset));
                $externalSheetNames[] = $externalSheetNameString["value"];
                $offset += $externalSheetNameString["size"];
            }
            $this->externalBooks[] = array("type" => "external", "encodedUrl" => $encodedUrlString["value"], "externalSheetNames" => $externalSheetNames);
        } else {
            if (substr($recordData, 2, 2) == pack("CC", 1, 4)) {
                $this->externalBooks[] = array("type" => "internal");
            } else {
                if (substr($recordData, 0, 4) == pack("vCC", 1, 1, 58)) {
                    $this->externalBooks[] = array("type" => "addInFunction");
                } else {
                    if (substr($recordData, 0, 2) == pack("v", 0)) {
                        $this->externalBooks[] = array("type" => "DDEorOLE");
                    }
                }
            }
        }
    }
    /**
     * Read EXTERNNAME record.
     */
    private function readExternName()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->version == self::XLS_BIFF8) {
            $options = self::getInt2d($recordData, 0);
            $nameString = self::readUnicodeStringShort(substr($recordData, 6));
            $offset = 6 + $nameString["size"];
            $formula = $this->getFormulaFromStructure(substr($recordData, $offset));
            $this->externalNames[] = array("name" => $nameString["value"], "formula" => $formula);
        }
    }
    /**
     * Read EXTERNSHEET record
     */
    private function readExternSheet()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->version == self::XLS_BIFF8) {
            $nm = self::getInt2d($recordData, 0);
            for ($i = 0; $i < $nm; $i++) {
                $this->ref[] = array("externalBookIndex" => self::getInt2d($recordData, 2 + 6 * $i), "firstSheetIndex" => self::getInt2d($recordData, 4 + 6 * $i), "lastSheetIndex" => self::getInt2d($recordData, 6 + 6 * $i));
            }
        }
    }
    /**
     * DEFINEDNAME
     *
     * This record is part of a Link Table. It contains the name
     * and the token array of an internal defined name. Token
     * arrays of defined names contain tokens with aberrant
     * token classes.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readDefinedName()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->version == self::XLS_BIFF8) {
            $opts = self::getInt2d($recordData, 0);
            $isBuiltInName = (32 & $opts) >> 5;
            $nlen = ord($recordData[3]);
            $flen = self::getInt2d($recordData, 4);
            $scope = self::getInt2d($recordData, 8);
            $string = self::readUnicodeString(substr($recordData, 14), $nlen);
            $offset = 14 + $string["size"];
            $formulaStructure = pack("v", $flen) . substr($recordData, $offset);
            try {
                $formula = $this->getFormulaFromStructure($formulaStructure);
            } catch (PHPExcel_Exception $e) {
                $formula = "";
            }
            $this->definedname[] = array("isBuiltInName" => $isBuiltInName, "name" => $string["value"], "formula" => $formula, "scope" => $scope);
        }
    }
    /**
     * Read MSODRAWINGGROUP record
     */
    private function readMsoDrawingGroup()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $splicedRecordData = $this->getSplicedRecordData();
        $recordData = $splicedRecordData["recordData"];
        $this->drawingGroupData .= $recordData;
    }
    /**
     * SST - Shared String Table
     *
     * This record contains a list of all strings used anywhere
     * in the workbook. Each string occurs only once. The
     * workbook uses indexes into the list to reference the
     * strings.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     **/
    private function readSst()
    {
        $pos = 0;
        $splicedRecordData = $this->getSplicedRecordData();
        $recordData = $splicedRecordData["recordData"];
        $spliceOffsets = $splicedRecordData["spliceOffsets"];
        $pos += 4;
        $nm = self::getInt4d($recordData, 4);
        $pos += 4;
        for ($i = 0; $i < $nm; $i++) {
            $numChars = self::getInt2d($recordData, $pos);
            $pos += 2;
            $optionFlags = ord($recordData[$pos]);
            $pos++;
            $isCompressed = ($optionFlags & 1) == 0;
            $hasAsian = ($optionFlags & 4) != 0;
            $hasRichText = ($optionFlags & 8) != 0;
            if ($hasRichText) {
                $formattingRuns = self::getInt2d($recordData, $pos);
                $pos += 2;
            }
            if ($hasAsian) {
                $extendedRunLength = self::getInt4d($recordData, $pos);
                $pos += 4;
            }
            $len = $isCompressed ? $numChars : $numChars * 2;
            foreach ($spliceOffsets as $spliceOffset) {
                if ($pos <= $spliceOffset) {
                    $limitpos = $spliceOffset;
                    break;
                }
            }
            if ($pos + $len <= $limitpos) {
                $retstr = substr($recordData, $pos, $len);
                $pos += $len;
            } else {
                $retstr = substr($recordData, $pos, $limitpos - $pos);
                $bytesRead = $limitpos - $pos;
                $charsLeft = $numChars - ($isCompressed ? $bytesRead : $bytesRead / 2);
                $pos = $limitpos;
                while (0 < $charsLeft) {
                    foreach ($spliceOffsets as $spliceOffset) {
                        if ($pos < $spliceOffset) {
                            $limitpos = $spliceOffset;
                            break;
                        }
                    }
                    $option = ord($recordData[$pos]);
                    $pos++;
                    if ($isCompressed && $option == 0) {
                        $len = min($charsLeft, $limitpos - $pos);
                        $retstr .= substr($recordData, $pos, $len);
                        $charsLeft -= $len;
                        $isCompressed = true;
                    } else {
                        if (!$isCompressed && $option != 0) {
                            $len = min($charsLeft * 2, $limitpos - $pos);
                            $retstr .= substr($recordData, $pos, $len);
                            $charsLeft -= $len / 2;
                            $isCompressed = false;
                        } else {
                            if (!$isCompressed && $option == 0) {
                                $len = min($charsLeft, $limitpos - $pos);
                                for ($j = 0; $j < $len; $j++) {
                                    $retstr .= $recordData[$pos + $j] . chr(0);
                                }
                                $charsLeft -= $len;
                                $isCompressed = false;
                            } else {
                                $newstr = "";
                                for ($j = 0; $j < strlen($retstr); $j++) {
                                    $newstr .= $retstr[$j] . chr(0);
                                }
                                $retstr = $newstr;
                                $len = min($charsLeft * 2, $limitpos - $pos);
                                $retstr .= substr($recordData, $pos, $len);
                                $charsLeft -= $len / 2;
                                $isCompressed = false;
                            }
                        }
                    }
                    $pos += $len;
                }
            }
            $retstr = self::encodeUTF16($retstr, $isCompressed);
            $fmtRuns = array();
            if ($hasRichText) {
                for ($j = 0; $j < $formattingRuns; $j++) {
                    $charPos = self::getInt2d($recordData, $pos + $j * 4);
                    $fontIndex = self::getInt2d($recordData, $pos + 2 + $j * 4);
                    $fmtRuns[] = array("charPos" => $charPos, "fontIndex" => $fontIndex);
                }
                $pos += 4 * $formattingRuns;
            }
            if ($hasAsian) {
                $pos += $extendedRunLength;
            }
            $this->sst[] = array("value" => $retstr, "fmtRuns" => $fmtRuns);
        }
    }
    /**
     * Read PRINTGRIDLINES record
     */
    private function readPrintGridlines()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->version == self::XLS_BIFF8 && !$this->readDataOnly) {
            $printGridlines = (bool) self::getInt2d($recordData, 0);
            $this->phpSheet->setPrintGridlines($printGridlines);
        }
    }
    /**
     * Read DEFAULTROWHEIGHT record
     */
    private function readDefaultRowHeight()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $height = self::getInt2d($recordData, 2);
        $this->phpSheet->getDefaultRowDimension()->setRowHeight($height / 20);
    }
    /**
     * Read SHEETPR record
     */
    private function readSheetPr()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $isSummaryBelow = (64 & self::getInt2d($recordData, 0)) >> 6;
        $this->phpSheet->setShowSummaryBelow($isSummaryBelow);
        $isSummaryRight = (128 & self::getInt2d($recordData, 0)) >> 7;
        $this->phpSheet->setShowSummaryRight($isSummaryRight);
        $this->isFitToPages = (bool) ((256 & self::getInt2d($recordData, 0)) >> 8);
    }
    /**
     * Read HORIZONTALPAGEBREAKS record
     */
    private function readHorizontalPageBreaks()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->version == self::XLS_BIFF8 && !$this->readDataOnly) {
            $nm = self::getInt2d($recordData, 0);
            for ($i = 0; $i < $nm; $i++) {
                $r = self::getInt2d($recordData, 2 + 6 * $i);
                $cf = self::getInt2d($recordData, 2 + 6 * $i + 2);
                $cl = self::getInt2d($recordData, 2 + 6 * $i + 4);
                $this->phpSheet->setBreakByColumnAndRow($cf, $r, PHPExcel_Worksheet::BREAK_ROW);
            }
        }
    }
    /**
     * Read VERTICALPAGEBREAKS record
     */
    private function readVerticalPageBreaks()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->version == self::XLS_BIFF8 && !$this->readDataOnly) {
            $nm = self::getInt2d($recordData, 0);
            for ($i = 0; $i < $nm; $i++) {
                $c = self::getInt2d($recordData, 2 + 6 * $i);
                $rf = self::getInt2d($recordData, 2 + 6 * $i + 2);
                $rl = self::getInt2d($recordData, 2 + 6 * $i + 4);
                $this->phpSheet->setBreakByColumnAndRow($c, $rf, PHPExcel_Worksheet::BREAK_COLUMN);
            }
        }
    }
    /**
     * Read HEADER record
     */
    private function readHeader()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly && $recordData) {
            if ($this->version == self::XLS_BIFF8) {
                $string = self::readUnicodeStringLong($recordData);
            } else {
                $string = $this->readByteStringShort($recordData);
            }
            $this->phpSheet->getHeaderFooter()->setOddHeader($string["value"]);
            $this->phpSheet->getHeaderFooter()->setEvenHeader($string["value"]);
        }
    }
    /**
     * Read FOOTER record
     */
    private function readFooter()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly && $recordData) {
            if ($this->version == self::XLS_BIFF8) {
                $string = self::readUnicodeStringLong($recordData);
            } else {
                $string = $this->readByteStringShort($recordData);
            }
            $this->phpSheet->getHeaderFooter()->setOddFooter($string["value"]);
            $this->phpSheet->getHeaderFooter()->setEvenFooter($string["value"]);
        }
    }
    /**
     * Read HCENTER record
     */
    private function readHcenter()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $isHorizontalCentered = (bool) self::getInt2d($recordData, 0);
            $this->phpSheet->getPageSetup()->setHorizontalCentered($isHorizontalCentered);
        }
    }
    /**
     * Read VCENTER record
     */
    private function readVcenter()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $isVerticalCentered = (bool) self::getInt2d($recordData, 0);
            $this->phpSheet->getPageSetup()->setVerticalCentered($isVerticalCentered);
        }
    }
    /**
     * Read LEFTMARGIN record
     */
    private function readLeftMargin()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $this->phpSheet->getPageMargins()->setLeft(self::extractNumber($recordData));
        }
    }
    /**
     * Read RIGHTMARGIN record
     */
    private function readRightMargin()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $this->phpSheet->getPageMargins()->setRight(self::extractNumber($recordData));
        }
    }
    /**
     * Read TOPMARGIN record
     */
    private function readTopMargin()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $this->phpSheet->getPageMargins()->setTop(self::extractNumber($recordData));
        }
    }
    /**
     * Read BOTTOMMARGIN record
     */
    private function readBottomMargin()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $this->phpSheet->getPageMargins()->setBottom(self::extractNumber($recordData));
        }
    }
    /**
     * Read PAGESETUP record
     */
    private function readPageSetup()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $paperSize = self::getInt2d($recordData, 0);
            $scale = self::getInt2d($recordData, 2);
            $fitToWidth = self::getInt2d($recordData, 6);
            $fitToHeight = self::getInt2d($recordData, 8);
            $isPortrait = (2 & self::getInt2d($recordData, 10)) >> 1;
            $isNotInit = (4 & self::getInt2d($recordData, 10)) >> 2;
            if (!$isNotInit) {
                $this->phpSheet->getPageSetup()->setPaperSize($paperSize);
                switch ($isPortrait) {
                    case 0:
                        $this->phpSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        break;
                    case 1:
                        $this->phpSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
                        break;
                }
                $this->phpSheet->getPageSetup()->setScale($scale, false);
                $this->phpSheet->getPageSetup()->setFitToPage((bool) $this->isFitToPages);
                $this->phpSheet->getPageSetup()->setFitToWidth($fitToWidth, false);
                $this->phpSheet->getPageSetup()->setFitToHeight($fitToHeight, false);
            }
            $marginHeader = self::extractNumber(substr($recordData, 16, 8));
            $this->phpSheet->getPageMargins()->setHeader($marginHeader);
            $marginFooter = self::extractNumber(substr($recordData, 24, 8));
            $this->phpSheet->getPageMargins()->setFooter($marginFooter);
        }
    }
    /**
     * PROTECT - Sheet protection (BIFF2 through BIFF8)
     *   if this record is omitted, then it also means no sheet protection
     */
    private function readProtect()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->readDataOnly) {
            return NULL;
        }
        $bool = (1 & self::getInt2d($recordData, 0)) >> 0;
        $this->phpSheet->getProtection()->setSheet((bool) $bool);
    }
    /**
     * SCENPROTECT
     */
    private function readScenProtect()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->readDataOnly) {
            return NULL;
        }
        $bool = (1 & self::getInt2d($recordData, 0)) >> 0;
        $this->phpSheet->getProtection()->setScenarios((bool) $bool);
    }
    /**
     * OBJECTPROTECT
     */
    private function readObjectProtect()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->readDataOnly) {
            return NULL;
        }
        $bool = (1 & self::getInt2d($recordData, 0)) >> 0;
        $this->phpSheet->getProtection()->setObjects((bool) $bool);
    }
    /**
     * PASSWORD - Sheet protection (hashed) password (BIFF2 through BIFF8)
     */
    private function readPassword()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $password = strtoupper(dechex(self::getInt2d($recordData, 0)));
            $this->phpSheet->getProtection()->setPassword($password, true);
        }
    }
    /**
     * Read DEFCOLWIDTH record
     */
    private function readDefColWidth()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $width = self::getInt2d($recordData, 0);
        if ($width != 8) {
            $this->phpSheet->getDefaultColumnDimension()->setWidth($width);
        }
    }
    /**
     * Read COLINFO record
     */
    private function readColInfo()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $fc = self::getInt2d($recordData, 0);
            $lc = self::getInt2d($recordData, 2);
            $width = self::getInt2d($recordData, 4);
            $xfIndex = self::getInt2d($recordData, 6);
            $isHidden = (1 & self::getInt2d($recordData, 8)) >> 0;
            $level = (1792 & self::getInt2d($recordData, 8)) >> 8;
            $isCollapsed = (4096 & self::getInt2d($recordData, 8)) >> 12;
            for ($i = $fc; $i <= $lc; $i++) {
                if ($lc == 255 || $lc == 256) {
                    $this->phpSheet->getDefaultColumnDimension()->setWidth($width / 256);
                    break;
                }
                $this->phpSheet->getColumnDimensionByColumn($i)->setWidth($width / 256);
                $this->phpSheet->getColumnDimensionByColumn($i)->setVisible(!$isHidden);
                $this->phpSheet->getColumnDimensionByColumn($i)->setOutlineLevel($level);
                $this->phpSheet->getColumnDimensionByColumn($i)->setCollapsed($isCollapsed);
                $this->phpSheet->getColumnDimensionByColumn($i)->setXfIndex($this->mapCellXfIndex[$xfIndex]);
            }
        }
    }
    /**
     * ROW
     *
     * This record contains the properties of a single row in a
     * sheet. Rows and cells in a sheet are divided into blocks
     * of 32 rows.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readRow()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $r = self::getInt2d($recordData, 0);
            $height = (32767 & self::getInt2d($recordData, 6)) >> 0;
            $useDefaultHeight = (32768 & self::getInt2d($recordData, 6)) >> 15;
            if (!$useDefaultHeight) {
                $this->phpSheet->getRowDimension($r + 1)->setRowHeight($height / 20);
            }
            $level = (7 & self::getInt4d($recordData, 12)) >> 0;
            $this->phpSheet->getRowDimension($r + 1)->setOutlineLevel($level);
            $isCollapsed = (16 & self::getInt4d($recordData, 12)) >> 4;
            $this->phpSheet->getRowDimension($r + 1)->setCollapsed($isCollapsed);
            $isHidden = (32 & self::getInt4d($recordData, 12)) >> 5;
            $this->phpSheet->getRowDimension($r + 1)->setVisible(!$isHidden);
            $hasExplicitFormat = (128 & self::getInt4d($recordData, 12)) >> 7;
            $xfIndex = (268369920 & self::getInt4d($recordData, 12)) >> 16;
            if ($hasExplicitFormat) {
                $this->phpSheet->getRowDimension($r + 1)->setXfIndex($this->mapCellXfIndex[$xfIndex]);
            }
        }
    }
    /**
     * Read RK record
     * This record represents a cell that contains an RK value
     * (encoded integer or floating-point value). If a
     * floating-point value cannot be encoded to an RK value,
     * a NUMBER record will be written. This record replaces the
     * record INTEGER written in BIFF2.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readRk()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $column = self::getInt2d($recordData, 2);
        $columnString = PHPExcel_Cell::stringFromColumnIndex($column);
        if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
            $xfIndex = self::getInt2d($recordData, 4);
            $rknum = self::getInt4d($recordData, 6);
            $numValue = self::getIEEE754($rknum);
            $cell = $this->phpSheet->getCell($columnString . ($row + 1));
            if (!$this->readDataOnly) {
                $cell->setXfIndex($this->mapCellXfIndex[$xfIndex]);
            }
            $cell->setValueExplicit($numValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        }
    }
    /**
     * Read LABELSST record
     * This record represents a cell that contains a string. It
     * replaces the LABEL record and RSTRING record used in
     * BIFF2-BIFF5.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readLabelSst()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $column = self::getInt2d($recordData, 2);
        $columnString = PHPExcel_Cell::stringFromColumnIndex($column);
        $emptyCell = true;
        if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
            $xfIndex = self::getInt2d($recordData, 4);
            $index = self::getInt4d($recordData, 6);
            if (($fmtRuns = $this->sst[$index]["fmtRuns"]) && !$this->readDataOnly) {
                $richText = new PHPExcel_RichText();
                $charPos = 0;
                $sstCount = count($this->sst[$index]["fmtRuns"]);
                for ($i = 0; $i <= $sstCount; $i++) {
                    if (isset($fmtRuns[$i])) {
                        $text = PHPExcel_Shared_String::Substring($this->sst[$index]["value"], $charPos, $fmtRuns[$i]["charPos"] - $charPos);
                        $charPos = $fmtRuns[$i]["charPos"];
                    } else {
                        $text = PHPExcel_Shared_String::Substring($this->sst[$index]["value"], $charPos, PHPExcel_Shared_String::CountCharacters($this->sst[$index]["value"]));
                    }
                    if (0 < PHPExcel_Shared_String::CountCharacters($text)) {
                        if ($i == 0) {
                            $richText->createText($text);
                        } else {
                            $textRun = $richText->createTextRun($text);
                            if (isset($fmtRuns[$i - 1])) {
                                if ($fmtRuns[$i - 1]["fontIndex"] < 4) {
                                    $fontIndex = $fmtRuns[$i - 1]["fontIndex"];
                                } else {
                                    $fontIndex = $fmtRuns[$i - 1]["fontIndex"] - 1;
                                }
                                $textRun->setFont(clone $this->objFonts[$fontIndex]);
                            }
                        }
                    }
                }
                if ($this->readEmptyCells || trim($richText->getPlainText()) !== "") {
                    $cell = $this->phpSheet->getCell($columnString . ($row + 1));
                    $cell->setValueExplicit($richText, PHPExcel_Cell_DataType::TYPE_STRING);
                    $emptyCell = false;
                }
            } else {
                if ($this->readEmptyCells || trim($this->sst[$index]["value"]) !== "") {
                    $cell = $this->phpSheet->getCell($columnString . ($row + 1));
                    $cell->setValueExplicit($this->sst[$index]["value"], PHPExcel_Cell_DataType::TYPE_STRING);
                    $emptyCell = false;
                }
            }
            if (!$this->readDataOnly && !$emptyCell) {
                $cell->setXfIndex($this->mapCellXfIndex[$xfIndex]);
            }
        }
    }
    /**
     * Read MULRK record
     * This record represents a cell range containing RK value
     * cells. All cells are located in the same row.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readMulRk()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $colFirst = self::getInt2d($recordData, 2);
        $colLast = self::getInt2d($recordData, $length - 2);
        $columns = $colLast - $colFirst + 1;
        $offset = 4;
        for ($i = 0; $i < $columns; $i++) {
            $columnString = PHPExcel_Cell::stringFromColumnIndex($colFirst + $i);
            if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
                $xfIndex = self::getInt2d($recordData, $offset);
                $numValue = self::getIEEE754(self::getInt4d($recordData, $offset + 2));
                $cell = $this->phpSheet->getCell($columnString . ($row + 1));
                if (!$this->readDataOnly) {
                    $cell->setXfIndex($this->mapCellXfIndex[$xfIndex]);
                }
                $cell->setValueExplicit($numValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            }
            $offset += 6;
        }
    }
    /**
     * Read NUMBER record
     * This record represents a cell that contains a
     * floating-point value.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readNumber()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $column = self::getInt2d($recordData, 2);
        $columnString = PHPExcel_Cell::stringFromColumnIndex($column);
        if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
            $xfIndex = self::getInt2d($recordData, 4);
            $numValue = self::extractNumber(substr($recordData, 6, 8));
            $cell = $this->phpSheet->getCell($columnString . ($row + 1));
            if (!$this->readDataOnly) {
                $cell->setXfIndex($this->mapCellXfIndex[$xfIndex]);
            }
            $cell->setValueExplicit($numValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        }
    }
    /**
     * Read FORMULA record + perhaps a following STRING record if formula result is a string
     * This record contains the token array and the result of a
     * formula cell.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readFormula()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $column = self::getInt2d($recordData, 2);
        $columnString = PHPExcel_Cell::stringFromColumnIndex($column);
        $formulaStructure = substr($recordData, 20);
        $options = self::getInt2d($recordData, 14);
        $isPartOfSharedFormula = (bool) (8 & $options);
        $isPartOfSharedFormula = $isPartOfSharedFormula && ord($formulaStructure[2]) == 1;
        if ($isPartOfSharedFormula) {
            $baseRow = self::getInt2d($formulaStructure, 3);
            $baseCol = self::getInt2d($formulaStructure, 5);
            $this->_baseCell = PHPExcel_Cell::stringFromColumnIndex($baseCol) . ($baseRow + 1);
        }
        if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
            if ($isPartOfSharedFormula) {
                $this->sharedFormulaParts[$columnString . ($row + 1)] = $this->_baseCell;
            }
            $xfIndex = self::getInt2d($recordData, 4);
            if (ord($recordData[6]) == 0 && ord($recordData[12]) == 255 && ord($recordData[13]) == 255) {
                $dataType = PHPExcel_Cell_DataType::TYPE_STRING;
                $code = self::getInt2d($this->data, $this->pos);
                if ($code == self::XLS_TYPE_SHAREDFMLA) {
                    $this->readSharedFmla();
                }
                $value = $this->readString();
            } else {
                if (ord($recordData[6]) == 1 && ord($recordData[12]) == 255 && ord($recordData[13]) == 255) {
                    $dataType = PHPExcel_Cell_DataType::TYPE_BOOL;
                    $value = (bool) ord($recordData[8]);
                } else {
                    if (ord($recordData[6]) == 2 && ord($recordData[12]) == 255 && ord($recordData[13]) == 255) {
                        $dataType = PHPExcel_Cell_DataType::TYPE_ERROR;
                        $value = PHPExcel_Reader_Excel5_ErrorCode::lookup(ord($recordData[8]));
                    } else {
                        if (ord($recordData[6]) == 3 && ord($recordData[12]) == 255 && ord($recordData[13]) == 255) {
                            $dataType = PHPExcel_Cell_DataType::TYPE_NULL;
                            $value = "";
                        } else {
                            $dataType = PHPExcel_Cell_DataType::TYPE_NUMERIC;
                            $value = self::extractNumber(substr($recordData, 6, 8));
                        }
                    }
                }
            }
            $cell = $this->phpSheet->getCell($columnString . ($row + 1));
            if (!$this->readDataOnly) {
                $cell->setXfIndex($this->mapCellXfIndex[$xfIndex]);
            }
            if (!$isPartOfSharedFormula) {
                try {
                    if ($this->version != self::XLS_BIFF8) {
                        throw new PHPExcel_Reader_Exception("Not BIFF8. Can only read BIFF8 formulas");
                    }
                    $formula = $this->getFormulaFromStructure($formulaStructure);
                    $cell->setValueExplicit("=" . $formula, PHPExcel_Cell_DataType::TYPE_FORMULA);
                } catch (PHPExcel_Exception $e) {
                    $cell->setValueExplicit($value, $dataType);
                }
            } else {
                if ($this->version != self::XLS_BIFF8) {
                    $cell->setValueExplicit($value, $dataType);
                }
            }
            $cell->setCalculatedValue($value);
        }
    }
    /**
     * Read a SHAREDFMLA record. This function just stores the binary shared formula in the reader,
     * which usually contains relative references.
     * These will be used to construct the formula in each shared formula part after the sheet is read.
     */
    private function readSharedFmla()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $cellRange = substr($recordData, 0, 6);
        $cellRange = $this->readBIFF5CellRangeAddressFixed($cellRange);
        $no = ord($recordData[7]);
        $formula = substr($recordData, 8);
        $this->sharedFormulas[$this->_baseCell] = $formula;
    }
    /**
     * Read a STRING record from current stream position and advance the stream pointer to next record
     * This record is used for storing result from FORMULA record when it is a string, and
     * it occurs directly after the FORMULA record
     *
     * @return string The string contents as UTF-8
     */
    private function readString()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->version == self::XLS_BIFF8) {
            $string = self::readUnicodeStringLong($recordData);
            $value = $string["value"];
        } else {
            $string = $this->readByteStringLong($recordData);
            $value = $string["value"];
        }
        return $value;
    }
    /**
     * Read BOOLERR record
     * This record represents a Boolean value or error value
     * cell.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readBoolErr()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $column = self::getInt2d($recordData, 2);
        $columnString = PHPExcel_Cell::stringFromColumnIndex($column);
        if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
            $xfIndex = self::getInt2d($recordData, 4);
            $boolErr = ord($recordData[6]);
            $isError = ord($recordData[7]);
            $cell = $this->phpSheet->getCell($columnString . ($row + 1));
            switch ($isError) {
                case 0:
                    $value = (bool) $boolErr;
                    $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_BOOL);
                    break;
                case 1:
                    $value = PHPExcel_Reader_Excel5_ErrorCode::lookup($boolErr);
                    $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_ERROR);
                    break;
            }
            if (!$this->readDataOnly) {
                $cell->setXfIndex($this->mapCellXfIndex[$xfIndex]);
            }
        }
    }
    /**
     * Read MULBLANK record
     * This record represents a cell range of empty cells. All
     * cells are located in the same row
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readMulBlank()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $fc = self::getInt2d($recordData, 2);
        if (!$this->readDataOnly && $this->readEmptyCells) {
            for ($i = 0; $i < $length / 2 - 3; $i++) {
                $columnString = PHPExcel_Cell::stringFromColumnIndex($fc + $i);
                if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
                    $xfIndex = self::getInt2d($recordData, 4 + 2 * $i);
                    $this->phpSheet->getCell($columnString . ($row + 1))->setXfIndex($this->mapCellXfIndex[$xfIndex]);
                }
            }
        }
    }
    /**
     * Read LABEL record
     * This record represents a cell that contains a string. In
     * BIFF8 it is usually replaced by the LABELSST record.
     * Excel still uses this record, if it copies unformatted
     * text cells to the clipboard.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readLabel()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $column = self::getInt2d($recordData, 2);
        $columnString = PHPExcel_Cell::stringFromColumnIndex($column);
        if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
            $xfIndex = self::getInt2d($recordData, 4);
            if ($this->version == self::XLS_BIFF8) {
                $string = self::readUnicodeStringLong(substr($recordData, 6));
                $value = $string["value"];
            } else {
                $string = $this->readByteStringLong(substr($recordData, 6));
                $value = $string["value"];
            }
            if ($this->readEmptyCells || trim($value) !== "") {
                $cell = $this->phpSheet->getCell($columnString . ($row + 1));
                $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
                if (!$this->readDataOnly) {
                    $cell->setXfIndex($this->mapCellXfIndex[$xfIndex]);
                }
            }
        }
    }
    /**
     * Read BLANK record
     */
    private function readBlank()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $row = self::getInt2d($recordData, 0);
        $col = self::getInt2d($recordData, 2);
        $columnString = PHPExcel_Cell::stringFromColumnIndex($col);
        if ($this->getReadFilter() !== NULL && $this->getReadFilter()->readCell($columnString, $row + 1, $this->phpSheet->getTitle())) {
            $xfIndex = self::getInt2d($recordData, 4);
            if (!$this->readDataOnly && $this->readEmptyCells) {
                $this->phpSheet->getCell($columnString . ($row + 1))->setXfIndex($this->mapCellXfIndex[$xfIndex]);
            }
        }
    }
    /**
     * Read MSODRAWING record
     */
    private function readMsoDrawing()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $splicedRecordData = $this->getSplicedRecordData();
        $recordData = $splicedRecordData["recordData"];
        $this->drawingData .= $recordData;
    }
    /**
     * Read OBJ record
     */
    private function readObj()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->readDataOnly || $this->version != self::XLS_BIFF8) {
            return NULL;
        }
        $ftCmoType = self::getInt2d($recordData, 0);
        $cbCmoSize = self::getInt2d($recordData, 2);
        $otObjType = self::getInt2d($recordData, 4);
        $idObjID = self::getInt2d($recordData, 6);
        $grbitOpts = self::getInt2d($recordData, 6);
        $this->objs[] = array("ftCmoType" => $ftCmoType, "cbCmoSize" => $cbCmoSize, "otObjType" => $otObjType, "idObjID" => $idObjID, "grbitOpts" => $grbitOpts);
        $this->textObjRef = $idObjID;
    }
    /**
     * Read WINDOW2 record
     */
    private function readWindow2()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $options = self::getInt2d($recordData, 0);
        $firstVisibleRow = self::getInt2d($recordData, 2);
        $firstVisibleColumn = self::getInt2d($recordData, 4);
        if ($this->version === self::XLS_BIFF8) {
            $zoomscaleInPageBreakPreview = self::getInt2d($recordData, 10);
            if ($zoomscaleInPageBreakPreview === 0) {
                $zoomscaleInPageBreakPreview = 60;
            }
            $zoomscaleInNormalView = self::getInt2d($recordData, 12);
            if ($zoomscaleInNormalView === 0) {
                $zoomscaleInNormalView = 100;
            }
        }
        $showGridlines = (bool) ((2 & $options) >> 1);
        $this->phpSheet->setShowGridlines($showGridlines);
        $showRowColHeaders = (bool) ((4 & $options) >> 2);
        $this->phpSheet->setShowRowColHeaders($showRowColHeaders);
        $this->frozen = (bool) ((8 & $options) >> 3);
        $this->phpSheet->setRightToLeft((bool) ((64 & $options) >> 6));
        $isActive = (bool) ((1024 & $options) >> 10);
        if ($isActive) {
            $this->phpExcel->setActiveSheetIndex($this->phpExcel->getIndex($this->phpSheet));
        }
        $isPageBreakPreview = (bool) ((2048 & $options) >> 11);
        if ($this->phpSheet->getSheetView()->getView() !== PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_LAYOUT) {
            $view = $isPageBreakPreview ? PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_BREAK_PREVIEW : PHPExcel_Worksheet_SheetView::SHEETVIEW_NORMAL;
            $this->phpSheet->getSheetView()->setView($view);
            if ($this->version === self::XLS_BIFF8) {
                $zoomScale = $isPageBreakPreview ? $zoomscaleInPageBreakPreview : $zoomscaleInNormalView;
                $this->phpSheet->getSheetView()->setZoomScale($zoomScale);
                $this->phpSheet->getSheetView()->setZoomScaleNormal($zoomscaleInNormalView);
            }
        }
    }
    /**
     * Read PLV Record(Created by Excel2007 or upper)
     */
    private function readPageLayoutView()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $rt = self::getInt2d($recordData, 0);
        $grbitFrt = self::getInt2d($recordData, 2);
        $wScalePLV = self::getInt2d($recordData, 12);
        $grbit = self::getInt2d($recordData, 14);
        $fPageLayoutView = $grbit & 1;
        $fRulerVisible = $grbit >> 1 & 1;
        $fWhitespaceHidden = $grbit >> 3 & 1;
        if ($fPageLayoutView === 1) {
            $this->phpSheet->getSheetView()->setView(PHPExcel_Worksheet_SheetView::SHEETVIEW_PAGE_LAYOUT);
            $this->phpSheet->getSheetView()->setZoomScale($wScalePLV);
        }
    }
    /**
     * Read SCL record
     */
    private function readScl()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $numerator = self::getInt2d($recordData, 0);
        $denumerator = self::getInt2d($recordData, 2);
        $this->phpSheet->getSheetView()->setZoomScale($numerator * 100 / $denumerator);
    }
    /**
     * Read PANE record
     */
    private function readPane()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $px = self::getInt2d($recordData, 0);
            $py = self::getInt2d($recordData, 2);
            if ($this->frozen) {
                $this->phpSheet->freezePane(PHPExcel_Cell::stringFromColumnIndex($px) . ($py + 1));
            }
        }
    }
    /**
     * Read SELECTION record. There is one such record for each pane in the sheet.
     */
    private function readSelection()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            $paneId = ord($recordData[0]);
            $r = self::getInt2d($recordData, 1);
            $c = self::getInt2d($recordData, 3);
            $index = self::getInt2d($recordData, 5);
            $data = substr($recordData, 7);
            $cellRangeAddressList = $this->readBIFF5CellRangeAddressList($data);
            $selectedCells = $cellRangeAddressList["cellRangeAddresses"][0];
            if (preg_match("/^([A-Z]+1\\:[A-Z]+)16384\$/", $selectedCells)) {
                $selectedCells = preg_replace("/^([A-Z]+1\\:[A-Z]+)16384\$/", "\${1}1048576", $selectedCells);
            }
            if (preg_match("/^([A-Z]+1\\:[A-Z]+)65536\$/", $selectedCells)) {
                $selectedCells = preg_replace("/^([A-Z]+1\\:[A-Z]+)65536\$/", "\${1}1048576", $selectedCells);
            }
            if (preg_match("/^(A[0-9]+\\:)IV([0-9]+)\$/", $selectedCells)) {
                $selectedCells = preg_replace("/^(A[0-9]+\\:)IV([0-9]+)\$/", "\${1}XFD\${2}", $selectedCells);
            }
            $this->phpSheet->setSelectedCells($selectedCells);
        }
    }
    private function includeCellRangeFiltered($cellRangeAddress)
    {
        $includeCellRange = true;
        if ($this->getReadFilter() !== NULL) {
            $includeCellRange = false;
            $rangeBoundaries = PHPExcel_Cell::getRangeBoundaries($cellRangeAddress);
            $rangeBoundaries[1][0]++;
            for ($row = $rangeBoundaries[0][1]; $row <= $rangeBoundaries[1][1]; $row++) {
                for ($column = $rangeBoundaries[0][0]; $column != $rangeBoundaries[1][0]; $column++) {
                    if ($this->getReadFilter()->readCell($column, $row, $this->phpSheet->getTitle())) {
                        $includeCellRange = true;
                        break 2;
                    }
                }
            }
        }
        return $includeCellRange;
    }
    /**
     * MERGEDCELLS
     *
     * This record contains the addresses of merged cell ranges
     * in the current sheet.
     *
     * --    "OpenOffice.org's Documentation of the Microsoft
     *         Excel File Format"
     */
    private function readMergedCells()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->version == self::XLS_BIFF8 && !$this->readDataOnly) {
            $cellRangeAddressList = $this->readBIFF8CellRangeAddressList($recordData);
            foreach ($cellRangeAddressList["cellRangeAddresses"] as $cellRangeAddress) {
                if (strpos($cellRangeAddress, ":") !== false && $this->includeCellRangeFiltered($cellRangeAddress)) {
                    $this->phpSheet->mergeCells($cellRangeAddress);
                }
            }
        }
    }
    /**
     * Read HYPERLINK record
     */
    private function readHyperLink()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if (!$this->readDataOnly) {
            try {
                $cellRange = $this->readBIFF8CellRangeAddressFixed($recordData, 0, 8);
            } catch (PHPExcel_Exception $e) {
                return NULL;
            }
            $isFileLinkOrUrl = (1 & self::getInt2d($recordData, 28)) >> 0;
            $isAbsPathOrUrl = (1 & self::getInt2d($recordData, 28)) >> 1;
            $hasDesc = (20 & self::getInt2d($recordData, 28)) >> 2;
            $hasText = (8 & self::getInt2d($recordData, 28)) >> 3;
            $hasFrame = (128 & self::getInt2d($recordData, 28)) >> 7;
            $isUNC = (256 & self::getInt2d($recordData, 28)) >> 8;
            $offset = 32;
            if ($hasDesc) {
                $dl = self::getInt4d($recordData, 32);
                $desc = self::encodeUTF16(substr($recordData, 36, 2 * ($dl - 1)), false);
                $offset += 4 + 2 * $dl;
            }
            if ($hasFrame) {
                $fl = self::getInt4d($recordData, $offset);
                $offset += 4 + 2 * $fl;
            }
            $hyperlinkType = NULL;
            if ($isUNC) {
                $hyperlinkType = "UNC";
            } else {
                if (!$isFileLinkOrUrl) {
                    $hyperlinkType = "workbook";
                } else {
                    if (ord($recordData[$offset]) == 3) {
                        $hyperlinkType = "local";
                    } else {
                        if (ord($recordData[$offset]) == 224) {
                            $hyperlinkType = "URL";
                        }
                    }
                }
            }
            switch ($hyperlinkType) {
                case "URL":
                    $offset += 16;
                    $us = self::getInt4d($recordData, $offset);
                    $offset += 4;
                    $url = self::encodeUTF16(substr($recordData, $offset, $us - 2), false);
                    $nullOffset = strpos($url, 0);
                    if ($nullOffset) {
                        $url = substr($url, 0, $nullOffset);
                    }
                    $url .= $hasText ? "#" : "";
                    $offset += $us;
                    break;
                case "local":
                    $offset += 16;
                    $upLevelCount = self::getInt2d($recordData, $offset);
                    $offset += 2;
                    $sl = self::getInt4d($recordData, $offset);
                    $offset += 4;
                    $shortenedFilePath = substr($recordData, $offset, $sl);
                    $shortenedFilePath = self::encodeUTF16($shortenedFilePath, true);
                    $shortenedFilePath = substr($shortenedFilePath, 0, -1);
                    $offset += $sl;
                    $offset += 24;
                    $sz = self::getInt4d($recordData, $offset);
                    $offset += 4;
                    if (0 < $sz) {
                        $xl = self::getInt4d($recordData, $offset);
                        $offset += 4;
                        $offset += 2;
                        $extendedFilePath = substr($recordData, $offset, $xl);
                        $extendedFilePath = self::encodeUTF16($extendedFilePath, false);
                        $offset += $xl;
                    }
                    $url = str_repeat("..\\", $upLevelCount);
                    $url .= 0 < $sz ? $extendedFilePath : $shortenedFilePath;
                    $url .= $hasText ? "#" : "";
                    break;
                case "UNC":
                    return NULL;
                case "workbook":
                    $url = "sheet://";
                    break;
                default:
                    return NULL;
            }
            if ($hasText) {
                $tl = self::getInt4d($recordData, $offset);
                $offset += 4;
                $text = self::encodeUTF16(substr($recordData, $offset, 2 * ($tl - 1)), false);
                $url .= $text;
            }
            foreach (PHPExcel_Cell::extractAllCellReferencesInRange($cellRange) as $coordinate) {
                $this->phpSheet->getCell($coordinate)->getHyperLink()->setUrl($url);
            }
        }
    }
    /**
     * Read DATAVALIDATIONS record
     */
    private function readDataValidations()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
    }
    /**
     * Read DATAVALIDATION record
     */
    private function readDataValidation()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->readDataOnly) {
            return NULL;
        }
        $options = self::getInt4d($recordData, 0);
        $type = (15 & $options) >> 0;
        switch ($type) {
            case 0:
                $type = PHPExcel_Cell_DataValidation::TYPE_NONE;
                break;
            case 1:
                $type = PHPExcel_Cell_DataValidation::TYPE_WHOLE;
                break;
            case 2:
                $type = PHPExcel_Cell_DataValidation::TYPE_DECIMAL;
                break;
            case 3:
                $type = PHPExcel_Cell_DataValidation::TYPE_LIST;
                break;
            case 4:
                $type = PHPExcel_Cell_DataValidation::TYPE_DATE;
                break;
            case 5:
                $type = PHPExcel_Cell_DataValidation::TYPE_TIME;
                break;
            case 6:
                $type = PHPExcel_Cell_DataValidation::TYPE_TEXTLENGTH;
                break;
            case 7:
                $type = PHPExcel_Cell_DataValidation::TYPE_CUSTOM;
                break;
        }
        $errorStyle = (112 & $options) >> 4;
        switch ($errorStyle) {
            case 0:
                $errorStyle = PHPExcel_Cell_DataValidation::STYLE_STOP;
                break;
            case 1:
                $errorStyle = PHPExcel_Cell_DataValidation::STYLE_WARNING;
                break;
            case 2:
                $errorStyle = PHPExcel_Cell_DataValidation::STYLE_INFORMATION;
                break;
        }
        $explicitFormula = (128 & $options) >> 7;
        $allowBlank = (256 & $options) >> 8;
        $suppressDropDown = (512 & $options) >> 9;
        $showInputMessage = (262144 & $options) >> 18;
        $showErrorMessage = (524288 & $options) >> 19;
        $operator = (15728640 & $options) >> 20;
        switch ($operator) {
            case 0:
                $operator = PHPExcel_Cell_DataValidation::OPERATOR_BETWEEN;
                break;
            case 1:
                $operator = PHPExcel_Cell_DataValidation::OPERATOR_NOTBETWEEN;
                break;
            case 2:
                $operator = PHPExcel_Cell_DataValidation::OPERATOR_EQUAL;
                break;
            case 3:
                $operator = PHPExcel_Cell_DataValidation::OPERATOR_NOTEQUAL;
                break;
            case 4:
                $operator = PHPExcel_Cell_DataValidation::OPERATOR_GREATERTHAN;
                break;
            case 5:
                $operator = PHPExcel_Cell_DataValidation::OPERATOR_LESSTHAN;
                break;
            case 6:
                $operator = PHPExcel_Cell_DataValidation::OPERATOR_GREATERTHANOREQUAL;
                break;
            case 7:
                $operator = PHPExcel_Cell_DataValidation::OPERATOR_LESSTHANOREQUAL;
                break;
        }
        $offset = 4;
        $string = self::readUnicodeStringLong(substr($recordData, $offset));
        $promptTitle = $string["value"] !== chr(0) ? $string["value"] : "";
        $offset += $string["size"];
        $string = self::readUnicodeStringLong(substr($recordData, $offset));
        $errorTitle = $string["value"] !== chr(0) ? $string["value"] : "";
        $offset += $string["size"];
        $string = self::readUnicodeStringLong(substr($recordData, $offset));
        $prompt = $string["value"] !== chr(0) ? $string["value"] : "";
        $offset += $string["size"];
        $string = self::readUnicodeStringLong(substr($recordData, $offset));
        $error = $string["value"] !== chr(0) ? $string["value"] : "";
        $offset += $string["size"];
        $sz1 = self::getInt2d($recordData, $offset);
        $offset += 2;
        $offset += 2;
        $formula1 = substr($recordData, $offset, $sz1);
        $formula1 = pack("v", $sz1) . $formula1;
        try {
            $formula1 = $this->getFormulaFromStructure($formula1);
            if ($type == PHPExcel_Cell_DataValidation::TYPE_LIST) {
                $formula1 = str_replace(chr(0), ",", $formula1);
            }
        } catch (PHPExcel_Exception $e) {
            return NULL;
        }
        $offset += $sz1;
        $sz2 = self::getInt2d($recordData, $offset);
        $offset += 2;
        $offset += 2;
        $formula2 = substr($recordData, $offset, $sz2);
        $formula2 = pack("v", $sz2) . $formula2;
        try {
            $formula2 = $this->getFormulaFromStructure($formula2);
        } catch (PHPExcel_Exception $e) {
            return NULL;
        }
        $offset += $sz2;
        $cellRangeAddressList = $this->readBIFF8CellRangeAddressList(substr($recordData, $offset));
        $cellRangeAddresses = $cellRangeAddressList["cellRangeAddresses"];
        foreach ($cellRangeAddresses as $cellRange) {
            $stRange = $this->phpSheet->shrinkRangeToFit($cellRange);
            foreach (PHPExcel_Cell::extractAllCellReferencesInRange($stRange) as $coordinate) {
                $objValidation = $this->phpSheet->getCell($coordinate)->getDataValidation();
                $objValidation->setType($type);
                $objValidation->setErrorStyle($errorStyle);
                $objValidation->setAllowBlank((bool) $allowBlank);
                $objValidation->setShowInputMessage((bool) $showInputMessage);
                $objValidation->setShowErrorMessage((bool) $showErrorMessage);
                $objValidation->setShowDropDown(!$suppressDropDown);
                $objValidation->setOperator($operator);
                $objValidation->setErrorTitle($errorTitle);
                $objValidation->setError($error);
                $objValidation->setPromptTitle($promptTitle);
                $objValidation->setPrompt($prompt);
                $objValidation->setFormula1($formula1);
                $objValidation->setFormula2($formula2);
            }
        }
    }
    /**
     * Read SHEETLAYOUT record. Stores sheet tab color information.
     */
    private function readSheetLayout()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $offset = 0;
        if (!$this->readDataOnly) {
            $sz = self::getInt4d($recordData, 12);
            switch ($sz) {
                case 20:
                    $colorIndex = self::getInt2d($recordData, 16);
                    $color = PHPExcel_Reader_Excel5_Color::map($colorIndex, $this->palette, $this->version);
                    $this->phpSheet->getTabColor()->setRGB($color["rgb"]);
                    break;
                case 40:
                    return NULL;
            }
        }
    }
    /**
     * Read SHEETPROTECTION record (FEATHEADR)
     */
    private function readSheetProtection()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        if ($this->readDataOnly) {
            return NULL;
        }
        $isf = self::getInt2d($recordData, 12);
        if ($isf != 2) {
            return NULL;
        }
        $options = self::getInt2d($recordData, 19);
        $bool = (1 & $options) >> 0;
        $this->phpSheet->getProtection()->setObjects(!$bool);
        $bool = (2 & $options) >> 1;
        $this->phpSheet->getProtection()->setScenarios(!$bool);
        $bool = (4 & $options) >> 2;
        $this->phpSheet->getProtection()->setFormatCells(!$bool);
        $bool = (8 & $options) >> 3;
        $this->phpSheet->getProtection()->setFormatColumns(!$bool);
        $bool = (16 & $options) >> 4;
        $this->phpSheet->getProtection()->setFormatRows(!$bool);
        $bool = (32 & $options) >> 5;
        $this->phpSheet->getProtection()->setInsertColumns(!$bool);
        $bool = (64 & $options) >> 6;
        $this->phpSheet->getProtection()->setInsertRows(!$bool);
        $bool = (128 & $options) >> 7;
        $this->phpSheet->getProtection()->setInsertHyperlinks(!$bool);
        $bool = (256 & $options) >> 8;
        $this->phpSheet->getProtection()->setDeleteColumns(!$bool);
        $bool = (512 & $options) >> 9;
        $this->phpSheet->getProtection()->setDeleteRows(!$bool);
        $bool = (1024 & $options) >> 10;
        $this->phpSheet->getProtection()->setSelectLockedCells(!$bool);
        $bool = (2048 & $options) >> 11;
        $this->phpSheet->getProtection()->setSort(!$bool);
        $bool = (4096 & $options) >> 12;
        $this->phpSheet->getProtection()->setAutoFilter(!$bool);
        $bool = (8192 & $options) >> 13;
        $this->phpSheet->getProtection()->setPivotTables(!$bool);
        $bool = (16384 & $options) >> 14;
        $this->phpSheet->getProtection()->setSelectUnlockedCells(!$bool);
    }
    /**
     * Read RANGEPROTECTION record
     * Reading of this record is based on Microsoft Office Excel 97-2000 Binary File Format Specification,
     * where it is referred to as FEAT record
     */
    private function readRangeProtection()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        $this->pos += 4 + $length;
        $offset = 0;
        if (!$this->readDataOnly) {
            $offset += 12;
            $isf = self::getInt2d($recordData, 12);
            if ($isf != 2) {
                return NULL;
            }
            $offset += 2;
            $offset += 5;
            $cref = self::getInt2d($recordData, 19);
            $offset += 2;
            $offset += 6;
            $cellRanges = array();
            for ($i = 0; $i < $cref; $i++) {
                try {
                    $cellRange = $this->readBIFF8CellRangeAddressFixed(substr($recordData, 27 + 8 * $i, 8));
                } catch (PHPExcel_Exception $e) {
                    return NULL;
                }
                $cellRanges[] = $cellRange;
                $offset += 8;
            }
            $rgbFeat = substr($recordData, $offset);
            $offset += 4;
            $wPassword = self::getInt4d($recordData, $offset);
            $offset += 4;
            if ($cellRanges) {
                $this->phpSheet->protectCells(implode(" ", $cellRanges), strtoupper(dechex($wPassword)), true);
            }
        }
    }
    /**
     * Read IMDATA record
     */
    private function readImData()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $splicedRecordData = $this->getSplicedRecordData();
        $recordData = $splicedRecordData["recordData"];
        $cf = self::getInt2d($recordData, 0);
        $env = self::getInt2d($recordData, 2);
        $lcb = self::getInt4d($recordData, 4);
        $iData = substr($recordData, 8);
        switch ($cf) {
            case 9:
                $bcSize = self::getInt4d($iData, 0);
                $bcWidth = self::getInt2d($iData, 4);
                $bcHeight = self::getInt2d($iData, 6);
                $ih = imagecreatetruecolor($bcWidth, $bcHeight);
                $bcBitCount = self::getInt2d($iData, 10);
                $rgbString = substr($iData, 12);
                $rgbTriples = array();
                while (0 < strlen($rgbString)) {
                    $rgbTriples[] = unpack("Cb/Cg/Cr", $rgbString);
                    $rgbString = substr($rgbString, 3);
                }
                $x = 0;
                $y = 0;
                foreach ($rgbTriples as $i => $rgbTriple) {
                    $color = imagecolorallocate($ih, $rgbTriple["r"], $rgbTriple["g"], $rgbTriple["b"]);
                    imagesetpixel($ih, $x, $bcHeight - 1 - $y, $color);
                    $x = ($x + 1) % $bcWidth;
                    $y = $y + floor(($x + 1) / $bcWidth);
                }
                $drawing = new PHPExcel_Worksheet_Drawing();
                $drawing->setPath($filename);
                $drawing->setWorksheet($this->phpSheet);
                break;
            case 2:
            case 14:
            default:
                break;
        }
    }
    /**
     * Read a free CONTINUE record. Free CONTINUE record may be a camouflaged MSODRAWING record
     * When MSODRAWING data on a sheet exceeds 8224 bytes, CONTINUE records are used instead. Undocumented.
     * In this case, we must treat the CONTINUE record as a MSODRAWING record
     */
    private function readContinue()
    {
        $length = self::getInt2d($this->data, $this->pos + 2);
        $recordData = $this->readRecordData($this->data, $this->pos + 4, $length);
        if ($this->drawingData == "") {
            $this->pos += 4 + $length;
        } else {
            if ($length < 4) {
                $this->pos += 4 + $length;
            } else {
                $validSplitPoints = array(61443, 61444, 61453);
                $splitPoint = self::getInt2d($recordData, 2);
                if (in_array($splitPoint, $validSplitPoints)) {
                    $splicedRecordData = $this->getSplicedRecordData();
                    $this->drawingData .= $splicedRecordData["recordData"];
                } else {
                    $this->pos += 4 + $length;
                }
            }
        }
    }
    /**
     * Reads a record from current position in data stream and continues reading data as long as CONTINUE
     * records are found. Splices the record data pieces and returns the combined string as if record data
     * is in one piece.
     * Moves to next current position in data stream to start of next record different from a CONtINUE record
     *
     * @return array
     */
    private function getSplicedRecordData()
    {
        $data = "";
        $spliceOffsets = array();
        $i = 0;
        $spliceOffsets[0] = 0;
        do {
            $i++;
            $identifier = self::getInt2d($this->data, $this->pos);
            $length = self::getInt2d($this->data, $this->pos + 2);
            $data .= $this->readRecordData($this->data, $this->pos + 4, $length);
            $spliceOffsets[$i] = $spliceOffsets[$i - 1] + $length;
            $this->pos += 4 + $length;
            $nextIdentifier = self::getInt2d($this->data, $this->pos);
        } while ($nextIdentifier == self::XLS_TYPE_CONTINUE);
        $splicedData = array("recordData" => $data, "spliceOffsets" => $spliceOffsets);
        return $splicedData;
    }
    /**
     * Convert formula structure into human readable Excel formula like 'A3+A5*5'
     *
     * @param string $formulaStructure The complete binary data for the formula
     * @param string $baseCell Base cell, only needed when formula contains tRefN tokens, e.g. with shared formulas
     * @return string Human readable formula
     */
    private function getFormulaFromStructure($formulaStructure, $baseCell = "A1")
    {
        $sz = self::getInt2d($formulaStructure, 0);
        $formulaData = substr($formulaStructure, 2, $sz);
        if (2 + $sz < strlen($formulaStructure)) {
            $additionalData = substr($formulaStructure, 2 + $sz);
        } else {
            $additionalData = "";
        }
        return $this->getFormulaFromData($formulaData, $additionalData, $baseCell);
    }
    /**
     * Take formula data and additional data for formula and return human readable formula
     *
     * @param string $formulaData The binary data for the formula itself
     * @param string $additionalData Additional binary data going with the formula
     * @param string $baseCell Base cell, only needed when formula contains tRefN tokens, e.g. with shared formulas
     * @return string Human readable formula
     */
    private function getFormulaFromData($formulaData, $additionalData = "", $baseCell = "A1")
    {
        $tokens = array();
        while (0 < strlen($formulaData) && ($token = $this->getNextToken($formulaData, $baseCell))) {
            $tokens[] = $token;
            $formulaData = substr($formulaData, $token["size"]);
        }
        $formulaString = $this->createFormulaFromTokens($tokens, $additionalData);
        return $formulaString;
    }
    /**
     * Take array of tokens together with additional data for formula and return human readable formula
     *
     * @param array $tokens
     * @param array $additionalData Additional binary data going with the formula
     * @param string $baseCell Base cell, only needed when formula contains tRefN tokens, e.g. with shared formulas
     * @return string Human readable formula
     */
    private function createFormulaFromTokens($tokens, $additionalData)
    {
        if (empty($tokens)) {
            return "";
        }
        $formulaStrings = array();
        foreach ($tokens as $token) {
            $space0 = isset($space0) ? $space0 : "";
            $space1 = isset($space1) ? $space1 : "";
            $space2 = isset($space2) ? $space2 : "";
            $space3 = isset($space3) ? $space3 : "";
            $space4 = isset($space4) ? $space4 : "";
            $space5 = isset($space5) ? $space5 : "";
            switch ($token["name"]) {
                case "tAdd":
                case "tConcat":
                case "tDiv":
                case "tEQ":
                case "tGE":
                case "tGT":
                case "tIsect":
                case "tLE":
                case "tList":
                case "tLT":
                case "tMul":
                case "tNE":
                case "tPower":
                case "tRange":
                case "tSub":
                    $op2 = array_pop($formulaStrings);
                    $op1 = array_pop($formulaStrings);
                    $formulaStrings[] = (string) $op1 . $space1 . $space0 . $token["data"] . $op2;
                    unset($space0);
                    unset($space1);
                    break;
                case "tUplus":
                case "tUminus":
                    $op = array_pop($formulaStrings);
                    $formulaStrings[] = (string) $space1 . $space0 . $token["data"] . $op;
                    unset($space0);
                    unset($space1);
                    break;
                case "tPercent":
                    $op = array_pop($formulaStrings);
                    $formulaStrings[] = (string) $op . $space1 . $space0 . $token["data"];
                    unset($space0);
                    unset($space1);
                    break;
                case "tAttrVolatile":
                case "tAttrIf":
                case "tAttrSkip":
                case "tAttrChoose":
                    break;
                case "tAttrSpace":
                    switch ($token["data"]["spacetype"]) {
                        case "type0":
                            $space0 = str_repeat(" ", $token["data"]["spacecount"]);
                            break;
                        case "type1":
                            $space1 = str_repeat("\n", $token["data"]["spacecount"]);
                            break;
                        case "type2":
                            $space2 = str_repeat(" ", $token["data"]["spacecount"]);
                            break;
                        case "type3":
                            $space3 = str_repeat("\n", $token["data"]["spacecount"]);
                            break;
                        case "type4":
                            $space4 = str_repeat(" ", $token["data"]["spacecount"]);
                            break;
                        case "type5":
                            $space5 = str_repeat("\n", $token["data"]["spacecount"]);
                            break;
                    }
                    break;
                case "tAttrSum":
                    $op = array_pop($formulaStrings);
                    $formulaStrings[] = (string) $space1 . $space0 . "SUM(" . $op . ")";
                    unset($space0);
                    unset($space1);
                    break;
                case "tFunc":
                case "tFuncV":
                    if ($token["data"]["function"] != "") {
                        $ops = array();
                        for ($i = 0; $i < $token["data"]["args"]; $i++) {
                            $ops[] = array_pop($formulaStrings);
                        }
                        $ops = array_reverse($ops);
                        $formulaStrings[] = (string) $space1 . $space0 . $token["data"]["function"] . "(" . implode(",", $ops) . ")";
                        unset($space0);
                        unset($space1);
                    } else {
                        $ops = array();
                        for ($i = 0; $i < $token["data"]["args"] - 1; $i++) {
                            $ops[] = array_pop($formulaStrings);
                        }
                        $ops = array_reverse($ops);
                        $function = array_pop($formulaStrings);
                        $formulaStrings[] = (string) $space1 . $space0 . $function . "(" . implode(",", $ops) . ")";
                        unset($space0);
                        unset($space1);
                    }
                    break;
                case "tParen":
                    $expression = array_pop($formulaStrings);
                    $formulaStrings[] = (string) $space3 . $space2 . "(" . $expression . $space5 . $space4 . ")";
                    unset($space2);
                    unset($space3);
                    unset($space4);
                    unset($space5);
                    break;
                case "tArray":
                    $constantArray = self::readBIFF8ConstantArray($additionalData);
                    $formulaStrings[] = $space1 . $space0 . $constantArray["value"];
                    $additionalData = substr($additionalData, $constantArray["size"]);
                    unset($space0);
                    unset($space1);
                    break;
                case "tMemArea":
                    $cellRangeAddressList = $this->readBIFF8CellRangeAddressList($additionalData);
                    $additionalData = substr($additionalData, $cellRangeAddressList["size"]);
                    $formulaStrings[] = (string) $space1 . $space0 . $token["data"];
                    unset($space0);
                    unset($space1);
                    break;
                case "tArea":
                case "tBool":
                case "tErr":
                case "tInt":
                case "tMemErr":
                case "tMemFunc":
                case "tMissArg":
                case "tName":
                case "tNameX":
                case "tNum":
                case "tRef":
                case "tRef3d":
                case "tArea3d":
                case "tRefN":
                case "tAreaN":
                case "tStr":
                    $formulaStrings[] = (string) $space1 . $space0 . $token["data"];
                    unset($space0);
                    unset($space1);
                    break;
            }
        }
        $formulaString = $formulaStrings[0];
        return $formulaString;
    }
    /**
     * Fetch next token from binary formula data
     *
     * @param string Formula data
     * @param string $baseCell Base cell, only needed when formula contains tRefN tokens, e.g. with shared formulas
     * @return array
     * @throws PHPExcel_Reader_Exception
     */
    private function getNextToken($formulaData, $baseCell = "A1")
    {
        $id = ord($formulaData[0]);
        $name = false;
        switch ($id) {
            case 3:
                $name = "tAdd";
                $size = 1;
                $data = "+";
                break;
            case 4:
                $name = "tSub";
                $size = 1;
                $data = "-";
                break;
            case 5:
                $name = "tMul";
                $size = 1;
                $data = "*";
                break;
            case 6:
                $name = "tDiv";
                $size = 1;
                $data = "/";
                break;
            case 7:
                $name = "tPower";
                $size = 1;
                $data = "^";
                break;
            case 8:
                $name = "tConcat";
                $size = 1;
                $data = "&";
                break;
            case 9:
                $name = "tLT";
                $size = 1;
                $data = "<";
                break;
            case 10:
                $name = "tLE";
                $size = 1;
                $data = "<=";
                break;
            case 11:
                $name = "tEQ";
                $size = 1;
                $data = "=";
                break;
            case 12:
                $name = "tGE";
                $size = 1;
                $data = ">=";
                break;
            case 13:
                $name = "tGT";
                $size = 1;
                $data = ">";
                break;
            case 14:
                $name = "tNE";
                $size = 1;
                $data = "<>";
                break;
            case 15:
                $name = "tIsect";
                $size = 1;
                $data = " ";
                break;
            case 16:
                $name = "tList";
                $size = 1;
                $data = ",";
                break;
            case 17:
                $name = "tRange";
                $size = 1;
                $data = ":";
                break;
            case 18:
                $name = "tUplus";
                $size = 1;
                $data = "+";
                break;
            case 19:
                $name = "tUminus";
                $size = 1;
                $data = "-";
                break;
            case 20:
                $name = "tPercent";
                $size = 1;
                $data = "%";
                break;
            case 21:
                $name = "tParen";
                $size = 1;
                $data = NULL;
                break;
            case 22:
                $name = "tMissArg";
                $size = 1;
                $data = "";
                break;
            case 23:
                $name = "tStr";
                $string = self::readUnicodeStringShort(substr($formulaData, 1));
                $size = 1 + $string["size"];
                $data = self::UTF8toExcelDoubleQuoted($string["value"]);
                break;
            case 25:
                switch (ord($formulaData[1])) {
                    case 1:
                        $name = "tAttrVolatile";
                        $size = 4;
                        $data = NULL;
                        break;
                    case 2:
                        $name = "tAttrIf";
                        $size = 4;
                        $data = NULL;
                        break;
                    case 4:
                        $name = "tAttrChoose";
                        $nc = self::getInt2d($formulaData, 2);
                        $size = 2 * $nc + 6;
                        $data = NULL;
                        break;
                    case 8:
                        $name = "tAttrSkip";
                        $size = 4;
                        $data = NULL;
                        break;
                    case 16:
                        $name = "tAttrSum";
                        $size = 4;
                        $data = NULL;
                        break;
                    case 64:
                    case 65:
                        $name = "tAttrSpace";
                        $size = 4;
                        switch (ord($formulaData[2])) {
                            case 0:
                                $spacetype = "type0";
                                break;
                            case 1:
                                $spacetype = "type1";
                                break;
                            case 2:
                                $spacetype = "type2";
                                break;
                            case 3:
                                $spacetype = "type3";
                                break;
                            case 4:
                                $spacetype = "type4";
                                break;
                            case 5:
                                $spacetype = "type5";
                                break;
                            default:
                                throw new PHPExcel_Reader_Exception("Unrecognized space type in tAttrSpace token");
                        }
                    default:
                        throw new PHPExcel_Reader_Exception("Unrecognized attribute flag in tAttr token");
                }
                $spacecount = ord($formulaData[3]);
                $data = array("spacetype" => $spacetype, "spacecount" => $spacecount);
                break;
            case 28:
                $name = "tErr";
                $size = 2;
                $data = PHPExcel_Reader_Excel5_ErrorCode::lookup(ord($formulaData[1]));
                break;
            case 29:
                $name = "tBool";
                $size = 2;
                $data = ord($formulaData[1]) ? "TRUE" : "FALSE";
                break;
            case 30:
                $name = "tInt";
                $size = 3;
                $data = self::getInt2d($formulaData, 1);
                break;
            case 31:
                $name = "tNum";
                $size = 9;
                $data = self::extractNumber(substr($formulaData, 1));
                $data = str_replace(",", ".", (string) $data);
                break;
            case 32:
            case 64:
            case 96:
                $name = "tArray";
                $size = 8;
                $data = NULL;
                break;
            case 33:
            case 65:
            case 97:
                $name = "tFunc";
                $size = 3;
                switch (self::getInt2d($formulaData, 1)) {
                    case 2:
                        $function = "ISNA";
                        $args = 1;
                        break;
                    case 3:
                        $function = "ISERROR";
                        $args = 1;
                        break;
                    case 10:
                        $function = "NA";
                        $args = 0;
                        break;
                    case 15:
                        $function = "SIN";
                        $args = 1;
                        break;
                    case 16:
                        $function = "COS";
                        $args = 1;
                        break;
                    case 17:
                        $function = "TAN";
                        $args = 1;
                        break;
                    case 18:
                        $function = "ATAN";
                        $args = 1;
                        break;
                    case 19:
                        $function = "PI";
                        $args = 0;
                        break;
                    case 20:
                        $function = "SQRT";
                        $args = 1;
                        break;
                    case 21:
                        $function = "EXP";
                        $args = 1;
                        break;
                    case 22:
                        $function = "LN";
                        $args = 1;
                        break;
                    case 23:
                        $function = "LOG10";
                        $args = 1;
                        break;
                    case 24:
                        $function = "ABS";
                        $args = 1;
                        break;
                    case 25:
                        $function = "INT";
                        $args = 1;
                        break;
                    case 26:
                        $function = "SIGN";
                        $args = 1;
                        break;
                    case 27:
                        $function = "ROUND";
                        $args = 2;
                        break;
                    case 30:
                        $function = "REPT";
                        $args = 2;
                        break;
                    case 31:
                        $function = "MID";
                        $args = 3;
                        break;
                    case 32:
                        $function = "LEN";
                        $args = 1;
                        break;
                    case 33:
                        $function = "VALUE";
                        $args = 1;
                        break;
                    case 34:
                        $function = "TRUE";
                        $args = 0;
                        break;
                    case 35:
                        $function = "FALSE";
                        $args = 0;
                        break;
                    case 38:
                        $function = "NOT";
                        $args = 1;
                        break;
                    case 39:
                        $function = "MOD";
                        $args = 2;
                        break;
                    case 40:
                        $function = "DCOUNT";
                        $args = 3;
                        break;
                    case 41:
                        $function = "DSUM";
                        $args = 3;
                        break;
                    case 42:
                        $function = "DAVERAGE";
                        $args = 3;
                        break;
                    case 43:
                        $function = "DMIN";
                        $args = 3;
                        break;
                    case 44:
                        $function = "DMAX";
                        $args = 3;
                        break;
                    case 45:
                        $function = "DSTDEV";
                        $args = 3;
                        break;
                    case 48:
                        $function = "TEXT";
                        $args = 2;
                        break;
                    case 61:
                        $function = "MIRR";
                        $args = 3;
                        break;
                    case 63:
                        $function = "RAND";
                        $args = 0;
                        break;
                    case 65:
                        $function = "DATE";
                        $args = 3;
                        break;
                    case 66:
                        $function = "TIME";
                        $args = 3;
                        break;
                    case 67:
                        $function = "DAY";
                        $args = 1;
                        break;
                    case 68:
                        $function = "MONTH";
                        $args = 1;
                        break;
                    case 69:
                        $function = "YEAR";
                        $args = 1;
                        break;
                    case 71:
                        $function = "HOUR";
                        $args = 1;
                        break;
                    case 72:
                        $function = "MINUTE";
                        $args = 1;
                        break;
                    case 73:
                        $function = "SECOND";
                        $args = 1;
                        break;
                    case 74:
                        $function = "NOW";
                        $args = 0;
                        break;
                    case 75:
                        $function = "AREAS";
                        $args = 1;
                        break;
                    case 76:
                        $function = "ROWS";
                        $args = 1;
                        break;
                    case 77:
                        $function = "COLUMNS";
                        $args = 1;
                        break;
                    case 83:
                        $function = "TRANSPOSE";
                        $args = 1;
                        break;
                    case 86:
                        $function = "TYPE";
                        $args = 1;
                        break;
                    case 97:
                        $function = "ATAN2";
                        $args = 2;
                        break;
                    case 98:
                        $function = "ASIN";
                        $args = 1;
                        break;
                    case 99:
                        $function = "ACOS";
                        $args = 1;
                        break;
                    case 105:
                        $function = "ISREF";
                        $args = 1;
                        break;
                    case 111:
                        $function = "CHAR";
                        $args = 1;
                        break;
                    case 112:
                        $function = "LOWER";
                        $args = 1;
                        break;
                    case 113:
                        $function = "UPPER";
                        $args = 1;
                        break;
                    case 114:
                        $function = "PROPER";
                        $args = 1;
                        break;
                    case 117:
                        $function = "EXACT";
                        $args = 2;
                        break;
                    case 118:
                        $function = "TRIM";
                        $args = 1;
                        break;
                    case 119:
                        $function = "REPLACE";
                        $args = 4;
                        break;
                    case 121:
                        $function = "CODE";
                        $args = 1;
                        break;
                    case 126:
                        $function = "ISERR";
                        $args = 1;
                        break;
                    case 127:
                        $function = "ISTEXT";
                        $args = 1;
                        break;
                    case 128:
                        $function = "ISNUMBER";
                        $args = 1;
                        break;
                    case 129:
                        $function = "ISBLANK";
                        $args = 1;
                        break;
                    case 130:
                        $function = "T";
                        $args = 1;
                        break;
                    case 131:
                        $function = "N";
                        $args = 1;
                        break;
                    case 140:
                        $function = "DATEVALUE";
                        $args = 1;
                        break;
                    case 141:
                        $function = "TIMEVALUE";
                        $args = 1;
                        break;
                    case 142:
                        $function = "SLN";
                        $args = 3;
                        break;
                    case 143:
                        $function = "SYD";
                        $args = 4;
                        break;
                    case 162:
                        $function = "CLEAN";
                        $args = 1;
                        break;
                    case 163:
                        $function = "MDETERM";
                        $args = 1;
                        break;
                    case 164:
                        $function = "MINVERSE";
                        $args = 1;
                        break;
                    case 165:
                        $function = "MMULT";
                        $args = 2;
                        break;
                    case 184:
                        $function = "FACT";
                        $args = 1;
                        break;
                    case 189:
                        $function = "DPRODUCT";
                        $args = 3;
                        break;
                    case 190:
                        $function = "ISNONTEXT";
                        $args = 1;
                        break;
                    case 195:
                        $function = "DSTDEVP";
                        $args = 3;
                        break;
                    case 196:
                        $function = "DVARP";
                        $args = 3;
                        break;
                    case 198:
                        $function = "ISLOGICAL";
                        $args = 1;
                        break;
                    case 199:
                        $function = "DCOUNTA";
                        $args = 3;
                        break;
                    case 207:
                        $function = "REPLACEB";
                        $args = 4;
                        break;
                    case 210:
                        $function = "MIDB";
                        $args = 3;
                        break;
                    case 211:
                        $function = "LENB";
                        $args = 1;
                        break;
                    case 212:
                        $function = "ROUNDUP";
                        $args = 2;
                        break;
                    case 213:
                        $function = "ROUNDDOWN";
                        $args = 2;
                        break;
                    case 214:
                        $function = "ASC";
                        $args = 1;
                        break;
                    case 215:
                        $function = "DBCS";
                        $args = 1;
                        break;
                    case 221:
                        $function = "TODAY";
                        $args = 0;
                        break;
                    case 229:
                        $function = "SINH";
                        $args = 1;
                        break;
                    case 230:
                        $function = "COSH";
                        $args = 1;
                        break;
                    case 231:
                        $function = "TANH";
                        $args = 1;
                        break;
                    case 232:
                        $function = "ASINH";
                        $args = 1;
                        break;
                    case 233:
                        $function = "ACOSH";
                        $args = 1;
                        break;
                    case 234:
                        $function = "ATANH";
                        $args = 1;
                        break;
                    case 235:
                        $function = "DGET";
                        $args = 3;
                        break;
                    case 244:
                        $function = "INFO";
                        $args = 1;
                        break;
                    case 252:
                        $function = "FREQUENCY";
                        $args = 2;
                        break;
                    case 261:
                        $function = "ERROR.TYPE";
                        $args = 1;
                        break;
                    case 271:
                        $function = "GAMMALN";
                        $args = 1;
                        break;
                    case 273:
                        $function = "BINOMDIST";
                        $args = 4;
                        break;
                    case 274:
                        $function = "CHIDIST";
                        $args = 2;
                        break;
                    case 275:
                        $function = "CHIINV";
                        $args = 2;
                        break;
                    case 276:
                        $function = "COMBIN";
                        $args = 2;
                        break;
                    case 277:
                        $function = "CONFIDENCE";
                        $args = 3;
                        break;
                    case 278:
                        $function = "CRITBINOM";
                        $args = 3;
                        break;
                    case 279:
                        $function = "EVEN";
                        $args = 1;
                        break;
                    case 280:
                        $function = "EXPONDIST";
                        $args = 3;
                        break;
                    case 281:
                        $function = "FDIST";
                        $args = 3;
                        break;
                    case 282:
                        $function = "FINV";
                        $args = 3;
                        break;
                    case 283:
                        $function = "FISHER";
                        $args = 1;
                        break;
                    case 284:
                        $function = "FISHERINV";
                        $args = 1;
                        break;
                    case 285:
                        $function = "FLOOR";
                        $args = 2;
                        break;
                    case 286:
                        $function = "GAMMADIST";
                        $args = 4;
                        break;
                    case 287:
                        $function = "GAMMAINV";
                        $args = 3;
                        break;
                    case 288:
                        $function = "CEILING";
                        $args = 2;
                        break;
                    case 289:
                        $function = "HYPGEOMDIST";
                        $args = 4;
                        break;
                    case 290:
                        $function = "LOGNORMDIST";
                        $args = 3;
                        break;
                    case 291:
                        $function = "LOGINV";
                        $args = 3;
                        break;
                    case 292:
                        $function = "NEGBINOMDIST";
                        $args = 3;
                        break;
                    case 293:
                        $function = "NORMDIST";
                        $args = 4;
                        break;
                    case 294:
                        $function = "NORMSDIST";
                        $args = 1;
                        break;
                    case 295:
                        $function = "NORMINV";
                        $args = 3;
                        break;
                    case 296:
                        $function = "NORMSINV";
                        $args = 1;
                        break;
                    case 297:
                        $function = "STANDARDIZE";
                        $args = 3;
                        break;
                    case 298:
                        $function = "ODD";
                        $args = 1;
                        break;
                    case 299:
                        $function = "PERMUT";
                        $args = 2;
                        break;
                    case 300:
                        $function = "POISSON";
                        $args = 3;
                        break;
                    case 301:
                        $function = "TDIST";
                        $args = 3;
                        break;
                    case 302:
                        $function = "WEIBULL";
                        $args = 4;
                        break;
                    case 303:
                        $function = "SUMXMY2";
                        $args = 2;
                        break;
                    case 304:
                        $function = "SUMX2MY2";
                        $args = 2;
                        break;
                    case 305:
                        $function = "SUMX2PY2";
                        $args = 2;
                        break;
                    case 306:
                        $function = "CHITEST";
                        $args = 2;
                        break;
                    case 307:
                        $function = "CORREL";
                        $args = 2;
                        break;
                    case 308:
                        $function = "COVAR";
                        $args = 2;
                        break;
                    case 309:
                        $function = "FORECAST";
                        $args = 3;
                        break;
                    case 310:
                        $function = "FTEST";
                        $args = 2;
                        break;
                    case 311:
                        $function = "INTERCEPT";
                        $args = 2;
                        break;
                    case 312:
                        $function = "PEARSON";
                        $args = 2;
                        break;
                    case 313:
                        $function = "RSQ";
                        $args = 2;
                        break;
                    case 314:
                        $function = "STEYX";
                        $args = 2;
                        break;
                    case 315:
                        $function = "SLOPE";
                        $args = 2;
                        break;
                    case 316:
                        $function = "TTEST";
                        $args = 4;
                        break;
                    case 325:
                        $function = "LARGE";
                        $args = 2;
                        break;
                    case 326:
                        $function = "SMALL";
                        $args = 2;
                        break;
                    case 327:
                        $function = "QUARTILE";
                        $args = 2;
                        break;
                    case 328:
                        $function = "PERCENTILE";
                        $args = 2;
                        break;
                    case 331:
                        $function = "TRIMMEAN";
                        $args = 2;
                        break;
                    case 332:
                        $function = "TINV";
                        $args = 2;
                        break;
                    case 337:
                        $function = "POWER";
                        $args = 2;
                        break;
                    case 342:
                        $function = "RADIANS";
                        $args = 1;
                        break;
                    case 343:
                        $function = "DEGREES";
                        $args = 1;
                        break;
                    case 346:
                        $function = "COUNTIF";
                        $args = 2;
                        break;
                    case 347:
                        $function = "COUNTBLANK";
                        $args = 1;
                        break;
                    case 350:
                        $function = "ISPMT";
                        $args = 4;
                        break;
                    case 351:
                        $function = "DATEDIF";
                        $args = 3;
                        break;
                    case 352:
                        $function = "DATESTRING";
                        $args = 1;
                        break;
                    case 353:
                        $function = "NUMBERSTRING";
                        $args = 2;
                        break;
                    case 360:
                        $function = "PHONETIC";
                        $args = 1;
                        break;
                    case 368:
                        $function = "BAHTTEXT";
                        $args = 1;
                        break;
                    default:
                        throw new PHPExcel_Reader_Exception("Unrecognized function in formula");
                }
            case 34:
            case 66:
            case 98:
                $name = "tFuncV";
                $size = 4;
                $args = ord($formulaData[1]);
                $index = self::getInt2d($formulaData, 2);
                switch ($index) {
                    case 0:
                        $function = "COUNT";
                        break;
                    case 1:
                        $function = "IF";
                        break;
                    case 4:
                        $function = "SUM";
                        break;
                    case 5:
                        $function = "AVERAGE";
                        break;
                    case 6:
                        $function = "MIN";
                        break;
                    case 7:
                        $function = "MAX";
                        break;
                    case 8:
                        $function = "ROW";
                        break;
                    case 9:
                        $function = "COLUMN";
                        break;
                    case 11:
                        $function = "NPV";
                        break;
                    case 12:
                        $function = "STDEV";
                        break;
                    case 13:
                        $function = "DOLLAR";
                        break;
                    case 14:
                        $function = "FIXED";
                        break;
                    case 28:
                        $function = "LOOKUP";
                        break;
                    case 29:
                        $function = "INDEX";
                        break;
                    case 36:
                        $function = "AND";
                        break;
                    case 37:
                        $function = "OR";
                        break;
                    case 46:
                        $function = "VAR";
                        break;
                    case 49:
                        $function = "LINEST";
                        break;
                    case 50:
                        $function = "TREND";
                        break;
                    case 51:
                        $function = "LOGEST";
                        break;
                    case 52:
                        $function = "GROWTH";
                        break;
                    case 56:
                        $function = "PV";
                        break;
                    case 57:
                        $function = "FV";
                        break;
                    case 58:
                        $function = "NPER";
                        break;
                    case 59:
                        $function = "PMT";
                        break;
                    case 60:
                        $function = "RATE";
                        break;
                    case 62:
                        $function = "IRR";
                        break;
                    case 64:
                        $function = "MATCH";
                        break;
                    case 70:
                        $function = "WEEKDAY";
                        break;
                    case 78:
                        $function = "OFFSET";
                        break;
                    case 82:
                        $function = "SEARCH";
                        break;
                    case 100:
                        $function = "CHOOSE";
                        break;
                    case 101:
                        $function = "HLOOKUP";
                        break;
                    case 102:
                        $function = "VLOOKUP";
                        break;
                    case 109:
                        $function = "LOG";
                        break;
                    case 115:
                        $function = "LEFT";
                        break;
                    case 116:
                        $function = "RIGHT";
                        break;
                    case 120:
                        $function = "SUBSTITUTE";
                        break;
                    case 124:
                        $function = "FIND";
                        break;
                    case 125:
                        $function = "CELL";
                        break;
                    case 144:
                        $function = "DDB";
                        break;
                    case 148:
                        $function = "INDIRECT";
                        break;
                    case 167:
                        $function = "IPMT";
                        break;
                    case 168:
                        $function = "PPMT";
                        break;
                    case 169:
                        $function = "COUNTA";
                        break;
                    case 183:
                        $function = "PRODUCT";
                        break;
                    case 193:
                        $function = "STDEVP";
                        break;
                    case 194:
                        $function = "VARP";
                        break;
                    case 197:
                        $function = "TRUNC";
                        break;
                    case 204:
                        $function = "USDOLLAR";
                        break;
                    case 205:
                        $function = "FINDB";
                        break;
                    case 206:
                        $function = "SEARCHB";
                        break;
                    case 208:
                        $function = "LEFTB";
                        break;
                    case 209:
                        $function = "RIGHTB";
                        break;
                    case 216:
                        $function = "RANK";
                        break;
                    case 219:
                        $function = "ADDRESS";
                        break;
                    case 220:
                        $function = "DAYS360";
                        break;
                    case 222:
                        $function = "VDB";
                        break;
                    case 227:
                        $function = "MEDIAN";
                        break;
                    case 228:
                        $function = "SUMPRODUCT";
                        break;
                    case 247:
                        $function = "DB";
                        break;
                    case 255:
                        $function = "";
                        break;
                    case 269:
                        $function = "AVEDEV";
                        break;
                    case 270:
                        $function = "BETADIST";
                        break;
                    case 272:
                        $function = "BETAINV";
                        break;
                    case 317:
                        $function = "PROB";
                        break;
                    case 318:
                        $function = "DEVSQ";
                        break;
                    case 319:
                        $function = "GEOMEAN";
                        break;
                    case 320:
                        $function = "HARMEAN";
                        break;
                    case 321:
                        $function = "SUMSQ";
                        break;
                    case 322:
                        $function = "KURT";
                        break;
                    case 323:
                        $function = "SKEW";
                        break;
                    case 324:
                        $function = "ZTEST";
                        break;
                    case 329:
                        $function = "PERCENTRANK";
                        break;
                    case 330:
                        $function = "MODE";
                        break;
                    case 336:
                        $function = "CONCATENATE";
                        break;
                    case 344:
                        $function = "SUBTOTAL";
                        break;
                    case 345:
                        $function = "SUMIF";
                        break;
                    case 354:
                        $function = "ROMAN";
                        break;
                    case 358:
                        $function = "GETPIVOTDATA";
                        break;
                    case 359:
                        $function = "HYPERLINK";
                        break;
                    case 361:
                        $function = "AVERAGEA";
                        break;
                    case 362:
                        $function = "MAXA";
                        break;
                    case 363:
                        $function = "MINA";
                        break;
                    case 364:
                        $function = "STDEVPA";
                        break;
                    case 365:
                        $function = "VARPA";
                        break;
                    case 366:
                        $function = "STDEVA";
                        break;
                    case 367:
                        $function = "VARA";
                        break;
                    default:
                        throw new PHPExcel_Reader_Exception("Unrecognized function in formula");
                }
            case 35:
            case 67:
            case 99:
                $name = "tName";
                $size = 5;
                $definedNameIndex = self::getInt2d($formulaData, 1) - 1;
                $data = $this->definedname[$definedNameIndex]["name"];
                break;
            case 36:
            case 68:
            case 100:
                $name = "tRef";
                $size = 5;
                $data = $this->readBIFF8CellAddress(substr($formulaData, 1, 4));
                break;
            case 37:
            case 69:
            case 101:
                $name = "tArea";
                $size = 9;
                $data = $this->readBIFF8CellRangeAddress(substr($formulaData, 1, 8));
                break;
            case 38:
            case 70:
            case 102:
                $name = "tMemArea";
                $subSize = self::getInt2d($formulaData, 5);
                $size = 7 + $subSize;
                $data = $this->getFormulaFromData(substr($formulaData, 7, $subSize));
                break;
            case 39:
            case 71:
            case 103:
                $name = "tMemErr";
                $subSize = self::getInt2d($formulaData, 5);
                $size = 7 + $subSize;
                $data = $this->getFormulaFromData(substr($formulaData, 7, $subSize));
                break;
            case 41:
            case 73:
            case 105:
                $name = "tMemFunc";
                $subSize = self::getInt2d($formulaData, 1);
                $size = 3 + $subSize;
                $data = $this->getFormulaFromData(substr($formulaData, 3, $subSize));
                break;
            case 44:
            case 76:
            case 108:
                $name = "tRefN";
                $size = 5;
                $data = $this->readBIFF8CellAddressB(substr($formulaData, 1, 4), $baseCell);
                break;
            case 45:
            case 77:
            case 109:
                $name = "tAreaN";
                $size = 9;
                $data = $this->readBIFF8CellRangeAddressB(substr($formulaData, 1, 8), $baseCell);
                break;
            case 57:
            case 89:
            case 121:
                $name = "tNameX";
                $size = 7;
                $index = self::getInt2d($formulaData, 3);
                $data = $this->externalNames[$index - 1]["name"];
                break;
            case 58:
            case 90:
            case 122:
                $name = "tRef3d";
                $size = 7;
                try {
                    $sheetRange = $this->readSheetRangeByRefIndex(self::getInt2d($formulaData, 1));
                    $cellAddress = $this->readBIFF8CellAddress(substr($formulaData, 3, 4));
                    $data = (string) $sheetRange . "!" . $cellAddress;
                } catch (PHPExcel_Exception $e) {
                    $data = "#REF!";
                }
                break;
            case 59:
            case 91:
            case 123:
                $name = "tArea3d";
                $size = 11;
                try {
                    $sheetRange = $this->readSheetRangeByRefIndex(self::getInt2d($formulaData, 1));
                    $cellRangeAddress = $this->readBIFF8CellRangeAddress(substr($formulaData, 3, 8));
                    $data = (string) $sheetRange . "!" . $cellRangeAddress;
                } catch (PHPExcel_Exception $e) {
                    $data = "#REF!";
                }
                break;
            default:
                throw new PHPExcel_Reader_Exception("Unrecognized token " . sprintf("%02X", $id) . " in formula");
        }
        $data = array("function" => $function, "args" => $args);
        break;
    }
    /**
     * Reads a cell address in BIFF8 e.g. 'A2' or '$A$2'
     * section 3.3.4
     *
     * @param string $cellAddressStructure
     * @return string
     */
    private function readBIFF8CellAddress($cellAddressStructure)
    {
        $row = self::getInt2d($cellAddressStructure, 0) + 1;
        $column = PHPExcel_Cell::stringFromColumnIndex(255 & self::getInt2d($cellAddressStructure, 2));
        if (!(16384 & self::getInt2d($cellAddressStructure, 2))) {
            $column = "\$" . $column;
        }
        if (!(32768 & self::getInt2d($cellAddressStructure, 2))) {
            $row = "\$" . $row;
        }
        return $column . $row;
    }
    /**
     * Reads a cell address in BIFF8 for shared formulas. Uses positive and negative values for row and column
     * to indicate offsets from a base cell
     * section 3.3.4
     *
     * @param string $cellAddressStructure
     * @param string $baseCell Base cell, only needed when formula contains tRefN tokens, e.g. with shared formulas
     * @return string
     */
    private function readBIFF8CellAddressB($cellAddressStructure, $baseCell = "A1")
    {
        list($baseCol, $baseRow) = PHPExcel_Cell::coordinateFromString($baseCell);
        $baseCol = PHPExcel_Cell::columnIndexFromString($baseCol) - 1;
        $rowIndex = self::getInt2d($cellAddressStructure, 0);
        $row = self::getInt2d($cellAddressStructure, 0) + 1;
        $colIndex = 255 & self::getInt2d($cellAddressStructure, 2);
        if (!(16384 & self::getInt2d($cellAddressStructure, 2))) {
            $column = PHPExcel_Cell::stringFromColumnIndex($colIndex);
            $column = "\$" . $column;
        } else {
            $colIndex = $colIndex <= 127 ? $colIndex : $colIndex - 256;
            $column = PHPExcel_Cell::stringFromColumnIndex($baseCol + $colIndex);
        }
        if (!(32768 & self::getInt2d($cellAddressStructure, 2))) {
            $row = "\$" . $row;
        } else {
            $rowIndex = $rowIndex <= 32767 ? $rowIndex : $rowIndex - 65536;
            $row = $baseRow + $rowIndex;
        }
        return $column . $row;
    }
    /**
     * Reads a cell range address in BIFF5 e.g. 'A2:B6' or 'A1'
     * always fixed range
     * section 2.5.14
     *
     * @param string $subData
     * @return string
     * @throws PHPExcel_Reader_Exception
     */
    private function readBIFF5CellRangeAddressFixed($subData)
    {
        $fr = self::getInt2d($subData, 0) + 1;
        $lr = self::getInt2d($subData, 2) + 1;
        $fc = ord($subData[4]);
        $lc = ord($subData[5]);
        if ($lr < $fr || $lc < $fc) {
            throw new PHPExcel_Reader_Exception("Not a cell range address");
        }
        $fc = PHPExcel_Cell::stringFromColumnIndex($fc);
        $lc = PHPExcel_Cell::stringFromColumnIndex($lc);
        if ($fr == $lr && $fc == $lc) {
            return (string) $fc . $fr;
        }
        return (string) $fc . $fr . ":" . $lc . $lr;
    }
    /**
     * Reads a cell range address in BIFF8 e.g. 'A2:B6' or 'A1'
     * always fixed range
     * section 2.5.14
     *
     * @param string $subData
     * @return string
     * @throws PHPExcel_Reader_Exception
     */
    private function readBIFF8CellRangeAddressFixed($subData)
    {
        $fr = self::getInt2d($subData, 0) + 1;
        $lr = self::getInt2d($subData, 2) + 1;
        $fc = self::getInt2d($subData, 4);
        $lc = self::getInt2d($subData, 6);
        if ($lr < $fr || $lc < $fc) {
            throw new PHPExcel_Reader_Exception("Not a cell range address");
        }
        $fc = PHPExcel_Cell::stringFromColumnIndex($fc);
        $lc = PHPExcel_Cell::stringFromColumnIndex($lc);
        if ($fr == $lr && $fc == $lc) {
            return (string) $fc . $fr;
        }
        return (string) $fc . $fr . ":" . $lc . $lr;
    }
    /**
     * Reads a cell range address in BIFF8 e.g. 'A2:B6' or '$A$2:$B$6'
     * there are flags indicating whether column/row index is relative
     * section 3.3.4
     *
     * @param string $subData
     * @return string
     */
    private function readBIFF8CellRangeAddress($subData)
    {
        $fr = self::getInt2d($subData, 0) + 1;
        $lr = self::getInt2d($subData, 2) + 1;
        $fc = PHPExcel_Cell::stringFromColumnIndex(255 & self::getInt2d($subData, 4));
        if (!(16384 & self::getInt2d($subData, 4))) {
            $fc = "\$" . $fc;
        }
        if (!(32768 & self::getInt2d($subData, 4))) {
            $fr = "\$" . $fr;
        }
        $lc = PHPExcel_Cell::stringFromColumnIndex(255 & self::getInt2d($subData, 6));
        if (!(16384 & self::getInt2d($subData, 6))) {
            $lc = "\$" . $lc;
        }
        if (!(32768 & self::getInt2d($subData, 6))) {
            $lr = "\$" . $lr;
        }
        return (string) $fc . $fr . ":" . $lc . $lr;
    }
    /**
     * Reads a cell range address in BIFF8 for shared formulas. Uses positive and negative values for row and column
     * to indicate offsets from a base cell
     * section 3.3.4
     *
     * @param string $subData
     * @param string $baseCell Base cell
     * @return string Cell range address
     */
    private function readBIFF8CellRangeAddressB($subData, $baseCell = "A1")
    {
        list($baseCol, $baseRow) = PHPExcel_Cell::coordinateFromString($baseCell);
        $baseCol = PHPExcel_Cell::columnIndexFromString($baseCol) - 1;
        $frIndex = self::getInt2d($subData, 0);
        $lrIndex = self::getInt2d($subData, 2);
        $fcIndex = 255 & self::getInt2d($subData, 4);
        if (!(16384 & self::getInt2d($subData, 4))) {
            $fc = PHPExcel_Cell::stringFromColumnIndex($fcIndex);
            $fc = "\$" . $fc;
        } else {
            $fcIndex = $fcIndex <= 127 ? $fcIndex : $fcIndex - 256;
            $fc = PHPExcel_Cell::stringFromColumnIndex($baseCol + $fcIndex);
        }
        if (!(32768 & self::getInt2d($subData, 4))) {
            $fr = $frIndex + 1;
            $fr = "\$" . $fr;
        } else {
            $frIndex = $frIndex <= 32767 ? $frIndex : $frIndex - 65536;
            $fr = $baseRow + $frIndex;
        }
        $lcIndex = 255 & self::getInt2d($subData, 6);
        $lcIndex = $lcIndex <= 127 ? $lcIndex : $lcIndex - 256;
        $lc = PHPExcel_Cell::stringFromColumnIndex($baseCol + $lcIndex);
        if (!(16384 & self::getInt2d($subData, 6))) {
            $lc = PHPExcel_Cell::stringFromColumnIndex($lcIndex);
            $lc = "\$" . $lc;
        } else {
            $lcIndex = $lcIndex <= 127 ? $lcIndex : $lcIndex - 256;
            $lc = PHPExcel_Cell::stringFromColumnIndex($baseCol + $lcIndex);
        }
        if (!(32768 & self::getInt2d($subData, 6))) {
            $lr = $lrIndex + 1;
            $lr = "\$" . $lr;
        } else {
            $lrIndex = $lrIndex <= 32767 ? $lrIndex : $lrIndex - 65536;
            $lr = $baseRow + $lrIndex;
        }
        return (string) $fc . $fr . ":" . $lc . $lr;
    }
    /**
     * Read BIFF8 cell range address list
     * section 2.5.15
     *
     * @param string $subData
     * @return array
     */
    private function readBIFF8CellRangeAddressList($subData)
    {
        $cellRangeAddresses = array();
        $nm = self::getInt2d($subData, 0);
        $offset = 2;
        for ($i = 0; $i < $nm; $i++) {
            $cellRangeAddresses[] = $this->readBIFF8CellRangeAddressFixed(substr($subData, $offset, 8));
            $offset += 8;
        }
        return array("size" => 2 + 8 * $nm, "cellRangeAddresses" => $cellRangeAddresses);
    }
    /**
     * Read BIFF5 cell range address list
     * section 2.5.15
     *
     * @param string $subData
     * @return array
     */
    private function readBIFF5CellRangeAddressList($subData)
    {
        $cellRangeAddresses = array();
        $nm = self::getInt2d($subData, 0);
        $offset = 2;
        for ($i = 0; $i < $nm; $i++) {
            $cellRangeAddresses[] = $this->readBIFF5CellRangeAddressFixed(substr($subData, $offset, 6));
            $offset += 6;
        }
        return array("size" => 2 + 6 * $nm, "cellRangeAddresses" => $cellRangeAddresses);
    }
    /**
     * Get a sheet range like Sheet1:Sheet3 from REF index
     * Note: If there is only one sheet in the range, one gets e.g Sheet1
     * It can also happen that the REF structure uses the -1 (FFFF) code to indicate deleted sheets,
     * in which case an PHPExcel_Reader_Exception is thrown
     *
     * @param int $index
     * @return string|false
     * @throws PHPExcel_Reader_Exception
     */
    private function readSheetRangeByRefIndex($index)
    {
        if (isset($this->ref[$index])) {
            $type = $this->externalBooks[$this->ref[$index]["externalBookIndex"]]["type"];
            switch ($type) {
                case "internal":
                    if ($this->ref[$index]["firstSheetIndex"] == 65535 || $this->ref[$index]["lastSheetIndex"] == 65535) {
                        throw new PHPExcel_Reader_Exception("Deleted sheet reference");
                    }
                    $firstSheetName = $this->sheets[$this->ref[$index]["firstSheetIndex"]]["name"];
                    $lastSheetName = $this->sheets[$this->ref[$index]["lastSheetIndex"]]["name"];
                    if ($firstSheetName == $lastSheetName) {
                        $sheetRange = $firstSheetName;
                    } else {
                        $sheetRange = (string) $firstSheetName . ":" . $lastSheetName;
                    }
                    $sheetRange = str_replace("'", "''", $sheetRange);
                    if (preg_match("/[ !\"@#Â£\$%&{()}<>=+'|^,;-]/", $sheetRange)) {
                        $sheetRange = "'" . $sheetRange . "'";
                    }
                    return $sheetRange;
                default:
                    throw new PHPExcel_Reader_Exception("Excel5 reader only supports internal sheets in fomulas");
            }
        } else {
            return false;
        }
    }
    /**
     * read BIFF8 constant value array from array data
     * returns e.g. array('value' => '{1,2;3,4}', 'size' => 40}
     * section 2.5.8
     *
     * @param string $arrayData
     * @return array
     */
    private static function readBIFF8ConstantArray($arrayData)
    {
        $nc = ord($arrayData[0]);
        $nr = self::getInt2d($arrayData, 1);
        $size = 3;
        $arrayData = substr($arrayData, 3);
        $matrixChunks = array();
        for ($r = 1; $r <= $nr + 1; $r++) {
            $items = array();
            for ($c = 1; $c <= $nc + 1; $c++) {
                $constant = self::readBIFF8Constant($arrayData);
                $items[] = $constant["value"];
                $arrayData = substr($arrayData, $constant["size"]);
                $size += $constant["size"];
            }
            $matrixChunks[] = implode(",", $items);
        }
        $matrix = "{" . implode(";", $matrixChunks) . "}";
        return array("value" => $matrix, "size" => $size);
    }
    /**
     * read BIFF8 constant value which may be 'Empty Value', 'Number', 'String Value', 'Boolean Value', 'Error Value'
     * section 2.5.7
     * returns e.g. array('value' => '5', 'size' => 9)
     *
     * @param string $valueData
     * @return array
     */
    private static function readBIFF8Constant($valueData)
    {
        $identifier = ord($valueData[0]);
        switch ($identifier) {
            case 0:
                $value = "";
                $size = 9;
                break;
            case 1:
                $value = self::extractNumber(substr($valueData, 1, 8));
                $size = 9;
                break;
            case 2:
                $string = self::readUnicodeStringLong(substr($valueData, 1));
                $value = "\"" . $string["value"] . "\"";
                $size = 1 + $string["size"];
                break;
            case 4:
                if (ord($valueData[1])) {
                    $value = "TRUE";
                } else {
                    $value = "FALSE";
                }
                $size = 9;
                break;
            case 16:
                $value = PHPExcel_Reader_Excel5_ErrorCode::lookup(ord($valueData[1]));
                $size = 9;
                break;
        }
        return array("value" => $value, "size" => $size);
    }
    /**
     * Extract RGB color
     * OpenOffice.org's Documentation of the Microsoft Excel File Format, section 2.5.4
     *
     * @param string $rgb Encoded RGB value (4 bytes)
     * @return array
     */
    private static function readRGB($rgb)
    {
        $r = ord($rgb[0]);
        $g = ord($rgb[1]);
        $b = ord($rgb[2]);
        $rgb = sprintf("%02X%02X%02X", $r, $g, $b);
        return array("rgb" => $rgb);
    }
    /**
     * Read byte string (8-bit string length)
     * OpenOffice documentation: 2.5.2
     *
     * @param string $subData
     * @return array
     */
    private function readByteStringShort($subData)
    {
        $ln = ord($subData[0]);
        $value = $this->decodeCodepage(substr($subData, 1, $ln));
        return array("value" => $value, "size" => 1 + $ln);
    }
    /**
     * Read byte string (16-bit string length)
     * OpenOffice documentation: 2.5.2
     *
     * @param string $subData
     * @return array
     */
    private function readByteStringLong($subData)
    {
        $ln = self::getInt2d($subData, 0);
        $value = $this->decodeCodepage(substr($subData, 2));
        return array("value" => $value, "size" => 2 + $ln);
    }
    /**
     * Extracts an Excel Unicode short string (8-bit string length)
     * OpenOffice documentation: 2.5.3
     * function will automatically find out where the Unicode string ends.
     *
     * @param string $subData
     * @return array
     */
    private static function readUnicodeStringShort($subData)
    {
        $value = "";
        $characterCount = ord($subData[0]);
        $string = self::readUnicodeString(substr($subData, 1), $characterCount);
        $string["size"] += 1;
        return $string;
    }
    /**
     * Extracts an Excel Unicode long string (16-bit string length)
     * OpenOffice documentation: 2.5.3
     * this function is under construction, needs to support rich text, and Asian phonetic settings
     *
     * @param string $subData
     * @return array
     */
    private static function readUnicodeStringLong($subData)
    {
        $value = "";
        $characterCount = self::getInt2d($subData, 0);
        $string = self::readUnicodeString(substr($subData, 2), $characterCount);
        $string["size"] += 2;
        return $string;
    }
    /**
     * Read Unicode string with no string length field, but with known character count
     * this function is under construction, needs to support rich text, and Asian phonetic settings
     * OpenOffice.org's Documentation of the Microsoft Excel File Format, section 2.5.3
     *
     * @param string $subData
     * @param int $characterCount
     * @return array
     */
    private static function readUnicodeString($subData, $characterCount)
    {
        $value = "";
        $isCompressed = !((1 & ord($subData[0])) >> 0);
        $hasAsian = 4 & ord($subData[0]) >> 2;
        $hasRichText = 8 & ord($subData[0]) >> 3;
        $value = self::encodeUTF16(substr($subData, 1, $isCompressed ? $characterCount : 2 * $characterCount), $isCompressed);
        return array("value" => $value, "size" => $isCompressed ? 1 + $characterCount : 1 + 2 * $characterCount);
    }
    /**
     * Convert UTF-8 string to string surounded by double quotes. Used for explicit string tokens in formulas.
     * Example:  hello"world  -->  "hello""world"
     *
     * @param string $value UTF-8 encoded string
     * @return string
     */
    private static function UTF8toExcelDoubleQuoted($value)
    {
        return "\"" . str_replace("\"", "\"\"", $value) . "\"";
    }
    /**
     * Reads first 8 bytes of a string and return IEEE 754 float
     *
     * @param string $data Binary string that is at least 8 bytes long
     * @return float
     */
    private static function extractNumber($data)
    {
        $rknumhigh = self::getInt4d($data, 4);
        $rknumlow = self::getInt4d($data, 0);
        $sign = ($rknumhigh & 2147483648.0) >> 31;
        $exp = (($rknumhigh & 2146435072) >> 20) - 1023;
        $mantissa = 1048576 | $rknumhigh & 1048575;
        $mantissalow1 = ($rknumlow & 2147483648.0) >> 31;
        $mantissalow2 = $rknumlow & 2147483647;
        $value = $mantissa / pow(2, 20 - $exp);
        if ($mantissalow1 != 0) {
            $value += 1 / pow(2, 21 - $exp);
        }
        $value += $mantissalow2 / pow(2, 52 - $exp);
        if ($sign) {
            $value *= -1;
        }
        return $value;
    }
    private static function getIEEE754($rknum)
    {
        if (($rknum & 2) != 0) {
            $value = $rknum >> 2;
        } else {
            $sign = ($rknum & 2147483648.0) >> 31;
            $exp = ($rknum & 2146435072) >> 20;
            $mantissa = 1048576 | $rknum & 1048572;
            $value = $mantissa / pow(2, 20 - ($exp - 1023));
            if ($sign) {
                $value = -1 * $value;
            }
        }
        if (($rknum & 1) != 0) {
            $value /= 100;
        }
        return $value;
    }
    /**
     * Get UTF-8 string from (compressed or uncompressed) UTF-16 string
     *
     * @param string $string
     * @param bool $compressed
     * @return string
     */
    private static function encodeUTF16($string, $compressed = "")
    {
        if ($compressed) {
            $string = self::uncompressByteString($string);
        }
        return PHPExcel_Shared_String::ConvertEncoding($string, "UTF-8", "UTF-16LE");
    }
    /**
     * Convert UTF-16 string in compressed notation to uncompressed form. Only used for BIFF8.
     *
     * @param string $string
     * @return string
     */
    private static function uncompressByteString($string)
    {
        $uncompressedString = "";
        $strLen = strlen($string);
        for ($i = 0; $i < $strLen; $i++) {
            $uncompressedString .= $string[$i] . "";
        }
        return $uncompressedString;
    }
    /**
     * Convert string to UTF-8. Only used for BIFF5.
     *
     * @param string $string
     * @return string
     */
    private function decodeCodepage($string)
    {
        return PHPExcel_Shared_String::ConvertEncoding($string, "UTF-8", $this->codepage);
    }
    /**
     * Read 16-bit unsigned integer
     *
     * @param string $data
     * @param int $pos
     * @return int
     */
    public static function getInt2d($data, $pos)
    {
        return ord($data[$pos]) | ord($data[$pos + 1]) << 8;
    }
    /**
     * Read 32-bit signed integer
     *
     * @param string $data
     * @param int $pos
     * @return int
     */
    public static function getInt4d($data, $pos)
    {
        $_or_24 = ord($data[$pos + 3]);
        if (128 <= $_or_24) {
            $_ord_24 = 0 - abs(256 - $_or_24 << 24);
        } else {
            $_ord_24 = ($_or_24 & 127) << 24;
        }
        return ord($data[$pos]) | ord($data[$pos + 1]) << 8 | ord($data[$pos + 2]) << 16 | $_ord_24;
    }
    private function parseRichText($is = "")
    {
        $value = new PHPExcel_RichText();
        $value->createText($is);
        return $value;
    }
}

?>