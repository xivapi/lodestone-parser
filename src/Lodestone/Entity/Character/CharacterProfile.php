<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class CharacterProfile extends AbstractEntity
{
    public $ID;
    public $Name;
    public $Server;
    public $Lang;
    public $Title;
    public $Avatar;
    public $Portrait;
    public $Bio = '';
    public $Race;
    public $Tribe;
    public $Gender;
    public $Nameday;
    public $GuardianDeity;
    public $Town;
    public $GrandCompany;
    public $FreeCompanyId;
    public $PvPTeamId;
    public $ClassJobs = [];
    public $Gear = [];
    public $Attributes = [];
    public $ActiveClassJob;
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
