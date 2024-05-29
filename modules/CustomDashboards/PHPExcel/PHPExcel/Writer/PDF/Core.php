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
 *  PHPExcel_Writer_PDF_Core
 *
 *  Copyright (c) 2006 - 2015 PHPExcel
 *
 *  This library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 *
 *  This library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with this library; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *  @category    PHPExcel
 *  @package     PHPExcel_Writer_PDF
 *  @copyright   Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 *  @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 *  @version     ##VERSION##, ##DATE##
 */
abstract class PHPExcel_Writer_PDF_Core extends PHPExcel_Writer_HTML
{
    /**
     * Temporary storage directory
     *
     * @var string
     */
    protected $tempDir = "";
    /**
     * Font
     *
     * @var string
     */
    protected $font = "freesans";
    /**
     * Orientation (Over-ride)
     *
     * @var string
     */
    protected $orientation = NULL;
    /**
     * Paper size (Over-ride)
     *
     * @var int
     */
    protected $paperSize = NULL;
    /**
     * Temporary storage for Save Array Return type
     *
     * @var string
     */
    private $saveArrayReturnType = NULL;
    /**
     * Paper Sizes xRef List
     *
     * @var array
     */
    protected static $paperSizes = NULL;
    /**
     *  Create a new PHPExcel_Writer_PDF
     *
     *  @param     PHPExcel    $phpExcel    PHPExcel object
     */
    public function __construct(PHPExcel $phpExcel)
    {
        parent::__construct($phpExcel);
        $this->setUseInlineCss(true);
        $this->tempDir = PHPExcel_Shared_File::sys_get_temp_dir();
    }
    /**
     *  Get Font
     *
     *  @return string
     */
    public function getFont()
    {
        return $this->font;
    }
    /**
     *  Set font. Examples:
     *      'arialunicid0-chinese-simplified'
     *      'arialunicid0-chinese-traditional'
     *      'arialunicid0-korean'
     *      'arialunicid0-japanese'
     *
     *  @param    string    $fontName
     */
    public function setFont($fontName)
    {
        $this->font = $fontName;
        return $this;
    }
    /**
     *  Get Paper Size
     *
     *  @return int
     */
    public function getPaperSize()
    {
        return $this->paperSize;
    }
    /**
     *  Set Paper Size
     *
     *  @param  string  $pValue Paper size
     *  @return PHPExcel_Writer_PDF
     */
    public function setPaperSize($pValue = PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER)
    {
        $this->paperSize = $pValue;
        return $this;
    }
    /**
     *  Get Orientation
     *
     *  @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }
    /**
     *  Set Orientation
     *
     *  @param string $pValue  Page orientation
     *  @return PHPExcel_Writer_PDF
     */
    public function setOrientation($pValue = PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT)
    {
        $this->orientation = $pValue;
        return $this;
    }
    /**
     *  Get temporary storage directory
     *
     *  @return string
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }
    /**
     *  Set temporary storage directory
     *
     *  @param     string        $pValue        Temporary storage directory
     *  @throws    PHPExcel_Writer_Exception    when directory does not exist
     *  @return    PHPExcel_Writer_PDF
     */
    public function setTempDir($pValue = "")
    {
        if (is_dir($pValue)) {
            $this->tempDir = $pValue;
            return $this;
        }
        throw new PHPExcel_Writer_Exception("Directory does not exist: " . $pValue);
    }
    /**
     *  Save PHPExcel to PDF file, pre-save
     *
     *  @param     string     $pFilename   Name of the file to save as
     *  @throws    PHPExcel_Writer_Exception
     */
    protected function prepareForSave($pFilename = NULL)
    {
        $this->phpExcel->garbageCollect();
        $this->saveArrayReturnType = PHPExcel_Calculation::getArrayReturnType();
        PHPExcel_Calculation::setArrayReturnType(PHPExcel_Calculation::RETURN_ARRAY_AS_VALUE);
        $fileHandle = fopen($pFilename, "w");
        if ($fileHandle === false) {
            throw new PHPExcel_Writer_Exception("Could not open file " . $pFilename . " for writing.");
        }
        $this->isPdf = true;
        $this->buildCSS(true);
        return $fileHandle;
    }
    /**
     *  Save PHPExcel to PDF file, post-save
     *
     *  @param     resource      $fileHandle
     *  @throws    PHPExcel_Writer_Exception
     */
    protected function restoreStateAfterSave($fileHandle)
    {
        fclose($fileHandle);
        PHPExcel_Calculation::setArrayReturnType($this->saveArrayReturnType);
    }
}

?>