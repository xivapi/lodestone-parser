<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class CharacterFriends extends AbstractEntity
{
    public $ID;
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
