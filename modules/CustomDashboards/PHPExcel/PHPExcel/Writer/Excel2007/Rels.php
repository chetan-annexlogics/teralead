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
 * PHPExcel_Writer_Excel2007_Rels
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
 * @package    PHPExcel_Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Writer_Excel2007_Rels extends PHPExcel_Writer_Excel2007_WriterPart
{
    /**
     * Write relationships to XML format
     *
     * @param     PHPExcel    $pPHPExcel
     * @return     string         XML Output
     * @throws     PHPExcel_Writer_Exception
     */
    public function writeRelationships(PHPExcel $pPHPExcel = NULL)
    {
        $objWriter = NULL;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
        }
        $objWriter->startDocument("1.0", "UTF-8", "yes");
        $objWriter->startElement("Relationships");
        $objWriter->writeAttribute("xmlns", "http://schemas.openxmlformats.org/package/2006/relationships");
        $customPropertyList = $pPHPExcel->getProperties()->getCustomProperties();
        if (!empty($customPropertyList)) {
            $this->writeRelationship($objWriter, 4, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/custom-properties", "docProps/custom.xml");
        }
        $this->writeRelationship($objWriter, 3, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties", "docProps/app.xml");
        $this->writeRelationship($objWriter, 2, "http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties", "docProps/core.xml");
        $this->writeRelationship($objWriter, 1, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument", "xl/workbook.xml");
        if ($pPHPExcel->hasRibbon()) {
            $this->writeRelationShip($objWriter, 5, "http://schemas.microsoft.com/office/2006/relationships/ui/extensibility", $pPHPExcel->getRibbonXMLData("target"));
        }
        $objWriter->endElement();
        return $objWriter->getData();
    }
    /**
     * Write workbook relationships to XML format
     *
     * @param     PHPExcel    $pPHPExcel
     * @return     string         XML Output
     * @throws     PHPExcel_Writer_Exception
     */
    public function writeWorkbookRelationships(PHPExcel $pPHPExcel = NULL)
    {
        $objWriter = NULL;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
        }
        $objWriter->startDocument("1.0", "UTF-8", "yes");
        $objWriter->startElement("Relationships");
        $objWriter->writeAttribute("xmlns", "http://schemas.openxmlformats.org/package/2006/relationships");
        $this->writeRelationship($objWriter, 1, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles", "styles.xml");
        $this->writeRelationship($objWriter, 2, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme", "theme/theme1.xml");
        $this->writeRelationship($objWriter, 3, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings", "sharedStrings.xml");
        $sheetCount = $pPHPExcel->getSheetCount();
        for ($i = 0; $i < $sheetCount; $i++) {
            $this->writeRelationship($objWriter, $i + 1 + 3, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet", "worksheets/sheet" . ($i + 1) . ".xml");
        }
        if ($pPHPExcel->hasMacros()) {
            $this->writeRelationShip($objWriter, $i + 1 + 3, "http://schemas.microsoft.com/office/2006/relationships/vbaProject", "vbaProject.bin");
            $i++;
        }
        $objWriter->endElement();
        return $objWriter->getData();
    }
    /**
     * Write worksheet relationships to XML format
     *
     * Numbering is as follows:
     *     rId1                 - Drawings
     *  rId_hyperlink_x     - Hyperlinks
     *
     * @param     PHPExcel_Worksheet    $pWorksheet
     * @param     int                    $pWorksheetId
     * @param    boolean                $includeCharts    Flag indicating if we should write charts
     * @return     string                 XML Output
     * @throws     PHPExcel_Writer_Exception
     */
    public function writeWorksheetRelationships(PHPExcel_Worksheet $pWorksheet = NULL, $pWorksheetId = 1, $includeCharts = false)
    {
        $objWriter = NULL;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
        }
        $objWriter->startDocument("1.0", "UTF-8", "yes");
        $objWriter->startElement("Relationships");
        $objWriter->writeAttribute("xmlns", "http://schemas.openxmlformats.org/package/2006/relationships");
        $d = 0;
        if ($includeCharts) {
            $charts = $pWorksheet->getChartCollection();
        } else {
            $charts = array();
        }
        if (0 < $pWorksheet->getDrawingCollection()->count() || 0 < count($charts)) {
            $this->writeRelationship($objWriter, ++$d, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing", "../drawings/drawing" . $pWorksheetId . ".xml");
        }
        $i = 1;
        foreach ($pWorksheet->getHyperlinkCollection() as $hyperlink) {
            if (!$hyperlink->isInternal()) {
                $this->writeRelationship($objWriter, "_hyperlink_" . $i, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink", $hyperlink->getUrl(), "External");
                $i++;
            }
        }
        $i = 1;
        if (0 < count($pWorksheet->getComments())) {
            $this->writeRelationship($objWriter, "_comments_vml" . $i, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/vmlDrawing", "../drawings/vmlDrawing" . $pWorksheetId . ".vml");
            $this->writeRelationship($objWriter, "_comments" . $i, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/comments", "../comments" . $pWorksheetId . ".xml");
        }
        $i = 1;
        if (0 < count($pWorksheet->getHeaderFooter()->getImages())) {
            $this->writeRelationship($objWriter, "_headerfooter_vml" . $i, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/vmlDrawing", "../drawings/vmlDrawingHF" . $pWorksheetId . ".vml");
        }
        $objWriter->endElement();
        return $objWriter->getData();
    }
    /**
     * Write drawing relationships to XML format
     *
     * @param     PHPExcel_Worksheet    $pWorksheet
     * @param    int                    &$chartRef        Chart ID
     * @param    boolean                $includeCharts    Flag indicating if we should write charts
     * @return     string                 XML Output
     * @throws     PHPExcel_Writer_Exception
     */
    public function writeDrawingRelationships(PHPExcel_Worksheet $pWorksheet = NULL, &$chartRef, $includeCharts = false)
    {
        $objWriter = NULL;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
        }
        $objWriter->startDocument("1.0", "UTF-8", "yes");
        $objWriter->startElement("Relationships");
        $objWriter->writeAttribute("xmlns", "http://schemas.openxmlformats.org/package/2006/relationships");
        $i = 1;
        for ($iterator = $pWorksheet->getDrawingCollection()->getIterator(); $iterator->valid(); $i++) {
            if ($iterator->current() instanceof PHPExcel_Worksheet_Drawing || $iterator->current() instanceof PHPExcel_Worksheet_MemoryDrawing) {
                $this->writeRelationship($objWriter, $i, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/image", "../media/" . str_replace(" ", "", $iterator->current()->getIndexedFilename()));
            }
            $iterator->next();
        }
        if ($includeCharts) {
            $chartCount = $pWorksheet->getChartCount();
            if (0 < $chartCount) {
                for ($c = 0; $c < $chartCount; $c++) {
                    $this->writeRelationship($objWriter, $i++, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/chart", "../charts/chart" . ++$chartRef . ".xml");
                }
            }
        }
        $objWriter->endElement();
        return $objWriter->getData();
    }
    /**
     * Write header/footer drawing relationships to XML format
     *
     * @param     PHPExcel_Worksheet            $pWorksheet
     * @return     string                         XML Output
     * @throws     PHPExcel_Writer_Exception
     */
    public function writeHeaderFooterDrawingRelationships(PHPExcel_Worksheet $pWorksheet = NULL)
    {
        $objWriter = NULL;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
        }
        $objWriter->startDocument("1.0", "UTF-8", "yes");
        $objWriter->startElement("Relationships");
        $objWriter->writeAttribute("xmlns", "http://schemas.openxmlformats.org/package/2006/relationships");
        foreach ($pWorksheet->getHeaderFooter()->getImages() as $key => $value) {
            $this->writeRelationship($objWriter, $key, "http://schemas.openxmlformats.org/officeDocument/2006/relationships/image", "../media/" . $value->getIndexedFilename());
        }
        $objWriter->endElement();
        return $objWriter->getData();
    }
    /**
     * Write Override content type
     *
     * @param     PHPExcel_Shared_XMLWriter     $objWriter         XML Writer
     * @param     int                            $pId            Relationship ID. rId will be prepended!
     * @param     string                        $pType            Relationship type
     * @param     string                         $pTarget        Relationship target
     * @param     string                         $pTargetMode    Relationship target mode
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeRelationship(PHPExcel_Shared_XMLWriter $objWriter = NULL, $pId = 1, $pType = "", $pTarget = "", $pTargetMode = "")
    {
        if ($pType != "" && $pTarget != "") {
            $objWriter->startElement("Relationship");
            $objWriter->writeAttribute("Id", "rId" . $pId);
            $objWriter->writeAttribute("Type", $pType);
            $objWriter->writeAttribute("Target", $pTarget);
            if ($pTargetMode != "") {
                $objWriter->writeAttribute("TargetMode", $pTargetMode);
            }
            $objWriter->endElement();
        } else {
            throw new PHPExcel_Writer_Exception("Invalid parameters passed.");
        }
    }
}

?>