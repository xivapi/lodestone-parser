<?php

namespace Lodestone\Entity\Search;

use Lodestone\{
    Entity\AbstractEntity,
    Entity\Traits\CharacterListTrait,
    Entity\Traits\ListTrait
};

class SearchCharacter extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;
}
