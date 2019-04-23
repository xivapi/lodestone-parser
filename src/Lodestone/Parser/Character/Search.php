<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entity\Character\CharacterSimple;
use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Parser\ListView\ListPagingTrait;

class Search extends ParserHelper
{
    use ListPagingTrait;
    
    private function parseResults(): void
    {
        foreach ($this->getDocumentFromClassname('.ldst__window')->find('.entry') as $node) {
            $obj         = new CharacterSimple();
            $obj->ID     = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name   = trim($node->find('.entry__name')->plaintext);
            $obj->Server = explode(' ', trim($node->find('.entry__world')->plaintext))[0];
            $obj->Avatar = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];
            
            $this->list->Results[] = $obj;
        }
    }
}
