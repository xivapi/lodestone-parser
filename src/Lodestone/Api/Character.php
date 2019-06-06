<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParseCharacter;

class Character extends ApiAbstract
{
    public function search(string $name, string $server = null, int $page = 1)
    {

    }

    public function get(int $id)
    {
        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/character/{$id}",
        ]);
    }

    public function friends(int $id)
    {

    }

    public function following(int $id)
    {

    }

    public function achievements(int $id, int $kindId = 1)
    {

    }
}
