<?php

namespace Lodestone\Entity\Search;

use Lodestone\{
    Entity\AbstractEntity,
    Entity\Traits\FreeCompanyListTrait,
    Entity\Traits\ListTrait
};

class SearchFreeCompany extends AbstractEntity
{
    use ListTrait;
    use FreeCompanyListTrait;
}
