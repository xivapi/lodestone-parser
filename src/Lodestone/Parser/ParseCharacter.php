<?php

namespace Lodestone\Parser;

class ParseCharacter implements Parser
{
    public function handle(string $content)
    {
        $content = substr(trim($content), 0, 250);

        return $content;
    }
}
