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
 * PHPExcel_Shared_Drawing
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Shared_Drawing
{
    /**
     * Convert pixels to EMU
     *
     * @param     int $pValue    Value in pixels
     * @return     int            Value in EMU
     */
    public static function pixelsToEMU($pValue = 0)
    {
        return round($pValue * 9525);
    }
    /**
     * Convert EMU to pixels
     *
     * @param     int $pValue    Value in EMU
     * @return     int            Value in pixels
     */
    public static function EMUToPixels($pValue = 0)
    {
        if ($pValue != 0) {
            return round($pValue / 9525);
        }
        return 0;
    }
    /**
     * Convert pixels to column width. Exact algorithm not known.
     * By inspection of a real Excel file using Calibri 11, one finds 1000px ~ 142.85546875
     * This gives a conversion factor of 7. Also, we assume that pixels and font size are proportional.
     *
     * @param     int $pValue    Value in pixels
     * @param     PHPExcel_Style_Font $pDefaultFont    Default font of the workbook
     * @return     int            Value in cell dimension
     */
    public static function pixelsToCellDimension($pValue = 0, PHPExcel_Style_Font $pDefaultFont)
    {
        $name = $pDefaultFont->getName();
        $size = $pDefaultFont->getSize();
        if (isset(PHPExcel_Shared_Font::$defaultColumnWidths[$name][$size])) {
            $colWidth = $pValue * PHPExcel_Shared_Font::$defaultColumnWidths[$name][$size]["width"] / PHPExcel_Shared_Font::$defaultColumnWidths[$name][$size]["px"];
        } else {
            $colWidth = $pValue * 11 * PHPExcel_Shared_Font::$defaultColumnWidths["Calibri"][11]["width"] / PHPExcel_Shared_Font::$defaultColumnWidths["Calibri"][11]["px"] / $size;
        }
        return $colWidth;
    }
    /**
     * Convert column width from (intrinsic) Excel units to pixels
     *
     * @param     float    $pValue        Value in cell dimension
     * @param     PHPExcel_Style_Font $pDefaultFont    Default font of the workbook
     * @return     int        Value in pixels
     */
    public static function cellDimensionToPixels($pValue = 0, PHPExcel_Style_Font $pDefaultFont)
    {
        $name = $pDefaultFont->getName();
        $size = $pDefaultFont->getSize();
        if (isset(PHPExcel_Shared_Font::$defaultColumnWidths[$name][$size])) {
            $colWidth = $pValue * PHPExcel_Shared_Font::$defaultColumnWidths[$name][$size]["px"] / PHPExcel_Shared_Font::$defaultColumnWidths[$name][$size]["width"];
        } else {
            $colWidth = $pValue * $size * PHPExcel_Shared_Font::$defaultColumnWidths["Calibri"][11]["px"] / PHPExcel_Shared_Font::$defaultColumnWidths["Calibri"][11]["width"] / 11;
        }
        $colWidth = (int) round($colWidth);
        return $colWidth;
    }
    /**
     * Convert pixels to points
     *
     * @param     int $pValue    Value in pixels
     * @return     int            Value in points
     */
    public static function pixelsToPoints($pValue = 0)
    {
        return $pValue * 0.6777777699999999;
    }
    /**
     * Convert points to pixels
     *
     * @param     int $pValue    Value in points
     * @return     int            Value in pixels
     */
    public static function pointsToPixels($pValue = 0)
    {
        if ($pValue != 0) {
            return (int) ceil($pValue * 1.333333333);
        }
        return 0;
    }
    /**
     * Convert degrees to angle
     *
     * @param     int $pValue    Degrees
     * @return     int            Angle
     */
    public static function degreesToAngle($pValue = 0)
    {
        return (int) round($pValue * 60000);
    }
    /**
     * Convert angle to degrees
     *
     * @param     int $pValue    Angle
     * @return     int            Degrees
     */
    public static function angleToDegrees($pValue = 0)
    {
        if ($pValue != 0) {
            return round($pValue / 60000);
        }
        return 0;
    }
    /**
     * Create a new image from file. By alexander at alexauto dot nl
     *
     * @link http://www.php.net/manual/en/function.imagecreatefromwbmp.php#86214
     * @param string $filename Path to Windows DIB (BMP) image
     * @return resource
     */
    public static function imagecreatefrombmp($p_sFile)
    {
        $file = fopen($p_sFile, "rb");
        $read = fread($file, 10);
        while (!feof($file) && $read != "") {
            $read .= fread($file, 1024);
        }
        $temp = unpack("H*", $read);
        $hex = $temp[1];
        $header = substr($hex, 0, 108);
        if (substr($header, 0, 4) == "424d") {
            $header_parts = str_split($header, 2);
            $width = hexdec($header_parts[19] . $header_parts[18]);
            $height = hexdec($header_parts[23] . $header_parts[22]);
            unset($header_parts);
        }
        $x = 0;
        $y = 1;
        $image = imagecreatetruecolor($width, $height);
        $body = substr($hex, 108);
        $body_size = strlen($body) / 2;
        $header_size = $width * $height;
        $usePadding = $header_size * 3 + 4 < $body_size;
        $i = 0;
        while ($i < $body_size) {
            if ($width <= $x) {
                if ($usePadding) {
                    $i += $width % 4;
                }
                $x = 0;
                $y++;
                if ($height < $y) {
                    break;
                }
            }
            $i_pos = $i * 2;
            $r = hexdec($body[$i_pos + 4] . $body[$i_pos + 5]);
            $g = hexdec($body[$i_pos + 2] . $body[$i_pos + 3]);
            $b = hexdec($body[$i_pos] . $body[$i_pos + 1]);
            $color = imagecolorallocate($image, $r, $g, $b);
            imagesetpixel($image, $x, $height - $y, $color);
            $x++;
            $i += 3;
        }
        unset($body);
        return $image;
    }
}

?>