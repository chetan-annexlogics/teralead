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
 * PHPExcel_Shared_Escher_DggContainer_BstoreContainer
 *
 * @category   PHPExcel
 * @package    PHPExcel_Writer_Excel5
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Writer_Excel5_Escher
{
    /**
     * The object we are writing
     */
    private $object = NULL;
    /**
     * The written binary data
     */
    private $data = NULL;
    /**
     * Shape offsets. Positions in binary stream where a new shape record begins
     *
     * @var array
     */
    private $spOffsets = NULL;
    /**
     * Shape types.
     *
     * @var array
     */
    private $spTypes = NULL;
    /**
     * Constructor
     *
     * @param mixed
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
    /**
     * Process the object to be written
     */
    public function close()
    {
        $this->data = "";
        switch (get_class($this->object)) {
            case "PHPExcel_Shared_Escher":
                if ($dggContainer = $this->object->getDggContainer()) {
                    $writer = new PHPExcel_Writer_Excel5_Escher($dggContainer);
                    $this->data = $writer->close();
                } else {
                    if ($dgContainer = $this->object->getDgContainer()) {
                        $writer = new PHPExcel_Writer_Excel5_Escher($dgContainer);
                        $this->data = $writer->close();
                        $this->spOffsets = $writer->getSpOffsets();
                        $this->spTypes = $writer->getSpTypes();
                    }
                }
                break;
            case "PHPExcel_Shared_Escher_DggContainer":
                $innerData = "";
                $recVer = 0;
                $recInstance = 0;
                $recType = 61446;
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $dggData = pack("VVVV", $this->object->getSpIdMax(), $this->object->getCDgSaved() + 1, $this->object->getCSpSaved(), $this->object->getCDgSaved());
                $IDCLs = $this->object->getIDCLs();
                foreach ($IDCLs as $dgId => $maxReducedSpId) {
                    $dggData .= pack("VV", $dgId, $maxReducedSpId + 1);
                }
                $header = pack("vvV", $recVerInstance, $recType, strlen($dggData));
                $innerData .= $header . $dggData;
                if ($bstoreContainer = $this->object->getBstoreContainer()) {
                    $writer = new PHPExcel_Writer_Excel5_Escher($bstoreContainer);
                    $innerData .= $writer->close();
                }
                $recVer = 15;
                $recInstance = 0;
                $recType = 61440;
                $length = strlen($innerData);
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $header = pack("vvV", $recVerInstance, $recType, $length);
                $this->data = $header . $innerData;
                break;
            case "PHPExcel_Shared_Escher_DggContainer_BstoreContainer":
                $innerData = "";
                if ($BSECollection = $this->object->getBSECollection()) {
                    foreach ($BSECollection as $BSE) {
                        $writer = new PHPExcel_Writer_Excel5_Escher($BSE);
                        $innerData .= $writer->close();
                    }
                }
                $recVer = 15;
                $recInstance = count($this->object->getBSECollection());
                $recType = 61441;
                $length = strlen($innerData);
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $header = pack("vvV", $recVerInstance, $recType, $length);
                $this->data = $header . $innerData;
                break;
            case "PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE":
                $innerData = "";
                if ($blip = $this->object->getBlip()) {
                    $writer = new PHPExcel_Writer_Excel5_Escher($blip);
                    $innerData .= $writer->close();
                }
                $data = "";
                $btWin32 = $this->object->getBlipType();
                $btMacOS = $this->object->getBlipType();
                $data .= pack("CC", $btWin32, $btMacOS);
                $rgbUid = pack("VVVV", 0, 0, 0, 0);
                $data .= $rgbUid;
                $tag = 0;
                $size = strlen($innerData);
                $cRef = 1;
                $foDelay = 0;
                $unused1 = 0;
                $cbName = 0;
                $unused2 = 0;
                $unused3 = 0;
                $data .= pack("vVVVCCCC", $tag, $size, $cRef, $foDelay, $unused1, $cbName, $unused2, $unused3);
                $data .= $innerData;
                $recVer = 2;
                $recInstance = $this->object->getBlipType();
                $recType = 61447;
                $length = strlen($data);
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $header = pack("vvV", $recVerInstance, $recType, $length);
                $this->data = $header;
                $this->data .= $data;
                break;
            case "PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip":
                switch ($this->object->getParent()->getBlipType()) {
                    case PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_JPEG:
                        $innerData = "";
                        $rgbUid1 = pack("VVVV", 0, 0, 0, 0);
                        $innerData .= $rgbUid1;
                        $tag = 255;
                        $innerData .= pack("C", $tag);
                        $innerData .= $this->object->getData();
                        $recVer = 0;
                        $recInstance = 1130;
                        $recType = 61469;
                        $length = strlen($innerData);
                        $recVerInstance = $recVer;
                        $recVerInstance |= $recInstance << 4;
                        $header = pack("vvV", $recVerInstance, $recType, $length);
                        $this->data = $header;
                        $this->data .= $innerData;
                        break;
                    case PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE::BLIPTYPE_PNG:
                        $innerData = "";
                        $rgbUid1 = pack("VVVV", 0, 0, 0, 0);
                        $innerData .= $rgbUid1;
                        $tag = 255;
                        $innerData .= pack("C", $tag);
                        $innerData .= $this->object->getData();
                        $recVer = 0;
                        $recInstance = 1760;
                        $recType = 61470;
                        $length = strlen($innerData);
                        $recVerInstance = $recVer;
                        $recVerInstance |= $recInstance << 4;
                        $header = pack("vvV", $recVerInstance, $recType, $length);
                        $this->data = $header;
                        $this->data .= $innerData;
                        break;
                }
                break;
            case "PHPExcel_Shared_Escher_DgContainer":
                $innerData = "";
                $recVer = 0;
                $recInstance = $this->object->getDgId();
                $recType = 61448;
                $length = 8;
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $header = pack("vvV", $recVerInstance, $recType, $length);
                $countShapes = count($this->object->getSpgrContainer()->getChildren());
                $innerData .= $header . pack("VV", $countShapes, $this->object->getLastSpId());
                if ($spgrContainer = $this->object->getSpgrContainer()) {
                    $writer = new PHPExcel_Writer_Excel5_Escher($spgrContainer);
                    $innerData .= $writer->close();
                    $spOffsets = $writer->getSpOffsets();
                    $spTypes = $writer->getSpTypes();
                    foreach ($spOffsets as &$spOffset) {
                        $spOffset += 24;
                    }
                    $this->spOffsets = $spOffsets;
                    $this->spTypes = $spTypes;
                }
                $recVer = 15;
                $recInstance = 0;
                $recType = 61442;
                $length = strlen($innerData);
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $header = pack("vvV", $recVerInstance, $recType, $length);
                $this->data = $header . $innerData;
                break;
            case "PHPExcel_Shared_Escher_DgContainer_SpgrContainer":
                $innerData = "";
                $totalSize = 8;
                $spOffsets = array();
                $spTypes = array();
                foreach ($this->object->getChildren() as $spContainer) {
                    $writer = new PHPExcel_Writer_Excel5_Escher($spContainer);
                    $spData = $writer->close();
                    $innerData .= $spData;
                    $totalSize += strlen($spData);
                    $spOffsets[] = $totalSize;
                    $spTypes = array_merge($spTypes, $writer->getSpTypes());
                }
                $recVer = 15;
                $recInstance = 0;
                $recType = 61443;
                $length = strlen($innerData);
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $header = pack("vvV", $recVerInstance, $recType, $length);
                $this->data = $header . $innerData;
                $this->spOffsets = $spOffsets;
                $this->spTypes = $spTypes;
                break;
            case "PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer":
                $data = "";
                if ($this->object->getSpgr()) {
                    $recVer = 1;
                    $recInstance = 0;
                    $recType = 61449;
                    $length = 16;
                    $recVerInstance = $recVer;
                    $recVerInstance |= $recInstance << 4;
                    $header = pack("vvV", $recVerInstance, $recType, $length);
                    $data .= $header . pack("VVVV", 0, 0, 0, 0);
                }
                $this->spTypes[] = $this->object->getSpType();
                $recVer = 2;
                $recInstance = $this->object->getSpType();
                $recType = 61450;
                $length = 8;
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $header = pack("vvV", $recVerInstance, $recType, $length);
                $data .= $header . pack("VV", $this->object->getSpId(), $this->object->getSpgr() ? 5 : 2560);
                if ($this->object->getOPTCollection()) {
                    $optData = "";
                    $recVer = 3;
                    $recInstance = count($this->object->getOPTCollection());
                    $recType = 61451;
                    foreach ($this->object->getOPTCollection() as $property => $value) {
                        $optData .= pack("vV", $property, $value);
                    }
                    $length = strlen($optData);
                    $recVerInstance = $recVer;
                    $recVerInstance |= $recInstance << 4;
                    $header = pack("vvV", $recVerInstance, $recType, $length);
                    $data .= $header . $optData;
                }
                if ($this->object->getStartCoordinates()) {
                    $clientAnchorData = "";
                    $recVer = 0;
                    $recInstance = 0;
                    $recType = 61456;
                    list($column, $row) = PHPExcel_Cell::coordinateFromString($this->object->getStartCoordinates());
                    $c1 = PHPExcel_Cell::columnIndexFromString($column) - 1;
                    $r1 = $row - 1;
                    $startOffsetX = $this->object->getStartOffsetX();
                    $startOffsetY = $this->object->getStartOffsetY();
                    list($column, $row) = PHPExcel_Cell::coordinateFromString($this->object->getEndCoordinates());
                    $c2 = PHPExcel_Cell::columnIndexFromString($column) - 1;
                    $r2 = $row - 1;
                    $endOffsetX = $this->object->getEndOffsetX();
                    $endOffsetY = $this->object->getEndOffsetY();
                    $clientAnchorData = pack("vvvvvvvvv", $this->object->getSpFlag(), $c1, $startOffsetX, $r1, $startOffsetY, $c2, $endOffsetX, $r2, $endOffsetY);
                    $length = strlen($clientAnchorData);
                    $recVerInstance = $recVer;
                    $recVerInstance |= $recInstance << 4;
                    $header = pack("vvV", $recVerInstance, $recType, $length);
                    $data .= $header . $clientAnchorData;
                }
                if (!$this->object->getSpgr()) {
                    $clientDataData = "";
                    $recVer = 0;
                    $recInstance = 0;
                    $recType = 61457;
                    $length = strlen($clientDataData);
                    $recVerInstance = $recVer;
                    $recVerInstance |= $recInstance << 4;
                    $header = pack("vvV", $recVerInstance, $recType, $length);
                    $data .= $header . $clientDataData;
                }
                $recVer = 15;
                $recInstance = 0;
                $recType = 61444;
                $length = strlen($data);
                $recVerInstance = $recVer;
                $recVerInstance |= $recInstance << 4;
                $header = pack("vvV", $recVerInstance, $recType, $length);
                $this->data = $header . $data;
                break;
        }
        return $this->data;
    }
    /**
     * Gets the shape offsets
     *
     * @return array
     */
    public function getSpOffsets()
    {
        return $this->spOffsets;
    }
    /**
     * Gets the shape types
     *
     * @return array
     */
    public function getSpTypes()
    {
        return $this->spTypes;
    }
}

?>