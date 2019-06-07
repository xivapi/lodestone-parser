<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class CharacterFollowing extends AbstractEntity
{
    public $ID;
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
