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
 * PHPExcel_Writer_Excel5
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
class PHPExcel_Writer_Excel5 extends PHPExcel_Writer_Abstract implements PHPExcel_Writer_IWriter
{
    /**
     * PHPExcel object
     *
     * @var PHPExcel
     */
    private $phpExcel = NULL;
    /**
     * Total number of shared strings in workbook
     *
     * @var int
     */
    private $strTotal = 0;
    /**
     * Number of unique shared strings in workbook
     *
     * @var int
     */
    private $strUnique = 0;
    /**
     * Array of unique shared strings in workbook
     *
     * @var array
     */
    private $strTable = array();
    /**
     * Color cache. Mapping between RGB value and color index.
     *
     * @var array
     */
    private $colors = NULL;
    /**
     * Formula parser
     *
     * @var PHPExcel_Writer_Excel5_Parser
     */
    private $parser = NULL;
    /**
     * Identifier clusters for drawings. Used in MSODRAWINGGROUP record.
     *
     * @var array
     */
    private $IDCLs = NULL;
    /**
     * Basic OLE object summary information
     *
     * @var array
     */
    private $summaryInformation = NULL;
    /**
     * Extended OLE object document summary information
     *
     * @var array
     */
    private $documentSummaryInformation = NULL;
    /**
     * Create a new PHPExcel_Writer_Excel5
     *
     * @param    PHPExcel    $phpExcel    PHPExcel object
     */
    public function __construct(PHPExcel $phpExcel)
    {
        $this->phpExcel = $phpExcel;
        $this->parser = new PHPExcel_Writer_Excel5_Parser();
    }
    /**
     * Save PHPExcel to file
     *
     * @param    string        $pFilename
     * @throws    PHPExcel_Writer_Exception
     */
    public function save($pFilename = NULL)
    {
        $this->phpExcel->garbageCollect();
        $saveDebugLog = PHPExcel_Calculation::getInstance($this->phpExcel)->getDebugLog()->getWriteDebugLog();
        PHPExcel_Calculation::getInstance($this->phpExcel)->getDebugLog()->setWriteDebugLog(false);
        $saveDateReturnType = PHPExcel_Calculation_Functions::getReturnDateType();
        PHPExcel_Calculation_Functions::setReturnDateType(PHPExcel_Calculation_Functions::RETURNDATE_EXCEL);
        $this->colors = array();
        $this->writerWorkbook = new PHPExcel_Writer_Excel5_Workbook($this->phpExcel, $this->strTotal, $this->strUnique, $this->strTable, $this->colors, $this->parser);
        $countSheets = $this->phpExcel->getSheetCount();
        for ($i = 0; $i < $countSheets; $i++) {
            $this->writerWorksheets[$i] = new PHPExcel_Writer_Excel5_Worksheet($this->strTotal, $this->strUnique, $this->strTable, $this->colors, $this->parser, $this->preCalculateFormulas, $this->phpExcel->getSheet($i));
        }
        $this->buildWorksheetEschers();
        $this->buildWorkbookEscher();
        $cellXfCollection = $this->phpExcel->getCellXfCollection();
        for ($i = 0; $i < 15; $i++) {
            $this->writerWorkbook->addXfWriter($cellXfCollection[0], true);
        }
        foreach ($this->phpExcel->getCellXfCollection() as $style) {
            $this->writerWorkbook->addXfWriter($style, false);
        }
        for ($i = 0; $i < $countSheets; $i++) {
            foreach ($this->writerWorksheets[$i]->phpSheet->getCellCollection() as $cellID) {
                $cell = $this->writerWorksheets[$i]->phpSheet->getCell($cellID);
                $cVal = $cell->getValue();
                if ($cVal instanceof PHPExcel_RichText) {
                    $elements = $cVal->getRichTextElements();
                    foreach ($elements as $element) {
                        if ($element instanceof PHPExcel_RichText_Run) {
                            $font = $element->getFont();
                            $this->writerWorksheets[$i]->fontHashIndex[$font->getHashCode()] = $this->writerWorkbook->addFont($font);
                        }
                    }
                }
            }
        }
        $workbookStreamName = "Workbook";
        $OLE = new PHPExcel_Shared_OLE_PPS_File(PHPExcel_Shared_OLE::Asc2Ucs($workbookStreamName));
        $worksheetSizes = array();
        for ($i = 0; $i < $countSheets; $i++) {
            $this->writerWorksheets[$i]->close();
            $worksheetSizes[] = $this->writerWorksheets[$i]->_datasize;
        }
        $OLE->append($this->writerWorkbook->writeWorkbook($worksheetSizes));
        for ($i = 0; $i < $countSheets; $i++) {
            $OLE->append($this->writerWorksheets[$i]->getData());
        }
        $this->documentSummaryInformation = $this->writeDocumentSummaryInformation();
        if (isset($this->documentSummaryInformation) && !empty($this->documentSummaryInformation)) {
            $OLE_DocumentSummaryInformation = new PHPExcel_Shared_OLE_PPS_File(PHPExcel_Shared_OLE::Asc2Ucs(chr(5) . "DocumentSummaryInformation"));
            $OLE_DocumentSummaryInformation->append($this->documentSummaryInformation);
        }
        $this->summaryInformation = $this->writeSummaryInformation();
        if (isset($this->summaryInformation) && !empty($this->summaryInformation)) {
            $OLE_SummaryInformation = new PHPExcel_Shared_OLE_PPS_File(PHPExcel_Shared_OLE::Asc2Ucs(chr(5) . "SummaryInformation"));
            $OLE_SummaryInformation->append($this->summaryInformation);
        }
        $arrRootData = array($OLE);
        if (isset($OLE_SummaryInformation)) {
            $arrRootData[] = $OLE_SummaryInformation;
        }
        if (isset($OLE_DocumentSummaryInformation)) {
            $arrRootData[] = $OLE_DocumentSummaryInformation;
        }
        $root = new PHPExcel_Shared_OLE_PPS_Root(time(), time(), $arrRootData);
        $res = $root->save($pFilename);
        PHPExcel_Calculation_Functions::setReturnDateType($saveDateReturnType);
        PHPExcel_Calculation::getInstance($this->phpExcel)->getDebugLog()->setWriteDebugLog($saveDebugLog);
    }
    /**
     * Set temporary storage directory
     *
     * @deprecated
     * @param    string    $pValue        Temporary storage directory
     * @throws    PHPExcel_Writer_Exception    when directory does not exist
     * @return PHPExcel_Writer_Excel5
     */
    public function setTempDir($pValue = "")
    {
        return $this;
    }
    /**
     * Build the Worksheet Escher objects
     *
     */
    private function buildWorksheetEschers()
    {
        $blipIndex = 0;
        $lastReducedSpId = 0;
        $lastSpId = 0;
        foreach ($this->phpExcel->getAllsheets() as $sheet) {
            $sheetIndex = $sheet->getParent()->getIndex($sheet);
            $escher = NULL;
            $filterRange = $sheet->getAutoFilter()->getRange();
            if (count($sheet->getDrawingCollection()) == 0 && empty($filterRange)) {
                continue;
            }
            $escher = new PHPExcel_Shared_Escher();
            $dgContainer = new PHPExcel_Shared_Escher_DgContainer();
            $dgId = $sheet->getParent()->getIndex($sheet) + 1;
            $dgContainer->setDgId($dgId);
            $escher->setDgContainer($dgContainer);
            $spgrContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer();
            $dgContainer->setSpgrContainer($spgrContainer);
            $spContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer();
            $spContainer->setSpgr(true);
            $spContainer->setSpType(0);
            $spContainer->setSpId($sheet->getParent()->getIndex($sheet) + 1 << 10);
            $spgrContainer->addChild($spContainer);
            $countShapes[$sheetIndex] = 0;
            foreach ($sheet->getDrawingCollection() as $drawing) {
                $blipIndex++;
                $countShapes[$sheetIndex]++;
                $spContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer();
                $spContainer->setSpType(75);
                $spContainer->setSpFlag(2);
                $reducedSpId = $countShapes[$sheetIndex];
                $spId = $reducedSpId | $sheet->getParent()->getIndex($sheet) + 1 << 10;
                $spContainer->setSpId($spId);
                $lastReducedSpId = $reducedSpId;
                $lastSpId = $spId;
                $spContainer->setOPT(16644, $blipIndex);
                $coordinates = $drawing->getCoordinates();
                $offsetX = $drawing->getOffsetX();
                $offsetY = $drawing->getOffsetY();
                $width = $drawing->getWidth();
                $height = $drawing->getHeight();
                $twoAnchor = PHPExcel_Shared_Excel5::oneAnchor2twoAnchor($sheet, $coordinates, $offsetX, $offsetY, $width, $height);
                $spContainer->setStartCoordinates($twoAnchor["startCoordinates"]);
                $spContainer->setStartOffsetX($twoAnchor["startOffsetX"]);
                $spContainer->setStartOffsetY($twoAnchor["startOffsetY"]);
                $spContainer->setEndCoordinates($twoAnchor["endCoordinates"]);
                $spContainer->setEndOffsetX($twoAnchor["endOffsetX"]);
                $spContainer->setEndOffsetY($twoAnchor["endOffsetY"]);
                $spgrContainer->addChild($spContainer);
            }
            if (!empty($filterRange)) {
                $rangeBounds = PHPExcel_Cell::rangeBoundaries($filterRange);
                $iNumColStart = $rangeBounds[0][0];
                $iNumColEnd = $rangeBounds[1][0];
                for ($iInc = $iNumColStart; $iInc <= $iNumColEnd; $iInc++) {
                    $countShapes[$sheetIndex]++;
                    $oDrawing = new PHPExcel_Worksheet_BaseDrawing();
                    $cDrawing = PHPExcel_Cell::stringFromColumnIndex($iInc - 1) . $rangeBounds[0][1];
                    $oDrawing->setCoordinates($cDrawing);
                    $oDrawing->setWorksheet($sheet);
                    $spContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer();
                    $spContainer->setSpType(201);
                    $spContainer->setSpFlag(1);
                    $reducedSpId = $countShapes[$sheetIndex];
                    $spId = $reducedSpId | $sheet->getParent()->getIndex($sheet) + 1 << 10;
                    $spContainer->setSpId($spId);
                    $lastReducedSpId = $reducedSpId;
                    $lastSpId = $spId;
                    $spContainer->setOPT(127, 17039620);
                    $spContainer->setOPT(191, 524296);
                    $spContainer->setOPT(447, 65536);
                    $spContainer->setOPT(511, 524288);
                    $spContainer->setOPT(959, 655360);
                    $endCoordinates = PHPExcel_Cell::stringFromColumnIndex(PHPExcel_Cell::stringFromColumnIndex($iInc - 1));
                    $endCoordinates .= $rangeBounds[0][1] + 1;
                    $spContainer->setStartCoordinates($cDrawing);
                    $spContainer->setStartOffsetX(0);
                    $spContainer->setStartOffsetY(0);
                    $spContainer->setEndCoordinates($endCoordinates);
                    $spContainer->setEndOffsetX(0);
                    $spContainer->setEndOffsetY(0);
                    $spgrContainer->addChild($spContainer);
                }
            }
            $this->IDCLs[$dgId] = $lastReducedSpId;
            $dgContainer->setLastSpId($lastSpId);
            $this->writerWorksheets[$sheetIndex]->setEscher($escher);
        }
    }
    /**
     * Build the Escher object corresponding to the MSODRAWINGGROUP record
     */
    private function buildWorkbookEscher()
    {
        $escher = NULL;
        $found = false;
        foreach ($this->phpExcel->getAllSheets() as $sheet) {
            if (0 < count($sheet->getDrawingCollection())) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            return NULL;
        }
        $escher = new PHPExcel_Shared_Escher();
        $dggContainer = new PHPExcel_Shared_Escher_DggContainer();
        $escher->setDggContainer($dggContainer);
        $dggContainer->setIDCLs($this->IDCLs);
        $spIdMax = 0;
        $totalCountShapes = 0;
        $countDrawings = 0;
        foreach ($this->phpExcel->getAllsheets() as $sheet) {
            $sheetCountShapes = 0;
            if (0 < count($sheet->getDrawingCollection())) {
                $countDrawings++;
                foreach ($sheet->getDrawingCollection() as $drawing) {
                    $sheetCountShapes++;
                    $totalCountShapes++;
                    $spId = $sheetCountShapes | $this->phpExcel->getIndex($sheet) + 1 << 10;
                    $spIdMax = max($spId, $spIdMax);
                }
            }
        }
        $dggContainer->setSpIdMax($spIdMax + 1);
        $dggContainer->setCDgSaved($countDrawings);
        $dggContainer->setCSpSaved($totalCountShapes + $countDrawings);
        $bstoreContainer = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer();
        $dggContainer->setBstoreContainer($bstoreContainer);
        foreach ($this->phpExcel->getAllsheets() as $sheet) {
            foreach ($sheet->getDrawingCollection() as $drawing) {
                if ($drawing instanceof PHPExcel_Worksheet_Drawing) {
                    $filename = $drawing->getPath();
                    list($imagesx, $imagesy, $imageFormat) = getimagesize($filename);
                    switch ($imageFormat) {
                        case 1:
                            $blipType = PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_PNG;
                            ob_start();
                            imagepng(imagecreatefromgif($filename));
                            $blipData = ob_get_contents();
                            ob_end_clean();
                            break;
                        case 2:
                            $blipType = PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_JPEG;
                            $blipData = file_get_contents($filename);
                            break;
                        case 3:
                            $blipType = PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_PNG;
                            $blipData = file_get_contents($filename);
                            break;
                        case 6:
                            $blipType = PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_PNG;
                            ob_start();
                            imagepng(PHPExcel_Shared_Drawing::imagecreatefrombmp($filename));
                            $blipData = ob_get_contents();
                            ob_end_clean();
                            break;
                        default:
                            continue 2;
                    }
                    $blip = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip();
                    $blip->setData($blipData);
                    $BSE = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE();
                    $BSE->setBlipType($blipType);
                    $BSE->setBlip($blip);
                    $bstoreContainer->addBSE($BSE);
                } else {
                    if ($drawing instanceof PHPExcel_Worksheet_MemoryDrawing) {
                        switch ($drawing->getRenderingFunction()) {
                            case PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG:
                                $blipType = PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_JPEG;
                                $renderingFunction = "imagejpeg";
                                break;
                            case PHPExcel_Worksheet_MemoryDrawing::RENDERING_GIF:
                            case PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG:
                            case PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT:
                                $blipType = PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_PNG;
                                $renderingFunction = "imagepng";
                                break;
                        }
                        ob_start();
                        call_user_func($renderingFunction, $drawing->getImageResource());
                        $blipData = ob_get_contents();
                        ob_end_clean();
                        $blip = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip();
                        $blip->setData($blipData);
                        $BSE = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE();
                        $BSE->setBlipType($blipType);
                        $BSE->setBlip($blip);
                        $bstoreContainer->addBSE($BSE);
                    }
                }
            }
        }
        $this->writerWorkbook->setEscher($escher);
    }
    /**
     * Build the OLE Part for DocumentSummary Information
     * @return string
     */
    private function writeDocumentSummaryInformation()
    {
        $data = pack("v", 65534);
        $data .= pack("v", 0);
        $data .= pack("v", 262);
        $data .= pack("v", 2);
        $data .= pack("VVVV", 0, 0, 0, 0);
        $data .= pack("V", 1);
        $data .= pack("vvvvvvvv", 54530, 54733, 11932, 4123, 38803, 8, 11307, 44793);
        $data .= pack("V", 48);
        $dataSection = array();
        $dataSection_NumProps = 0;
        $dataSection_Summary = "";
        $dataSection_Content = "";
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 1), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 2), "data" => array("data" => 1252));
        $dataSection_NumProps++;
        if ($this->phpExcel->getProperties()->getCategory()) {
            $dataProp = $this->phpExcel->getProperties()->getCategory();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 2), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 30), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
            $dataSection_NumProps++;
        }
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 23), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 3), "data" => array("pack" => "V", "data" => 786432));
        $dataSection_NumProps++;
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 11), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 11), "data" => array("data" => false));
        $dataSection_NumProps++;
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 16), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 11), "data" => array("data" => false));
        $dataSection_NumProps++;
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 19), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 11), "data" => array("data" => false));
        $dataSection_NumProps++;
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 22), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 11), "data" => array("data" => false));
        $dataSection_NumProps++;
        $dataProp = pack("v", 1);
        $dataProp .= pack("v", 0);
        $dataProp .= pack("v", 10);
        $dataProp .= pack("v", 0);
        $dataProp .= "Worksheet" . chr(0);
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 13), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 4126), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
        $dataSection_NumProps++;
        $dataProp = pack("v", 2);
        $dataProp .= pack("v", 0);
        $dataProp .= pack("v", 30);
        $dataProp .= pack("v", 0);
        $dataProp .= pack("v", 19);
        $dataProp .= pack("v", 0);
        $dataProp .= "Feuilles de calcul";
        $dataProp .= pack("v", 768);
        $dataProp .= pack("v", 0);
        $dataProp .= pack("v", 256);
        $dataProp .= pack("v", 0);
        $dataProp .= pack("v", 0);
        $dataProp .= pack("v", 0);
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 12), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 4108), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
        $dataSection_NumProps++;
        $dataSection_Content_Offset = 8 + $dataSection_NumProps * 8;
        foreach ($dataSection as $dataProp) {
            $dataSection_Summary .= pack($dataProp["summary"]["pack"], $dataProp["summary"]["data"]);
            $dataSection_Summary .= pack($dataProp["offset"]["pack"], $dataSection_Content_Offset);
            $dataSection_Content .= pack($dataProp["type"]["pack"], $dataProp["type"]["data"]);
            if ($dataProp["type"]["data"] == 2) {
                $dataSection_Content .= pack("V", $dataProp["data"]["data"]);
                $dataSection_Content_Offset += 4 + 4;
            } else {
                if ($dataProp["type"]["data"] == 3) {
                    $dataSection_Content .= pack("V", $dataProp["data"]["data"]);
                    $dataSection_Content_Offset += 4 + 4;
                } else {
                    if ($dataProp["type"]["data"] == 11) {
                        if ($dataProp["data"]["data"] == false) {
                            $dataSection_Content .= pack("V", 0);
                        } else {
                            $dataSection_Content .= pack("V", 1);
                        }
                        $dataSection_Content_Offset += 4 + 4;
                    } else {
                        if ($dataProp["type"]["data"] == 30) {
                            $dataProp["data"]["data"] .= chr(0);
                            $dataProp["data"]["length"] += 1;
                            $dataProp["data"]["length"] = $dataProp["data"]["length"] + (4 - $dataProp["data"]["length"] % 4 == 4 ? 0 : 4 - $dataProp["data"]["length"] % 4);
                            $dataProp["data"]["data"] = str_pad($dataProp["data"]["data"], $dataProp["data"]["length"], chr(0), STR_PAD_RIGHT);
                            $dataSection_Content .= pack("V", $dataProp["data"]["length"]);
                            $dataSection_Content .= $dataProp["data"]["data"];
                            $dataSection_Content_Offset += 4 + 4 + strlen($dataProp["data"]["data"]);
                        } else {
                            if ($dataProp["type"]["data"] == 64) {
                                $dataSection_Content .= $dataProp["data"]["data"];
                                $dataSection_Content_Offset += 4 + 8;
                            } else {
                                $dataSection_Content .= $dataProp["data"]["data"];
                                $dataSection_Content_Offset += 4 + $dataProp["data"]["length"];
                            }
                        }
                    }
                }
            }
        }
        $data .= pack("V", $dataSection_Content_Offset);
        $data .= pack("V", $dataSection_NumProps);
        $data .= $dataSection_Summary;
        $data .= $dataSection_Content;
        return $data;
    }
    /**
     * Build the OLE Part for Summary Information
     * @return string
     */
    private function writeSummaryInformation()
    {
        $data = pack("v", 65534);
        $data .= pack("v", 0);
        $data .= pack("v", 262);
        $data .= pack("v", 2);
        $data .= pack("VVVV", 0, 0, 0, 0);
        $data .= pack("V", 1);
        $data .= pack("vvvvvvvv", 34272, 62111, 20473, 4200, 37291, 8, 10027, 55731);
        $data .= pack("V", 48);
        $dataSection = array();
        $dataSection_NumProps = 0;
        $dataSection_Summary = "";
        $dataSection_Content = "";
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 1), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 2), "data" => array("data" => 1252));
        $dataSection_NumProps++;
        if ($this->phpExcel->getProperties()->getTitle()) {
            $dataProp = $this->phpExcel->getProperties()->getTitle();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 2), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 30), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
            $dataSection_NumProps++;
        }
        if ($this->phpExcel->getProperties()->getSubject()) {
            $dataProp = $this->phpExcel->getProperties()->getSubject();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 3), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 30), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
            $dataSection_NumProps++;
        }
        if ($this->phpExcel->getProperties()->getCreator()) {
            $dataProp = $this->phpExcel->getProperties()->getCreator();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 4), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 30), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
            $dataSection_NumProps++;
        }
        if ($this->phpExcel->getProperties()->getKeywords()) {
            $dataProp = $this->phpExcel->getProperties()->getKeywords();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 5), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 30), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
            $dataSection_NumProps++;
        }
        if ($this->phpExcel->getProperties()->getDescription()) {
            $dataProp = $this->phpExcel->getProperties()->getDescription();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 6), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 30), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
            $dataSection_NumProps++;
        }
        if ($this->phpExcel->getProperties()->getLastModifiedBy()) {
            $dataProp = $this->phpExcel->getProperties()->getLastModifiedBy();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 8), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 30), "data" => array("data" => $dataProp, "length" => strlen($dataProp)));
            $dataSection_NumProps++;
        }
        if ($this->phpExcel->getProperties()->getCreated()) {
            $dataProp = $this->phpExcel->getProperties()->getCreated();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 12), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 64), "data" => array("data" => PHPExcel_Shared_OLE::LocalDate2OLE($dataProp)));
            $dataSection_NumProps++;
        }
        if ($this->phpExcel->getProperties()->getModified()) {
            $dataProp = $this->phpExcel->getProperties()->getModified();
            $dataSection[] = array("summary" => array("pack" => "V", "data" => 13), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 64), "data" => array("data" => PHPExcel_Shared_OLE::LocalDate2OLE($dataProp)));
            $dataSection_NumProps++;
        }
        $dataSection[] = array("summary" => array("pack" => "V", "data" => 19), "offset" => array("pack" => "V"), "type" => array("pack" => "V", "data" => 3), "data" => array("data" => 0));
        $dataSection_NumProps++;
        $dataSection_Content_Offset = 8 + $dataSection_NumProps * 8;
        foreach ($dataSection as $dataProp) {
            $dataSection_Summary .= pack($dataProp["summary"]["pack"], $dataProp["summary"]["data"]);
            $dataSection_Summary .= pack($dataProp["offset"]["pack"], $dataSection_Content_Offset);
            $dataSection_Content .= pack($dataProp["type"]["pack"], $dataProp["type"]["data"]);
            if ($dataProp["type"]["data"] == 2) {
                $dataSection_Content .= pack("V", $dataProp["data"]["data"]);
                $dataSection_Content_Offset += 4 + 4;
            } else {
                if ($dataProp["type"]["data"] == 3) {
                    $dataSection_Content .= pack("V", $dataProp["data"]["data"]);
                    $dataSection_Content_Offset += 4 + 4;
                } else {
                    if ($dataProp["type"]["data"] == 30) {
                        $dataProp["data"]["data"] .= chr(0);
                        $dataProp["data"]["length"] += 1;
                        $dataProp["data"]["length"] = $dataProp["data"]["length"] + (4 - $dataProp["data"]["length"] % 4 == 4 ? 0 : 4 - $dataProp["data"]["length"] % 4);
                        $dataProp["data"]["data"] = str_pad($dataProp["data"]["data"], $dataProp["data"]["length"], chr(0), STR_PAD_RIGHT);
                        $dataSection_Content .= pack("V", $dataProp["data"]["length"]);
                        $dataSection_Content .= $dataProp["data"]["data"];
                        $dataSection_Content_Offset += 4 + 4 + strlen($dataProp["data"]["data"]);
                    } else {
                        if ($dataProp["type"]["data"] == 64) {
                            $dataSection_Content .= $dataProp["data"]["data"];
                            $dataSection_Content_Offset += 4 + 8;
                        }
                    }
                }
            }
        }
        $data .= pack("V", $dataSection_Content_Offset);
        $data .= pack("V", $dataSection_NumProps);
        $data .= $dataSection_Summary;
        $data .= $dataSection_Content;
        return $data;
    }
}

?>