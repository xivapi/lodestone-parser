<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\CharacterSimple;
use Lodestone\Entity\FreeCompany\FreeCompanySimple;
use Rct567\DomQuery\DomQuery;

class ParsePvPTeamMembers extends ParseAbstract implements Parser
{
    use HelpersTrait;
    use ListTrait;

    public function handle(string $html)
    {
        // set dom
        $this->setDom($html);

        // build list
        $this->setList();

        $this->list->Profile = (Object)[
            'Name'   => $this->dom->find('.entry__pvpteam__name--team')->text(),
            'Server' => explode(' ', $this->dom->find('.entry__pvpteam__name--dc')->text())[0],
            'Crest'  => [],
        ];

        /** @var DomQuery $img */
        foreach($this->dom->find('.entry__pvpteam__crest__image img') as $img) {
            $this->list->Profile->Crest[] = str_ireplace('64x64', '128x128', $img->attr('src'));
        }

        // parse list
        /** @var DomQuery $node */
        foreach ($this->dom->find('.ldst__window > .pvpteam__member > .entry') as $node) {
            $obj           = new CharacterSimple();
            $obj->ID       = $this->getLodestoneId($node);
            $obj->Name     = $node->find('.entry__name')->text();
            $obj->Server   = trim(explode(' ', $node->find('.entry__world')->text())[0]);
            $obj->Avatar   = $node->find('.entry__chara__face img')->attr('src');

            if ($rank = $node->find('.entry__freecompany__info span')->text()) {
                if (!is_numeric($rank)) {
                    $obj->Rank     = $rank;
                    $obj->RankIcon = $node->find('.entry__freecompany__info img')->attr('src');
                }
            }

            $temp = $node->find('.entry__freecompany__info > li > span');
            $obj->FeastMatches = $temp->last()->text();

            $this->list->Results[] = $obj;
        }

        return $this->list;
    }
}
