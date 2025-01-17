<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

PHPExcel_Autoloader::register();
if (ini_get("mbstring.func_overload") & 2) {
    throw new PHPExcel_Exception("Multibyte function overloading in PHP must be disabled for string functions (2).");
}
PHPExcel_Shared_String::buildCharacterSets();
/**
 * PHPExcel
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
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Autoloader
{
    /**
     * Register the Autoloader with SPL
     *
     */
    public static function register()
    {
        if (function_exists("__autoload")) {
            spl_autoload_register("__autoload");
        }
        if (0 <= version_compare(PHP_VERSION, "5.3.0")) {
            return spl_autoload_register(array("PHPExcel_Autoloader", "load"), true, true);
        }
        return spl_autoload_register(array("PHPExcel_Autoloader", "load"));
    }
    /**
     * Autoload a class identified by name
     *
     * @param    string    $pClassName        Name of the object to load
     */
    public static function load($pClassName)
    {
        if (class_exists($pClassName, false) || strpos($pClassName, "PHPExcel") !== 0) {
            return false;
        }
        $pClassFilePath = PHPEXCEL_ROOT . str_replace("_", DIRECTORY_SEPARATOR, $pClassName) . ".php";
        if (file_exists($pClassFilePath) === false || is_readable($pClassFilePath) === false) {
            return false;
        }
        require $pClassFilePath;
    }
}

?>