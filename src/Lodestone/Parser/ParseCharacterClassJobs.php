<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\ClassJob;
use Lodestone\Exceptions\LodestonePrivateException;
use Lodestone\Game\ClassJobs;
use Rct567\DomQuery\DomQuery;

class ParseCharacterClassJobs extends ParseAbstract implements Parser
{
    use HelpersTrait;

    /**
     * @throws LodestonePrivateException
     */
    public function handle(string $html)
    {
        // set dom
        $this->setDom($html, true);

        $classjobs = [];

        // loop through roles
        /** @var DomQuery $li */
        foreach ($this->dom->find('.character__job li') as $li)
        {
            // class name
            $name   = trim($li->find('.character__job__name')->text());
            $master = trim($li->find('.character__job__name--meister')->text());
            $name   = str_ireplace('(Limited Job)', null, $name);
            $name   = $name ?: $master;

            if (empty($name)) {
                continue;
            }

            // get game data ids
            $gd = ClassJobs::findGameData($name);

            // build role
            $role          = new ClassJob();
            $role->Name    = $gd->Name;
            $role->ClassID = $gd->ClassID;
            $role->JobID   = $gd->JobID;

            // level
            $level = trim($li->find('.character__job__level')->text());
            $level = ($level == '--') ? 0 : intval($level);
            $role->Level = $level;

            //specialist
            $role->IsSpecialised = !empty($li->find('.character__job__name--meister')->text());

            // current exp
            [$current, $max] = explode('/', $li->find('.character__job__exp')->text());
            $current = filter_var(trim(str_ireplace('-', null, $current)) ?: 0, FILTER_SANITIZE_NUMBER_INT);
            $max     = filter_var(trim(str_ireplace('-', null, $max)) ?: 0, FILTER_SANITIZE_NUMBER_INT);

            $role->ExpLevel     = $current;
            $role->ExpLevelMax  = $max;
            $role->ExpLevelTogo = $max - $current;

            $classjobs[] = $role;
        }

        unset($box);
        unset($node);

        return $classjobs;
    }

}
