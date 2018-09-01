<?php

namespace Lodestone\Parser\Achievements;

use Lodestone\{
    Entity\Character\Achievements, Entity\Character\Achievement, Exceptions\AchievementsPrivateException, Parser\Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var Achievements */
    protected $achievements;

    function __construct($kindId)
    {
        $this->achievements = new Achievements();
        $this->achievements->KindID = $kindId;
    }

    public function parse(bool $nonObtained = true): Achievements
    {
        $this->initialize();
        $this->parseAchievements($nonObtained);
        return $this->achievements;
    }

    private function parseAchievements(bool $nonObtained): void
    {
        $box = $this->getSpecial__Achievements();
        $rows = $box->find('li');

        if (!$rows) {
            throw new AchievementsPrivateException();
        }
        
        foreach($rows as $node) {
            $obj = new Achievement();

            if (!empty($node = $node->find(($nonObtained ? '.entry__achievement' : '.entry__achievement--complete'), 0))) {
                $obj->ID     = explode('/', $node->getAttribute('href'))[6];
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
