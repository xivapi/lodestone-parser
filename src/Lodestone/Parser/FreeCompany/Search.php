<?php

namespace Lodestone\Parser\FreeCompany;

use Lodestone\Entity\FreeCompany\FreeCompanySimple;
use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Parser\ListView\ListPagingTrait;

class Search extends ParserHelper
{
    use ListPagingTrait;

    private function parseResults()
    {
        foreach ($this->getDocumentFromClassname('.ldst__window')->find('.entry') as $node) {
            $obj = new FreeCompanySimple();
            $obj->ID = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name = trim($node->find('.entry__name')->plaintext);
            $obj->Server = explode(' ', trim($node->find('.entry__world', 1)->plaintext))[0];

            foreach($node->find('.entry__freecompany__crest__image img') as $img) {
                $obj->Crest[] = explode('?', $img->src)[0];
            }

            $this->list->Results[] = $obj;
        }
    }
}
