<?php

namespace Lodestone\Api;

use Lodestone\Parser\ParseCharacter;

class Database extends ApiAbstract
{
    public function item(string $id)
    {
        return $this->handle(ParseCharacter::class, [
            'endpoint' => "/lodestone/playguide/db/item/{$id}",
        ]);
    }
}
