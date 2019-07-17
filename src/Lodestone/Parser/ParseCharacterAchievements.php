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

        /** @var DomQuery $li */
        foreach ($this->dom->find('li') as $li) {
            if ($li->hasClass('entry__achievement--complete')) {
                $obj         = new Achievement();
                $obj->ID     = explode('/', $li->attr('href'))[6];
                $obj->Name   = $li->find('.entry__activity__txt')->text();
                $obj->Icon   = $li->find('.entry__achievement__frame img')->attr('src');
                $obj->Points = (int)$li->find('.entry__achievement__number')->text();

                $this->handledObtainedState($obj, $li->find('.entry__activity__time'));

                $achievements->Achievements[] = $obj;
            }
        }

        return $achievements;
    }

    /**
     * Handle parsing the obtained timestamp
     */
    private function handledObtainedState(Achievement $obj, $timestamp): void
    {
        if ($timestamp) {
            $timestamp = $timestamp->plaintext;
            $timestamp = trim(explode('(', $timestamp)[2]);
            $timestamp = trim(explode(',', $timestamp)[0]);
            $timestamp = $timestamp ? (new \DateTime('@' . $timestamp))->format('U') : null;

            if ($timestamp) {
                $obj->ObtainedTimestamp = $timestamp;
            }
        }
    }
}
