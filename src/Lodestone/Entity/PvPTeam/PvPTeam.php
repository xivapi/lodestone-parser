<?php

namespace Lodestone\Entity\PvPTeam;

use Lodestone\{
    Entity\Traits\CharacterListTrait,
    Entity\AbstractEntity,
    Entity\Traits\ListTrait
};

class PvPTeam extends AbstractEntity
{
    use ListTrait;
    use CharacterListTrait;

    public $ID;
    public $Crest = [];
    public $Name;
    public $Server;
}
