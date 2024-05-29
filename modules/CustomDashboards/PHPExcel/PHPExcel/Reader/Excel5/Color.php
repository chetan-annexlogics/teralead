<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class PHPExcel_Reader_Excel5_Color
{
    /**
     * Read color
     *
     * @param int $color Indexed color
     * @param array $palette Color palette
     * @return array RGB color value, example: array('rgb' => 'FF0000')
     */
    public static function map($color, $palette, $version)
    {
        if ($color <= 7 || 64 <= $color) {
            return PHPExcel_Reader_Excel5_Color_BuiltIn::lookup($color);
        }
        if (isset($palette) && isset($palette[$color - 8])) {
            return $palette[$color - 8];
        }
        if ($version == PHPExcel_Reader_Excel5::XLS_BIFF8) {
            return PHPExcel_Reader_Excel5_Color_BIFF8::lookup($color);
        }
        return PHPExcel_Reader_Excel5_Color_BIFF5::lookup($color);
    }
}

?>