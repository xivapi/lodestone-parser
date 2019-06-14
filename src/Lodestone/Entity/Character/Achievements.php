<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class Achievements extends AbstractEntity
{
    public $Achievements   = [];
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
