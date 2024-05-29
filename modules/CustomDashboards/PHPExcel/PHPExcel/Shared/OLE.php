<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

$GLOBALS["_OLE_INSTANCES"] = array();
/**
* OLE package base class.
*
* @author   Xavier Noguer <xnoguer@php.net>
* @author   Christian Schmidt <schmidt@php.net>
* @category   PHPExcel
* @package    PHPExcel_Shared_OLE
*/
class PHPExcel_Shared_OLE
{
    /**
     * The file handle for reading an OLE container
     * @var resource
     */
    public $_file_handle = NULL;
    /**
     * Array of PPS's found on the OLE container
     * @var array
     */
    public $_list = array();
    /**
     * Root directory of OLE container
     * @var OLE_PPS_Root
     */
    public $root = NULL;
    /**
     * Big Block Allocation Table
     * @var array  (blockId => nextBlockId)
     */
    public $bbat = NULL;
    /**
     * Short Block Allocation Table
     * @var array  (blockId => nextBlockId)
     */
    public $sbat = NULL;
    /**
     * Size of big blocks. This is usually 512.
     * @var  int  number of octets per block.
     */
    public $bigBlockSize = NULL;
    /**
     * Size of small blocks. This is usually 64.
     * @var  int  number of octets per block
     */
    public $smallBlockSize = NULL;
    const OLE_PPS_TYPE_ROOT = 5;
    const OLE_PPS_TYPE_DIR = 1;
    const OLE_PPS_TYPE_FILE = 2;
    const OLE_DATA_SIZE_SMALL = 4096;
    const OLE_LONG_INT_SIZE = 4;
    const OLE_PPS_SIZE = 128;
    /**
     * Reads an OLE container from the contents of the file given.
     *
     * @acces public
     * @param string $file
     * @return mixed true on success, PEAR_Error on failure
     */
    public function read($file)
    {
        $fh = fopen($file, "r");
        if (!$fh) {
            throw new PHPExcel_Reader_Exception("Can't open file " . $file);
        }
        $this->_file_handle = $fh;
        $signature = fread($fh, 8);
        if ("ÐÏ\21à¡±\32á" != $signature) {
            throw new PHPExcel_Reader_Exception("File doesn't seem to be an OLE container.");
        }
        fseek($fh, 28);
        if (fread($fh, 2) != "þÿ") {
            throw new PHPExcel_Reader_Exception("Only Little-Endian encoding is supported.");
        }
        $this->bigBlockSize = pow(2, self::_readInt2($fh));
        $this->smallBlockSize = pow(2, self::_readInt2($fh));
        fseek($fh, 44);
        $bbatBlockCount = self::_readInt4($fh);
        $directoryFirstBlockId = self::_readInt4($fh);
        fseek($fh, 56);
        $this->bigBlockThreshold = self::_readInt4($fh);
        $sbatFirstBlockId = self::_readInt4($fh);
        $sbbatBlockCount = self::_readInt4($fh);
        $mbatFirstBlockId = self::_readInt4($fh);
        $mbbatBlockCount = self::_readInt4($fh);
        $this->bbat = array();
        $mbatBlocks = array();
        for ($i = 0; $i < 109; $i++) {
            $mbatBlocks[] = self::_readInt4($fh);
        }
        $pos = $this->_getBlockOffset($mbatFirstBlockId);
        for ($i = 0; $i < $mbbatBlockCount; $i++) {
            fseek($fh, $pos);
            for ($j = 0; $j < $this->bigBlockSize / 4 - 1; $j++) {
                $mbatBlocks[] = self::_readInt4($fh);
            }
            $pos = $this->_getBlockOffset(self::_readInt4($fh));
        }
        for ($i = 0; $i < $bbatBlockCount; $i++) {
            $pos = $this->_getBlockOffset($mbatBlocks[$i]);
            fseek($fh, $pos);
            for ($j = 0; $j < $this->bigBlockSize / 4; $j++) {
                $this->bbat[] = self::_readInt4($fh);
            }
        }
        $this->sbat = array();
        $shortBlockCount = $sbbatBlockCount * $this->bigBlockSize / 4;
        $sbatFh = $this->getStream($sbatFirstBlockId);
        for ($blockId = 0; $blockId < $shortBlockCount; $blockId++) {
            $this->sbat[$blockId] = self::_readInt4($sbatFh);
        }
        fclose($sbatFh);
        $this->_readPpsWks($directoryFirstBlockId);
        return true;
    }
    /**
     * @param  int  block id
     * @param  int  byte offset from beginning of file
     * @access public
     */
    public function _getBlockOffset($blockId)
    {
        return 512 + $blockId * $this->bigBlockSize;
    }
    /**
     * Returns a stream for use with fread() etc. External callers should
     * use PHPExcel_Shared_OLE_PPS_File::getStream().
     * @param   int|PPS   block id or PPS
     * @return  resource  read-only stream
     */
    public function getStream($blockIdOrPps)
    {
        static $isRegistered = false;
        if (!$isRegistered) {
            stream_wrapper_register("ole-chainedblockstream", "PHPExcel_Shared_OLE_ChainedBlockStream");
            $isRegistered = true;
        }
        $GLOBALS["_OLE_INSTANCES"][] = $this;
        $instanceId = end(array_keys($GLOBALS["_OLE_INSTANCES"]));
        $path = "ole-chainedblockstream://oleInstanceId=" . $instanceId;
        if ($blockIdOrPps instanceof PHPExcel_Shared_OLE_PPS) {
            $path .= "&blockId=" . $blockIdOrPps->_StartBlock;
            $path .= "&size=" . $blockIdOrPps->Size;
        } else {
            $path .= "&blockId=" . $blockIdOrPps;
        }
        return fopen($path, "r");
    }
    /**
     * Reads a signed char.
     * @param   resource  file handle
     * @return  int
     * @access public
     */
    private static function _readInt1($fh)
    {
        list($tmp) = unpack("c", fread($fh, 1));
        return $tmp;
    }
    /**
     * Reads an unsigned short (2 octets).
     * @param   resource  file handle
     * @return  int
     * @access public
     */
    private static function _readInt2($fh)
    {
        list($tmp) = unpack("v", fread($fh, 2));
        return $tmp;
    }
    /**
     * Reads an unsigned long (4 octets).
     * @param   resource  file handle
     * @return  int
     * @access public
     */
    private static function _readInt4($fh)
    {
        list($tmp) = unpack("V", fread($fh, 4));
        return $tmp;
    }
    /**
     * Gets information about all PPS's on the OLE container from the PPS WK's
     * creates an OLE_PPS object for each one.
     *
     * @access public
     * @param  integer  the block id of the first block
     * @return mixed true on success, PEAR_Error on failure
     */
    public function _readPpsWks($blockId)
    {
        $fh = $this->getStream($blockId);
        $pos = 0;
        while (true) {
            fseek($fh, $pos, SEEK_SET);
            $nameUtf16 = fread($fh, 64);
            $nameLength = self::_readInt2($fh);
            $nameUtf16 = substr($nameUtf16, 0, $nameLength - 2);
            $name = str_replace("", "", $nameUtf16);
            $type = self::_readInt1($fh);
            switch ($type) {
                case self::OLE_PPS_TYPE_ROOT:
                    $pps = new PHPExcel_Shared_OLE_PPS_Root(NULL, NULL, array());
                    $this->root = $pps;
                    break;
                case self::OLE_PPS_TYPE_DIR:
                    $pps = new PHPExcel_Shared_OLE_PPS(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, array());
                    break;
                case self::OLE_PPS_TYPE_FILE:
                    $pps = new PHPExcel_Shared_OLE_PPS_File($name);
                    break;
                default:
                    continue;
            }
            fseek($fh, 1, SEEK_CUR);
            $pps->Type = $type;
            $pps->Name = $name;
            $pps->PrevPps = self::_readInt4($fh);
            $pps->NextPps = self::_readInt4($fh);
            $pps->DirPps = self::_readInt4($fh);
            fseek($fh, 20, SEEK_CUR);
            $pps->Time1st = self::OLE2LocalDate(fread($fh, 8));
            $pps->Time2nd = self::OLE2LocalDate(fread($fh, 8));
            $pps->_StartBlock = self::_readInt4($fh);
            $pps->Size = self::_readInt4($fh);
            $pps->No = count($this->_list);
            $this->_list[] = $pps;
            if (isset($this->root) && $this->_ppsTreeComplete($this->root->No)) {
                break;
            }
            $pos += 128;
        }
        fclose($fh);
        foreach ($this->_list as $pps) {
            if ($pps->Type == self::OLE_PPS_TYPE_DIR || $pps->Type == self::OLE_PPS_TYPE_ROOT) {
                $nos = array($pps->DirPps);
                $pps->children = array();
                while ($nos) {
                    $no = array_pop($nos);
                    if ($no != -1) {
                        $childPps = $this->_list[$no];
                        $nos[] = $childPps->PrevPps;
                        $nos[] = $childPps->NextPps;
                        $pps->children[] = $childPps;
                    }
                }
            }
        }
        return true;
    }
    /**
     * It checks whether the PPS tree is complete (all PPS's read)
     * starting with the given PPS (not necessarily root)
     *
     * @access public
     * @param integer $index The index of the PPS from which we are checking
     * @return boolean Whether the PPS tree for the given PPS is complete
     */
    public function _ppsTreeComplete($index)
    {
        return isset($this->_list[$index]) && ($pps = $this->_list[$index]) && ($pps->PrevPps == -1 || $this->_ppsTreeComplete($pps->PrevPps)) && ($pps->NextPps == -1 || $this->_ppsTreeComplete($pps->NextPps)) && ($pps->DirPps == -1 || $this->_ppsTreeComplete($pps->DirPps));
    }
    /**
     * Checks whether a PPS is a File PPS or not.
     * If there is no PPS for the index given, it will return false.
     *
     * @access public
     * @param integer $index The index for the PPS
     * @return bool true if it's a File PPS, false otherwise
     */
    public function isFile($index)
    {
        if (isset($this->_list[$index])) {
            return $this->_list[$index]->Type == self::OLE_PPS_TYPE_FILE;
        }
        return false;
    }
    /**
     * Checks whether a PPS is a Root PPS or not.
     * If there is no PPS for the index given, it will return false.
     *
     * @access public
     * @param integer $index The index for the PPS.
     * @return bool true if it's a Root PPS, false otherwise
     */
    public function isRoot($index)
    {
        if (isset($this->_list[$index])) {
            return $this->_list[$index]->Type == self::OLE_PPS_TYPE_ROOT;
        }
        return false;
    }
    /**
     * Gives the total number of PPS's found in the OLE container.
     *
     * @access public
     * @return integer The total number of PPS's found in the OLE container
     */
    public function ppsTotal()
    {
        return count($this->_list);
    }
    /**
     * Gets data from a PPS
     * If there is no PPS for the index given, it will return an empty string.
     *
     * @access public
     * @param integer $index    The index for the PPS
     * @param integer $position The position from which to start reading
     *                          (relative to the PPS)
     * @param integer $length   The amount of bytes to read (at most)
     * @return string The binary string containing the data requested
     * @see OLE_PPS_File::getStream()
     */
    public function getData($index, $position, $length)
    {
        if (!isset($this->_list[$index]) || $this->_list[$index]->Size <= $position || $position < 0) {
            return "";
        }
        $fh = $this->getStream($this->_list[$index]);
        $data = stream_get_contents($fh, $length, $position);
        fclose($fh);
        return $data;
    }
    /**
     * Gets the data length from a PPS
     * If there is no PPS for the index given, it will return 0.
     *
     * @access public
     * @param integer $index    The index for the PPS
     * @return integer The amount of bytes in data the PPS has
     */
    public function getDataLength($index)
    {
        if (isset($this->_list[$index])) {
            return $this->_list[$index]->Size;
        }
        return 0;
    }
    /**
     * Utility function to transform ASCII text to Unicode
     *
     * @access public
     * @static
     * @param string $ascii The ASCII string to transform
     * @return string The string in Unicode
     */
    public static function Asc2Ucs($ascii)
    {
        $rawname = "";
        for ($i = 0; $i < strlen($ascii); $i++) {
            $rawname .= $ascii[$i] . "";
        }
        return $rawname;
    }
    /**
     * Utility function
     * Returns a string for the OLE container with the date given
     *
     * @access public
     * @static
     * @param integer $date A timestamp
     * @return string The string for the OLE container
     */
    public static function LocalDate2OLE($date = NULL)
    {
        if (!isset($date)) {
            return "";
        }
        $factor = pow(2, 32);
        $days = 134774;
        $big_date = $days * 24 * 3600 + gmmktime(date("H", $date), date("i", $date), date("s", $date), date("m", $date), date("d", $date), date("Y", $date));
        $big_date *= 10000000;
        $high_part = floor($big_date / $factor);
        $low_part = floor(($big_date / $factor - $high_part) * $factor);
        $res = "";
        for ($i = 0; $i < 4; $i++) {
            $hex = $low_part % 256;
            $res .= pack("c", $hex);
            $low_part /= 256;
        }
        for ($i = 0; $i < 4; $i++) {
            $hex = $high_part % 256;
            $res .= pack("c", $hex);
            $high_part /= 256;
        }
        return $res;
    }
    /**
     * Returns a timestamp from an OLE container's date
     *
     * @access public
     * @static
     * @param integer $string A binary string with the encoded date
     * @return string The timestamp corresponding to the string
     */
    public static function OLE2LocalDate($string)
    {
        if (strlen($string) != 8) {
            return new PEAR_Error("Expecting 8 byte string");
        }
        $factor = pow(2, 32);
        list($high_part) = unpack("V", substr($string, 4, 4));
        list($low_part) = unpack("V", substr($string, 0, 4));
        $big_date = $high_part * $factor + $low_part;
        $big_date /= 10000000;
        $days = 134774;
        $big_date -= $days * 24 * 3600;
        return floor($big_date);
    }
}

?>