<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class CharacterProfile extends AbstractEntity
{
    public $ID;
    public $Name;
    public $Server;
    public $DC;
    public $Title;
    public $TitleTop;
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
    public $GearSet = []; // gear + attributes
    public $ActiveClassJob;
    public $ParseDate;
    public $Lang;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
