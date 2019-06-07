<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\ListView\ListView;

class CharacterFriendsFull extends AbstractEntity
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
