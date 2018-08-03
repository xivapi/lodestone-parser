<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class ClassJob extends AbstractEntity
{
    public $ClassID;
    public $JobID;
    public $ClassName;
    public $JobName;
    public $Level;
    public $ExpLevel;
    public $ExpLevelTogo;
    public $ExpLevelMax;
}
