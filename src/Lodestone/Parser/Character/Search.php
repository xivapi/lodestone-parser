<?php

namespace Lodestone\Parser\Character;

use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Entity\{
    Character\CharacterSimple,
    Search\SearchCharacter
};

class Search extends ParserHelper
{
    /** @var SearchCharacter */
    protected $results;

    function __construct()
    {
        $this->results = new SearchCharacter();
    }

    public function parse(): SearchCharacter
    {
        $this->initialize();
        $this->pageCount();
        $this->parseList();
        return $this->results;
    }

    private function pageCount(): void
    {
        if (!$this->getDocument()->find('.btn__pager__current', 0)) {
            return;
        }
        
        // page count
        $data = $this->getDocument()->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);

        $this->results->PageCurrent = filter_var($current, FILTER_SANITIZE_NUMBER_INT);
        $this->results->PageTotal   = filter_var($total, FILTER_SANITIZE_NUMBER_INT);
        $this->results->setNextPrevious();
        
        // member count
        $count = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $this->results->Total = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
    }

    private function parseList(): void
    {
        if ($this->results->Total == 0) {
            return;
        }

        foreach($this->getDocumentFromClassname('.ldst__window')->find('.entry') as $node) {
            $obj         = new CharacterSimple();
            $obj->ID     = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name   = trim($node->find('.entry__name')->plaintext);
            $obj->Server = trim($node->find('.entry__world')->plaintext);
            $obj->Avatar = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];
            $this->results->Characters[] = $obj;
        }
    }
}
