<?php

namespace Lodestone\Parser\PvPTeam;

use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Entity\{
    PvPTeam\PvPTeamSimple,
    Search\SearchPvPTeam
};

class Search extends ParserHelper
{
    /** @var SearchPvPTeam */
    protected $results;

    function __construct()
    {
        $this->results = new SearchPvPTeam();
    }

    public function parse(): SearchPvPTeam
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
        $this->results->Total = filter_var(
            $this->getDocument()->find('.parts__total', 0)->plaintext,
            FILTER_SANITIZE_NUMBER_INT
        );
    }
    
    private function parseList(): void
    {
        if ($this->results->Total == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');
        
        // loop through the list of characters
        foreach($rows->find('.entry') as $node) {
            $obj = new PvPTeamSimple();
            $obj->ID     = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name   = trim($node->find('.entry__name')->plaintext);
            $obj->Server = trim($node->find('.entry__world')->plaintext);
            
            $this->results->PvPTeams = $obj;
        }
    }
}
