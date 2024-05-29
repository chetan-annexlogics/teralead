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
 * PHPExcel_Shared_PasswordHasher
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Shared_PasswordHasher
{
    /**
     * Create a password hash from a given string.
     *
     * This method is based on the algorithm provided by
     * Daniel Rentz of OpenOffice and the PEAR package
     * Spreadsheet_Excel_Writer by Xavier Noguer <xnoguer@rezebra.com>.
     *
     * @param     string    $pPassword    Password to hash
     * @return     string                Hashed password
     */
    public static function hashPassword($pPassword = "")
    {
        $password = 0;
        $charPos = 1;
        $chars = preg_split("//", $pPassword, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($chars as $char) {
            $value = ord($char) << $charPos++;
            $rotated_bits = $value >> 15;
            $value &= 32767;
            $password ^= $value | $rotated_bits;
        }
        $password ^= strlen($pPassword);
        $password ^= 52811;
        return strtoupper(dechex($password));
    }
}

?>