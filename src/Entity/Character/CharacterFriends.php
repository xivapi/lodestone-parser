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
}
