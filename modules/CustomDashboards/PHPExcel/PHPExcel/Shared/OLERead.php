<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

defined("IDENTIFIER_OLE") or define("IDENTIFIER_OLE", pack("CCCCCCCC", 208, 207, 17, 224, 161, 177, 26, 225));
defined("IDENTIFIER_OLE") || define("IDENTIFIER_OLE", pack("CCCCCCCC", 208, 207, 17, 224, 161, 177, 26, 225));
/**
 * PHPExcel
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
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Shared_OLERead
{
    private $data = "";
    public $wrkbook = NULL;
    public $summaryInformation = NULL;
    public $documentSummaryInformation = NULL;
    const IDENTIFIER_OLE = IDENTIFIER_OLE;
    const BIG_BLOCK_SIZE = 512;
    const SMALL_BLOCK_SIZE = 64;
    const PROPERTY_STORAGE_BLOCK_SIZE = 128;
    const SMALL_BLOCK_THRESHOLD = 4096;
    const NUM_BIG_BLOCK_DEPOT_BLOCKS_POS = 44;
    const ROOT_START_BLOCK_POS = 48;
    const SMALL_BLOCK_DEPOT_BLOCK_POS = 60;
    const EXTENSION_BLOCK_POS = 68;
    const NUM_EXTENSION_BLOCK_POS = 72;
    const BIG_BLOCK_DEPOT_BLOCKS_POS = 76;
    const SIZE_OF_NAME_POS = 64;
    const TYPE_POS = 66;
    const START_BLOCK_POS = 116;
    const SIZE_POS = 120;
    /**
     * Read the file
     *
     * @param $sFileName string Filename
     * @throws PHPExcel_Reader_Exception
     */
    public function read($sFileName)
    {
        if (!is_readable($sFileName)) {
            throw new PHPExcel_Reader_Exception("Could not open " . $sFileName . " for reading! File does not exist, or it is not readable.");
        }
        $this->data = file_get_contents($sFileName, false, NULL, 0, 8);
        if ($this->data != self::IDENTIFIER_OLE) {
            throw new PHPExcel_Reader_Exception("The filename " . $sFileName . " is not recognised as an OLE file");
        }
        $this->data = file_get_contents($sFileName);
        $this->numBigBlockDepotBlocks = self::getInt4d($this->data, self::NUM_BIG_BLOCK_DEPOT_BLOCKS_POS);
        $this->rootStartBlock = self::getInt4d($this->data, self::ROOT_START_BLOCK_POS);
        $this->sbdStartBlock = self::getInt4d($this->data, self::SMALL_BLOCK_DEPOT_BLOCK_POS);
        $this->extensionBlock = self::getInt4d($this->data, self::EXTENSION_BLOCK_POS);
        $this->numExtensionBlocks = self::getInt4d($this->data, self::NUM_EXTENSION_BLOCK_POS);
        $bigBlockDepotBlocks = array();
        $pos = self::BIG_BLOCK_DEPOT_BLOCKS_POS;
        $bbdBlocks = $this->numBigBlockDepotBlocks;
        if ($this->numExtensionBlocks != 0) {
            $bbdBlocks = (self::BIG_BLOCK_SIZE - self::BIG_BLOCK_DEPOT_BLOCKS_POS) / 4;
        }
        for ($i = 0; $i < $bbdBlocks; $i++) {
            $bigBlockDepotBlocks[$i] = self::getInt4d($this->data, $pos);
            $pos += 4;
        }
        for ($j = 0; $j < $this->numExtensionBlocks; $j++) {
            $pos = ($this->extensionBlock + 1) * self::BIG_BLOCK_SIZE;
            $blocksToRead = min($this->numBigBlockDepotBlocks - $bbdBlocks, self::BIG_BLOCK_SIZE / 4 - 1);
            for ($i = $bbdBlocks; $i < $bbdBlocks + $blocksToRead; $i++) {
                $bigBlockDepotBlocks[$i] = self::getInt4d($this->data, $pos);
                $pos += 4;
            }
            $bbdBlocks += $blocksToRead;
            if ($bbdBlocks < $this->numBigBlockDepotBlocks) {
                $this->extensionBlock = self::getInt4d($this->data, $pos);
            }
        }
        $pos = 0;
        $this->bigBlockChain = "";
        $bbs = self::BIG_BLOCK_SIZE / 4;
        for ($i = 0; $i < $this->numBigBlockDepotBlocks; $i++) {
            $pos = ($bigBlockDepotBlocks[$i] + 1) * self::BIG_BLOCK_SIZE;
            $this->bigBlockChain .= substr($this->data, $pos, 4 * $bbs);
            $pos += 4 * $bbs;
        }
        $pos = 0;
        $sbdBlock = $this->sbdStartBlock;
        $this->smallBlockChain = "";
        while ($sbdBlock != -2) {
            $pos = ($sbdBlock + 1) * self::BIG_BLOCK_SIZE;
            $this->smallBlockChain .= substr($this->data, $pos, 4 * $bbs);
            $pos += 4 * $bbs;
            $sbdBlock = self::getInt4d($this->bigBlockChain, $sbdBlock * 4);
        }
        $block = $this->rootStartBlock;
        $this->entry = $this->_readData($block);
        $this->readPropertySets();
    }
    /**
     * Extract binary stream data
     *
     * @return string
     */
    public function getStream($stream)
    {
        if ($stream === NULL) {
            return NULL;
        }
        $streamData = "";
        if ($this->props[$stream]["size"] < self::SMALL_BLOCK_THRESHOLD) {
            $rootdata = $this->_readData($this->props[$this->rootentry]["startBlock"]);
            $block = $this->props[$stream]["startBlock"];
            while ($block != -2) {
                $pos = $block * self::SMALL_BLOCK_SIZE;
                $streamData .= substr($rootdata, $pos, self::SMALL_BLOCK_SIZE);
                $block = self::getInt4d($this->smallBlockChain, $block * 4);
            }
            return $streamData;
        }
        $numBlocks = $this->props[$stream]["size"] / self::BIG_BLOCK_SIZE;
        if ($this->props[$stream]["size"] % self::BIG_BLOCK_SIZE != 0) {
            $numBlocks++;
        }
        if ($numBlocks == 0) {
            return "";
        }
        $block = $this->props[$stream]["startBlock"];
        while ($block != -2) {
            $pos = ($block + 1) * self::BIG_BLOCK_SIZE;
            $streamData .= substr($this->data, $pos, self::BIG_BLOCK_SIZE);
            $block = self::getInt4d($this->bigBlockChain, $block * 4);
        }
        return $streamData;
    }
    /**
     * Read a standard stream (by joining sectors using information from SAT)
     *
     * @param int $bl Sector ID where the stream starts
     * @return string Data for standard stream
     */
    private function _readData($bl)
    {
        $block = $bl;
        $data = "";
        while ($block != -2) {
            $pos = ($block + 1) * self::BIG_BLOCK_SIZE;
            $data .= substr($this->data, $pos, self::BIG_BLOCK_SIZE);
            $block = self::getInt4d($this->bigBlockChain, $block * 4);
        }
        return $data;
    }
    /**
     * Read entries in the directory stream.
     */
    private function readPropertySets()
    {
        $offset = 0;
        $entryLen = strlen($this->entry);
        while ($offset < $entryLen) {
            $d = substr($this->entry, $offset, self::PROPERTY_STORAGE_BLOCK_SIZE);
            $nameSize = ord($d[self::SIZE_OF_NAME_POS]) | ord($d[self::SIZE_OF_NAME_POS + 1]) << 8;
            $type = ord($d[self::TYPE_POS]);
            $startBlock = self::getInt4d($d, self::START_BLOCK_POS);
            $size = self::getInt4d($d, self::SIZE_POS);
            $name = str_replace("", "", substr($d, 0, $nameSize));
            $this->props[] = array("name" => $name, "type" => $type, "startBlock" => $startBlock, "size" => $size);
            $upName = strtoupper($name);
            if ($upName === "WORKBOOK" || $upName === "BOOK") {
                $this->wrkbook = count($this->props) - 1;
            } else {
                if ($upName === "ROOT ENTRY" || $upName === "R") {
                    $this->rootentry = count($this->props) - 1;
                }
            }
            if ($name == chr(5) . "SummaryInformation") {
                $this->summaryInformation = count($this->props) - 1;
            }
            if ($name == chr(5) . "DocumentSummaryInformation") {
                $this->documentSummaryInformation = count($this->props) - 1;
            }
            $offset += self::PROPERTY_STORAGE_BLOCK_SIZE;
        }
    }
    /**
     * Read 4 bytes of data at specified position
     *
     * @param string $data
     * @param int $pos
     * @return int
     */
    private static function getInt4d($data, $pos)
    {
        $_or_24 = ord($data[$pos + 3]);
        if (128 <= $_or_24) {
            $_ord_24 = 0 - abs(256 - $_or_24 << 24);
        } else {
            $_ord_24 = ($_or_24 & 127) << 24;
        }
        return ord($data[$pos]) | ord($data[$pos + 1]) << 8 | ord($data[$pos + 2]) << 16 | $_ord_24;
    }
}

?>