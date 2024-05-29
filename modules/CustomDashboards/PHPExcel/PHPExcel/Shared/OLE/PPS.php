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
* Class for creating PPS's for OLE containers
*
* @author   Xavier Noguer <xnoguer@php.net>
* @category PHPExcel
* @package  PHPExcel_Shared_OLE
*/
class PHPExcel_Shared_OLE_PPS
{
    /**
     * The PPS index
     * @var integer
     */
    public $No = NULL;
    /**
     * The PPS name (in Unicode)
     * @var string
     */
    public $Name = NULL;
    /**
     * The PPS type. Dir, Root or File
     * @var integer
     */
    public $Type = NULL;
    /**
     * The index of the previous PPS
     * @var integer
     */
    public $PrevPps = NULL;
    /**
     * The index of the next PPS
     * @var integer
     */
    public $NextPps = NULL;
    /**
     * The index of it's first child if this is a Dir or Root PPS
     * @var integer
     */
    public $DirPps = NULL;
    /**
     * A timestamp
     * @var integer
     */
    public $Time1st = NULL;
    /**
     * A timestamp
     * @var integer
     */
    public $Time2nd = NULL;
    /**
     * Starting block (small or big) for this PPS's data  inside the container
     * @var integer
     */
    public $_StartBlock = NULL;
    /**
     * The size of the PPS's data (in bytes)
     * @var integer
     */
    public $Size = NULL;
    /**
     * The PPS's data (only used if it's not using a temporary file)
     * @var string
     */
    public $_data = NULL;
    /**
     * Array of child PPS's (only used by Root and Dir PPS's)
     * @var array
     */
    public $children = array();
    /**
     * Pointer to OLE container
     * @var OLE
     */
    public $ole = NULL;
    /**
     * The constructor
     *
     * @access public
     * @param integer $No   The PPS index
     * @param string  $name The PPS name
     * @param integer $type The PPS type. Dir, Root or File
     * @param integer $prev The index of the previous PPS
     * @param integer $next The index of the next PPS
     * @param integer $dir  The index of it's first child if this is a Dir or Root PPS
     * @param integer $time_1st A timestamp
     * @param integer $time_2nd A timestamp
     * @param string  $data  The (usually binary) source data of the PPS
     * @param array   $children Array containing children PPS for this PPS
     */
    public function __construct($No, $name, $type, $prev, $next, $dir, $time_1st, $time_2nd, $data, $children)
    {
        $this->No = $No;
        $this->Name = $name;
        $this->Type = $type;
        $this->PrevPps = $prev;
        $this->NextPps = $next;
        $this->DirPps = $dir;
        $this->Time1st = $time_1st;
        $this->Time2nd = $time_2nd;
        $this->_data = $data;
        $this->children = $children;
        if ($data != "") {
            $this->Size = strlen($data);
        } else {
            $this->Size = 0;
        }
    }
    /**
     * Returns the amount of data saved for this PPS
     *
     * @access public
     * @return integer The amount of data (in bytes)
     */
    public function _DataLen()
    {
        if (!isset($this->_data)) {
            return 0;
        }
        return strlen($this->_data);
    }
    /**
     * Returns a string with the PPS's WK (What is a WK?)
     *
     * @access public
     * @return string The binary string
     */
    public function _getPpsWk()
    {
        $ret = str_pad($this->Name, 64, "");
        $ret .= pack("v", strlen($this->Name) + 2) . pack("c", $this->Type) . pack("c", 0) . pack("V", $this->PrevPps) . pack("V", $this->NextPps) . pack("V", $this->DirPps) . "" . "" . "À" . "" . "" . PHPExcel_Shared_OLE::LocalDate2OLE($this->Time1st) . PHPExcel_Shared_OLE::LocalDate2OLE($this->Time2nd) . pack("V", isset($this->_StartBlock) ? $this->_StartBlock : 0) . pack("V", $this->Size) . pack("V", 0);
        return $ret;
    }
    /**
     * Updates index and pointers to previous, next and children PPS's for this
     * PPS. I don't think it'll work with Dir PPS's.
     *
     * @access public
     * @param array &$raList Reference to the array of PPS's for the whole OLE
     *                          container
     * @return integer          The index for this PPS
     */
    public static function _savePpsSetPnt(&$raList, $to_save, $depth = 0)
    {
        if (!is_array($to_save) || empty($to_save)) {
            return 4294967295.0;
        }
        if (count($to_save) == 1) {
            $cnt = count($raList);
            $raList[$cnt] = $depth == 0 ? $to_save[0] : clone $to_save[0];
            $raList[$cnt]->No = $cnt;
            $raList[$cnt]->PrevPps = 4294967295.0;
            $raList[$cnt]->NextPps = 4294967295.0;
            $raList[$cnt]->DirPps = self::_savePpsSetPnt($raList, $raList[$cnt]->children, $depth++);
        } else {
            $iPos = floor(count($to_save) / 2);
            $aPrev = array_slice($to_save, 0, $iPos);
            $aNext = array_slice($to_save, $iPos + 1);
            $cnt = count($raList);
            $raList[$cnt] = $depth == 0 ? $to_save[$iPos] : clone $to_save[$iPos];
            $raList[$cnt]->No = $cnt;
            $raList[$cnt]->PrevPps = self::_savePpsSetPnt($raList, $aPrev, $depth++);
            $raList[$cnt]->NextPps = self::_savePpsSetPnt($raList, $aNext, $depth++);
            $raList[$cnt]->DirPps = self::_savePpsSetPnt($raList, $raList[$cnt]->children, $depth++);
        }
        return $cnt;
    }
}

?>