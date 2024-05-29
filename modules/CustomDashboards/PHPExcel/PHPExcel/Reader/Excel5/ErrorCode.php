<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

class PHPExcel_Reader_Excel5_ErrorCode
{
    protected static $map = array("#NULL!", "7" => "#DIV/0!", "15" => "#VALUE!", "23" => "#REF!", "29" => "#NAME?", "36" => "#NUM!", "42" => "#N/A");
    /**
     * Map error code, e.g. '#N/A'
     *
     * @param int $code
     * @return string
     */
    public static function lookup($code)
    {
        if (isset(self::$map[$code])) {
            return self::$map[$code];
        }
        return false;
    }
}

?>