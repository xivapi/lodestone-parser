<?php

namespace Lodestone\Game;

class ClassJobs
{
    // pull from: https://xivapi.com/classjob?columns=ID,Name

    const ROLES = [
        0 => 'adventurer',
        1 => 'gladiator',
        2 => 'pugilist',
        3 => 'marauder',
        4 => 'lancer',
        5 => 'archer',
        6 => 'conjurer',
        7 => 'thaumaturge',
        8 => 'carpenter',
        9 => 'blacksmith',
        10 => 'armorer',
        11 => 'goldsmith',
        12 => 'leatherworker',
        13 => 'weaver',
        14 => 'alchemist',
        15 => 'culinarian',
        16 => 'miner',
        17 => 'botanist',
        18 => 'fisher',
        19 => 'paladin',
        20 => 'monk',
        21 => 'warrior',
        22 => 'dragoon',
        23 => 'bard',
        24 => 'white mage',
        25 => 'black mage',
        26 => 'arcanist',
        27 => 'summoner',
        28 => 'scholar',
        29 => 'rogue',
        30 => 'ninja',
        31 => 'machinist',
        32 => 'dark knight',
        33 => 'astrologian',
        34 => 'samurai',
        35 => 'red mage',
        36 => 'blue mage',
        37 => 'gunbreaker',
        38 => 'dancer'
    ];

    /**
     * This provides a link between a class/job, select
     * any id from either a class or job and it will return the class/job ids
     *
     *  ROLE => [ CLASS_ID, JOB_ID ]
     */
    const CLASS_JOB_LINKS = [
        0 =>  [ 0, 0 ],
        1 =>  [ 1, 1 ],
        2 =>  [ 2, 2 ],
        3 =>  [ 3, 3 ],
        4 =>  [ 4, 4 ],
        5 =>  [ 5, 5 ],
        6 =>  [ 6, 6 ],
        7 =>  [ 7, 7 ],
        8 =>  [ 8, 8 ],
        9 =>  [ 9, 9 ],
        10 => [ 10, 10 ],
        11 => [ 11, 11 ],
        12 => [ 12, 12 ],
        13 => [ 13, 13 ],
        14 => [ 14, 14 ],
        15 => [ 15, 15 ],
        16 => [ 16, 16 ],
        17 => [ 17, 17 ],
        18 => [ 18, 18 ],
        19 => [ 1, 19 ],
        20 => [ 2, 20 ],
        21 => [ 3, 21 ],
        22 => [ 4, 22 ],
        23 => [ 5, 23 ],
        24 => [ 6, 24 ],
        25 => [ 7, 25 ],
        26 => [ 26, 26 ],
        27 => [ 26, 27 ],
        28 => [ 26, 28 ],
        29 => [ 29, 29 ],
        30 => [ 29, 30 ],
        31 => [ 31, 31 ],
        32 => [ 32, 32 ],
        33 => [ 33, 33 ],
        34 => [ 34, 34 ],
        35 => [ 35, 35 ],
        36 => [ 36, 36 ],
        37 => [ 37, 37, ],
        38 => [ 38, 38, ],
    ];
    
    public static function findGameData($name)
    {
        [$ClassID, $JobID] = self::findClassJob($name);
        
        $className = self::ROLES[$ClassID];
        $jobName   = self::ROLES[$JobID] ?? null;
        
        return (Object)[
            'Name'    => "{$className} / {$jobName}",
            'ClassID' => self::CLASS_JOB_LINKS[$ClassID][0],
            'JobID'   => self::CLASS_JOB_LINKS[$JobID][1] ?? null
        ];
    }
    
    /**
     * Find class/job in the json data
     *
     * @param $name
     * @return bool|object
     */
    private static function findClassJob($name)
    {
        foreach(self::CLASS_JOB_LINKS as $classjob) {
            [$ClassID, $JobID] = $classjob;
            
            $className = self::ROLES[$ClassID] ?? null;
            $jobName   = self::ROLES[$JobID] ?? null;
            
            if (
                ($className && self::minifyname($name) == self::minifyname($className)) ||
                ($jobName && self::minifyname($name) == self::minifyname($jobName))
            ) {
                return $classjob;
            }
        }
        
        return false;
    }
    
    /**
     * @param $name
     * @return string
     */
    private static function minifyname($name)
    {
        return trim(strtolower(str_ireplace(' ', null, $name)));
    }
}
