<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParseLodestoneBanners;
use Lodestone\Parser\ParseLodestoneWorldStatus;

class Lodestone extends ApiAbstract
{
    public function banners()
    {
        return $this->handle(ParseLodestoneBanners::class, [
            'endpoint' => "/lodestone",
        ]);
    }

    public function news()
    {
        throw new \Exception("Not Implemented");
    }

    public function topics()
    {
        throw new \Exception("Not Implemented");
    }

    public function notices()
    {
        throw new \Exception("Not Implemented");
    }

    public function maintenance()
    {
        throw new \Exception("Not Implemented");
    }

    public function maintenanceTimes()
    {
        throw new \Exception("Not Implemented");
    }

    public function updates()
    {
        throw new \Exception("Not Implemented");
    }

    public function status()
    {
        throw new \Exception("Not Implemented");
    }

    public function worldstatus()
    {
        return $this->handle(ParseLodestoneWorldStatus::class, [
            'endpoint' => "/lodestone/worldstatus",
        ]);
    }
}
