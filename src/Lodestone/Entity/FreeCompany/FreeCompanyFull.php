<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\ListView\ListView;

class FreeCompanyFull extends AbstractEntity
{
    public $ID;
    /** @var FreeCompany */
    public $Profile;
    /** @var array */
    public $Members = [];

    public function addMembers(ListView $list)
    {
        $this->Members = array_merge(
            $this->Members,
            $list->Results
        );
    }
}
