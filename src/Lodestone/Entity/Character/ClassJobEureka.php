<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class ClassJobEureka extends AbstractEntity
{
    public $Name = 'Eureka';
    public $Level;
    public $ExpLevel;
    public $ExpLevelTogo;
    public $ExpLevelMax;
    
    public function __construct(string $name)
    {
        $this->Name = $name;
    }
}
