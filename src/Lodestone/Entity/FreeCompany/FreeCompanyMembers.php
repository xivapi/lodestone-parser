<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;

class FreeCompanyMembers extends AbstractEntity
{
    public $ID;
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
