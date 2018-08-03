<?php

namespace Lodestone\Parser\Character;

use Lodestone\Game\ClassJobsData;

trait TraitClassJobActive
{
    /**
     * Get the characters active class/job
     *
     * THIS HAS TO RUN AFTER GEAR AS IT NEEDS
     * TO LOOK FOR SOUL CRYSTAL EQUIPPED
     */
    protected function parseActiveClass(): void
    {
        // get main hand previously parsed
        $item = $this->profile->Gear['MainHand'];
        $name = explode("'", $item->Category)[0];

        // get class job id from the main-hand category name
        $ids = (new ClassJobsData())->getClassJobIds($name);

        // set id and name
        $role = $this->profile->ClassJobs[$ids->Key];
        $role = $role ? clone $role : false;
        $this->profile->ActiveClassJob = $role;
    }
}
