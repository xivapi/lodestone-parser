<?php

namespace Lodestone\Parser\PvPTeam;

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
            'Crest' => [],
        ];
    
        $box = $this->getDocumentFromClassname('.ldst__window .entry', 0);
    
        foreach($box->find('.entry__pvpteam__crest__image img') as $img) {
            $this->list->Profile->Crest[] = str_ireplace('64x64', '128x128', $img->getAttribute('src'));
        }
    
        $this->list->Profile->Name   = trim($box->find('.entry__pvpteam__name--team')->plaintext);
        $this->list->Profile->Server = trim($box->find('.entry__pvpteam__name--dc')->plaintext);
    }

    private function parseResults(): void
    {
        foreach ($this->getDocumentFromClassname('.pvpteam__member')->find('div.entry') as $node) {
            $obj         = new CharacterSimple();
            $obj->ID     = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name   = trim($node->find('.entry__name')->plaintext);
            $obj->Server = trim($node->find('.entry__world')->plaintext);
            $obj->Avatar = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];

            if ($rank = $node->find('.entry__freecompany__info')->find('span')[0]->plaintext) {
                if (!is_numeric($rank)) {
                    $obj->Rank     = $rank;
                    $obj->RankIcon = $this->getImageSource($node->find('.entry__freecompany__info')->find('img')[0]);
                }
            }

            $tmp = $node->find('.entry__freecompany__info li');
            $obj->FeastMatches = end($tmp)->find('span')->plaintext;
            
            $this->list->Results[] = $obj;
        }
    }
}
