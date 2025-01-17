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
 * PHPExcel_Writer_Excel2007_Style
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
class PHPExcel_Writer_Excel2007_Style extends PHPExcel_Writer_Excel2007_WriterPart
{
    /**
     * Write styles to XML format
     *
     * @param     PHPExcel    $pPHPExcel
     * @return     string         XML Output
     * @throws     PHPExcel_Writer_Exception
     */
    public function writeStyles(PHPExcel $pPHPExcel = NULL)
    {
        $objWriter = NULL;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
        }
        $objWriter->startDocument("1.0", "UTF-8", "yes");
        $objWriter->startElement("styleSheet");
        $objWriter->writeAttribute("xml:space", "preserve");
        $objWriter->writeAttribute("xmlns", "http://schemas.openxmlformats.org/spreadsheetml/2006/main");
        $objWriter->startElement("numFmts");
        $objWriter->writeAttribute("count", $this->getParentWriter()->getNumFmtHashTable()->count());
        for ($i = 0; $i < $this->getParentWriter()->getNumFmtHashTable()->count(); $i++) {
            $this->writeNumFmt($objWriter, $this->getParentWriter()->getNumFmtHashTable()->getByIndex($i), $i);
        }
        $objWriter->endElement();
        $objWriter->startElement("fonts");
        $objWriter->writeAttribute("count", $this->getParentWriter()->getFontHashTable()->count());
        for ($i = 0; $i < $this->getParentWriter()->getFontHashTable()->count(); $i++) {
            $this->writeFont($objWriter, $this->getParentWriter()->getFontHashTable()->getByIndex($i));
        }
        $objWriter->endElement();
        $objWriter->startElement("fills");
        $objWriter->writeAttribute("count", $this->getParentWriter()->getFillHashTable()->count());
        for ($i = 0; $i < $this->getParentWriter()->getFillHashTable()->count(); $i++) {
            $this->writeFill($objWriter, $this->getParentWriter()->getFillHashTable()->getByIndex($i));
        }
        $objWriter->endElement();
        $objWriter->startElement("borders");
        $objWriter->writeAttribute("count", $this->getParentWriter()->getBordersHashTable()->count());
        for ($i = 0; $i < $this->getParentWriter()->getBordersHashTable()->count(); $i++) {
            $this->writeBorder($objWriter, $this->getParentWriter()->getBordersHashTable()->getByIndex($i));
        }
        $objWriter->endElement();
        $objWriter->startElement("cellStyleXfs");
        $objWriter->writeAttribute("count", 1);
        $objWriter->startElement("xf");
        $objWriter->writeAttribute("numFmtId", 0);
        $objWriter->writeAttribute("fontId", 0);
        $objWriter->writeAttribute("fillId", 0);
        $objWriter->writeAttribute("borderId", 0);
        $objWriter->endElement();
        $objWriter->endElement();
        $objWriter->startElement("cellXfs");
        $objWriter->writeAttribute("count", count($pPHPExcel->getCellXfCollection()));
        foreach ($pPHPExcel->getCellXfCollection() as $cellXf) {
            $this->writeCellStyleXf($objWriter, $cellXf, $pPHPExcel);
        }
        $objWriter->endElement();
        $objWriter->startElement("cellStyles");
        $objWriter->writeAttribute("count", 1);
        $objWriter->startElement("cellStyle");
        $objWriter->writeAttribute("name", "Normal");
        $objWriter->writeAttribute("xfId", 0);
        $objWriter->writeAttribute("builtinId", 0);
        $objWriter->endElement();
        $objWriter->endElement();
        $objWriter->startElement("dxfs");
        $objWriter->writeAttribute("count", $this->getParentWriter()->getStylesConditionalHashTable()->count());
        for ($i = 0; $i < $this->getParentWriter()->getStylesConditionalHashTable()->count(); $i++) {
            $this->writeCellStyleDxf($objWriter, $this->getParentWriter()->getStylesConditionalHashTable()->getByIndex($i)->getStyle());
        }
        $objWriter->endElement();
        $objWriter->startElement("tableStyles");
        $objWriter->writeAttribute("defaultTableStyle", "TableStyleMedium9");
        $objWriter->writeAttribute("defaultPivotStyle", "PivotTableStyle1");
        $objWriter->endElement();
        $objWriter->endElement();
        return $objWriter->getData();
    }
    /**
     * Write Fill
     *
     * @param     PHPExcel_Shared_XMLWriter     $objWriter         XML Writer
     * @param     PHPExcel_Style_Fill            $pFill            Fill style
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeFill(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Style_Fill $pFill = NULL)
    {
        if ($pFill->getFillType() === PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR || $pFill->getFillType() === PHPExcel_Style_Fill::FILL_GRADIENT_PATH) {
            $this->writeGradientFill($objWriter, $pFill);
        } else {
            if ($pFill->getFillType() !== NULL) {
                $this->writePatternFill($objWriter, $pFill);
            }
        }
    }
    /**
     * Write Gradient Fill
     *
     * @param     PHPExcel_Shared_XMLWriter     $objWriter         XML Writer
     * @param     PHPExcel_Style_Fill            $pFill            Fill style
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeGradientFill(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Style_Fill $pFill = NULL)
    {
        $objWriter->startElement("fill");
        $objWriter->startElement("gradientFill");
        $objWriter->writeAttribute("type", $pFill->getFillType());
        $objWriter->writeAttribute("degree", $pFill->getRotation());
        $objWriter->startElement("stop");
        $objWriter->writeAttribute("position", "0");
        $objWriter->startElement("color");
        $objWriter->writeAttribute("rgb", $pFill->getStartColor()->getARGB());
        $objWriter->endElement();
        $objWriter->endElement();
        $objWriter->startElement("stop");
        $objWriter->writeAttribute("position", "1");
        $objWriter->startElement("color");
        $objWriter->writeAttribute("rgb", $pFill->getEndColor()->getARGB());
        $objWriter->endElement();
        $objWriter->endElement();
        $objWriter->endElement();
        $objWriter->endElement();
    }
    /**
     * Write Pattern Fill
     *
     * @param     PHPExcel_Shared_XMLWriter            $objWriter         XML Writer
     * @param     PHPExcel_Style_Fill                    $pFill            Fill style
     * @throws     PHPExcel_Writer_Exception
     */
    private function writePatternFill(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Style_Fill $pFill = NULL)
    {
        $objWriter->startElement("fill");
        $objWriter->startElement("patternFill");
        $objWriter->writeAttribute("patternType", $pFill->getFillType());
        if ($pFill->getFillType() !== PHPExcel_Style_Fill::FILL_NONE && $pFill->getStartColor()->getARGB()) {
            $objWriter->startElement("fgColor");
            $objWriter->writeAttribute("rgb", $pFill->getStartColor()->getARGB());
            $objWriter->endElement();
        }
        if ($pFill->getFillType() !== PHPExcel_Style_Fill::FILL_NONE && $pFill->getEndColor()->getARGB()) {
            $objWriter->startElement("bgColor");
            $objWriter->writeAttribute("rgb", $pFill->getEndColor()->getARGB());
            $objWriter->endElement();
        }
        $objWriter->endElement();
        $objWriter->endElement();
    }
    /**
     * Write Font
     *
     * @param     PHPExcel_Shared_XMLWriter        $objWriter         XML Writer
     * @param     PHPExcel_Style_Font                $pFont            Font style
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeFont(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Style_Font $pFont = NULL)
    {
        $objWriter->startElement("font");
        if ($pFont->getBold() !== NULL) {
            $objWriter->startElement("b");
            $objWriter->writeAttribute("val", $pFont->getBold() ? "1" : "0");
            $objWriter->endElement();
        }
        if ($pFont->getItalic() !== NULL) {
            $objWriter->startElement("i");
            $objWriter->writeAttribute("val", $pFont->getItalic() ? "1" : "0");
            $objWriter->endElement();
        }
        if ($pFont->getStrikethrough() !== NULL) {
            $objWriter->startElement("strike");
            $objWriter->writeAttribute("val", $pFont->getStrikethrough() ? "1" : "0");
            $objWriter->endElement();
        }
        if ($pFont->getUnderline() !== NULL) {
            $objWriter->startElement("u");
            $objWriter->writeAttribute("val", $pFont->getUnderline());
            $objWriter->endElement();
        }
        if ($pFont->getSuperScript() === true || $pFont->getSubScript() === true) {
            $objWriter->startElement("vertAlign");
            if ($pFont->getSuperScript() === true) {
                $objWriter->writeAttribute("val", "superscript");
            } else {
                if ($pFont->getSubScript() === true) {
                    $objWriter->writeAttribute("val", "subscript");
                }
            }
            $objWriter->endElement();
        }
        if ($pFont->getSize() !== NULL) {
            $objWriter->startElement("sz");
            $objWriter->writeAttribute("val", $pFont->getSize());
            $objWriter->endElement();
        }
        if ($pFont->getColor()->getARGB() !== NULL) {
            $objWriter->startElement("color");
            $objWriter->writeAttribute("rgb", $pFont->getColor()->getARGB());
            $objWriter->endElement();
        }
        if ($pFont->getName() !== NULL) {
            $objWriter->startElement("name");
            $objWriter->writeAttribute("val", $pFont->getName());
            $objWriter->endElement();
        }
        $objWriter->endElement();
    }
    /**
     * Write Border
     *
     * @param     PHPExcel_Shared_XMLWriter            $objWriter         XML Writer
     * @param     PHPExcel_Style_Borders                $pBorders        Borders style
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeBorder(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Style_Borders $pBorders = NULL)
    {
        $objWriter->startElement("border");
        switch ($pBorders->getDiagonalDirection()) {
            case PHPExcel_Style_Borders::DIAGONAL_UP:
                $objWriter->writeAttribute("diagonalUp", "true");
                $objWriter->writeAttribute("diagonalDown", "false");
                break;
            case PHPExcel_Style_Borders::DIAGONAL_DOWN:
                $objWriter->writeAttribute("diagonalUp", "false");
                $objWriter->writeAttribute("diagonalDown", "true");
                break;
            case PHPExcel_Style_Borders::DIAGONAL_BOTH:
                $objWriter->writeAttribute("diagonalUp", "true");
                $objWriter->writeAttribute("diagonalDown", "true");
                break;
        }
        $this->writeBorderPr($objWriter, "left", $pBorders->getLeft());
        $this->writeBorderPr($objWriter, "right", $pBorders->getRight());
        $this->writeBorderPr($objWriter, "top", $pBorders->getTop());
        $this->writeBorderPr($objWriter, "bottom", $pBorders->getBottom());
        $this->writeBorderPr($objWriter, "diagonal", $pBorders->getDiagonal());
        $objWriter->endElement();
    }
    /**
     * Write Cell Style Xf
     *
     * @param     PHPExcel_Shared_XMLWriter            $objWriter         XML Writer
     * @param     PHPExcel_Style                        $pStyle            Style
     * @param     PHPExcel                            $pPHPExcel        Workbook
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeCellStyleXf(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Style $pStyle = NULL, PHPExcel $pPHPExcel = NULL)
    {
        $objWriter->startElement("xf");
        $objWriter->writeAttribute("xfId", 0);
        $objWriter->writeAttribute("fontId", (int) $this->getParentWriter()->getFontHashTable()->getIndexForHashCode($pStyle->getFont()->getHashCode()));
        if ($pStyle->getQuotePrefix()) {
            $objWriter->writeAttribute("quotePrefix", 1);
        }
        if ($pStyle->getNumberFormat()->getBuiltInFormatCode() === false) {
            $objWriter->writeAttribute("numFmtId", (int) ($this->getParentWriter()->getNumFmtHashTable()->getIndexForHashCode($pStyle->getNumberFormat()->getHashCode()) + 164));
        } else {
            $objWriter->writeAttribute("numFmtId", (int) $pStyle->getNumberFormat()->getBuiltInFormatCode());
        }
        $objWriter->writeAttribute("fillId", (int) $this->getParentWriter()->getFillHashTable()->getIndexForHashCode($pStyle->getFill()->getHashCode()));
        $objWriter->writeAttribute("borderId", (int) $this->getParentWriter()->getBordersHashTable()->getIndexForHashCode($pStyle->getBorders()->getHashCode()));
        $objWriter->writeAttribute("applyFont", $pPHPExcel->getDefaultStyle()->getFont()->getHashCode() != $pStyle->getFont()->getHashCode() ? "1" : "0");
        $objWriter->writeAttribute("applyNumberFormat", $pPHPExcel->getDefaultStyle()->getNumberFormat()->getHashCode() != $pStyle->getNumberFormat()->getHashCode() ? "1" : "0");
        $objWriter->writeAttribute("applyFill", $pPHPExcel->getDefaultStyle()->getFill()->getHashCode() != $pStyle->getFill()->getHashCode() ? "1" : "0");
        $objWriter->writeAttribute("applyBorder", $pPHPExcel->getDefaultStyle()->getBorders()->getHashCode() != $pStyle->getBorders()->getHashCode() ? "1" : "0");
        $objWriter->writeAttribute("applyAlignment", $pPHPExcel->getDefaultStyle()->getAlignment()->getHashCode() != $pStyle->getAlignment()->getHashCode() ? "1" : "0");
        if ($pStyle->getProtection()->getLocked() != PHPExcel_Style_Protection::PROTECTION_INHERIT || $pStyle->getProtection()->getHidden() != PHPExcel_Style_Protection::PROTECTION_INHERIT) {
            $objWriter->writeAttribute("applyProtection", "true");
        }
        $objWriter->startElement("alignment");
        $objWriter->writeAttribute("horizontal", $pStyle->getAlignment()->getHorizontal());
        $objWriter->writeAttribute("vertical", $pStyle->getAlignment()->getVertical());
        $textRotation = 0;
        if (0 <= $pStyle->getAlignment()->getTextRotation()) {
            $textRotation = $pStyle->getAlignment()->getTextRotation();
        } else {
            if ($pStyle->getAlignment()->getTextRotation() < 0) {
                $textRotation = 90 - $pStyle->getAlignment()->getTextRotation();
            }
        }
        $objWriter->writeAttribute("textRotation", $textRotation);
        $objWriter->writeAttribute("wrapText", $pStyle->getAlignment()->getWrapText() ? "true" : "false");
        $objWriter->writeAttribute("shrinkToFit", $pStyle->getAlignment()->getShrinkToFit() ? "true" : "false");
        if (0 < $pStyle->getAlignment()->getIndent()) {
            $objWriter->writeAttribute("indent", $pStyle->getAlignment()->getIndent());
        }
        if (0 < $pStyle->getAlignment()->getReadorder()) {
            $objWriter->writeAttribute("readingOrder", $pStyle->getAlignment()->getReadorder());
        }
        $objWriter->endElement();
        if ($pStyle->getProtection()->getLocked() != PHPExcel_Style_Protection::PROTECTION_INHERIT || $pStyle->getProtection()->getHidden() != PHPExcel_Style_Protection::PROTECTION_INHERIT) {
            $objWriter->startElement("protection");
            if ($pStyle->getProtection()->getLocked() != PHPExcel_Style_Protection::PROTECTION_INHERIT) {
                $objWriter->writeAttribute("locked", $pStyle->getProtection()->getLocked() == PHPExcel_Style_Protection::PROTECTION_PROTECTED ? "true" : "false");
            }
            if ($pStyle->getProtection()->getHidden() != PHPExcel_Style_Protection::PROTECTION_INHERIT) {
                $objWriter->writeAttribute("hidden", $pStyle->getProtection()->getHidden() == PHPExcel_Style_Protection::PROTECTION_PROTECTED ? "true" : "false");
            }
            $objWriter->endElement();
        }
        $objWriter->endElement();
    }
    /**
     * Write Cell Style Dxf
     *
     * @param     PHPExcel_Shared_XMLWriter         $objWriter         XML Writer
     * @param     PHPExcel_Style                    $pStyle            Style
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeCellStyleDxf(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Style $pStyle = NULL)
    {
        $objWriter->startElement("dxf");
        $this->writeFont($objWriter, $pStyle->getFont());
        $this->writeNumFmt($objWriter, $pStyle->getNumberFormat());
        $this->writeFill($objWriter, $pStyle->getFill());
        $objWriter->startElement("alignment");
        if ($pStyle->getAlignment()->getHorizontal() !== NULL) {
            $objWriter->writeAttribute("horizontal", $pStyle->getAlignment()->getHorizontal());
        }
        if ($pStyle->getAlignment()->getVertical() !== NULL) {
            $objWriter->writeAttribute("vertical", $pStyle->getAlignment()->getVertical());
        }
        if ($pStyle->getAlignment()->getTextRotation() !== NULL) {
            $textRotation = 0;
            if (0 <= $pStyle->getAlignment()->getTextRotation()) {
                $textRotation = $pStyle->getAlignment()->getTextRotation();
            } else {
                if ($pStyle->getAlignment()->getTextRotation() < 0) {
                    $textRotation = 90 - $pStyle->getAlignment()->getTextRotation();
                }
            }
            $objWriter->writeAttribute("textRotation", $textRotation);
        }
        $objWriter->endElement();
        $this->writeBorder($objWriter, $pStyle->getBorders());
        if (($pStyle->getProtection()->getLocked() !== NULL || $pStyle->getProtection()->getHidden() !== NULL) && ($pStyle->getProtection()->getLocked() !== PHPExcel_Style_Protection::PROTECTION_INHERIT || $pStyle->getProtection()->getHidden() !== PHPExcel_Style_Protection::PROTECTION_INHERIT)) {
            $objWriter->startElement("protection");
            if ($pStyle->getProtection()->getLocked() !== NULL && $pStyle->getProtection()->getLocked() !== PHPExcel_Style_Protection::PROTECTION_INHERIT) {
                $objWriter->writeAttribute("locked", $pStyle->getProtection()->getLocked() == PHPExcel_Style_Protection::PROTECTION_PROTECTED ? "true" : "false");
            }
            if ($pStyle->getProtection()->getHidden() !== NULL && $pStyle->getProtection()->getHidden() !== PHPExcel_Style_Protection::PROTECTION_INHERIT) {
                $objWriter->writeAttribute("hidden", $pStyle->getProtection()->getHidden() == PHPExcel_Style_Protection::PROTECTION_PROTECTED ? "true" : "false");
            }
            $objWriter->endElement();
        }
        $objWriter->endElement();
    }
    /**
     * Write BorderPr
     *
     * @param     PHPExcel_Shared_XMLWriter        $objWriter         XML Writer
     * @param     string                            $pName            Element name
     * @param     PHPExcel_Style_Border            $pBorder        Border style
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeBorderPr(PHPExcel_Shared_XMLWriter $objWriter = NULL, $pName = "left", PHPExcel_Style_Border $pBorder = NULL)
    {
        if ($pBorder->getBorderStyle() != PHPExcel_Style_Border::BORDER_NONE) {
            $objWriter->startElement($pName);
            $objWriter->writeAttribute("style", $pBorder->getBorderStyle());
            $objWriter->startElement("color");
            $objWriter->writeAttribute("rgb", $pBorder->getColor()->getARGB());
            $objWriter->endElement();
            $objWriter->endElement();
        }
    }
    /**
     * Write NumberFormat
     *
     * @param     PHPExcel_Shared_XMLWriter            $objWriter         XML Writer
     * @param     PHPExcel_Style_NumberFormat            $pNumberFormat    Number Format
     * @param     int                                    $pId            Number Format identifier
     * @throws     PHPExcel_Writer_Exception
     */
    private function writeNumFmt(PHPExcel_Shared_XMLWriter $objWriter = NULL, PHPExcel_Style_NumberFormat $pNumberFormat = NULL, $pId = 0)
    {
        $formatCode = $pNumberFormat->getFormatCode();
        if ($formatCode !== NULL) {
            $objWriter->startElement("numFmt");
            $objWriter->writeAttribute("numFmtId", $pId + 164);
            $objWriter->writeAttribute("formatCode", $formatCode);
            $objWriter->endElement();
        }
    }
    /**
     * Get an array of all styles
     *
     * @param     PHPExcel                $pPHPExcel
     * @return     PHPExcel_Style[]        All styles in PHPExcel
     * @throws     PHPExcel_Writer_Exception
     */
    public function allStyles(PHPExcel $pPHPExcel = NULL)
    {
        return $pPHPExcel->getCellXfCollection();
    }
    /**
     * Get an array of all conditional styles
     *
     * @param     PHPExcel                            $pPHPExcel
     * @return     PHPExcel_Style_Conditional[]        All conditional styles in PHPExcel
     * @throws     PHPExcel_Writer_Exception
     */
    public function allConditionalStyles(PHPExcel $pPHPExcel = NULL)
    {
        $aStyles = array();
        $sheetCount = $pPHPExcel->getSheetCount();
        for ($i = 0; $i < $sheetCount; $i++) {
            foreach ($pPHPExcel->getSheet($i)->getConditionalStylesCollection() as $conditionalStyles) {
                foreach ($conditionalStyles as $conditionalStyle) {
                    $aStyles[] = $conditionalStyle;
                }
            }
        }
        return $aStyles;
    }
    /**
     * Get an array of all fills
     *
     * @param     PHPExcel                        $pPHPExcel
     * @return     PHPExcel_Style_Fill[]        All fills in PHPExcel
     * @throws     PHPExcel_Writer_Exception
     */
    public function allFills(PHPExcel $pPHPExcel = NULL)
    {
        $aFills = array();
        $fill0 = new PHPExcel_Style_Fill();
        $fill0->setFillType(PHPExcel_Style_Fill::FILL_NONE);
        $aFills[] = $fill0;
        $fill1 = new PHPExcel_Style_Fill();
        $fill1->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
        $aFills[] = $fill1;
        $aStyles = $this->allStyles($pPHPExcel);
        foreach ($aStyles as $style) {
            if (!array_key_exists($style->getFill()->getHashCode(), $aFills)) {
                $aFills[$style->getFill()->getHashCode()] = $style->getFill();
            }
        }
        return $aFills;
    }
    /**
     * Get an array of all fonts
     *
     * @param     PHPExcel                        $pPHPExcel
     * @return     PHPExcel_Style_Font[]        All fonts in PHPExcel
     * @throws     PHPExcel_Writer_Exception
     */
    public function allFonts(PHPExcel $pPHPExcel = NULL)
    {
        $aFonts = array();
        $aStyles = $this->allStyles($pPHPExcel);
        foreach ($aStyles as $style) {
            if (!array_key_exists($style->getFont()->getHashCode(), $aFonts)) {
                $aFonts[$style->getFont()->getHashCode()] = $style->getFont();
            }
        }
        return $aFonts;
    }
    /**
     * Get an array of all borders
     *
     * @param     PHPExcel                        $pPHPExcel
     * @return     PHPExcel_Style_Borders[]        All borders in PHPExcel
     * @throws     PHPExcel_Writer_Exception
     */
    public function allBorders(PHPExcel $pPHPExcel = NULL)
    {
        $aBorders = array();
        $aStyles = $this->allStyles($pPHPExcel);
        foreach ($aStyles as $style) {
            if (!array_key_exists($style->getBorders()->getHashCode(), $aBorders)) {
                $aBorders[$style->getBorders()->getHashCode()] = $style->getBorders();
            }
        }
        return $aBorders;
    }
    /**
     * Get an array of all number formats
     *
     * @param     PHPExcel                                $pPHPExcel
     * @return     PHPExcel_Style_NumberFormat[]        All number formats in PHPExcel
     * @throws     PHPExcel_Writer_Exception
     */
    public function allNumberFormats(PHPExcel $pPHPExcel = NULL)
    {
        $aNumFmts = array();
        $aStyles = $this->allStyles($pPHPExcel);
        foreach ($aStyles as $style) {
            if ($style->getNumberFormat()->getBuiltInFormatCode() === false && !array_key_exists($style->getNumberFormat()->getHashCode(), $aNumFmts)) {
                $aNumFmts[$style->getNumberFormat()->getHashCode()] = $style->getNumberFormat();
            }
        }
        return $aNumFmts;
    }
}

?>