<?php

namespace Lodestone\Api;

use Lodestone\Http\Request;
use Lodestone\Http\RequestConfig;
use Lodestone\Parser\ParseCharacter;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Character extends ApiAbstract
{
    public function search(string $name, string $server = null, int $page = 1)
    {

    }

    public function get(int $id)
    {
        $request = new Request([
            'method'   => 'GET',
            'endpoint' => "/character/{$id}", # "lodestone/character/{$id}"
            'query' => [
                'Columns' => 'Character.Name,Character.Server'
            ]
        ]);

        /** @var ResponseInterface $response */
        $response = $this->http->request(ParseCharacter::class, $request);

        if (RequestConfig::$isAsync) {
            return null;
        }

        return $response;
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
