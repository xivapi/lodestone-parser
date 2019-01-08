<?php

namespace Lodestone\Game;

/**
 * Class ClassJobsData.
 *
 * @package Lodestone\Modules\Game
 */
class ClassJobsData
{
    /**
     * A custom class/job list in the format:
     *  CLASS ID => CLASS ID , JOB ID , NAME , IS_JOB (only for those with sub classes)
     *
     * Initially obtained from: http://api.xivdb.com/data/classjobs?pretty=1
     *
     * The order of class/job id is important. New jobs will have duplicate
     * class/job id to maintain consistency
     */
    const CLASS_JOBS = [
        0 =>  [ 0, 0, 'Adventurer' ],
        1 =>  [ 1, 19, 'Gladiator' ],
        2 =>  [ 2, 20, 'Pugilist' ],
        3 =>  [ 3, 21, 'Marauder' ],
        4 =>  [ 4, 22, 'Lancer' ],
        5 =>  [ 5, 23, 'Archer' ],
        6 =>  [ 6, 24, 'Conjurer' ],
        7 =>  [ 7, 25, 'Thaumaturge' ],
        
        8 =>  [ 8, 8, 'Carpenter' ],
        9 =>  [ 9, 9, 'Blacksmith' ],
        10 => [ 10, 10, 'Armorer' ],
        11 => [ 11, 11, 'Goldsmith' ],
        12 => [ 12, 12, 'Leatherworker' ],
        13 => [ 13, 13, 'Weaver' ],
        14 => [ 14, 14, 'Alchemist' ],
        15 => [ 15, 15, 'Culinarian' ],
        
        16 => [ 16, 16, 'Miner' ],
        17 => [ 17, 17, 'Botanist' ],
        18 => [ 18, 18, 'Fisher' ],
        
        19 => [ 1, 19, 'Paladin' ],
        20 => [ 2, 20, 'Monk' ],
        21 => [ 3, 21, 'Warrior' ],
        22 => [ 4, 22, 'Dragoon' ],
        23 => [ 5, 23, 'Bard' ],
        24 => [ 6, 24, 'White Mage' ],
        25 => [ 7, 25, 'Black Mage' ],
        
        26 => [ 26, 27, 'Arcanist' ],
        27 => [ 26, 27, 'Summoner' ],
        28 => [ 26, 28, 'Scholar' ],
        
        29 => [ 29, 30, 'Rogue' ],
        30 => [ 29, 30, 'Ninja' ],
        
        31 => [ 31, 31, 'Machinist' ],
        32 => [ 32, 32, 'Dark Knight' ],
        33 => [ 33, 33, 'Astrologian' ],
        34 => [ 34, 34, 'Samurai' ],
        35 => [ 35, 35, 'Red Mage' ],

        36 => [ 36, 36, 'Blue Mage' ],
    ];
    
    /**
     * Get ids for a job/class
     *
     * @param $name
     * @return object
     */
    public function getClassJobIds($name)
    {
        list($classId, $jobId) = $this->findClassJob($name);
    
        return (Object)[
            'Key'   => sprintf('%s_%s', $classId, $jobId),
            'Class' => self::CLASS_JOBS[$classId],
            'Job'   => self::CLASS_JOBS[$jobId]
        ];
    }
    
    /**
     * Find class/job in the json data
     *
     * @param $name
     * @return bool|object
     */
    public function findClassJob($name)
    {
        foreach(self::CLASS_JOBS as $classjob) {
            list($classId, $jobId, $rolename) = $classjob;
            
            if ($this->minifyname($name) == $this->minifyname($rolename)) {
                return $classjob;
            }
        }
        
        return false;
    }
    
    /**
     * @param $name
     * @return string
     */
    private function minifyname($name)
    {
        return trim(strtolower(str_ireplace(' ', null, $name)));
    }
}
