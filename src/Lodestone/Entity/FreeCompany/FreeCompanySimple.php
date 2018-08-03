<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;

class FreeCompanySimple extends AbstractEntity
{
    public $ID;
    public $Name;
    public $Server;
    public $Crest = [];
}
