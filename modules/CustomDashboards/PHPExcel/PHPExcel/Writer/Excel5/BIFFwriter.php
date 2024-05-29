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
 * PHPExcel_Writer_Excel5_BIFFwriter
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
 * @package    PHPExcel_Writer_Excel5
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Writer_Excel5_BIFFwriter
{
    /**
     * The byte order of this architecture. 0 => little endian, 1 => big endian
     * @var integer
     */
    private static $byteOrder = NULL;
    /**
     * The string containing the data of the BIFF stream
     * @var string
     */
    public $_data = NULL;
    /**
     * The size of the data in bytes. Should be the same as strlen($this->_data)
     * @var integer
     */
    public $_datasize = NULL;
    /**
     * The maximum length for a BIFF record (excluding record header and length field). See addContinue()
     * @var integer
     * @see addContinue()
     */
    private $limit = 8224;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_data = "";
        $this->_datasize = 0;
    }
    /**
     * Determine the byte order and store it as class data to avoid
     * recalculating it for each call to new().
     *
     * @return int
     */
    public static function getByteOrder()
    {
        if (!isset($byteOrder)) {
            $teststr = pack("d", 1.2345);
            $number = pack("C8", 141, 151, 110, 18, 131, 192, 243, 63);
            if ($number == $teststr) {
                $byte_order = 0;
            } else {
                if ($number == strrev($teststr)) {
                    $byte_order = 1;
                } else {
                    throw new PHPExcel_Writer_Exception("Required floating point format not supported on this platform.");
                }
            }
            self::$byteOrder = $byte_order;
        }
        return self::$byteOrder;
    }
    /**
     * General storage function
     *
     * @param string $data binary data to append
     * @access private
     */
    protected function append($data)
    {
        if ($this->limit < strlen($data) - 4) {
            $data = $this->addContinue($data);
        }
        $this->_data .= $data;
        $this->_datasize += strlen($data);
    }
    /**
     * General storage function like append, but returns string instead of modifying $this->_data
     *
     * @param string $data binary data to write
     * @return string
     */
    public function writeData($data)
    {
        if ($this->limit < strlen($data) - 4) {
            $data = $this->addContinue($data);
        }
        $this->_datasize += strlen($data);
        return $data;
    }
    /**
     * Writes Excel BOF record to indicate the beginning of a stream or
     * sub-stream in the BIFF file.
     *
     * @param  integer $type Type of BIFF file to write: 0x0005 Workbook,
     *                       0x0010 Worksheet.
     * @access private
     */
    protected function storeBof($type)
    {
        $record = 2057;
        $length = 16;
        $unknown = pack("VV", 65745, 1030);
        $build = 3515;
        $year = 1996;
        $version = 1536;
        $header = pack("vv", $record, $length);
        $data = pack("vvvv", $version, $type, $build, $year);
        $this->append($header . $data . $unknown);
    }
    /**
     * Writes Excel EOF record to indicate the end of a BIFF stream.
     *
     * @access private
     */
    protected function storeEof()
    {
        $record = 10;
        $length = 0;
        $header = pack("vv", $record, $length);
        $this->append($header);
    }
    /**
     * Writes Excel EOF record to indicate the end of a BIFF stream.
     *
     * @access private
     */
    public function writeEof()
    {
        $record = 10;
        $length = 0;
        $header = pack("vv", $record, $length);
        return $this->writeData($header);
    }
    /**
     * Excel limits the size of BIFF records. In Excel 5 the limit is 2084 bytes. In
     * Excel 97 the limit is 8228 bytes. Records that are longer than these limits
     * must be split up into CONTINUE blocks.
     *
     * This function takes a long BIFF record and inserts CONTINUE records as
     * necessary.
     *
     * @param  string  $data The original binary data to be written
     * @return string        A very convenient string of continue blocks
     * @access private
     */
    private function addContinue($data)
    {
        $limit = $this->limit;
        $record = 60;
        $tmp = substr($data, 0, 2) . pack("v", $limit) . substr($data, 4, $limit);
        $header = pack("vv", $record, $limit);
        $data_length = strlen($data);
        $i = $limit + 4;
        while ($i < $data_length - $limit) {
            $tmp .= $header;
            $tmp .= substr($data, $i, $limit);
            $i += $limit;
        }
        $header = pack("vv", $record, strlen($data) - $i);
        $tmp .= $header;
        $tmp .= substr($data, $i, strlen($data) - $i);
        return $tmp;
    }
}

?>