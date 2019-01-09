<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entity\Character\ClassJob,
    Lodestone\Game\ClassJobsData;

trait TraitClassJob
{
    protected function parseClassJob(): void
    {
        $classjobs = new ClassJobsData();
        $box = $this->getSpecial__ClassJobs();

        // class jobs
        foreach ($box->find('.character__job') as $node)
        {
            $node = $this->getDocumentFromHtml($node->outertext);

            // loop through roles
            foreach ($node->find('li') as $li)
            {
                // class name
                $name = trim($li->find('.character__job__name', 0)->plaintext);

                // get class ids
                $ids = $classjobs->getClassJobIds($name);
                
                // build role
                $role = new ClassJob();
                $role->ClassID   = trim($ids->Class[0]);
                $role->ClassName = trim($ids->Class[2]);
                $role->JobID     = trim($ids->Job[1]);
                $role->JobName   = trim($ids->Job[2]);

                // level
                $level = trim($li->find('.character__job__level', 0)->plaintext);
                $level = ($level == '--') ? 0 : intval($level);
                $role->Level = $level;

                // current exp
                list($current, $max) = explode('/', $li->find('.character__job__exp', 0)->plaintext);
                $current = filter_var(trim(str_ireplace('-', null, $current)) ?: 0, FILTER_SANITIZE_NUMBER_INT);
                $max     = filter_var(trim(str_ireplace('-', null, $max)) ?: 0, FILTER_SANITIZE_NUMBER_INT);

                $role->ExpLevel     = $current;
                $role->ExpLevelMax  = $max;
                $role->ExpLevelTogo = $max - $current;

                $this->profile->ClassJobs[$ids->Key] = $role;
            }
        }
        
        unset($box);
        unset($node);
    }
}
