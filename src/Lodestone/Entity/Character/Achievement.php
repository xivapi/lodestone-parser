<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class Achievement extends AbstractEntity
{
    public $ID;
    public $Name;
    public $Icon;
    public $Points = 0;
    public $ObtainedTimestamp;
}
