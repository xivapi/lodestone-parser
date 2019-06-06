<?php

namespace Lodestone\Parser;

class ParseCharacter implements Parser
{
    public function handle(string $content)
    {
        $content = json_decode($content);


        return $content;
    }
}
