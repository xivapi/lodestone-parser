<?php

namespace Lodestone\Entity;

class AbstractEntity
{
    public function toArray()
    {
        $json  = json_encode($this);
        $array = json_decode($json, true);
        return $array;
    }
}
