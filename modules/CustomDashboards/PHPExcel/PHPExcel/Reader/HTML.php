<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

if (!defined("PHPEXCEL_ROOT")) {
    define("PHPEXCEL_ROOT", dirname(__FILE__) . "/../../");
    require PHPEXCEL_ROOT . "PHPExcel/Autoloader.php";
}
/** PHPExcel root directory */
class PHPExcel_Reader_HTML extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
{
    /**
     * Input encoding
     *
     * @var string
     */
    protected $inputEncoding = "ANSI";
    /**
     * Sheet index to read
     *
     * @var int
     */
    protected $sheetIndex = 0;
    /**
     * Formats
     *
     * @var array
     */
    protected $formats = NULL;
    protected $rowspan = array();
    protected $dataArray = array();
    protected $tableLevel = 0;
    protected $nestedColumn = array("A");
    /**
     * Create a new PHPExcel_Reader_HTML
     */
    public function __construct()
    {
        $this->readFilter = new PHPExcel_Reader_DefaultReadFilter();
    }
    /**
     * Validate that the current file is an HTML file
     *
     * @return boolean
     */
    protected function isValidFormat()
    {
        $data = fread($this->fileHandle, 2048);
        if (strpos($data, "<") !== false && strlen($data) !== strlen(strip_tags($data))) {
            return true;
        }
        return false;
    }
    /**
     * Loads PHPExcel from file
     *
     * @param  string                    $pFilename
     * @return PHPExcel
     * @throws PHPExcel_Reader_Exception
     */
    public function load($pFilename)
    {
        $objPHPExcel = new PHPExcel();
        return $this->loadIntoExisting($pFilename, $objPHPExcel);
    }
    /**
     * Set input encoding
     *
     * @param string $pValue Input encoding
     */
    public function setInputEncoding($pValue = "ANSI")
    {
        $this->inputEncoding = $pValue;
        return $this;
    }
    /**
     * Get input encoding
     *
     * @return string
     */
    public function getInputEncoding()
    {
        return $this->inputEncoding;
    }
    protected function setTableStartColumn($column)
    {
        if ($this->tableLevel == 0) {
            $column = "A";
        }
        $this->tableLevel++;
        $this->nestedColumn[$this->tableLevel] = $column;
        return $this->nestedColumn[$this->tableLevel];
    }
    protected function getTableStartColumn()
    {
        return $this->nestedColumn[$this->tableLevel];
    }
    protected function releaseTableStartColumn()
    {
        $this->tableLevel--;
        return array_pop($this->nestedColumn);
    }
    protected function flushCell($sheet, $column, $row, &$cellContent)
    {
        if (is_string($cellContent)) {
            if ("" < trim($cellContent)) {
                $sheet->setCellValue($column . $row, $cellContent, true);
                $this->dataArray[$row][$column] = $cellContent;
            }
        } else {
            $this->dataArray[$row][$column] = "RICH TEXT: " . $cellContent;
        }
        $cellContent = (string) "";
    }
    protected function processDomElement(DOMNode $element, $sheet, &$row, &$column, &$cellContent, $format = NULL)
    {
        foreach ($element->childNodes as $child) {
            if ($child instanceof DOMText) {
                $domText = preg_replace("/\\s+/u", " ", trim($child->nodeValue));
                if (is_string($cellContent)) {
                    $cellContent .= $domText;
                }
            } else {
                if ($child instanceof DOMElement) {
                    $attributeArray = array();
                    foreach ($child->attributes as $attribute) {
                        $attributeArray[$attribute->name] = $attribute->value;
                    }
                    switch ($child->nodeName) {
                        case "meta":
                            foreach ($attributeArray as $attributeName => $attributeValue) {
                                switch ($attributeName) {
                                    case "content":
                                        break;
                                }
                            }
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            break;
                        case "title":
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            $sheet->setTitle($cellContent);
                            $cellContent = "";
                            break;
                        case "span":
                        case "div":
                        case "font":
                        case "i":
                        case "em":
                        case "strong":
                        case "b":
                            if ("" < $cellContent) {
                                $cellContent .= " ";
                            }
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            if ("" < $cellContent) {
                                $cellContent .= " ";
                            }
                            break;
                        case "hr":
                            $this->flushCell($sheet, $column, $row, $cellContent);
                            $row++;
                            if (isset($this->formats[$child->nodeName])) {
                                $sheet->getStyle($column . $row)->applyFromArray($this->formats[$child->nodeName]);
                            } else {
                                $cellContent = "----------";
                                $this->flushCell($sheet, $column, $row, $cellContent);
                            }
                            $row++;
                        case "br":
                            if (0 < $this->tableLevel) {
                                $cellContent .= "\n";
                            } else {
                                $this->flushCell($sheet, $column, $row, $cellContent);
                                $row++;
                            }
                            break;
                        case "a":
                            foreach ($attributeArray as $attributeName => $attributeValue) {
                                switch ($attributeName) {
                                    case "href":
                                        $sheet->getCell($column . $row)->getHyperlink()->setUrl($attributeValue);
                                        if (isset($this->formats[$child->nodeName])) {
                                            $sheet->getStyle($column . $row)->applyFromArray($this->formats[$child->nodeName]);
                                        }
                                        break;
                                }
                            }
                            $cellContent .= " ";
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            break;
                        case "h1":
                        case "h2":
                        case "h3":
                        case "h4":
                        case "h5":
                        case "h6":
                        case "ol":
                        case "ul":
                        case "p":
                            if (0 < $this->tableLevel) {
                                $cellContent .= "\n";
                                $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            } else {
                                if ("" < $cellContent) {
                                    $this->flushCell($sheet, $column, $row, $cellContent);
                                    $row++;
                                }
                                $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                                $this->flushCell($sheet, $column, $row, $cellContent);
                                if (isset($this->formats[$child->nodeName])) {
                                    $sheet->getStyle($column . $row)->applyFromArray($this->formats[$child->nodeName]);
                                }
                                $row++;
                                $column = "A";
                            }
                            break;
                        case "li":
                            if (0 < $this->tableLevel) {
                                $cellContent .= "\n";
                                $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            } else {
                                if ("" < $cellContent) {
                                    $this->flushCell($sheet, $column, $row, $cellContent);
                                }
                                $row++;
                                $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                                $this->flushCell($sheet, $column, $row, $cellContent);
                                $column = "A";
                            }
                            break;
                        case "table":
                            $this->flushCell($sheet, $column, $row, $cellContent);
                            $column = $this->setTableStartColumn($column);
                            if (1 < $this->tableLevel) {
                                $row--;
                            }
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            $column = $this->releaseTableStartColumn();
                            if (1 < $this->tableLevel) {
                                $column++;
                            } else {
                                $row++;
                            }
                            break;
                        case "thead":
                        case "tbody":
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            break;
                        case "tr":
                            $column = $this->getTableStartColumn();
                            $cellContent = "";
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            $row++;
                            break;
                        case "th":
                        case "td":
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            while (isset($this->rowspan[$column . $row])) {
                                $column++;
                            }
                            if (isset($attributeArray["style"]) && !empty($attributeArray["style"])) {
                                $style_array = $this->string2KeyedArray($attributeArray["style"]);
                                foreach ($style_array as $s_key => $s_value) {
                                    if ($s_key == "background-color") {
                                        $sheet->getStyle($column . $row)->getFill()->applyFromArray(array("type" => PHPExcel_Style_Fill::FILL_SOLID, "startcolor" => array("rgb" => str_replace("#", "", $s_value))));
                                    }
                                }
                            }
                            $this->flushCell($sheet, $column, $row, $cellContent);
                            if (isset($attributeArray["rowspan"]) && isset($attributeArray["colspan"])) {
                                $columnTo = $column;
                                for ($i = 0; $i < $attributeArray["colspan"] - 1; $i++) {
                                    $columnTo++;
                                }
                                $range = $column . $row . ":" . $columnTo . ($row + $attributeArray["rowspan"] - 1);
                                foreach (PHPExcel_Cell::extractAllCellReferencesInRange($range) as $value) {
                                    $this->rowspan[$value] = true;
                                }
                                $sheet->mergeCells($range);
                                $column = $columnTo;
                            } else {
                                if (isset($attributeArray["rowspan"])) {
                                    $range = $column . $row . ":" . $column . ($row + $attributeArray["rowspan"] - 1);
                                    foreach (PHPExcel_Cell::extractAllCellReferencesInRange($range) as $value) {
                                        $this->rowspan[$value] = true;
                                    }
                                    $sheet->mergeCells($range);
                                } else {
                                    if (isset($attributeArray["colspan"])) {
                                        $columnTo = $column;
                                        for ($i = 0; $i < $attributeArray["colspan"] - 1; $i++) {
                                            $columnTo++;
                                        }
                                        $sheet->mergeCells($column . $row . ":" . $columnTo . $row);
                                        $column = $columnTo;
                                    }
                                }
                            }
                            $column++;
                            break;
                        case "body":
                            $row = 1;
                            $column = "A";
                            $content = "";
                            $this->tableLevel = 0;
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                            break;
                        default:
                            $this->processDomElement($child, $sheet, $row, $column, $cellContent);
                    }
                }
            }
        }
    }
    public function string2KeyedArray($string, $delimiter = ";", $kv = ":")
    {
        if ($a = explode($delimiter, $string)) {
            foreach ($a as $s) {
                if ($s) {
                    if ($pos = strpos($s, $kv)) {
                        $ka[trim(substr($s, 0, $pos))] = trim(substr($s, $pos + strlen($kv)));
                    } else {
                        $ka[] = trim($s);
                    }
                }
            }
            return $ka;
        }
    }
    /**
     * Loads PHPExcel from file into PHPExcel instance
     *
     * @param  string                    $pFilename
     * @param  PHPExcel                  $objPHPExcel
     * @return PHPExcel
     * @throws PHPExcel_Reader_Exception
     */
    public function loadIntoExisting($pFilename, PHPExcel $objPHPExcel)
    {
        $this->openFile($pFilename);
        if (!$this->isValidFormat()) {
            fclose($this->fileHandle);
            throw new PHPExcel_Reader_Exception($pFilename . " is an Invalid HTML file.");
        }
        fclose($this->fileHandle);
        while ($objPHPExcel->getSheetCount() <= $this->sheetIndex) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($this->sheetIndex);
        $dom = new domDocument();
        $loaded = $dom->loadHTML(mb_convert_encoding($this->securityScanFile($pFilename), "HTML-ENTITIES", "UTF-8"));
        if ($loaded === false) {
            throw new PHPExcel_Reader_Exception("Failed to load " . $pFilename . " as a DOM Document");
        }
        $dom->preserveWhiteSpace = false;
        $row = 0;
        $column = "A";
        $content = "";
        $this->processDomElement($dom, $objPHPExcel->getActiveSheet(), $row, $column, $content);
        return $objPHPExcel;
    }
    /**
     * Get sheet index
     *
     * @return int
     */
    public function getSheetIndex()
    {
        return $this->sheetIndex;
    }
    /**
     * Set sheet index
     *
     * @param  int                  $pValue Sheet index
     * @return PHPExcel_Reader_HTML
     */
    public function setSheetIndex($pValue = 0)
    {
        $this->sheetIndex = $pValue;
        return $this;
    }
    /**
     * Scan theXML for use of <!ENTITY to prevent XXE/XEE attacks
     *
     * @param     string         $xml
     * @throws PHPExcel_Reader_Exception
     */
    public function securityScan($xml)
    {
        $pattern = "/\\0?" . implode("\\0?", str_split("<!ENTITY")) . "\\0?/";
        if (preg_match($pattern, $xml)) {
            throw new PHPExcel_Reader_Exception("Detected use of ENTITY in XML, spreadsheet file load() aborted to prevent XXE/XEE attacks");
        }
        return $xml;
    }
}

?>