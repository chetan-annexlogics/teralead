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
 * PHPExcel_Writer_Excel5_Font
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
class PHPExcel_Writer_Excel5_Font
{
    /**
     * Color index
     *
     * @var int
     */
    private $colorIndex = NULL;
    /**
     * Font
     *
     * @var PHPExcel_Style_Font
     */
    private $font = NULL;
    /**
     * Map of BIFF2-BIFF8 codes for underline styles
     * @static    array of int
     *
     */
    private static $mapUnderline = NULL;
    /**
     * Constructor
     *
     * @param PHPExcel_Style_Font $font
     */
    public function __construct(PHPExcel_Style_Font $font = NULL)
    {
        $this->colorIndex = 32767;
        $this->font = $font;
    }
    /**
     * Set the color index
     *
     * @param int $colorIndex
     */
    public function setColorIndex($colorIndex)
    {
        $this->colorIndex = $colorIndex;
    }
    /**
     * Get font record data
     *
     * @return string
     */
    public function writeFont()
    {
        $font_outline = 0;
        $font_shadow = 0;
        $icv = $this->colorIndex;
        if ($this->font->getSuperScript()) {
            $sss = 1;
        } else {
            if ($this->font->getSubScript()) {
                $sss = 2;
            } else {
                $sss = 0;
            }
        }
        $bFamily = 0;
        $bCharSet = PHPExcel_Shared_Font::getCharsetFromFontName($this->font->getName());
        $record = 49;
        $reserved = 0;
        $grbit = 0;
        if ($this->font->getItalic()) {
            $grbit |= 2;
        }
        if ($this->font->getStrikethrough()) {
            $grbit |= 8;
        }
        if ($font_outline) {
            $grbit |= 16;
        }
        if ($font_shadow) {
            $grbit |= 32;
        }
        $data = pack("vvvvvCCCC", $this->font->getSize() * 20, $grbit, $icv, self::mapBold($this->font->getBold()), $sss, self::mapUnderline($this->font->getUnderline()), $bFamily, $bCharSet, $reserved);
        $data .= PHPExcel_Shared_String::UTF8toBIFF8UnicodeShort($this->font->getName());
        $length = strlen($data);
        $header = pack("vv", $record, $length);
        return $header . $data;
    }
    /**
     * Map to BIFF5-BIFF8 codes for bold
     *
     * @param boolean $bold
     * @return int
     */
    private static function mapBold($bold)
    {
        if ($bold) {
            return 700;
        }
        return 400;
    }
    /**
     * Map underline
     *
     * @param string
     * @return int
     */
    private static function mapUnderline($underline)
    {
        if (isset(self::$mapUnderline[$underline])) {
            return self::$mapUnderline[$underline];
        }
        return 0;
    }
}

?>