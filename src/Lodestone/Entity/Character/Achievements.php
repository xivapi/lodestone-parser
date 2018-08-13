<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class Achievements extends AbstractEntity
{
    public $KindID;
    public $PointsObtained = 0;
    public $PointsTotal = 0;
    public $Achievements = [];
}
