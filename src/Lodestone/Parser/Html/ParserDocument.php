<?php

namespace Lodestone\Parser\Html;

use Lodestone\Dom\Document;

trait ParserDocument
{
    protected function getDocumentFromHtml($html)
    {
        $html = new Document($html);
        return $html;
    }

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
