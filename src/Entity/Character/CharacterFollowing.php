<?php

namespace Lodestone\Entity\Character;

use Lodestone\{
    Entity\AbstractEntity,
    Entity\Traits\CharacterListTrait,
    Entity\Traits\ListTrait
};

class CharacterFollowing extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
}
