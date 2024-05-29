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
 * PHPExcel_Worksheet_BaseDrawing
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
 * @package    PHPExcel_Worksheet
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Worksheet_BaseDrawing implements PHPExcel_IComparable
{
    /**
     * Image counter
     *
     * @var int
     */
    private static $imageCounter = 0;
    /**
     * Image index
     *
     * @var int
     */
    private $imageIndex = 0;
    /**
     * Name
     *
     * @var string
     */
    protected $name = NULL;
    /**
     * Description
     *
     * @var string
     */
    protected $description = NULL;
    /**
     * Worksheet
     *
     * @var PHPExcel_Worksheet
     */
    protected $worksheet = NULL;
    /**
     * Coordinates
     *
     * @var string
     */
    protected $coordinates = NULL;
    /**
     * Offset X
     *
     * @var int
     */
    protected $offsetX = NULL;
    /**
     * Offset Y
     *
     * @var int
     */
    protected $offsetY = NULL;
    /**
     * Width
     *
     * @var int
     */
    protected $width = NULL;
    /**
     * Height
     *
     * @var int
     */
    protected $height = NULL;
    /**
     * Proportional resize
     *
     * @var boolean
     */
    protected $resizeProportional = NULL;
    /**
     * Rotation
     *
     * @var int
     */
    protected $rotation = NULL;
    /**
     * Shadow
     *
     * @var PHPExcel_Worksheet_Drawing_Shadow
     */
    protected $shadow = NULL;
    /**
     * Create a new PHPExcel_Worksheet_BaseDrawing
     */
    public function __construct()
    {
        $this->name = "";
        $this->description = "";
        $this->worksheet = NULL;
        $this->coordinates = "A1";
        $this->offsetX = 0;
        $this->offsetY = 0;
        $this->width = 0;
        $this->height = 0;
        $this->resizeProportional = true;
        $this->rotation = 0;
        $this->shadow = new PHPExcel_Worksheet_Drawing_Shadow();
        self::$imageCounter++;
        $this->imageIndex = self::$imageCounter;
    }
    /**
     * Get image index
     *
     * @return int
     */
    public function getImageIndex()
    {
        return $this->imageIndex;
    }
    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set Name
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setName($pValue = "")
    {
        $this->name = $pValue;
        return $this;
    }
    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Set Description
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setDescription($pValue = "")
    {
        $this->description = $pValue;
        return $this;
    }
    /**
     * Get Worksheet
     *
     * @return PHPExcel_Worksheet
     */
    public function getWorksheet()
    {
        return $this->worksheet;
    }
    /**
     * Set Worksheet
     *
     * @param     PHPExcel_Worksheet     $pValue
     * @param     bool                $pOverrideOld    If a Worksheet has already been assigned, overwrite it and remove image from old Worksheet?
     * @throws     PHPExcel_Exception
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setWorksheet(PHPExcel_Worksheet $pValue = NULL, $pOverrideOld = false)
    {
        if (is_null($this->worksheet)) {
            $this->worksheet = $pValue;
            $this->worksheet->getCell($this->coordinates);
            $this->worksheet->getDrawingCollection()->append($this);
        } else {
            if ($pOverrideOld) {
                $iterator = $this->worksheet->getDrawingCollection()->getIterator();
                while ($iterator->valid()) {
                    if ($iterator->current()->getHashCode() == $this->getHashCode()) {
                        $this->worksheet->getDrawingCollection()->offsetUnset($iterator->key());
                        $this->worksheet = NULL;
                        break;
                    }
                }
                $this->setWorksheet($pValue);
            } else {
                throw new PHPExcel_Exception("A PHPExcel_Worksheet has already been assigned. Drawings can only exist on one PHPExcel_Worksheet.");
            }
        }
        return $this;
    }
    /**
     * Get Coordinates
     *
     * @return string
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }
    /**
     * Set Coordinates
     *
     * @param string $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setCoordinates($pValue = "A1")
    {
        $this->coordinates = $pValue;
        return $this;
    }
    /**
     * Get OffsetX
     *
     * @return int
     */
    public function getOffsetX()
    {
        return $this->offsetX;
    }
    /**
     * Set OffsetX
     *
     * @param int $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setOffsetX($pValue = 0)
    {
        $this->offsetX = $pValue;
        return $this;
    }
    /**
     * Get OffsetY
     *
     * @return int
     */
    public function getOffsetY()
    {
        return $this->offsetY;
    }
    /**
     * Set OffsetY
     *
     * @param int $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setOffsetY($pValue = 0)
    {
        $this->offsetY = $pValue;
        return $this;
    }
    /**
     * Get Width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
    /**
     * Set Width
     *
     * @param int $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setWidth($pValue = 0)
    {
        if ($this->resizeProportional && $pValue != 0) {
            $ratio = $this->height / ($this->width != 0 ? $this->width : 1);
            $this->height = round($ratio * $pValue);
        }
        $this->width = $pValue;
        return $this;
    }
    /**
     * Get Height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
    /**
     * Set Height
     *
     * @param int $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setHeight($pValue = 0)
    {
        if ($this->resizeProportional && $pValue != 0) {
            $ratio = $this->width / ($this->height != 0 ? $this->height : 1);
            $this->width = round($ratio * $pValue);
        }
        $this->height = $pValue;
        return $this;
    }
    /**
     * Set width and height with proportional resize
     * Example:
     * <code>
     * $objDrawing->setResizeProportional(true);
     * $objDrawing->setWidthAndHeight(160,120);
     * </code>
     *
     * @author Vincent@luo MSN:kele_100@hotmail.com
     * @param int $width
     * @param int $height
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setWidthAndHeight($width = 0, $height = 0)
    {
        $xratio = $width / ($this->width != 0 ? $this->width : 1);
        $yratio = $height / ($this->height != 0 ? $this->height : 1);
        if ($this->resizeProportional && !($width == 0 || $height == 0)) {
            if ($xratio * $this->height < $height) {
                $this->height = ceil($xratio * $this->height);
                $this->width = $width;
            } else {
                $this->width = ceil($yratio * $this->width);
                $this->height = $height;
            }
        } else {
            $this->width = $width;
            $this->height = $height;
        }
        return $this;
    }
    /**
     * Get ResizeProportional
     *
     * @return boolean
     */
    public function getResizeProportional()
    {
        return $this->resizeProportional;
    }
    /**
     * Set ResizeProportional
     *
     * @param boolean $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setResizeProportional($pValue = true)
    {
        $this->resizeProportional = $pValue;
        return $this;
    }
    /**
     * Get Rotation
     *
     * @return int
     */
    public function getRotation()
    {
        return $this->rotation;
    }
    /**
     * Set Rotation
     *
     * @param int $pValue
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setRotation($pValue = 0)
    {
        $this->rotation = $pValue;
        return $this;
    }
    /**
     * Get Shadow
     *
     * @return PHPExcel_Worksheet_Drawing_Shadow
     */
    public function getShadow()
    {
        return $this->shadow;
    }
    /**
     * Set Shadow
     *
     * @param     PHPExcel_Worksheet_Drawing_Shadow $pValue
     * @throws     PHPExcel_Exception
     * @return PHPExcel_Worksheet_BaseDrawing
     */
    public function setShadow(PHPExcel_Worksheet_Drawing_Shadow $pValue = NULL)
    {
        $this->shadow = $pValue;
        return $this;
    }
    /**
     * Get hash code
     *
     * @return string    Hash code
     */
    public function getHashCode()
    {
        return md5($this->name . $this->description . $this->worksheet->getHashCode() . $this->coordinates . $this->offsetX . $this->offsetY . $this->width . $this->height . $this->rotation . $this->shadow->getHashCode() . "PHPExcel_Worksheet_BaseDrawing");
    }
    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                $this->{$key} = clone $value;
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

?>