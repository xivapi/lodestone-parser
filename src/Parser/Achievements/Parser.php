<?php

namespace Lodestone\Parser\Achievements;

use Lodestone\{
    Entity\Character\Achievements,
    Entity\Character\Achievement,
    Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var Achievements */
    protected $achievements;

    function __construct(int $category, $id)
    {
        $this->achievements = new Achievements();
        $this->achievements->Category = $category;
    }

    public function parse(bool $includeUnObtained = true): Achievements
    {
        $this->initialize();
        $this->parseAchievements($includeUnObtained);
        return $this->achievements;
    }

    private function parseAchievements(bool $includeUnObtained): void
    {
        $box = $this->getSpecial__Achievements();
        $rows = $box->find('li');
        
        foreach($rows as $node) {
            
            $achievement = new Achievement();

            // Get achievements data
            if (!empty($achnode = $node->find(($includeUnObtained ? '.entry__achievement' : '.entry__achievement--complete'), 0))) {
                $achievement->ID     = explode('/', $achnode->getAttribute('href'))[6];
                $achievement->Name   = $node->find('.entry__activity__txt', 0)->plaintext;
                $achievement->Icon   = explode('?', $node->find('.entry__achievement__frame', 0)->find('img', 0)->getAttribute("src"))[0];
                $achievement->Points = intval($node->find('.entry__achievement__number', 0)->plaintext);
                $this->handleObtainedState($achievement, $node->find('.entry__activity__time', 0));
                $this->achievements->Achievements[] = $achievement;
            }
        }
    }

    private function handleObtainedState(Achievement $achievement, $timestamp): void
    {
        if ($timestamp) {
            $timestamp = $timestamp->plaintext;
            $timestamp = trim(explode('(', $timestamp)[2]);
            $timestamp = trim(explode(',', $timestamp)[0]);
            $timestamp = $timestamp ? new \DateTime('@' . $timestamp) : null;

            if ($timestamp) {
                $this->achievements->PointsObtained += $achievement->Points;
                $this->achievements->PointsTotal += $achievement->Points;
                $achievement->ObtainedTimestamp = $timestamp;
                return;
            }
        }

        $this->achievements->PointsTotal += $achievement->Points;
    }
}
