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
 * PHPExcel_Shared_Font
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Shared_Font
{
    private static $autoSizeMethods = NULL;
    /**
     * AutoSize method
     *
     * @var string
     */
    private static $autoSizeMethod = self::AUTOSIZE_METHOD_APPROX;
    /**
     * Path to folder containing TrueType font .ttf files
     *
     * @var string
     */
    private static $trueTypeFontPath = NULL;
    /**
     * How wide is a default column for a given default font and size?
     * Empirical data found by inspecting real Excel files and reading off the pixel width
     * in Microsoft Office Excel 2007.
     *
     * @var array
     */
    public static $defaultColumnWidths = array("Arial" => array("1" => array("px" => 24, "width" => 12), "2" => array("px" => 24, "width" => 12), "3" => array("px" => 32, "width" => 10.6640625), "4" => array("px" => 32, "width" => 10.6640625), "5" => array("px" => 40, "width" => 10), "6" => array("px" => 48, "width" => 9.59765625), "7" => array("px" => 48, "width" => 9.59765625), "8" => array("px" => 56, "width" => 9.33203125), "9" => array("px" => 64, "width" => 9.140625), "10" => array("px" => 64, "width" => 9.140625)), "Calibri" => array("1" => array("px" => 24, "width" => 12), "2" => array("px" => 24, "width" => 12), "3" => array("px" => 32, "width" => 10.6640625), "4" => array("px" => 32, "width" => 10.6640625), "5" => array("px" => 40, "width" => 10), "6" => array("px" => 48, "width" => 9.59765625), "7" => array("px" => 48, "width" => 9.59765625), "8" => array("px" => 56, "width" => 9.33203125), "9" => array("px" => 56, "width" => 9.33203125), "10" => array("px" => 64, "width" => 9.140625), "11" => array("px" => 64, "width" => 9.140625)), "Verdana" => array("1" => array("px" => 24, "width" => 12), "2" => array("px" => 24, "width" => 12), "3" => array("px" => 32, "width" => 10.6640625), "4" => array("px" => 32, "width" => 10.6640625), "5" => array("px" => 40, "width" => 10), "6" => array("px" => 48, "width" => 9.59765625), "7" => array("px" => 48, "width" => 9.59765625), "8" => array("px" => 64, "width" => 9.140625), "9" => array("px" => 72, "width" => 9), "10" => array("px" => 72, "width" => 9)));
    const AUTOSIZE_METHOD_APPROX = "approx";
    const AUTOSIZE_METHOD_EXACT = "exact";
    const CHARSET_ANSI_LATIN = 0;
    const CHARSET_SYSTEM_DEFAULT = 1;
    const CHARSET_SYMBOL = 2;
    const CHARSET_APPLE_ROMAN = 77;
    const CHARSET_ANSI_JAPANESE_SHIFTJIS = 128;
    const CHARSET_ANSI_KOREAN_HANGUL = 129;
    const CHARSET_ANSI_KOREAN_JOHAB = 130;
    const CHARSET_ANSI_CHINESE_SIMIPLIFIED = 134;
    const CHARSET_ANSI_CHINESE_TRADITIONAL = 136;
    const CHARSET_ANSI_GREEK = 161;
    const CHARSET_ANSI_TURKISH = 162;
    const CHARSET_ANSI_VIETNAMESE = 163;
    const CHARSET_ANSI_HEBREW = 177;
    const CHARSET_ANSI_ARABIC = 178;
    const CHARSET_ANSI_BALTIC = 186;
    const CHARSET_ANSI_CYRILLIC = 204;
    const CHARSET_ANSI_THAI = 221;
    const CHARSET_ANSI_LATIN_II = 238;
    const CHARSET_OEM_LATIN_I = 255;
    const ARIAL = "arial.ttf";
    const ARIAL_BOLD = "arialbd.ttf";
    const ARIAL_ITALIC = "ariali.ttf";
    const ARIAL_BOLD_ITALIC = "arialbi.ttf";
    const CALIBRI = "CALIBRI.TTF";
    const CALIBRI_BOLD = "CALIBRIB.TTF";
    const CALIBRI_ITALIC = "CALIBRII.TTF";
    const CALIBRI_BOLD_ITALIC = "CALIBRIZ.TTF";
    const COMIC_SANS_MS = "comic.ttf";
    const COMIC_SANS_MS_BOLD = "comicbd.ttf";
    const COURIER_NEW = "cour.ttf";
    const COURIER_NEW_BOLD = "courbd.ttf";
    const COURIER_NEW_ITALIC = "couri.ttf";
    const COURIER_NEW_BOLD_ITALIC = "courbi.ttf";
    const GEORGIA = "georgia.ttf";
    const GEORGIA_BOLD = "georgiab.ttf";
    const GEORGIA_ITALIC = "georgiai.ttf";
    const GEORGIA_BOLD_ITALIC = "georgiaz.ttf";
    const IMPACT = "impact.ttf";
    const LIBERATION_SANS = "LiberationSans-Regular.ttf";
    const LIBERATION_SANS_BOLD = "LiberationSans-Bold.ttf";
    const LIBERATION_SANS_ITALIC = "LiberationSans-Italic.ttf";
    const LIBERATION_SANS_BOLD_ITALIC = "LiberationSans-BoldItalic.ttf";
    const LUCIDA_CONSOLE = "lucon.ttf";
    const LUCIDA_SANS_UNICODE = "l_10646.ttf";
    const MICROSOFT_SANS_SERIF = "micross.ttf";
    const PALATINO_LINOTYPE = "pala.ttf";
    const PALATINO_LINOTYPE_BOLD = "palab.ttf";
    const PALATINO_LINOTYPE_ITALIC = "palai.ttf";
    const PALATINO_LINOTYPE_BOLD_ITALIC = "palabi.ttf";
    const SYMBOL = "symbol.ttf";
    const TAHOMA = "tahoma.ttf";
    const TAHOMA_BOLD = "tahomabd.ttf";
    const TIMES_NEW_ROMAN = "times.ttf";
    const TIMES_NEW_ROMAN_BOLD = "timesbd.ttf";
    const TIMES_NEW_ROMAN_ITALIC = "timesi.ttf";
    const TIMES_NEW_ROMAN_BOLD_ITALIC = "timesbi.ttf";
    const TREBUCHET_MS = "trebuc.ttf";
    const TREBUCHET_MS_BOLD = "trebucbd.ttf";
    const TREBUCHET_MS_ITALIC = "trebucit.ttf";
    const TREBUCHET_MS_BOLD_ITALIC = "trebucbi.ttf";
    const VERDANA = "verdana.ttf";
    const VERDANA_BOLD = "verdanab.ttf";
    const VERDANA_ITALIC = "verdanai.ttf";
    const VERDANA_BOLD_ITALIC = "verdanaz.ttf";
    /**
     * Set autoSize method
     *
     * @param string $pValue
     * @return     boolean                    Success or failure
     */
    public static function setAutoSizeMethod($pValue = self::AUTOSIZE_METHOD_APPROX)
    {
        if (!in_array($pValue, self::$autoSizeMethods)) {
            return false;
        }
        self::$autoSizeMethod = $pValue;
        return true;
    }
    /**
     * Get autoSize method
     *
     * @return string
     */
    public static function getAutoSizeMethod()
    {
        return self::$autoSizeMethod;
    }
    /**
     * Set the path to the folder containing .ttf files. There should be a trailing slash.
     * Typical locations on variout some platforms:
     *    <ul>
     *        <li>C:/Windows/Fonts/</li>
     *        <li>/usr/share/fonts/truetype/</li>
     *        <li>~/.fonts/</li>
     *    </ul>
     *
     * @param string $pValue
     */
    public static function setTrueTypeFontPath($pValue = "")
    {
        self::$trueTypeFontPath = $pValue;
    }
    /**
     * Get the path to the folder containing .ttf files.
     *
     * @return string
     */
    public static function getTrueTypeFontPath()
    {
        return self::$trueTypeFontPath;
    }
    /**
     * Calculate an (approximate) OpenXML column width, based on font size and text contained
     *
     * @param     PHPExcel_Style_Font            $font            Font object
     * @param     PHPExcel_RichText|string    $cellText        Text to calculate width
     * @param     integer                        $rotation        Rotation angle
     * @param     PHPExcel_Style_Font|NULL    $defaultFont    Font object
     * @return     integer        Column width
     */
    public static function calculateColumnWidth(PHPExcel_Style_Font $font, $cellText = "", $rotation = 0, PHPExcel_Style_Font $defaultFont = NULL)
    {
        if ($cellText instanceof PHPExcel_RichText) {
            $cellText = $cellText->getPlainText();
        }
        if (strpos($cellText, "\n") !== false) {
            $lineTexts = explode("\n", $cellText);
            $lineWidths = array();
            foreach ($lineTexts as $lineText) {
                $lineWidths[] = self::calculateColumnWidth($font, $lineText, $rotation = 0, $defaultFont);
            }
            return max($lineWidths);
        } else {
            $approximate = self::$autoSizeMethod == self::AUTOSIZE_METHOD_APPROX;
            if (!$approximate) {
                $columnWidthAdjust = ceil(self::getTextWidthPixelsExact("n", $font, 0) * 1.07);
                try {
                    $columnWidth = self::getTextWidthPixelsExact($cellText, $font, $rotation) + $columnWidthAdjust;
                } catch (PHPExcel_Exception $e) {
                    $approximate = true;
                }
            }
            if ($approximate) {
                $columnWidthAdjust = self::getTextWidthPixelsApprox("n", $font, 0);
                $columnWidth = self::getTextWidthPixelsApprox($cellText, $font, $rotation) + $columnWidthAdjust;
            }
            $columnWidth = PHPExcel_Shared_Drawing::pixelsToCellDimension($columnWidth, $defaultFont);
            return round($columnWidth, 6);
        }
    }
    /**
     * Get GD text width in pixels for a string of text in a certain font at a certain rotation angle
     *
     * @param string $text
     * @param PHPExcel_Style_Font
     * @param int $rotation
     * @return int
     * @throws PHPExcel_Exception
     */
    public static function getTextWidthPixelsExact($text, PHPExcel_Style_Font $font, $rotation = 0)
    {
        if (!function_exists("imagettfbbox")) {
            throw new PHPExcel_Exception("GD library needs to be enabled");
        }
        $fontFile = self::getTrueTypeFontFileFromFont($font);
        $textBox = imagettfbbox($font->getSize(), $rotation, $fontFile, $text);
        list($lowerLeftCornerX, $lowerRightCornerX, $upperRightCornerX, $upperLeftCornerX) = $textBox;
        $textWidth = max($lowerRightCornerX - $upperLeftCornerX, $upperRightCornerX - $lowerLeftCornerX);
        return $textWidth;
    }
    /**
     * Get approximate width in pixels for a string of text in a certain font at a certain rotation angle
     *
     * @param string $columnText
     * @param PHPExcel_Style_Font $font
     * @param int $rotation
     * @return int Text width in pixels (no padding added)
     */
    public static function getTextWidthPixelsApprox($columnText, PHPExcel_Style_Font $font = NULL, $rotation = 0)
    {
        $fontName = $font->getName();
        $fontSize = $font->getSize();
        switch ($fontName) {
            case "Calibri":
                $columnWidth = (int) (8.26 * PHPExcel_Shared_String::CountCharacters($columnText));
                $columnWidth = $columnWidth * $fontSize / 11;
                break;
            case "Arial":
                $columnWidth = (int) (8 * PHPExcel_Shared_String::CountCharacters($columnText));
                $columnWidth = $columnWidth * $fontSize / 10;
                break;
            case "Verdana":
                $columnWidth = (int) (8 * PHPExcel_Shared_String::CountCharacters($columnText));
                $columnWidth = $columnWidth * $fontSize / 10;
                break;
            default:
                $columnWidth = (int) (8.26 * PHPExcel_Shared_String::CountCharacters($columnText));
                $columnWidth = $columnWidth * $fontSize / 11;
                break;
        }
        if ($rotation !== 0) {
            if ($rotation == -165) {
                $columnWidth = 4;
            } else {
                $columnWidth = $columnWidth * cos(deg2rad($rotation)) + $fontSize * abs(sin(deg2rad($rotation))) / 5;
            }
        }
        return (int) $columnWidth;
    }
    /**
     * Calculate an (approximate) pixel size, based on a font points size
     *
     * @param     int        $fontSizeInPoints    Font size (in points)
     * @return     int        Font size (in pixels)
     */
    public static function fontSizeToPixels($fontSizeInPoints = 11)
    {
        return (int) (4 / 3 * $fontSizeInPoints);
    }
    /**
     * Calculate an (approximate) pixel size, based on inch size
     *
     * @param     int        $sizeInInch    Font size (in inch)
     * @return     int        Size (in pixels)
     */
    public static function inchSizeToPixels($sizeInInch = 1)
    {
        return $sizeInInch * 96;
    }
    /**
     * Calculate an (approximate) pixel size, based on centimeter size
     *
     * @param     int        $sizeInCm    Font size (in centimeters)
     * @return     int        Size (in pixels)
     */
    public static function centimeterSizeToPixels($sizeInCm = 1)
    {
        return $sizeInCm * 37.795275591;
    }
    /**
     * Returns the font path given the font
     *
     * @param PHPExcel_Style_Font
     * @return string Path to TrueType font file
     */
    public static function getTrueTypeFontFileFromFont($font)
    {
        if (!file_exists(self::$trueTypeFontPath) || !is_dir(self::$trueTypeFontPath)) {
            throw new PHPExcel_Exception("Valid directory to TrueType Font files not specified");
        }
        $name = $font->getName();
        $bold = $font->getBold();
        $italic = $font->getItalic();
        switch ($name) {
            case "Arial":
                $fontFile = $bold ? $italic ? self::ARIAL_BOLD_ITALIC : self::ARIAL_BOLD : ($italic ? self::ARIAL_ITALIC : self::ARIAL);
                break;
            case "Calibri":
                $fontFile = $bold ? $italic ? self::CALIBRI_BOLD_ITALIC : self::CALIBRI_BOLD : ($italic ? self::CALIBRI_ITALIC : self::CALIBRI);
                break;
            case "Courier New":
                $fontFile = $bold ? $italic ? self::COURIER_NEW_BOLD_ITALIC : self::COURIER_NEW_BOLD : ($italic ? self::COURIER_NEW_ITALIC : self::COURIER_NEW);
                break;
            case "Comic Sans MS":
                $fontFile = $bold ? self::COMIC_SANS_MS_BOLD : self::COMIC_SANS_MS;
                break;
            case "Georgia":
                $fontFile = $bold ? $italic ? self::GEORGIA_BOLD_ITALIC : self::GEORGIA_BOLD : ($italic ? self::GEORGIA_ITALIC : self::GEORGIA);
                break;
            case "Impact":
                $fontFile = self::IMPACT;
                break;
            case "Liberation Sans":
                $fontFile = $bold ? $italic ? self::LIBERATION_SANS_BOLD_ITALIC : self::LIBERATION_SANS_BOLD : ($italic ? self::LIBERATION_SANS_ITALIC : self::LIBERATION_SANS);
                break;
            case "Lucida Console":
                $fontFile = self::LUCIDA_CONSOLE;
                break;
            case "Lucida Sans Unicode":
                $fontFile = self::LUCIDA_SANS_UNICODE;
                break;
            case "Microsoft Sans Serif":
                $fontFile = self::MICROSOFT_SANS_SERIF;
                break;
            case "Palatino Linotype":
                $fontFile = $bold ? $italic ? self::PALATINO_LINOTYPE_BOLD_ITALIC : self::PALATINO_LINOTYPE_BOLD : ($italic ? self::PALATINO_LINOTYPE_ITALIC : self::PALATINO_LINOTYPE);
                break;
            case "Symbol":
                $fontFile = self::SYMBOL;
                break;
            case "Tahoma":
                $fontFile = $bold ? self::TAHOMA_BOLD : self::TAHOMA;
                break;
            case "Times New Roman":
                $fontFile = $bold ? $italic ? self::TIMES_NEW_ROMAN_BOLD_ITALIC : self::TIMES_NEW_ROMAN_BOLD : ($italic ? self::TIMES_NEW_ROMAN_ITALIC : self::TIMES_NEW_ROMAN);
                break;
            case "Trebuchet MS":
                $fontFile = $bold ? $italic ? self::TREBUCHET_MS_BOLD_ITALIC : self::TREBUCHET_MS_BOLD : ($italic ? self::TREBUCHET_MS_ITALIC : self::TREBUCHET_MS);
                break;
            case "Verdana":
                $fontFile = $bold ? $italic ? self::VERDANA_BOLD_ITALIC : self::VERDANA_BOLD : ($italic ? self::VERDANA_ITALIC : self::VERDANA);
                break;
            default:
                throw new PHPExcel_Exception("Unknown font name \"" . $name . "\". Cannot map to TrueType font file");
        }
        $fontFile = self::$trueTypeFontPath . $fontFile;
        if (!file_exists($fontFile)) {
            throw new PHPExcel_Exception("TrueType Font file not found");
        }
        return $fontFile;
    }
    /**
     * Returns the associated charset for the font name.
     *
     * @param string $name Font name
     * @return int Character set code
     */
    public static function getCharsetFromFontName($name)
    {
        switch ($name) {
            case "EucrosiaUPC":
                return self::CHARSET_ANSI_THAI;
            case "Wingdings":
                return self::CHARSET_SYMBOL;
            case "Wingdings 2":
                return self::CHARSET_SYMBOL;
            case "Wingdings 3":
                return self::CHARSET_SYMBOL;
        }
        return self::CHARSET_ANSI_LATIN;
    }
    /**
     * Get the effective column width for columns without a column dimension or column with width -1
     * For example, for Calibri 11 this is 9.140625 (64 px)
     *
     * @param PHPExcel_Style_Font $font The workbooks default font
     * @param boolean $pPixels true = return column width in pixels, false = return in OOXML units
     * @return mixed Column width
     */
    public static function getDefaultColumnWidthByFont(PHPExcel_Style_Font $font, $pPixels = false)
    {
        if (isset(self::$defaultColumnWidths[$font->getName()][$font->getSize()])) {
            $columnWidth = $pPixels ? self::$defaultColumnWidths[$font->getName()][$font->getSize()]["px"] : self::$defaultColumnWidths[$font->getName()][$font->getSize()]["width"];
        } else {
            $columnWidth = $pPixels ? self::$defaultColumnWidths["Calibri"][11]["px"] : self::$defaultColumnWidths["Calibri"][11]["width"];
            $columnWidth = $columnWidth * $font->getSize() / 11;
            if ($pPixels) {
                $columnWidth = (int) round($columnWidth);
            }
        }
        return $columnWidth;
    }
    /**
     * Get the effective row height for rows without a row dimension or rows with height -1
     * For example, for Calibri 11 this is 15 points
     *
     * @param PHPExcel_Style_Font $font The workbooks default font
     * @return float Row height in points
     */
    public static function getDefaultRowHeightByFont(PHPExcel_Style_Font $font)
    {
        switch ($font->getName()) {
            case "Arial":
                switch ($font->getSize()) {
                    case 10:
                        $rowHeight = 12.75;
                        break;
                    case 9:
                        $rowHeight = 12;
                        break;
                    case 8:
                        $rowHeight = 11.25;
                        break;
                    case 7:
                        $rowHeight = 9;
                        break;
                    case 6:
                    case 5:
                        $rowHeight = 8.25;
                        break;
                    case 4:
                        $rowHeight = 6.75;
                        break;
                    case 3:
                        $rowHeight = 6;
                        break;
                    case 2:
                    case 1:
                        $rowHeight = 5.25;
                        break;
                    default:
                        $rowHeight = 12.75 * $font->getSize() / 10;
                        break;
                }
            case "Calibri":
                switch ($font->getSize()) {
                    case 11:
                        $rowHeight = 15;
                        break;
                    case 10:
                        $rowHeight = 12.75;
                        break;
                    case 9:
                        $rowHeight = 12;
                        break;
                    case 8:
                        $rowHeight = 11.25;
                        break;
                    case 7:
                        $rowHeight = 9;
                        break;
                    case 6:
                    case 5:
                        $rowHeight = 8.25;
                        break;
                    case 4:
                        $rowHeight = 6.75;
                        break;
                    case 3:
                        $rowHeight = 6;
                        break;
                    case 2:
                    case 1:
                        $rowHeight = 5.25;
                        break;
                    default:
                        $rowHeight = 15 * $font->getSize() / 11;
                        break;
                }
            case "Verdana":
                switch ($font->getSize()) {
                    case 10:
                        $rowHeight = 12.75;
                        break;
                    case 9:
                        $rowHeight = 11.25;
                        break;
                    case 8:
                        $rowHeight = 10.5;
                        break;
                    case 7:
                        $rowHeight = 9;
                        break;
                    case 6:
                    case 5:
                        $rowHeight = 8.25;
                        break;
                    case 4:
                        $rowHeight = 6.75;
                        break;
                    case 3:
                        $rowHeight = 6;
                        break;
                    case 2:
                    case 1:
                        $rowHeight = 5.25;
                        break;
                    default:
                        $rowHeight = 12.75 * $font->getSize() / 10;
                        break;
                }
            default:
                $rowHeight = 15 * $font->getSize() / 11;
                break;
        }
        break;
    }
}

?>