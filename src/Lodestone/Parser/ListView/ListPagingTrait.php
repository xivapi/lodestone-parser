<?php

namespace Lodestone\Parser\ListView;

use Lodestone\Entity\ListView\ListView;

trait ListPagingTrait
{
    /** @var ListView */
    protected $list;
    
    function __construct()
    {
        $this->list = new ListView();
    }
    
    public function parse(): ListView
    {
        $this->initialize();
        
        if (method_exists($this, 'parseProfile')) {
            $this->parseProfile();
        }
    
        $this->pageCount();
        $this->parseResults();

        return $this->list;
    }
    
    protected function pageCount()
    {
        if (!$this->getDocument()->find('.btn__pager__current', 0)) {
            return;
        }
        
        // page count
        $data = $this->getDocument()->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);
        
        $this->list->Pagination->Page = filter_var($current, FILTER_SANITIZE_NUMBER_INT);
        $this->list->Pagination->PageTotal = filter_var($total, FILTER_SANITIZE_NUMBER_INT);
        $this->list->Pagination->setNextPrevious();
        
        // member count
        $count = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $this->list->Pagination->ResultsTotal = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
    }
}
