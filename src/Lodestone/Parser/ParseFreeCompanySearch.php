<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\CharacterSimple;
use Lodestone\Entity\FreeCompany\FreeCompanySimple;
use Rct567\DomQuery\DomQuery;

class ParseFreeCompanySearch extends ParseAbstract implements Parser
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
        foreach ($this->dom->find('.ldst__window div.entry') as $node) {
            $obj         = new FreeCompanySimple();
            $obj->ID     = $this->getLodestoneId($node);
            $obj->Name   = $node->find('.entry__name')->text();
            $obj->Server = trim(explode(' ', $node->find('.entry__world')->eq(1)->text())[0]);

            /** @var DomQuery $img */
            foreach($node->find('.entry__freecompany__crest__image img') as $img) {
                $obj->Crest[] = explode('?', $img->attr('src'))[0];
            }

            $this->list->Results[] = $obj;
        }

        return $this->list;
    }
}
