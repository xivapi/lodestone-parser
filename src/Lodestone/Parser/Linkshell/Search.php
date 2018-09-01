<?php

namespace Lodestone\Parser\Linkshell;

use Lodestone\Entity\Linkshell\LinkshellSimple;
use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Parser\ListView\ListPagingTrait;

class Search extends ParserHelper
{
    use ListPagingTrait;
    
    private function parseResults(): void
    {
        foreach ($this->getDocumentFromClassname('.ldst__window')->find('.entry') as $node) {
            $obj         = new LinkshellSimple();
            $obj->ID     = explode('/', $node->find('a', 0)->getAttribute('href'))[3];
            $obj->Name   = trim($node->find('.entry__name')->plaintext);
            $obj->Server = trim($node->find('.entry__world')->plaintext);

            $this->list->Results[] = $obj;
        }
    }
}
