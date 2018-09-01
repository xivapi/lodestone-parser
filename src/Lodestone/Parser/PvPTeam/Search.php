<?php

namespace Lodestone\Parser\PvPTeam;

use Lodestone\Entity\PvPTeam\PvPTeamSimple;
use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Parser\ListView\ListPagingTrait;

class Search extends ParserHelper
{
    use ListPagingTrait;

    private function parseResults(): void
    {
        if ($this->list->Pagination->Results == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');
        
        // loop through the list of characters
        foreach ($rows->find('.entry') as $node) {
            $obj         = new PvPTeamSimple();
            $obj->ID     = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name   = trim($node->find('.entry__name')->plaintext);
            $obj->Server = trim($node->find('.entry__world')->plaintext);
    
            $this->list->Results[] = $obj;
        }
    }
}
