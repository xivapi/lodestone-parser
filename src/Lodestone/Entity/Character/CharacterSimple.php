<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class CharacterSimple extends AbstractEntity
{
    public $ID;
    public $Name;
    public $Server;
    public $Avatar;
    public $Rank;
    public $RankIcon;
    public $FeastMatches = 0;
}
