<?php

namespace Lodestone\Entity\ListView;

use Lodestone\Entity\AbstractEntity;

class Pagination extends AbstractEntity
{
    public $Page = 0;
    public $PageNext = null;
    public $PagePrev = null;
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
        $this->PageNext = ($this->Page == $this->PageTotal) ? null : $this->Page + 1;
        $this->PagePrev = ($this->Page > 1) ? $this->Page - 1 : null;
        
        // set results counts
        if ($this->Page == $this->PageTotal) {
            $this->Results = $this->ResultsTotal % $this->ResultsPerPage;
        } else {
            $this->Results = $this->ResultsPerPage;
        }
        
        return $this;
    }
}
