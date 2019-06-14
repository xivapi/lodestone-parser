<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParseLodestoneBanners;

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
        return $this->handle(ParseLodestoneBanners::class, [
            'endpoint' => "/lodestone/news",
        ]);
    }

    public function topics()
    {
        return $this->handle(ParseLodestoneBanners::class, [
            'endpoint' => "/lodestone/topics",
        ]);
    }

    public function notices()
    {
        return $this->handle(ParseLodestoneBanners::class, [
            'endpoint' => "/lodestone/news/category/1",
        ]);
    }

    public function maintenance()
    {
        return $this->handle(ParseLodestoneBanners::class, [
            'endpoint' => "/lodestone/news/category/2",
        ]);
    }

    public function maintenanceTimes()
    {

    }

    public function updates()
    {
        return $this->handle(ParseLodestoneBanners::class, [
            'endpoint' => "/lodestone/news/category/3",
        ]);
    }

    public function status()
    {
        return $this->handle(ParseLodestoneBanners::class, [
            'endpoint' => "/lodestone/news/category/4",
        ]);
    }

    public function worldstatus()
    {

    }
}
