<?php

namespace Lodestone\Entity\Linkshell;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\ListView\ListView;

class LinkshellFull extends AbstractEntity
{
    public $ID;
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
