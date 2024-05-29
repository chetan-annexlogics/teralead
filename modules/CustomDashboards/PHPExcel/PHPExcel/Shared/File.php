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
 * PHPExcel_Shared_File
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
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Shared_File
{
    protected static $useUploadTempDirectory = false;
    /**
     * Set the flag indicating whether the File Upload Temp directory should be used for temporary files
     *
     * @param     boolean    $useUploadTempDir        Use File Upload Temporary directory (true or false)
     */
    public static function setUseUploadTempDirectory($useUploadTempDir = false)
    {
        self::$useUploadTempDirectory = (bool) $useUploadTempDir;
    }
    /**
     * Get the flag indicating whether the File Upload Temp directory should be used for temporary files
     *
     * @return     boolean    Use File Upload Temporary directory (true or false)
     */
    public static function getUseUploadTempDirectory()
    {
        return self::$useUploadTempDirectory;
    }
    /**
     * Verify if a file exists
     *
     * @param     string    $pFilename    Filename
     * @return bool
     */
    public static function file_exists($pFilename)
    {
        if (strtolower(substr($pFilename, 0, 3)) == "zip") {
            $zipFile = substr($pFilename, 6, strpos($pFilename, "#") - 6);
            $archiveFile = substr($pFilename, strpos($pFilename, "#") + 1);
            $zip = new ZipArchive();
            if ($zip->open($zipFile) === true) {
                $returnValue = $zip->getFromName($archiveFile) !== false;
                $zip->close();
                return $returnValue;
            }
            return false;
        }
        return file_exists($pFilename);
    }
    /**
     * Returns canonicalized absolute pathname, also for ZIP archives
     *
     * @param string $pFilename
     * @return string
     */
    public static function realpath($pFilename)
    {
        $returnValue = "";
        if (file_exists($pFilename)) {
            $returnValue = realpath($pFilename);
        }
        if ($returnValue == "" || $returnValue === NULL) {
            $pathArray = explode("/", $pFilename);
            while (in_array("..", $pathArray) && $pathArray[0] != "..") {
                for ($i = 0; $i < count($pathArray); $i++) {
                    if ($pathArray[$i] == ".." && 0 < $i) {
                        unset($pathArray[$i]);
                        unset($pathArray[$i - 1]);
                        break;
                    }
                }
            }
            $returnValue = implode("/", $pathArray);
        }
        return $returnValue;
    }
    /**
     * Get the systems temporary directory.
     *
     * @return string
     */
    public static function sys_get_temp_dir()
    {
        if (self::$useUploadTempDirectory && ini_get("upload_tmp_dir") !== false && ($temp = ini_get("upload_tmp_dir")) && file_exists($temp)) {
            return realpath($temp);
        }
        if (!function_exists("sys_get_temp_dir")) {
            if (($temp = getenv("TMP")) && !empty($temp) && file_exists($temp)) {
                return realpath($temp);
            }
            if (($temp = getenv("TEMP")) && !empty($temp) && file_exists($temp)) {
                return realpath($temp);
            }
            if (($temp = getenv("TMPDIR")) && !empty($temp) && file_exists($temp)) {
                return realpath($temp);
            }
            $temp = tempnam(__FILE__, "");
            if (file_exists($temp)) {
                unlink($temp);
                return realpath(dirname($temp));
            }
            return NULL;
        }
        return realpath(sys_get_temp_dir());
    }
}

?>