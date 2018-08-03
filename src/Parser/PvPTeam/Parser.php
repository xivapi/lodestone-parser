<?php

namespace Lodestone\Parser\PvPTeam;

use Lodestone\{
    Entity\Character\CharacterSimple,
    Entity\PvPTeam\PvPTeam,
    Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var PvPTeam() */
    protected $PvPTeam;

    function __construct()
    {
        $this->PvPTeam = new PvPTeam();
    }

    public function parse(): PvPTeam
    {
        $this->initialize();

        // no members
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->PvPTeam;
        }
        
        $box = $this->getDocumentFromClassname('.ldst__window .entry', 0);

        foreach($box->find('.entry__pvpteam__crest__image img') as $img) {
            $this->PvPTeam->Crest[] = str_ireplace('64x64', '128x128', $img->getAttribute('src'));
        }
        
        $this->PvPTeam->Name   = trim($box->find('.entry__pvpteam__name--team')->plaintext);
        $this->PvPTeam->Server = trim($box->find('.entry__pvpteam__name--dc')->plaintext);
        $this->parseList();
        
        return $this->PvPTeam;
    }

    private function parseList(): void
    {
        foreach ($this->getDocumentFromClassname('.pvpteam__member')->find('div.entry') as $node) {
            $character = new CharacterSimple();
            $character->ID     = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $character->Name   = trim($node->find('.entry__name')->plaintext);
            $character->Server = trim($node->find('.entry__world')->plaintext);
            $character->Avatar = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];

            if ($rank = $node->find('.entry__freecompany__info')->find('span')[0]->plaintext) {
                if (!is_numeric($rank)) {
                    $character->Rank = $rank;
                    $character->RankIcon = $this->getImageSource($node->find('.entry__freecompany__info')->find('img')[0]);
                }
            }

            $character->Feasts = end($node->find('.entry__freecompany__info')->find('span'))->plaintext;
            $this->PvPTeam->Characters[] = $character;
        }
    }
}
