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
 * PHPExcel_Reader_Excel2007_Theme
 *
 * @category   PHPExcel
 * @package    PHPExcel_Reader_Excel2007
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Reader_Excel2007_Theme
{
    /**
     * Theme Name
     *
     * @var string
     */
    private $themeName = NULL;
    /**
     * Colour Scheme Name
     *
     * @var string
     */
    private $colourSchemeName = NULL;
    /**
     * Colour Map indexed by position
     *
     * @var array of string
     */
    private $colourMapValues = NULL;
    /**
     * Colour Map
     *
     * @var array of string
     */
    private $colourMap = NULL;
    /**
     * Create a new PHPExcel_Theme
     *
     */
    public function __construct($themeName, $colourSchemeName, $colourMap)
    {
        $this->themeName = $themeName;
        $this->colourSchemeName = $colourSchemeName;
        $this->colourMap = $colourMap;
    }
    /**
     * Get Theme Name
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
    }
    /**
     * Get colour Scheme Name
     *
     * @return string
     */
    public function getColourSchemeName()
    {
        return $this->colourSchemeName;
    }
    /**
     * Get colour Map Value by Position
     *
     * @return string
     */
    public function getColourByIndex($index = 0)
    {
        if (isset($this->colourMap[$index])) {
            return $this->colourMap[$index];
        }
    }
    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value) && $key != "_parent") {
                $this->{$key} = clone $value;
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

?>