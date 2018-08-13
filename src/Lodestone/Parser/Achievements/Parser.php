<?php

namespace Lodestone\Parser\Achievements;

use Lodestone\{
    Entity\Character\Achievements, Entity\Character\Achievement, Exceptions\AchievementsPrivateException, Parser\Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var Achievements */
    protected $achievements;

    function __construct()
    {
        $this->achievements = new Achievements();
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

        if (!$rows) {
            throw new AchievementsPrivateException();
        }
        
        foreach($rows as $node) {
            $obj = new Achievement();

            if (!empty($achnode = $node->find(($includeUnObtained ? '.entry__achievement' : '.entry__achievement--complete'), 0))) {
                $obj->ID     = explode('/', $achnode->getAttribute('href'))[6];
                $obj->Name   = $node->find('.entry__activity__txt', 0)->plaintext;
                $obj->Icon   = explode('?', $node->find('.entry__achievement__frame', 0)->find('img', 0)->getAttribute("src"))[0];
                $obj->Points = intval($node->find('.entry__achievement__number', 0)->plaintext);
                
                $this->handleObtainedState($obj, $node->find('.entry__activity__time', 0));
                $this->achievements->Achievements[] = $obj;
            }
        }
    }

    private function handleObtainedState(Achievement $obj, $timestamp): void
    {
        if ($timestamp) {
            $timestamp = $timestamp->plaintext;
            $timestamp = trim(explode('(', $timestamp)[2]);
            $timestamp = trim(explode(',', $timestamp)[0]);
            $timestamp = $timestamp ? (new \DateTime('@' . $timestamp))->format('U') : null;

            if ($timestamp) {
                $this->achievements->PointsObtained += $obj->Points;
                $this->achievements->PointsTotal += $obj->Points;
                $obj->ObtainedTimestamp = $timestamp;
                return;
            }
        }

        $this->achievements->PointsTotal += $obj->Points;
    }
}
