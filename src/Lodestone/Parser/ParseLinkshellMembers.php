<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\CharacterSimple;
use Lodestone\Entity\FreeCompany\FreeCompanySimple;
use Rct567\DomQuery\DomQuery;

class ParseLinkshellMembers extends ParseAbstract implements Parser
{
    use HelpersTrait;
    use ListTrait;

    public function handle(string $html)
    {
        // set dom
        $this->setDom($html);

        // build list
        $this->setList();

        $namedata = trim($this->dom->find('.heading__linkshell__name')->text());
        $namedata = explode("\n", $namedata);

        // set linkshell name
        $this->list->Profile = (Object)[
            'Name'   => trim($namedata[0]),
            'Server' => trim($namedata[1])
        ];

        // parse list
        /** @var DomQuery $node */
        foreach ($this->dom->find('.ldst__window div.entry') as $node) {
            $obj           = new CharacterSimple();
            $obj->ID       = $this->getLodestoneId($node);
            $obj->Name     = $node->find('.entry__name')->text();
            $obj->Server   = trim(explode(' ', $node->find('.entry__world')->text())[0]);
            $obj->Avatar   = $node->find('.entry__chara__face img')->attr('src');

            if ($rank = $node->find('.entry__chara_info__linkshell')->text()) {
                $obj->Rank     = $rank;
                $obj->RankIcon = $node->find('.entry__chara_info__linkshell > img')->attr('src');
            }

            $this->list->Results[] = $obj;
        }

        return $this->list;
    }
}
