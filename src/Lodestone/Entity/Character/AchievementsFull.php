<?php

namespace Lodestone\Entity\Character;

use Lodestone\Entity\AbstractEntity;

class AchievementsFull extends AbstractEntity
{
    public $ID;
    /** @var array */
    public $Achievements = [];

    public function addAchievements(Achievements $achievements)
    {
        $this->Achievements[] = $achievements;
    }
}
