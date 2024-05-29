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
 * PHPExcel_Writer_Excel5_Xf
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
class PHPExcel_Writer_Excel5_Xf
{
    /**
     * Style XF or a cell XF ?
     *
     * @var boolean
     */
    private $isStyleXf = NULL;
    /**
     * Index to the FONT record. Index 4 does not exist
     * @var integer
     */
    private $fontIndex = NULL;
    /**
     * An index (2 bytes) to a FORMAT record (number format).
     * @var integer
     */
    private $numberFormatIndex = NULL;
    /**
     * 1 bit, apparently not used.
     * @var integer
     */
    private $textJustLast = NULL;
    /**
     * The cell's foreground color.
     * @var integer
     */
    private $foregroundColor = NULL;
    /**
     * The cell's background color.
     * @var integer
     */
    private $backgroundColor = NULL;
    /**
     * Color of the bottom border of the cell.
     * @var integer
     */
    private $bottomBorderColor = NULL;
    /**
     * Color of the top border of the cell.
     * @var integer
     */
    private $topBorderColor = NULL;
    /**
     * Color of the left border of the cell.
     * @var integer
     */
    private $leftBorderColor = NULL;
    /**
     * Color of the right border of the cell.
     * @var integer
     */
    private $rightBorderColor = NULL;
    /**
     * Map of BIFF2-BIFF8 codes for border styles
     * @static    array of int
     *
     */
    private static $mapBorderStyles = NULL;
    /**
     * Map of BIFF2-BIFF8 codes for fill types
     * @static    array of int
     *
     */
    private static $mapFillTypes = NULL;
    /**
     * Map of BIFF2-BIFF8 codes for horizontal alignment
     * @static    array of int
     *
     */
    private static $mapHAlignments = NULL;
    /**
     * Map of BIFF2-BIFF8 codes for vertical alignment
     * @static    array of int
     *
     */
    private static $mapVAlignments = NULL;
    /**
     * Constructor
     *
     * @access public
     * @param PHPExcel_Style    The XF format
     */
    public function __construct(PHPExcel_Style $style = NULL)
    {
        $this->isStyleXf = false;
        $this->fontIndex = 0;
        $this->numberFormatIndex = 0;
        $this->textJustLast = 0;
        $this->foregroundColor = 64;
        $this->backgroundColor = 65;
        $this->_diag = 0;
        $this->bottomBorderColor = 64;
        $this->topBorderColor = 64;
        $this->leftBorderColor = 64;
        $this->rightBorderColor = 64;
        $this->_diag_color = 64;
        $this->_style = $style;
    }
    /**
     * Generate an Excel BIFF XF record (style or cell).
     *
     * @return string The XF record
     */
    public function writeXf()
    {
        if ($this->isStyleXf) {
            $style = 65525;
        } else {
            $style = self::mapLocked($this->_style->getProtection()->getLocked());
            $style |= self::mapHidden($this->_style->getProtection()->getHidden()) << 1;
        }
        $atr_num = $this->numberFormatIndex != 0 ? 1 : 0;
        $atr_fnt = $this->fontIndex != 0 ? 1 : 0;
        $atr_alc = (int) $this->_style->getAlignment()->getWrapText() ? 1 : 0;
        $atr_bdr = self::mapBorderStyle($this->_style->getBorders()->getBottom()->getBorderStyle()) || self::mapBorderStyle($this->_style->getBorders()->getTop()->getBorderStyle()) || self::mapBorderStyle($this->_style->getBorders()->getLeft()->getBorderStyle()) || self::mapBorderStyle($this->_style->getBorders()->getRight()->getBorderStyle()) ? 1 : 0;
        $atr_pat = $this->foregroundColor != 64 || $this->backgroundColor != 65 || self::mapFillType($this->_style->getFill()->getFillType()) ? 1 : 0;
        $atr_prot = self::mapLocked($this->_style->getProtection()->getLocked()) | self::mapHidden($this->_style->getProtection()->getHidden());
        if (self::mapBorderStyle($this->_style->getBorders()->getBottom()->getBorderStyle()) == 0) {
            $this->bottomBorderColor = 0;
        }
        if (self::mapBorderStyle($this->_style->getBorders()->getTop()->getBorderStyle()) == 0) {
            $this->topBorderColor = 0;
        }
        if (self::mapBorderStyle($this->_style->getBorders()->getRight()->getBorderStyle()) == 0) {
            $this->rightBorderColor = 0;
        }
        if (self::mapBorderStyle($this->_style->getBorders()->getLeft()->getBorderStyle()) == 0) {
            $this->leftBorderColor = 0;
        }
        if (self::mapBorderStyle($this->_style->getBorders()->getDiagonal()->getBorderStyle()) == 0) {
            $this->_diag_color = 0;
        }
        $record = 224;
        $length = 20;
        $ifnt = $this->fontIndex;
        $ifmt = $this->numberFormatIndex;
        $align = $this->mapHAlign($this->_style->getAlignment()->getHorizontal());
        $align |= (int) $this->_style->getAlignment()->getWrapText() << 3;
        $align |= self::mapVAlign($this->_style->getAlignment()->getVertical()) << 4;
        $align |= $this->textJustLast << 7;
        $used_attrib = $atr_num << 2;
        $used_attrib |= $atr_fnt << 3;
        $used_attrib |= $atr_alc << 4;
        $used_attrib |= $atr_bdr << 5;
        $used_attrib |= $atr_pat << 6;
        $used_attrib |= $atr_prot << 7;
        $icv = $this->foregroundColor;
        $icv |= $this->backgroundColor << 7;
        $border1 = self::mapBorderStyle($this->_style->getBorders()->getLeft()->getBorderStyle());
        $border1 |= self::mapBorderStyle($this->_style->getBorders()->getRight()->getBorderStyle()) << 4;
        $border1 |= self::mapBorderStyle($this->_style->getBorders()->getTop()->getBorderStyle()) << 8;
        $border1 |= self::mapBorderStyle($this->_style->getBorders()->getBottom()->getBorderStyle()) << 12;
        $border1 |= $this->leftBorderColor << 16;
        $border1 |= $this->rightBorderColor << 23;
        $diagonalDirection = $this->_style->getBorders()->getDiagonalDirection();
        $diag_tl_to_rb = $diagonalDirection == PHPExcel_Style_Borders::DIAGONAL_BOTH || $diagonalDirection == PHPExcel_Style_Borders::DIAGONAL_DOWN;
        $diag_tr_to_lb = $diagonalDirection == PHPExcel_Style_Borders::DIAGONAL_BOTH || $diagonalDirection == PHPExcel_Style_Borders::DIAGONAL_UP;
        $border1 |= $diag_tl_to_rb << 30;
        $border1 |= $diag_tr_to_lb << 31;
        $border2 = $this->topBorderColor;
        $border2 |= $this->bottomBorderColor << 7;
        $border2 |= $this->_diag_color << 14;
        $border2 |= self::mapBorderStyle($this->_style->getBorders()->getDiagonal()->getBorderStyle()) << 21;
        $border2 |= self::mapFillType($this->_style->getFill()->getFillType()) << 26;
        $header = pack("vv", $record, $length);
        $biff8_options = $this->_style->getAlignment()->getIndent();
        $biff8_options |= (int) $this->_style->getAlignment()->getShrinkToFit() << 4;
        $data = pack("vvvC", $ifnt, $ifmt, $style, $align);
        $data .= pack("CCC", self::mapTextRotation($this->_style->getAlignment()->getTextRotation()), $biff8_options, $used_attrib);
        $data .= pack("VVv", $border1, $border2, $icv);
        return $header . $data;
    }
    /**
     * Is this a style XF ?
     *
     * @param boolean $value
     */
    public function setIsStyleXf($value)
    {
        $this->isStyleXf = $value;
    }
    /**
     * Sets the cell's bottom border color
     *
     * @access public
     * @param int $colorIndex Color index
     */
    public function setBottomColor($colorIndex)
    {
        $this->bottomBorderColor = $colorIndex;
    }
    /**
     * Sets the cell's top border color
     *
     * @access public
     * @param int $colorIndex Color index
     */
    public function setTopColor($colorIndex)
    {
        $this->topBorderColor = $colorIndex;
    }
    /**
     * Sets the cell's left border color
     *
     * @access public
     * @param int $colorIndex Color index
     */
    public function setLeftColor($colorIndex)
    {
        $this->leftBorderColor = $colorIndex;
    }
    /**
     * Sets the cell's right border color
     *
     * @access public
     * @param int $colorIndex Color index
     */
    public function setRightColor($colorIndex)
    {
        $this->rightBorderColor = $colorIndex;
    }
    /**
     * Sets the cell's diagonal border color
     *
     * @access public
     * @param int $colorIndex Color index
     */
    public function setDiagColor($colorIndex)
    {
        $this->_diag_color = $colorIndex;
    }
    /**
     * Sets the cell's foreground color
     *
     * @access public
     * @param int $colorIndex Color index
     */
    public function setFgColor($colorIndex)
    {
        $this->foregroundColor = $colorIndex;
    }
    /**
     * Sets the cell's background color
     *
     * @access public
     * @param int $colorIndex Color index
     */
    public function setBgColor($colorIndex)
    {
        $this->backgroundColor = $colorIndex;
    }
    /**
     * Sets the index to the number format record
     * It can be date, time, currency, etc...
     *
     * @access public
     * @param integer $numberFormatIndex Index to format record
     */
    public function setNumberFormatIndex($numberFormatIndex)
    {
        $this->numberFormatIndex = $numberFormatIndex;
    }
    /**
     * Set the font index.
     *
     * @param int $value Font index, note that value 4 does not exist
     */
    public function setFontIndex($value)
    {
        $this->fontIndex = $value;
    }
    /**
     * Map border style
     *
     * @param string $borderStyle
     * @return int
     */
    private static function mapBorderStyle($borderStyle)
    {
        if (isset(self::$mapBorderStyles[$borderStyle])) {
            return self::$mapBorderStyles[$borderStyle];
        }
        return 0;
    }
    /**
     * Map fill type
     *
     * @param string $fillType
     * @return int
     */
    private static function mapFillType($fillType)
    {
        if (isset(self::$mapFillTypes[$fillType])) {
            return self::$mapFillTypes[$fillType];
        }
        return 0;
    }
    /**
     * Map to BIFF2-BIFF8 codes for horizontal alignment
     *
     * @param string $hAlign
     * @return int
     */
    private function mapHAlign($hAlign)
    {
        if (isset(self::$mapHAlignments[$hAlign])) {
            return self::$mapHAlignments[$hAlign];
        }
        return 0;
    }
    /**
     * Map to BIFF2-BIFF8 codes for vertical alignment
     *
     * @param string $vAlign
     * @return int
     */
    private static function mapVAlign($vAlign)
    {
        if (isset(self::$mapVAlignments[$vAlign])) {
            return self::$mapVAlignments[$vAlign];
        }
        return 2;
    }
    /**
     * Map to BIFF8 codes for text rotation angle
     *
     * @param int $textRotation
     * @return int
     */
    private static function mapTextRotation($textRotation)
    {
        if (0 <= $textRotation) {
            return $textRotation;
        }
        if ($textRotation == -165) {
            return 255;
        }
        if ($textRotation < 0) {
            return 90 - $textRotation;
        }
    }
    /**
     * Map locked
     *
     * @param string
     * @return int
     */
    private static function mapLocked($locked)
    {
        switch ($locked) {
            case PHPExcel_Style_Protection::PROTECTION_INHERIT:
                return 1;
            case PHPExcel_Style_Protection::PROTECTION_PROTECTED:
                return 1;
            case PHPExcel_Style_Protection::PROTECTION_UNPROTECTED:
                return 0;
        }
        return 1;
    }
    /**
     * Map hidden
     *
     * @param string
     * @return int
     */
    private static function mapHidden($hidden)
    {
        switch ($hidden) {
            case PHPExcel_Style_Protection::PROTECTION_INHERIT:
                return 0;
            case PHPExcel_Style_Protection::PROTECTION_PROTECTED:
                return 1;
            case PHPExcel_Style_Protection::PROTECTION_UNPROTECTED:
                return 0;
        }
        return 0;
    }
}

?>