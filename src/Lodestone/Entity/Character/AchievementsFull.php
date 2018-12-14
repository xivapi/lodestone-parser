<?php

namespace Lodestone\Entity\FreeCompany;

use Lodestone\Entity\AbstractEntity;
use Lodestone\Entity\Character\Achievements;

class AchievementsFull extends AbstractEntity
{
    public $ID;
    public $Achievements = [];

    public function addAchievements(Achievements $achievements)
    {
        $this->Achievements[] = $achievements;
    }
}
