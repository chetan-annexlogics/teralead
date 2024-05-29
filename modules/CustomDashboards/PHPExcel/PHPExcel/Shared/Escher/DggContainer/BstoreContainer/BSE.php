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
 * PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE
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
 * @package    PHPExcel_Shared_Escher
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE
{
    /**
     * The parent BLIP Store Entry Container
     *
     * @var PHPExcel_Shared_Escher_DggContainer_BstoreContainer
     */
    private $parent = NULL;
    /**
     * The BLIP (Big Large Image or Picture)
     *
     * @var PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip
     */
    private $blip = NULL;
    /**
     * The BLIP type
     *
     * @var int
     */
    private $blipType = NULL;
    const BLIPTYPE_ERROR = 0;
    const BLIPTYPE_UNKNOWN = 1;
    const BLIPTYPE_EMF = 2;
    const BLIPTYPE_WMF = 3;
    const BLIPTYPE_PICT = 4;
    const BLIPTYPE_JPEG = 5;
    const BLIPTYPE_PNG = 6;
    const BLIPTYPE_DIB = 7;
    const BLIPTYPE_TIFF = 17;
    const BLIPTYPE_CMYKJPEG = 18;
    /**
     * Set parent BLIP Store Entry Container
     *
     * @param PHPExcel_Shared_Escher_DggContainer_BstoreContainer $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
    /**
     * Get the BLIP
     *
     * @return PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip
     */
    public function getBlip()
    {
        return $this->blip;
    }
    /**
     * Set the BLIP
     *
     * @param PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip $blip
     */
    public function setBlip($blip)
    {
        $this->blip = $blip;
        $blip->setParent($this);
    }
    /**
     * Get the BLIP type
     *
     * @return int
     */
    public function getBlipType()
    {
        return $this->blipType;
    }
    /**
     * Set the BLIP type
     *
     * @param int
     */
    public function setBlipType($blipType)
    {
        $this->blipType = $blipType;
    }
}

?>