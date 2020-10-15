<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\CharacterSimple;
use Rct567\DomQuery\DomQuery;

class ParseCharacterFollowing extends ParseAbstract implements Parser
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
        $this->handleCharacterList();
        return $this->list;
    }
}
