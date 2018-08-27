<?php

namespace Lodestone\Parser\PvPTeam;

use Lodestone\{
    Entity\Character\CharacterSimple,
    Entity\PvPTeam\PvPTeam,
    Parser\Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var PvPTeam */
    protected $pvpTeam;

    function __construct($id)
    {
        $this->pvpTeam = new PvPTeam();
        $this->pvpTeam->ID = $id;
    }

    public function parse(): PvPTeam
    {
        $this->initialize();

        // no members
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->pvpTeam;
        }
        
        $box = $this->getDocumentFromClassname('.ldst__window .entry', 0);

        foreach($box->find('.entry__pvpteam__crest__image img') as $img) {
            $this->pvpTeam->Crest[] = str_ireplace('64x64', '128x128', $img->getAttribute('src'));
        }
        
        $this->pvpTeam->Name   = trim($box->find('.entry__pvpteam__name--team')->plaintext);
        $this->pvpTeam->Server = trim($box->find('.entry__pvpteam__name--dc')->plaintext);
        $this->parseList();
        
        return $this->pvpTeam;
    }

    private function parseList(): void
    {
        foreach ($this->getDocumentFromClassname('.pvpteam__member')->find('div.entry') as $node) {
            $obj = new CharacterSimple();
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
            $this->pvpTeam->Characters[] = $obj;
        }
    }
}
