<?php

namespace Lodestone;

/**
 * Class UrlBuilder
 * @package Lodestone
 */
class UrlBuilder
{
    private $params = [];

    /**
     * @param $param
     * @param $value
     */
    public function add($param, $value = false)
    {
        if (!$value) {
            return;
        }

        $this->params[$param] = $value;
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public function addMulti($params)
    {
        if (!$params) {
            return;
        }

        foreach($params as $param => $value) {
            if (in_array($param, ['class_job', 'subtype'])) {
                $param = 'subtype';
                $value = $this->getDeepDungeonClassId($value);
            }

            $this->add($param, $value);
        }
    }

    /**
     * @return string
     */
    public function get()
    {
        $query = [];
        foreach($this->params as $param => $value) {
            $query[] = $param .'='. $value;
        }

        return '?'. implode('&', $query);
    }

    /**
     * Special case because SE use hashed ID's for classes
     * @param $classname
     * @return string
     */
    public function getDeepDungeonClassId($classname)
    {
        switch($classname) {
            case 'gla':
            case 'pld':
                $hash = '125bf9c1198a3a148377efea9c167726d58fa1a5'; break;
            case 'mar':
            case 'war':
                $hash = '741ae8622fa496b4f98b040ff03f623bf46d790f'; break;
            case 'drk':
                $hash = 'c31f30f41ab1562461262daa74b4d374e633a790'; break;
            case 'cnj':
            case 'whm':
                $hash = '56d60f8dbf527ab9a4f96f2906f044b33e7bd349'; break;
            case 'sch':
                $hash = '56f91364620add6b8e53c80f0d5d315a246c3b94'; break;
            case 'ast':
                $hash = 'eb7fb1a2664ede39d2d921e0171a20fa7e57eb2b'; break;
            case 'mnk':
            case 'pug':
                $hash = '46fcce8b2166c8afb1d76f9e1fa3400427c73203'; break;
            case 'drg':
            case 'lnc':
                $hash = 'b16807bd2ef49bd57893c56727a8f61cbaeae008'; break;
            case 'nin':
            case 'rog':
                $hash = 'e8f417ab2afdd9a1e608cb08f4c7a1ae3fe4a441'; break;
            case 'brd':
            case 'arc':
                $hash = 'f50dbaf7512c54b426b991445ff06a6697f45d2a'; break;
            case 'mch':
                $hash = '773aae6e524e9a497fe3b09c7084af165bef434d'; break;
            case 'blm':
            case 'thm':
                $hash = 'f28896f2b4a22b014e3bb85a7f20041452319ff2'; break;
            case 'acn':
            case 'shm':
                $hash = '9ef51b0f36842b9566f40c5e3de2c55a672e4607'; break;
            case 'sam':
                $hash = '7c3485028121b84720df20de7772371d279d097d'; break;
            case 'rdm':
                $hash = '55a98ea6cf180332222184e9fb788a7941a03ec3'; break;
        }

        return $hash;
    }
}
