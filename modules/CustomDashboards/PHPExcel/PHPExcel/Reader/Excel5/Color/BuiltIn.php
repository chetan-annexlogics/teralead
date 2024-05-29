<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class PHPExcel_Reader_Excel5_Color_BuiltIn
{
    protected static $map = array("000000", "FFFFFF", "FF0000", "00FF00", "0000FF", "FFFF00", "FF00FF", "00FFFF", "64" => "000000", "65" => "FFFFFF");
    /**
     * Map built-in color to RGB value
     *
     * @param int $color Indexed color
     * @return array
     */
    public static function lookup($color)
    {
        if (isset(self::$map[$color])) {
            return array("rgb" => self::$map[$color]);
        }
        return array("rgb" => "000000");
    }
}

?>