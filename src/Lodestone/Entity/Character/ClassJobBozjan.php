<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class ClassJobBozjan extends AbstractEntity
{
    public $Name = 'Bozjan';
    public $Level;
    public $Mettle;
    
    public function __construct(string $name)
    {
        $this->Name = $name;
    }
}
