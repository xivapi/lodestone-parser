<?php

namespace Lodestone\Parser\FreeCompanyMembers;

use Lodestone\{
    Entity\Character\CharacterSimple,
    Entity\FreeCompany\FreeCompanyMembers,
    Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var FreeCompanyMembers */
    protected $members;

    function __construct()
    {
        $this->members = new FreeCompanyMembers();
    }

    public function parse(): FreeCompanyMembers
    {
        $this->initialize();

        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->members;
        }
        
        $this->pageCount();
        $this->parseList();

        return $this->members;
    }

    private function pageCount(): void
    {
        if (!$this->getDocument()->find('.btn__pager__current', 0)) {
            return;
        }
        
        // page count
        $data = $this->getDocument()->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);

        $this->members->PageCurrent = filter_var($current, FILTER_SANITIZE_NUMBER_INT);
        $this->members->PageTotal   = filter_var($total, FILTER_SANITIZE_NUMBER_INT);
        $this->members->setNextPrevious();
    
        // member count
        $this->members->Total = filter_var(
            $this->getDocument()->find('.parts__total', 0)->plaintext,
            FILTER_SANITIZE_NUMBER_INT
        );
    }

    private function parseList(): void
    {
        if ($this->members->Total == 0) {
            return;
        }

        // loop through the list of characters
        foreach ($this->getDocumentFromClassname('.ldst__window')->find('li.entry') as $node) {
            // create simple character
            $character = new CharacterSimple();
            $character->ID       = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $character->Name     = trim($node->find('.entry__name')->plaintext);
            $character->Server   = trim($node->find('.entry__world')->plaintext);
            $character->Avatar   = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];
            $character->Rank     = trim($node->find('.entry__freecompany__info span', 0)->plaintext);
            $character->RankIcon = trim($node->find('.entry__freecompany__info img', 0)->src);

            $this->members->Characters[] = $character;
        }
    }
}
