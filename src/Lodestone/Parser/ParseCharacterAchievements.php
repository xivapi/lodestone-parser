<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\Achievement;
use Lodestone\Entity\Character\Achievements;
use Lodestone\Exceptions\LodestonePrivateException;
use Rct567\DomQuery\DomQuery;

class ParseCharacterAchievements extends ParseAbstract implements Parser
{
    use HelpersTrait;

    /**
     * @throws LodestonePrivateException
     */
    public function handle(string $html)
    {
        // set dom
        $this->setDom($html);
        $achievements = new Achievements();

        /** @var DomQuery $a */
        foreach ($this->dom->find('.ldst__achievement .entry .entry__achievement') as $a) {
            if ($a->hasClass('entry__achievement--complete')) {
                $obj         = new Achievement();
                $obj->ID     = explode('/', $a->attr('href'))[6];
                $obj->Name   = $a->find('.entry__activity__txt')->text();
                $obj->Icon   = $a->find('.entry__achievement__frame img')->attr('src');
                $obj->Points = (int)$a->find('.entry__achievement__number')->text();

                $this->handledObtainedState($obj, $a->find('.entry__activity__time'));

                $achievements->Achievements[] = $obj;
            }
        }

        return $achievements;
    }

    /**
     * Handle parsing the obtained timestamp
     */
    private function handledObtainedState(Achievement $obj, $ts): void
    {
        /** @var DomQuery $ts */
        if ($ts) {
            $ts = trim($ts->html());
            $ts = trim(explode('(', $ts)[2]);
            $ts = trim(explode(',', $ts)[0]);
            $ts = $ts ? (new \DateTime('@' . $ts))->format('U') : null;

            if ($ts) {
                $obj->ObtainedTimestamp = $ts;
            }
        }
    }
}
