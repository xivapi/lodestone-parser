<?php

namespace Lodestone\Html;

use Lodestone\Dom\Document;

/**
 * Class ParserHtml
 * Wrapper for Html Document extraction.
 *
 * @package Lodestone\Parser\Html
 */
trait ParserDocument
{
    /**
     * Get a new document
     *
     * @param $html
     * @return Document
     */
    protected function getDocumentFromHtml($html)
    {
        $html = new Document($html);
        return $html;
    }

    /**
     * Get document from class name
     * NOTE: This can be slow depending on the HTML size.
     *
     * @param $classname
     * @param int $i - offset, if multiple css classes
     * @return bool|Document
     */
    protected function getDocumentFromClassname($classname, $i = 0)
    {
        $html = $this->dom->find($classname, $i);
        if (!$html) {
            return false;
        }

        $html = $html->outertext;
        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }
}
