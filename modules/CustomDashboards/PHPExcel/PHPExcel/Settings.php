<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

if (!defined("PHPEXCEL_ROOT")) {
    define("PHPEXCEL_ROOT", dirname(__FILE__) . "/../");
    require PHPEXCEL_ROOT . "PHPExcel/Autoloader.php";
}
/**
 * PHPExcel_Settings
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
 * @package    PHPExcel_Settings
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Settings
{
    private static $chartRenderers = NULL;
    private static $pdfRenderers = NULL;
    /**
     * Name of the class used for Zip file management
     *    e.g.
     *        ZipArchive
     *
     * @var string
     */
    private static $zipClass = self::ZIPARCHIVE;
    /**
     * Name of the external Library used for rendering charts
     *    e.g.
     *        jpgraph
     *
     * @var string
     */
    private static $chartRendererName = NULL;
    /**
     * Directory Path to the external Library used for rendering charts
     *
     * @var string
     */
    private static $chartRendererPath = NULL;
    /**
     * Name of the external Library used for rendering PDF files
     *    e.g.
     *         mPDF
     *
     * @var string
     */
    private static $pdfRendererName = NULL;
    /**
     * Directory Path to the external Library used for rendering PDF files
     *
     * @var string
     */
    private static $pdfRendererPath = NULL;
    /**
     * Default options for libxml loader
     *
     * @var int
     */
    private static $libXmlLoaderOptions = NULL;
    const PCLZIP = "PHPExcel_Shared_ZipArchive";
    const ZIPARCHIVE = "ZipArchive";
    const CHART_RENDERER_JPGRAPH = "jpgraph";
    const PDF_RENDERER_TCPDF = "tcPDF";
    const PDF_RENDERER_DOMPDF = "DomPDF";
    const PDF_RENDERER_MPDF = "mPDF";
    /**
     * Set the Zip handler Class that PHPExcel should use for Zip file management (PCLZip or ZipArchive)
     *
     * @param string $zipClass    The Zip handler class that PHPExcel should use for Zip file management
     *      e.g. PHPExcel_Settings::PCLZip or PHPExcel_Settings::ZipArchive
     * @return    boolean    Success or failure
     */
    public static function setZipClass($zipClass)
    {
        if ($zipClass === self::PCLZIP || $zipClass === self::ZIPARCHIVE) {
            self::$zipClass = $zipClass;
            return true;
        }
        return false;
    }
    /**
     * Return the name of the Zip handler Class that PHPExcel is configured to use (PCLZip or ZipArchive)
     *    or Zip file management
     *
     * @return string Name of the Zip handler Class that PHPExcel is configured to use
     *    for Zip file management
     *    e.g. PHPExcel_Settings::PCLZip or PHPExcel_Settings::ZipArchive
     */
    public static function getZipClass()
    {
        return self::$zipClass;
    }
    /**
     * Return the name of the method that is currently configured for cell cacheing
     *
     * @return string Name of the cacheing method
     */
    public static function getCacheStorageMethod()
    {
        return PHPExcel_CachedObjectStorageFactory::getCacheStorageMethod();
    }
    /**
     * Return the name of the class that is currently being used for cell cacheing
     *
     * @return string Name of the class currently being used for cacheing
     */
    public static function getCacheStorageClass()
    {
        return PHPExcel_CachedObjectStorageFactory::getCacheStorageClass();
    }
    /**
     * Set the method that should be used for cell cacheing
     *
     * @param string $method Name of the cacheing method
     * @param array $arguments Optional configuration arguments for the cacheing method
     * @return boolean Success or failure
     */
    public static function setCacheStorageMethod($method = PHPExcel_CachedObjectStorageFactory::cache_in_memory, $arguments = array())
    {
        return PHPExcel_CachedObjectStorageFactory::initialize($method, $arguments);
    }
    /**
     * Set the locale code to use for formula translations and any special formatting
     *
     * @param string $locale The locale code to use (e.g. "fr" or "pt_br" or "en_uk")
     * @return boolean Success or failure
     */
    public static function setLocale($locale = "en_us")
    {
        return PHPExcel_Calculation::getInstance()->setLocale($locale);
    }
    /**
     * Set details of the external library that PHPExcel should use for rendering charts
     *
     * @param string $libraryName    Internal reference name of the library
     *    e.g. PHPExcel_Settings::CHART_RENDERER_JPGRAPH
     * @param string $libraryBaseDir Directory path to the library's base folder
     *
     * @return    boolean    Success or failure
     */
    public static function setChartRenderer($libraryName, $libraryBaseDir)
    {
        if (!self::setChartRendererName($libraryName)) {
            return false;
        }
        return self::setChartRendererPath($libraryBaseDir);
    }
    /**
     * Identify to PHPExcel the external library to use for rendering charts
     *
     * @param string $libraryName    Internal reference name of the library
     *    e.g. PHPExcel_Settings::CHART_RENDERER_JPGRAPH
     *
     * @return    boolean    Success or failure
     */
    public static function setChartRendererName($libraryName)
    {
        if (!in_array($libraryName, self::$chartRenderers)) {
            return false;
        }
        self::$chartRendererName = $libraryName;
        return true;
    }
    /**
     * Tell PHPExcel where to find the external library to use for rendering charts
     *
     * @param string $libraryBaseDir    Directory path to the library's base folder
     * @return    boolean    Success or failure
     */
    public static function setChartRendererPath($libraryBaseDir)
    {
        if (file_exists($libraryBaseDir) === false || is_readable($libraryBaseDir) === false) {
            return false;
        }
        self::$chartRendererPath = $libraryBaseDir;
        return true;
    }
    /**
     * Return the Chart Rendering Library that PHPExcel is currently configured to use (e.g. jpgraph)
     *
     * @return string|NULL Internal reference name of the Chart Rendering Library that PHPExcel is
     *    currently configured to use
     *    e.g. PHPExcel_Settings::CHART_RENDERER_JPGRAPH
     */
    public static function getChartRendererName()
    {
        return self::$chartRendererName;
    }
    /**
     * Return the directory path to the Chart Rendering Library that PHPExcel is currently configured to use
     *
     * @return string|NULL Directory Path to the Chart Rendering Library that PHPExcel is
     *     currently configured to use
     */
    public static function getChartRendererPath()
    {
        return self::$chartRendererPath;
    }
    /**
     * Set details of the external library that PHPExcel should use for rendering PDF files
     *
     * @param string $libraryName Internal reference name of the library
     *     e.g. PHPExcel_Settings::PDF_RENDERER_TCPDF,
     *     PHPExcel_Settings::PDF_RENDERER_DOMPDF
     *  or PHPExcel_Settings::PDF_RENDERER_MPDF
     * @param string $libraryBaseDir Directory path to the library's base folder
     *
     * @return boolean Success or failure
     */
    public static function setPdfRenderer($libraryName, $libraryBaseDir)
    {
        if (!self::setPdfRendererName($libraryName)) {
            return false;
        }
        return self::setPdfRendererPath($libraryBaseDir);
    }
    /**
     * Identify to PHPExcel the external library to use for rendering PDF files
     *
     * @param string $libraryName Internal reference name of the library
     *     e.g. PHPExcel_Settings::PDF_RENDERER_TCPDF,
     *    PHPExcel_Settings::PDF_RENDERER_DOMPDF
     *     or PHPExcel_Settings::PDF_RENDERER_MPDF
     *
     * @return boolean Success or failure
     */
    public static function setPdfRendererName($libraryName)
    {
        if (!in_array($libraryName, self::$pdfRenderers)) {
            return false;
        }
        self::$pdfRendererName = $libraryName;
        return true;
    }
    /**
     * Tell PHPExcel where to find the external library to use for rendering PDF files
     *
     * @param string $libraryBaseDir Directory path to the library's base folder
     * @return boolean Success or failure
     */
    public static function setPdfRendererPath($libraryBaseDir)
    {
        if (file_exists($libraryBaseDir) === false || is_readable($libraryBaseDir) === false) {
            return false;
        }
        self::$pdfRendererPath = $libraryBaseDir;
        return true;
    }
    /**
     * Return the PDF Rendering Library that PHPExcel is currently configured to use (e.g. dompdf)
     *
     * @return string|NULL Internal reference name of the PDF Rendering Library that PHPExcel is
     *     currently configured to use
     *  e.g. PHPExcel_Settings::PDF_RENDERER_TCPDF,
     *  PHPExcel_Settings::PDF_RENDERER_DOMPDF
     *  or PHPExcel_Settings::PDF_RENDERER_MPDF
     */
    public static function getPdfRendererName()
    {
        return self::$pdfRendererName;
    }
    /**
     * Return the directory path to the PDF Rendering Library that PHPExcel is currently configured to use
     *
     * @return string|NULL Directory Path to the PDF Rendering Library that PHPExcel is
     *        currently configured to use
     */
    public static function getPdfRendererPath()
    {
        return self::$pdfRendererPath;
    }
    /**
     * Set options for libxml loader
     *
     * @param   int   $options   Options for libxml loader
     */
    public static function setLibXmlLoaderOptions($options = NULL)
    {
        if (is_null($options) && defined("LIBXML_DTDLOAD")) {
            $options = LIBXML_DTDLOAD | LIBXML_DTDATTR;
        }
        if (0 <= version_compare(PHP_VERSION, "5.2.11")) {
            @libxml_disable_entity_loader((bool) $options);
        }
        self::$libXmlLoaderOptions = $options;
    }
    /**
     * Get defined options for libxml loader.
     * Defaults to LIBXML_DTDLOAD | LIBXML_DTDATTR when not set explicitly.
     *
     * @return int Default options for libxml loader
     */
    public static function getLibXmlLoaderOptions()
    {
        if (is_null(self::$libXmlLoaderOptions) && defined("LIBXML_DTDLOAD")) {
            self::setLibXmlLoaderOptions(LIBXML_DTDLOAD | LIBXML_DTDATTR);
        } else {
            if (is_null(self::$libXmlLoaderOptions)) {
                self::$libXmlLoaderOptions = true;
            }
        }
        if (0 <= version_compare(PHP_VERSION, "5.2.11")) {
            @libxml_disable_entity_loader((bool) self::$libXmlLoaderOptions);
        }
        return self::$libXmlLoaderOptions;
    }
}

?>