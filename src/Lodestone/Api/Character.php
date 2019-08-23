<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParseCharacter;
use Lodestone\Parser\ParseCharacterAchievements;
use Lodestone\Parser\ParseCharacterFollowing;
use Lodestone\Parser\ParseCharacterFriends;
use Lodestone\Parser\ParseCharacterMinions;
use Lodestone\Parser\ParseCharacterMounts;
use Lodestone\Parser\ParseCharacterSearch;

class Character extends ApiAbstract
{
    public function search(string $name, string $server = null, int $page = 1)
    {
        $name = str_ireplace(self::STRING_FIXES[0], self::STRING_FIXES[1], $name);

        return $this->handle(ParseCharacterSearch::class, [
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
        return $this->handle(ParseCharacterFriends::class, [
            'endpoint' => "/lodestone/character/{$id}/friend",
            'query'    => [
                'page' => $page
            ]
        ]);
    }

    public function minions(int $id)
    {
        return $this->handle(ParseCharacterMinions::class, [
            'endpoint' => "/lodestone/character/{$id}/minion",
            'user-agent' => 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36'
        ]);
    }

    public function mounts(int $id)
    {
        return $this->handle(ParseCharacterMounts::class, [
            'endpoint' => "/lodestone/character/{$id}/mount",
            'user-agent' => 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36'
        ]);
    }

    public function following(int $id, int $page = 1)
    {
        return $this->handle(ParseCharacterFollowing::class, [
            'endpoint' => "/lodestone/character/{$id}/following",
            'query'    => [
                'page' => $page
            ]
        ]);
    }

    public function achievements(int $id, int $kindId = 1)
    {
        return $this->handle(ParseCharacterAchievements::class, [
            'endpoint' => "/lodestone/character/{$id}/achievement/kind/{$kindId}/",
        ]);
    }
}
