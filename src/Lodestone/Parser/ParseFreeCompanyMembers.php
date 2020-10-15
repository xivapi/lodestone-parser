<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\CharacterSimple;
use Lodestone\Entity\FreeCompany\FreeCompanySimple;
use Rct567\DomQuery\DomQuery;

class ParseFreeCompanyMembers extends ParseAbstract implements Parser
{
    use HelpersTrait;
    use ListTrait;

    public function handle(string $html)
    {
        // set dom
        $this->setDom($html);

        // build list
        $this->setList();

        // parse list
        /** @var DomQuery $node */
        foreach ($this->dom->find('.ldst__window li.entry') as $node) {
            $obj           = new CharacterSimple();
            $obj->ID       = $this->getLodestoneId($node);
            $obj->Name     = $node->find('.entry__name')->text();
            $obj->Server   = trim(explode(' ', $node->find('.entry__world')->text())[0]);
            $obj->Avatar   = $node->find('.entry__chara__face img')->attr('src');
            $obj->Rank     = $node->find('.entry__freecompany__info span')->text();
            $obj->RankIcon = $node->find('.entry__freecompany__info img')->attr('src');

            $this->list->Results[] = $obj;
        }

        return $this->list;
    }
}
