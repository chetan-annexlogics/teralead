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
 * PHPExcel_Reader_Excel5_Escher
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
 * @package    PHPExcel_Reader_Excel5
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Reader_Excel5_Escher
{
    /**
     * Escher stream data (binary)
     *
     * @var string
     */
    private $data = NULL;
    /**
     * Size in bytes of the Escher stream data
     *
     * @var int
     */
    private $dataSize = NULL;
    /**
     * Current position of stream pointer in Escher stream data
     *
     * @var int
     */
    private $pos = NULL;
    /**
     * The object to be returned by the reader. Modified during load.
     *
     * @var mixed
     */
    private $object = NULL;
    const DGGCONTAINER = 61440;
    const BSTORECONTAINER = 61441;
    const DGCONTAINER = 61442;
    const SPGRCONTAINER = 61443;
    const SPCONTAINER = 61444;
    const DGG = 61446;
    const BSE = 61447;
    const DG = 61448;
    const SPGR = 61449;
    const SP = 61450;
    const OPT = 61451;
    const CLIENTTEXTBOX = 61453;
    const CLIENTANCHOR = 61456;
    const CLIENTDATA = 61457;
    const BLIPJPEG = 61469;
    const BLIPPNG = 61470;
    const SPLITMENUCOLORS = 61726;
    const TERTIARYOPT = 61730;
    /**
     * Create a new PHPExcel_Reader_Excel5_Escher instance
     *
     * @param mixed $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
    /**
     * Load Escher stream data. May be a partial Escher stream.
     *
     * @param string $data
     */
    public function load($data)
    {
        $this->data = $data;
        $this->dataSize = strlen($this->data);
        $this->pos = 0;
        while ($this->pos < $this->dataSize) {
            $fbt = PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos + 2);
            switch ($fbt) {
                case self::DGGCONTAINER:
                    $this->readDggContainer();
                    break;
                case self::DGG:
                    $this->readDgg();
                    break;
                case self::BSTORECONTAINER:
                    $this->readBstoreContainer();
                    break;
                case self::BSE:
                    $this->readBSE();
                    break;
                case self::BLIPJPEG:
                    $this->readBlipJPEG();
                    break;
                case self::BLIPPNG:
                    $this->readBlipPNG();
                    break;
                case self::OPT:
                    $this->readOPT();
                    break;
                case self::TERTIARYOPT:
                    $this->readTertiaryOPT();
                    break;
                case self::SPLITMENUCOLORS:
                    $this->readSplitMenuColors();
                    break;
                case self::DGCONTAINER:
                    $this->readDgContainer();
                    break;
                case self::DG:
                    $this->readDg();
                    break;
                case self::SPGRCONTAINER:
                    $this->readSpgrContainer();
                    break;
                case self::SPCONTAINER:
                    $this->readSpContainer();
                    break;
                case self::SPGR:
                    $this->readSpgr();
                    break;
                case self::SP:
                    $this->readSp();
                    break;
                case self::CLIENTTEXTBOX:
                    $this->readClientTextbox();
                    break;
                case self::CLIENTANCHOR:
                    $this->readClientAnchor();
                    break;
                case self::CLIENTDATA:
                    $this->readClientData();
                    break;
                default:
                    $this->readDefault();
                    break;
            }
        }
        return $this->object;
    }
    /**
     * Read a generic record
     */
    private function readDefault()
    {
        $verInstance = PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos);
        $fbt = PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos + 2);
        $recVer = (15 & $verInstance) >> 0;
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read DggContainer record (Drawing Group Container)
     */
    private function readDggContainer()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $dggContainer = new PHPExcel_Shared_Escher_DggContainer();
        $this->object->setDggContainer($dggContainer);
        $reader = new PHPExcel_Reader_Excel5_Escher($dggContainer);
        $reader->load($recordData);
    }
    /**
     * Read Dgg record (Drawing Group)
     */
    private function readDgg()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read BstoreContainer record (Blip Store Container)
     */
    private function readBstoreContainer()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $bstoreContainer = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer();
        $this->object->setBstoreContainer($bstoreContainer);
        $reader = new PHPExcel_Reader_Excel5_Escher($bstoreContainer);
        $reader->load($recordData);
    }
    /**
     * Read BSE record
     */
    private function readBSE()
    {
        $recInstance = (65520 & PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos)) >> 4;
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $BSE = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE();
        $this->object->addBSE($BSE);
        $BSE->setBLIPType($recInstance);
        $btWin32 = ord($recordData[0]);
        $btMacOS = ord($recordData[1]);
        $rgbUid = substr($recordData, 2, 16);
        $tag = PHPExcel_Reader_Excel5::getInt2d($recordData, 18);
        $size = PHPExcel_Reader_Excel5::getInt4d($recordData, 20);
        $cRef = PHPExcel_Reader_Excel5::getInt4d($recordData, 24);
        $foDelay = PHPExcel_Reader_Excel5::getInt4d($recordData, 28);
        $unused1 = ord($recordData[32]);
        $cbName = ord($recordData[33]);
        $unused2 = ord($recordData[34]);
        $unused3 = ord($recordData[35]);
        $nameData = substr($recordData, 36, $cbName);
        $blipData = substr($recordData, 36 + $cbName);
        $reader = new PHPExcel_Reader_Excel5_Escher($BSE);
        $reader->load($blipData);
    }
    /**
     * Read BlipJPEG record. Holds raw JPEG image data
     */
    private function readBlipJPEG()
    {
        $recInstance = (65520 & PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos)) >> 4;
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $pos = 0;
        $rgbUid1 = substr($recordData, 0, 16);
        $pos += 16;
        if (in_array($recInstance, array(1131, 1763))) {
            $rgbUid2 = substr($recordData, 16, 16);
            $pos += 16;
        }
        $tag = ord($recordData[$pos]);
        $pos += 1;
        $data = substr($recordData, $pos);
        $blip = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip();
        $blip->setData($data);
        $this->object->setBlip($blip);
    }
    /**
     * Read BlipPNG record. Holds raw PNG image data
     */
    private function readBlipPNG()
    {
        $recInstance = (65520 & PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos)) >> 4;
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $pos = 0;
        $rgbUid1 = substr($recordData, 0, 16);
        $pos += 16;
        if ($recInstance == 1761) {
            $rgbUid2 = substr($recordData, 16, 16);
            $pos += 16;
        }
        $tag = ord($recordData[$pos]);
        $pos += 1;
        $data = substr($recordData, $pos);
        $blip = new PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip();
        $blip->setData($data);
        $this->object->setBlip($blip);
    }
    /**
     * Read OPT record. This record may occur within DggContainer record or SpContainer
     */
    private function readOPT()
    {
        $recInstance = (65520 & PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos)) >> 4;
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $this->readOfficeArtRGFOPTE($recordData, $recInstance);
    }
    /**
     * Read TertiaryOPT record
     */
    private function readTertiaryOPT()
    {
        $recInstance = (65520 & PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos)) >> 4;
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read SplitMenuColors record
     */
    private function readSplitMenuColors()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read DgContainer record (Drawing Container)
     */
    private function readDgContainer()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $dgContainer = new PHPExcel_Shared_Escher_DgContainer();
        $this->object->setDgContainer($dgContainer);
        $reader = new PHPExcel_Reader_Excel5_Escher($dgContainer);
        $escher = $reader->load($recordData);
    }
    /**
     * Read Dg record (Drawing)
     */
    private function readDg()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read SpgrContainer record (Shape Group Container)
     */
    private function readSpgrContainer()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $spgrContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer();
        if ($this->object instanceof PHPExcel_Shared_Escher_DgContainer) {
            $this->object->setSpgrContainer($spgrContainer);
        } else {
            $this->object->addChild($spgrContainer);
        }
        $reader = new PHPExcel_Reader_Excel5_Escher($spgrContainer);
        $escher = $reader->load($recordData);
    }
    /**
     * Read SpContainer record (Shape Container)
     */
    private function readSpContainer()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $spContainer = new PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer();
        $this->object->addChild($spContainer);
        $this->pos += 8 + $length;
        $reader = new PHPExcel_Reader_Excel5_Escher($spContainer);
        $escher = $reader->load($recordData);
    }
    /**
     * Read Spgr record (Shape Group)
     */
    private function readSpgr()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read Sp record (Shape)
     */
    private function readSp()
    {
        $recInstance = (65520 & PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos)) >> 4;
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read ClientTextbox record
     */
    private function readClientTextbox()
    {
        $recInstance = (65520 & PHPExcel_Reader_Excel5::getInt2d($this->data, $this->pos)) >> 4;
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read ClientAnchor record. This record holds information about where the shape is anchored in worksheet
     */
    private function readClientAnchor()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
        $c1 = PHPExcel_Reader_Excel5::getInt2d($recordData, 2);
        $startOffsetX = PHPExcel_Reader_Excel5::getInt2d($recordData, 4);
        $r1 = PHPExcel_Reader_Excel5::getInt2d($recordData, 6);
        $startOffsetY = PHPExcel_Reader_Excel5::getInt2d($recordData, 8);
        $c2 = PHPExcel_Reader_Excel5::getInt2d($recordData, 10);
        $endOffsetX = PHPExcel_Reader_Excel5::getInt2d($recordData, 12);
        $r2 = PHPExcel_Reader_Excel5::getInt2d($recordData, 14);
        $endOffsetY = PHPExcel_Reader_Excel5::getInt2d($recordData, 16);
        $this->object->setStartCoordinates(PHPExcel_Cell::stringFromColumnIndex($c1) . ($r1 + 1));
        $this->object->setStartOffsetX($startOffsetX);
        $this->object->setStartOffsetY($startOffsetY);
        $this->object->setEndCoordinates(PHPExcel_Cell::stringFromColumnIndex($c2) . ($r2 + 1));
        $this->object->setEndOffsetX($endOffsetX);
        $this->object->setEndOffsetY($endOffsetY);
    }
    /**
     * Read ClientData record
     */
    private function readClientData()
    {
        $length = PHPExcel_Reader_Excel5::getInt4d($this->data, $this->pos + 4);
        $recordData = substr($this->data, $this->pos + 8, $length);
        $this->pos += 8 + $length;
    }
    /**
     * Read OfficeArtRGFOPTE table of property-value pairs
     *
     * @param string $data Binary data
     * @param int $n Number of properties
     */
    private function readOfficeArtRGFOPTE($data, $n)
    {
        $splicedComplexData = substr($data, 6 * $n);
        for ($i = 0; $i < $n; $i++) {
            $fopte = substr($data, 6 * $i, 6);
            $opid = PHPExcel_Reader_Excel5::getInt2d($fopte, 0);
            $opidOpid = (16383 & $opid) >> 0;
            $opidFBid = (16384 & $opid) >> 14;
            $opidFComplex = (32768 & $opid) >> 15;
            $op = PHPExcel_Reader_Excel5::getInt4d($fopte, 2);
            if ($opidFComplex) {
                $complexData = substr($splicedComplexData, 0, $op);
                $splicedComplexData = substr($splicedComplexData, $op);
                $value = $complexData;
            } else {
                $value = $op;
            }
            $this->object->setOPT($opidOpid, $value);
        }
    }
}

?>