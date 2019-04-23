<?php

namespace Lodestone\Parser\FreeCompanyMembers;

use Lodestone\Entity\Character\CharacterSimple;
use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Parser\ListView\ListPagingTrait;

class Parser extends ParserHelper
{
    use ListPagingTrait;
    
    private function parseResults(): void
    {
        foreach ($this->getDocumentFromClassname('.ldst__window')->find('li.entry') as $node) {
            $obj           = new CharacterSimple();
            $obj->ID       = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name     = trim($node->find('.entry__name')->plaintext);
            $obj->Server   = explode(' ', trim($node->find('.entry__world')->plaintext))[0];
            $obj->Avatar   = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];
            $obj->Rank     = trim($node->find('.entry__freecompany__info span', 0)->plaintext);
            $obj->RankIcon = trim($node->find('.entry__freecompany__info img', 0)->src);

            $this->list->Results[] = $obj;
        }
    }
}
