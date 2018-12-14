<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;

class FreeCompany extends AbstractEntity
{
    public $ID;
    public $Crest = [];
    public $GrandCompany;
    public $Name;
    public $Server;
    public $Tag;
    public $Formed;
    public $ActiveMemberCount;
    public $Rank;
    public $Ranking;
    public $Slogan;
    public $Estate;
    public $Reputation = [];
    public $Active;
    public $Recruitment;
    public $Focus = [];
    public $Seeking = [];
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
