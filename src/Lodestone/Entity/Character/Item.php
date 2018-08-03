<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class Item extends AbstractEntity
{
    public $ID;
    public $Name;
    public $Slot;
    public $Category;
    public $Mirage;
    public $Creator;
    public $Dye;
    public $Materia = [];
}
