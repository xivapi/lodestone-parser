<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParseLinkshellMembers;
use Lodestone\Parser\ParseLinkshellSearch;

class Linkshell extends ApiAbstract
{
    public function search(string $name, string $server = null, int $page = 1)
    {
        $name = str_ireplace(self::STRING_FIXES[0], self::STRING_FIXES[1], $name);

        return $this->handle(ParseLinkshellSearch::class, [
            'endpoint' => "/lodestone/linkshell",
            'query'    => [
                'q'         => '"'. $name .'"',
                'worldname' => $server,
                'page'      => $page
            ]
        ]);
    }

    public function get(string $id, int $page = 1)
    {
        return $this->handle(ParseLinkshellMembers::class, [
            'endpoint' => "/lodestone/linkshell/{$id}",
            'query'    => [
                'page' => $page
            ]
        ]);
    }
}
