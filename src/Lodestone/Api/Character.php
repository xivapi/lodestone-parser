<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParseCharacter;

class Character extends ApiAbstract
{
    public function search(string $name, string $server = null, int $page = 1)
    {
        $name = str_ireplace(self::STRING_FIXES[0], self::STRING_FIXES[1], $name);

        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/character",
            'query'    => [
                'q'         => '"'. $name .'"',
                'worldname' => $server,
                'page'      => $page
            ]
        ]);
    }

    public function get(int $id)
    {
        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/character/{$id}",
        ]);
    }

    public function friends(int $id, int $page = 1)
    {
        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/character/{$id}/friend",
            'query'    => [
                'page' => $page
            ]
        ]);
    }

    public function following(int $id, int $page = 1)
    {
        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/character/{$id}/following",
            'query'    => [
                'page' => $page
            ]
        ]);
    }

    public function achievements(int $id, int $kindId = 1)
    {
        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/character/{$id}/achievement/kind/{$kindId}/",
        ]);
    }
}
