<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\{
    Entity\AbstractEntity,
    Entity\Traits\CharacterListTrait,
    Entity\Traits\ListTrait
};

class FreeCompanyMembers extends AbstractEntity
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
