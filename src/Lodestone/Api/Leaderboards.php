<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParseCharacter;

class Leaderboards extends ApiAbstract
{
    /**
     * Params: http://eu.finalfantasyxiv.com/lodestone/ranking/thefeast
     */
    public function feast($season = false, array $params = [])
    {
        $url = "/lodestone/ranking/thefeast";

        // append on season if it's provides
        if ($season !== false && is_numeric($season)) {
            $url .= "/result/{$season}";
        }

        return $this->handle(ParseCharacter::class, [
            'endpoint' => $url,
            'query'    => $params
        ]);
    }

    /**
     * Params: http://eu.finalfantasyxiv.com/lodestone/ranking/deepdungeon
     */
    public function ddPalaceOfTheDead(array $params = [])
    {
        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/ranking/deepdungeon",
            'query'    => $params,
        ]);
    }

    /**
     * Params: http://eu.finalfantasyxiv.com/lodestone/ranking/deepdungeon2
     */
    public function ddHeavenOnHigh(array $params = [])
    {
        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/ranking/deepdungeon2",
            'query'    => $params,
        ]);
    }
}
