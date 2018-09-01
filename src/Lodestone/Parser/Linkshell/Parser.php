<?php

namespace Lodestone\Parser\Linkshell;

use Lodestone\Entity\Character\CharacterSimple;
use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Parser\ListView\ListPagingTrait;

class Parser extends ParserHelper
{
    use ListPagingTrait;
    
    private function parseProfile(): void
    {
        $this->list->Profile = (Object)[
            'Name' => null,
            'Server' => null,
        ];
    
        $box = $this->getDocumentFromClassname('.ldst__window .heading__linkshell', 0);
        $this->list->Profile->Name = trim($box->find('.heading__linkshell__name')->plaintext);
    }
    
    private function parseResults(): void
    {
        // members
        foreach ($this->getDocumentFromClassname('.ldst__window')->find('div.entry') as $node) {
            $obj         = new CharacterSimple();
            $obj->ID     = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name   = trim($node->find('.entry__name')->plaintext);
            $obj->Server = trim($node->find('.entry__world')->plaintext);
            $obj->Avatar = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];

            if ($rank = $node->find('.entry__chara_info__linkshell')->plaintext) {
                $obj->Rank     = $rank;
                $obj->RankIcon = $this->getImageSource($node->find('.entry__chara_info__linkshell>img'));
            }
    
            $this->list->Results[] = $obj;
            $this->list->Profile->Server = $obj->Server;
        }
    }
}
