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
 * PHPExcel_Writer_OpenDocument_Cell_Comment
 *
 * @category   PHPExcel
 * @package    PHPExcel_Writer_OpenDocument
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @author     Alexander Pervakov <frost-nzcr4@jagmort.com>
 */
class PHPExcel_Writer_OpenDocument_Cell_Comment
{
    public static function write(PHPExcel_Shared_XMLWriter $objWriter, PHPExcel_Cell $cell)
    {
        $comments = $cell->getWorksheet()->getComments();
        if (!isset($comments[$cell->getCoordinate()])) {
            return NULL;
        }
        $comment = $comments[$cell->getCoordinate()];
        $objWriter->startElement("office:annotation");
        $objWriter->writeAttribute("svg:width", $comment->getWidth());
        $objWriter->writeAttribute("svg:height", $comment->getHeight());
        $objWriter->writeAttribute("svg:x", $comment->getMarginLeft());
        $objWriter->writeAttribute("svg:y", $comment->getMarginTop());
        $objWriter->writeElement("dc:creator", $comment->getAuthor());
        $objWriter->writeElement("text:p", $comment->getText()->getPlainText());
        $objWriter->endElement();
    }
}

?>