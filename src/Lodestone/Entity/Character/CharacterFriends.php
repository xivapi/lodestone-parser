<?php

namespace Lodestone\Entity\Character;

use Lodestone\{
    Entity\AbstractEntity,
    Entity\Traits\CharacterListTrait,
    Entity\Traits\ListTrait
};

class CharacterFriends extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
    
    public $ID;

    public $ParseDate;
    public function __construct()
    {
        $this->ParseDate = time();
    }
}
