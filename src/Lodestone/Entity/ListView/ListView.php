<?php

namespace Lodestone\Entity\ListView;

use Lodestone\Entity\AbstractEntity;

class ListView extends AbstractEntity
{
    /** @var Pagination */
    public $Pagination;
    /** @var array */
    public $Results = [];
    
    public function __construct()
    {
        $this->Pagination = new Pagination();
    }
}
