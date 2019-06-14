<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParsePvPTeamMembers;
use Lodestone\Parser\ParsePvPTeamSearch;

class PvPTeam extends ApiAbstract
{
    public function search(string $name, string $server = null, int $page = 1)
    {
        $name = str_ireplace(self::STRING_FIXES[0], self::STRING_FIXES[1], $name);

        return $this->handle(ParsePvPTeamSearch::class, [
            'endpoint' => "/lodestone/pvpteam",
            'query'    => [
                'q'         => '"'. $name .'"',
                'worldname' => $server,
                'page'      => $page
            ]
        ]);
    }

    public function get(string $id)
    {
        return $this->handle(ParsePvPTeamMembers::class, [
            'endpoint' => "/lodestone/pvpteam/{$id}/member",
        ]);
    }
}
