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
* Class for creating Root PPS's for OLE containers
*
* @author   Xavier Noguer <xnoguer@php.net>
* @category PHPExcel
* @package  PHPExcel_Shared_OLE
*/
class PHPExcel_Shared_OLE_PPS_Root extends PHPExcel_Shared_OLE_PPS
{
    /**
     * Directory for temporary files
     * @var string
     */
    protected $tempDirectory = NULL;
    /**
     * @param integer $time_1st A timestamp
     * @param integer $time_2nd A timestamp
     */
    public function __construct($time_1st, $time_2nd, $raChild)
    {
        $this->_tempDir = PHPExcel_Shared_File::sys_get_temp_dir();
        parent::__construct(NULL, PHPExcel_Shared_OLE::Asc2Ucs("Root Entry"), PHPExcel_Shared_OLE::OLE_PPS_TYPE_ROOT, NULL, NULL, NULL, $time_1st, $time_2nd, NULL, $raChild);
    }
    /**
     * Method for saving the whole OLE container (including files).
     * In fact, if called with an empty argument (or '-'), it saves to a
     * temporary file and then outputs it's contents to stdout.
     * If a resource pointer to a stream created by fopen() is passed
     * it will be used, but you have to close such stream by yourself.
     *
     * @param string|resource $filename The name of the file or stream where to save the OLE container.
     * @access public
     * @return mixed true on success
     */
    public function save($filename)
    {
        $this->_BIG_BLOCK_SIZE = pow(2, isset($this->_BIG_BLOCK_SIZE) ? self::adjust2($this->_BIG_BLOCK_SIZE) : 9);
        $this->_SMALL_BLOCK_SIZE = pow(2, isset($this->_SMALL_BLOCK_SIZE) ? self::adjust2($this->_SMALL_BLOCK_SIZE) : 6);
        if (is_resource($filename)) {
            $this->_FILEH_ = $filename;
        } else {
            if ($filename == "-" || $filename == "") {
                if ($this->tempDirectory === NULL) {
                    $this->tempDirectory = PHPExcel_Shared_File::sys_get_temp_dir();
                }
                $this->_tmp_filename = tempnam($this->tempDirectory, "OLE_PPS_Root");
                $this->_FILEH_ = fopen($this->_tmp_filename, "w+b");
                if ($this->_FILEH_ == false) {
                    throw new PHPExcel_Writer_Exception("Can't create temporary file.");
                }
            } else {
                $this->_FILEH_ = fopen($filename, "wb");
            }
        }
        if ($this->_FILEH_ == false) {
            throw new PHPExcel_Writer_Exception("Can't open " . $filename . ". It may be in use or protected.");
        }
        $aList = array();
        PHPExcel_Shared_OLE_PPS::_savePpsSetPnt($aList, array($this));
        list($iSBDcnt, $iBBcnt, $iPPScnt) = $this->_calcSize($aList);
        $this->_saveHeader($iSBDcnt, $iBBcnt, $iPPScnt);
        $this->_data = $this->_makeSmallData($aList);
        $this->_saveBigData($iSBDcnt, $aList);
        $this->_savePps($aList);
        $this->_saveBbd($iSBDcnt, $iBBcnt, $iPPScnt);
        if (!is_resource($filename)) {
            fclose($this->_FILEH_);
        }
        return true;
    }
    /**
     * Calculate some numbers
     *
     * @access public
     * @param array $raList Reference to an array of PPS's
     * @return array The array of numbers
     */
    public function _calcSize(&$raList)
    {
        list($iSBDcnt, $iBBcnt, $iPPScnt) = array(0, 0, 0);
        $iSmallLen = 0;
        $iSBcnt = 0;
        $iCount = count($raList);
        for ($i = 0; $i < $iCount; $i++) {
            if ($raList[$i]->Type == PHPExcel_Shared_OLE::OLE_PPS_TYPE_FILE) {
                $raList[$i]->Size = $raList[$i]->_DataLen();
                if ($raList[$i]->Size < PHPExcel_Shared_OLE::OLE_DATA_SIZE_SMALL) {
                    $iSBcnt += floor($raList[$i]->Size / $this->_SMALL_BLOCK_SIZE) + ($raList[$i]->Size % $this->_SMALL_BLOCK_SIZE ? 1 : 0);
                } else {
                    $iBBcnt += floor($raList[$i]->Size / $this->_BIG_BLOCK_SIZE) + ($raList[$i]->Size % $this->_BIG_BLOCK_SIZE ? 1 : 0);
                }
            }
        }
        $iSmallLen = $iSBcnt * $this->_SMALL_BLOCK_SIZE;
        $iSlCnt = floor($this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE);
        $iSBDcnt = floor($iSBcnt / $iSlCnt) + ($iSBcnt % $iSlCnt ? 1 : 0);
        $iBBcnt += floor($iSmallLen / $this->_BIG_BLOCK_SIZE) + ($iSmallLen % $this->_BIG_BLOCK_SIZE ? 1 : 0);
        $iCnt = count($raList);
        $iBdCnt = $this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_PPS_SIZE;
        $iPPScnt = floor($iCnt / $iBdCnt) + ($iCnt % $iBdCnt ? 1 : 0);
        return array($iSBDcnt, $iBBcnt, $iPPScnt);
    }
    /**
     * Helper function for caculating a magic value for block sizes
     *
     * @access public
     * @param integer $i2 The argument
     * @see save()
     * @return integer
     */
    private static function adjust2($i2)
    {
        $iWk = log($i2) / log(2);
        return floor($iWk) < $iWk ? floor($iWk) + 1 : $iWk;
    }
    /**
     * Save OLE header
     *
     * @access public
     * @param integer $iSBDcnt
     * @param integer $iBBcnt
     * @param integer $iPPScnt
     */
    public function _saveHeader($iSBDcnt, $iBBcnt, $iPPScnt)
    {
        $FILE = $this->_FILEH_;
        $iBlCnt = $this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE;
        $i1stBdL = ($this->_BIG_BLOCK_SIZE - 76) / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE;
        $iBdExL = 0;
        $iAll = $iBBcnt + $iPPScnt + $iSBDcnt;
        $iAllW = $iAll;
        $iBdCntW = floor($iAllW / $iBlCnt) + ($iAllW % $iBlCnt ? 1 : 0);
        $iBdCnt = floor(($iAll + $iBdCntW) / $iBlCnt) + (($iAllW + $iBdCntW) % $iBlCnt ? 1 : 0);
        if ($i1stBdL < $iBdCnt) {
            while (1) {
                $iBdExL++;
                $iAllW++;
                $iBdCntW = floor($iAllW / $iBlCnt) + ($iAllW % $iBlCnt ? 1 : 0);
                $iBdCnt = floor(($iAllW + $iBdCntW) / $iBlCnt) + (($iAllW + $iBdCntW) % $iBlCnt ? 1 : 0);
                if ($iBdCnt <= $iBdExL * $iBlCnt + $i1stBdL) {
                    break;
                }
            }
        }
        fwrite($FILE, "ÐÏ\21à¡±\32á" . "" . "" . "" . "" . pack("v", 59) . pack("v", 3) . pack("v", -2) . pack("v", 9) . pack("v", 6) . pack("v", 0) . "" . "" . pack("V", $iBdCnt) . pack("V", $iBBcnt + $iSBDcnt) . pack("V", 0) . pack("V", 4096) . pack("V", $iSBDcnt ? 0 : -2) . pack("V", $iSBDcnt));
        if ($iBdCnt < $i1stBdL) {
            fwrite($FILE, pack("V", -2) . pack("V", 0));
        } else {
            fwrite($FILE, pack("V", $iAll + $iBdCnt) . pack("V", $iBdExL));
        }
        for ($i = 0; $i < $i1stBdL && $i < $iBdCnt; $i++) {
            fwrite($FILE, pack("V", $iAll + $i));
        }
        if ($i < $i1stBdL) {
            $jB = $i1stBdL - $i;
            for ($j = 0; $j < $jB; $j++) {
                fwrite($FILE, pack("V", -1));
            }
        }
    }
    /**
     * Saving big data (PPS's with data bigger than PHPExcel_Shared_OLE::OLE_DATA_SIZE_SMALL)
     *
     * @access public
     * @param integer $iStBlk
     * @param array &$raList Reference to array of PPS's
     */
    public function _saveBigData($iStBlk, &$raList)
    {
        $FILE = $this->_FILEH_;
        $iCount = count($raList);
        for ($i = 0; $i < $iCount; $i++) {
            if ($raList[$i]->Type != PHPExcel_Shared_OLE::OLE_PPS_TYPE_DIR) {
                $raList[$i]->Size = $raList[$i]->_DataLen();
                if (PHPExcel_Shared_OLE::OLE_DATA_SIZE_SMALL <= $raList[$i]->Size || $raList[$i]->Type == PHPExcel_Shared_OLE::OLE_PPS_TYPE_ROOT && isset($raList[$i]->_data)) {
                    fwrite($FILE, $raList[$i]->_data);
                    if ($raList[$i]->Size % $this->_BIG_BLOCK_SIZE) {
                        fwrite($FILE, str_repeat("", $this->_BIG_BLOCK_SIZE - $raList[$i]->Size % $this->_BIG_BLOCK_SIZE));
                    }
                    $raList[$i]->_StartBlock = $iStBlk;
                    $iStBlk += floor($raList[$i]->Size / $this->_BIG_BLOCK_SIZE) + ($raList[$i]->Size % $this->_BIG_BLOCK_SIZE ? 1 : 0);
                }
            }
        }
    }
    /**
     * get small data (PPS's with data smaller than PHPExcel_Shared_OLE::OLE_DATA_SIZE_SMALL)
     *
     * @access public
     * @param array &$raList Reference to array of PPS's
     */
    public function _makeSmallData(&$raList)
    {
        $sRes = "";
        $FILE = $this->_FILEH_;
        $iSmBlk = 0;
        $iCount = count($raList);
        for ($i = 0; $i < $iCount; $i++) {
            if ($raList[$i]->Type == PHPExcel_Shared_OLE::OLE_PPS_TYPE_FILE) {
                if ($raList[$i]->Size <= 0) {
                    continue;
                }
                if ($raList[$i]->Size < PHPExcel_Shared_OLE::OLE_DATA_SIZE_SMALL) {
                    $iSmbCnt = floor($raList[$i]->Size / $this->_SMALL_BLOCK_SIZE) + ($raList[$i]->Size % $this->_SMALL_BLOCK_SIZE ? 1 : 0);
                    $jB = $iSmbCnt - 1;
                    for ($j = 0; $j < $jB; $j++) {
                        fwrite($FILE, pack("V", $j + $iSmBlk + 1));
                    }
                    fwrite($FILE, pack("V", -2));
                    $sRes .= $raList[$i]->_data;
                    if ($raList[$i]->Size % $this->_SMALL_BLOCK_SIZE) {
                        $sRes .= str_repeat("", $this->_SMALL_BLOCK_SIZE - $raList[$i]->Size % $this->_SMALL_BLOCK_SIZE);
                    }
                    $raList[$i]->_StartBlock = $iSmBlk;
                    $iSmBlk += $iSmbCnt;
                }
            }
        }
        $iSbCnt = floor($this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE);
        if ($iSmBlk % $iSbCnt) {
            $iB = $iSbCnt - $iSmBlk % $iSbCnt;
            for ($i = 0; $i < $iB; $i++) {
                fwrite($FILE, pack("V", -1));
            }
        }
        return $sRes;
    }
    /**
     * Saves all the PPS's WKs
     *
     * @access public
     * @param array $raList Reference to an array with all PPS's
     */
    public function _savePps(&$raList)
    {
        $iC = count($raList);
        for ($i = 0; $i < $iC; $i++) {
            fwrite($this->_FILEH_, $raList[$i]->_getPpsWk());
        }
        $iCnt = count($raList);
        $iBCnt = $this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_PPS_SIZE;
        if ($iCnt % $iBCnt) {
            fwrite($this->_FILEH_, str_repeat("", ($iBCnt - $iCnt % $iBCnt) * PHPExcel_Shared_OLE::OLE_PPS_SIZE));
        }
    }
    /**
     * Saving Big Block Depot
     *
     * @access public
     * @param integer $iSbdSize
     * @param integer $iBsize
     * @param integer $iPpsCnt
     */
    public function _saveBbd($iSbdSize, $iBsize, $iPpsCnt)
    {
        $FILE = $this->_FILEH_;
        $iBbCnt = $this->_BIG_BLOCK_SIZE / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE;
        $i1stBdL = ($this->_BIG_BLOCK_SIZE - 76) / PHPExcel_Shared_OLE::OLE_LONG_INT_SIZE;
        $iBdExL = 0;
        $iAll = $iBsize + $iPpsCnt + $iSbdSize;
        $iAllW = $iAll;
        $iBdCntW = floor($iAllW / $iBbCnt) + ($iAllW % $iBbCnt ? 1 : 0);
        $iBdCnt = floor(($iAll + $iBdCntW) / $iBbCnt) + (($iAllW + $iBdCntW) % $iBbCnt ? 1 : 0);
        if ($i1stBdL < $iBdCnt) {
            while (1) {
                $iBdExL++;
                $iAllW++;
                $iBdCntW = floor($iAllW / $iBbCnt) + ($iAllW % $iBbCnt ? 1 : 0);
                $iBdCnt = floor(($iAllW + $iBdCntW) / $iBbCnt) + (($iAllW + $iBdCntW) % $iBbCnt ? 1 : 0);
                if ($iBdCnt <= $iBdExL * $iBbCnt + $i1stBdL) {
                    break;
                }
            }
        }
        if (0 < $iSbdSize) {
            for ($i = 0; $i < $iSbdSize - 1; $i++) {
                fwrite($FILE, pack("V", $i + 1));
            }
            fwrite($FILE, pack("V", -2));
        }
        for ($i = 0; $i < $iBsize - 1; $i++) {
            fwrite($FILE, pack("V", $i + $iSbdSize + 1));
        }
        fwrite($FILE, pack("V", -2));
        for ($i = 0; $i < $iPpsCnt - 1; $i++) {
            fwrite($FILE, pack("V", $i + $iSbdSize + $iBsize + 1));
        }
        fwrite($FILE, pack("V", -2));
        for ($i = 0; $i < $iBdCnt; $i++) {
            fwrite($FILE, pack("V", 4294967293.0));
        }
        for ($i = 0; $i < $iBdExL; $i++) {
            fwrite($FILE, pack("V", 4294967292.0));
        }
        if (($iAllW + $iBdCnt) % $iBbCnt) {
            $iBlock = $iBbCnt - ($iAllW + $iBdCnt) % $iBbCnt;
            for ($i = 0; $i < $iBlock; $i++) {
                fwrite($FILE, pack("V", -1));
            }
        }
        if ($i1stBdL < $iBdCnt) {
            $iN = 0;
            $iNb = 0;
            for ($i = $i1stBdL; $i < $iBdCnt; $iN++) {
                if ($iBbCnt - 1 <= $iN) {
                    $iN = 0;
                    $iNb++;
                    fwrite($FILE, pack("V", $iAll + $iBdCnt + $iNb));
                }
                fwrite($FILE, pack("V", $iBsize + $iSbdSize + $iPpsCnt + $i));
                $i++;
            }
            if (($iBdCnt - $i1stBdL) % ($iBbCnt - 1)) {
                $iB = $iBbCnt - 1 - ($iBdCnt - $i1stBdL) % ($iBbCnt - 1);
                for ($i = 0; $i < $iB; $i++) {
                    fwrite($FILE, pack("V", -1));
                }
            }
            fwrite($FILE, pack("V", -2));
        }
    }
}

?>