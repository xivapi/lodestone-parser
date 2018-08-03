<?php

namespace Lodestone\Entity\Search;

use Lodestone\{
    Entity\AbstractEntity,
    Entity\Traits\LinkshellListTrait,
    Entity\Traits\ListTrait
};

class SearchLinkshell extends AbstractEntity
{
    use ListTrait;
    use LinkshellListTrait;
}
