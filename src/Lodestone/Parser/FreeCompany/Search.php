<?php

namespace Lodestone\Parser\FreeCompany;

use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Entity\{
    FreeCompany\FreeCompanySimple,
    Search\SearchFreeCompany
};

class Search extends ParserHelper
{
    /** @var SearchFreeCompany */
    protected $results;

    function __construct()
    {
        $this->results = new SearchFreeCompany();
    }

    public function parse()
    {
        $this->initialize();
        $this->pageCount();
        $this->parseList();

        return $this->results;
    }

    private function pageCount()
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

    private function parseList()
    {
        if ($this->results->Total == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');
        
        foreach ($rows->find('.entry') as $node) {
            $obj = new FreeCompanySimple();
            $obj->ID = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $obj->Name = trim($node->find('.entry__name')->plaintext);
            $obj->Server = trim($node->find('.entry__world', 1)->plaintext);

            foreach($node->find('.entry__freecompany__crest__image img') as $img) {
                $obj->Crest[] = explode('?', $img->src)[0];
            }

            $this->results->FreeCompanies[] = $obj;
        }
    }
}
