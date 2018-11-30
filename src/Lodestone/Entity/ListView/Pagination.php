<?php

namespace Lodestone\Entity\ListView;

use Lodestone\Entity\AbstractEntity;

class Pagination extends AbstractEntity
{
    public $Page = 0;
    public $PageNext = 0;
    public $PagePrevious = 0;
    public $PageTotal = 0;
    
    public $Results = 0;
    public $ResultsPerPage = 50;
    public $ResultsTotal = 0;
    
    public function setNextPrevious()
    {
        if (!$this->Page || !$this->PageTotal) {
            return $this;
        }
        
        // set next page
        $this->PageNext = ($this->Page == $this->PageTotal) ? $this->Page : $this->Page + 1;
        $this->PagePrevious = ($this->Page > 1) ? $this->Page - 1 : 1;
        
        // set results counts
        if ($this->Page == $this->PageTotal) {
            $this->Results = $this->ResultsTotal % $this->ResultsPerPage;
        } else {
            $this->Results = $this->ResultsPerPage;
        }
        
        return $this;
    }
}
