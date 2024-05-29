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
 * PHPExcel_Worksheet_PageMargins
 *
 * @category   PHPExcel
 * @package    PHPExcel_Worksheet
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Worksheet_PageMargins
{
    /**
     * Left
     *
     * @var double
     */
    private $left = 0.7;
    /**
     * Right
     *
     * @var double
     */
    private $right = 0.7;
    /**
     * Top
     *
     * @var double
     */
    private $top = 0.75;
    /**
     * Bottom
     *
     * @var double
     */
    private $bottom = 0.75;
    /**
     * Header
     *
     * @var double
     */
    private $header = 0.3;
    /**
     * Footer
     *
     * @var double
     */
    private $footer = 0.3;
    /**
     * Create a new PHPExcel_Worksheet_PageMargins
     */
    public function __construct()
    {
    }
    /**
     * Get Left
     *
     * @return double
     */
    public function getLeft()
    {
        return $this->left;
    }
    /**
     * Set Left
     *
     * @param double $pValue
     * @return PHPExcel_Worksheet_PageMargins
     */
    public function setLeft($pValue)
    {
        $this->left = $pValue;
        return $this;
    }
    /**
     * Get Right
     *
     * @return double
     */
    public function getRight()
    {
        return $this->right;
    }
    /**
     * Set Right
     *
     * @param double $pValue
     * @return PHPExcel_Worksheet_PageMargins
     */
    public function setRight($pValue)
    {
        $this->right = $pValue;
        return $this;
    }
    /**
     * Get Top
     *
     * @return double
     */
    public function getTop()
    {
        return $this->top;
    }
    /**
     * Set Top
     *
     * @param double $pValue
     * @return PHPExcel_Worksheet_PageMargins
     */
    public function setTop($pValue)
    {
        $this->top = $pValue;
        return $this;
    }
    /**
     * Get Bottom
     *
     * @return double
     */
    public function getBottom()
    {
        return $this->bottom;
    }
    /**
     * Set Bottom
     *
     * @param double $pValue
     * @return PHPExcel_Worksheet_PageMargins
     */
    public function setBottom($pValue)
    {
        $this->bottom = $pValue;
        return $this;
    }
    /**
     * Get Header
     *
     * @return double
     */
    public function getHeader()
    {
        return $this->header;
    }
    /**
     * Set Header
     *
     * @param double $pValue
     * @return PHPExcel_Worksheet_PageMargins
     */
    public function setHeader($pValue)
    {
        $this->header = $pValue;
        return $this;
    }
    /**
     * Get Footer
     *
     * @return double
     */
    public function getFooter()
    {
        return $this->footer;
    }
    /**
     * Set Footer
     *
     * @param double $pValue
     * @return PHPExcel_Worksheet_PageMargins
     */
    public function setFooter($pValue)
    {
        $this->footer = $pValue;
        return $this;
    }
    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                $this->{$key} = clone $value;
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

?>