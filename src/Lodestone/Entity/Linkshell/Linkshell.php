<?php

namespace Lodestone\Entity\Linkshell;

use Lodestone\{
    Entity\Traits\CharacterListTrait,
    Entity\AbstractEntity,
    Entity\Traits\ListTrait
};

class Linkshell extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;

    public $ID;
    public $Name;
    public $Server;

    public $ParseDate;
    public function __construct()
    {
        $this->ParseDate = time();
    }
}
