<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class CharacterProfile extends AbstractEntity
{
    public $ID;
    public $Name;
    public $Server;
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
    public $PvPTeam;
    public $ClassJobs = [];
    public $Gear = [];
    public $Attributes = [];
    public $ActiveClassJob;
    public $Minions = [];
    public $Mounts = [];

    public $ParseDate;
    public function __construct()
    {
        $this->ParseDate = time();
    }

    public function getHash()
    {
        $data = $this->toArray();

        // remove hash, obvs (its blank anyway)
        unset($data['Hash']);

        // remove images, urls can change
        unset($data['Avatar']);
        unset($data['Portrait']);
        unset($data['GuardianDeity']['Icon']);
        unset($data['Town']['Icon']);
        unset($data['GrandCompany']['Icon']);

        // remove free company id, being kicked
        // should not generate a new hash
        unset($data['FreeCompany']);
        
        // remove pvp team id, being kicked
        // should not generate a new hash
        unset($data['PvPTeam']);

        // remove bio as this is too "open"
        // and could become malformed easily.
        unset($data['Bio']);

        // remove stats, SE can change the formula
        unset($data['Stats']);

        return sha1(serialize($data));
    }
}
