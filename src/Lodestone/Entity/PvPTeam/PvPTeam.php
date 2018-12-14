<?php

namespace Lodestone\Entity\PvPTeam;

use Lodestone\Entity\AbstractEntity;

class PvPTeam extends AbstractEntity
{
    public $ID;
    public $Crest = [];
    public $Name;
    public $Server;
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
