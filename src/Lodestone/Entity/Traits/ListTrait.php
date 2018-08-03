<?php

namespace Lodestone\Entity\Traits;

trait ListTrait
{
    public $PageCurrent = 0;
    public $PageNext = 0;
    public $PagePrevious = 0;
    public $PageTotal = 0;
    public $Total = 0;

    public function setNextPrevious()
    {
        if (!$this->PageCurrent || !$this->PageTotal) {
            return $this;
        }
        
        // set next page
        $this->PageNext = ($this->PageCurrent == $this->PageTotal) ? $this->PageCurrent : $this->PageCurrent + 1;
        $this->PagePrevious = ($this->PageCurrent > 1) ? $this->PageCurrent - 1 : 1;
        return $this;
    }
}
