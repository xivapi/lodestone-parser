<?php

namespace Lodestone\Parser;

class NoParser implements Parser
{
    /**
     * Do no parsing :D just returns the content
     */
    public function handle(string $content)
    {
        return $content;
    }
}
