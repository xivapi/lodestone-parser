<?php

namespace Lodestone\Entity\Search;

use Lodestone\{
    Entity\AbstractEntity,
    Entity\Traits\PvPTeamListTrait,
    Entity\Traits\ListTrait
};

class SearchPvPTeam extends AbstractEntity
{
    use ListTrait;
    use PvPTeamListTrait;
}
