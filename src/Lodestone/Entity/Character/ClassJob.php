<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class ClassJob extends AbstractEntity
{
    public $Name;
    public $ClassID;
    public $JobID;
    public $Level;
    public $ExpLevel;
    public $ExpLevelTogo;
    public $ExpLevelMax;
    public $IsSpecialised;
}
