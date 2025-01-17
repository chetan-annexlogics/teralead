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
* Class for creating File PPS's for OLE containers
*
* @author   Xavier Noguer <xnoguer@php.net>
* @category PHPExcel
* @package  PHPExcel_Shared_OLE
*/
class PHPExcel_Shared_OLE_PPS_File extends PHPExcel_Shared_OLE_PPS
{
    /**
     * The constructor
     *
     * @access public
     * @param string $name The name of the file (in Unicode)
     * @see OLE::Asc2Ucs()
     */
    public function __construct($name)
    {
        parent::__construct(NULL, $name, PHPExcel_Shared_OLE::OLE_PPS_TYPE_FILE, NULL, NULL, NULL, NULL, NULL, "", array());
    }
    /**
     * Initialization method. Has to be called right after OLE_PPS_File().
     *
     * @access public
     * @return mixed true on success
     */
    public function init()
    {
        return true;
    }
    /**
     * Append data to PPS
     *
     * @access public
     * @param string $data The data to append
     */
    public function append($data)
    {
        $this->_data .= $data;
    }
    /**
     * Returns a stream for reading this file using fread() etc.
     * @return  resource  a read-only stream
     */
    public function getStream()
    {
        $this->ole->getStream($this);
    }
}

?>