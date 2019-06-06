<?php

namespace Lodestone\Parser;

interface Parser
{
    public function handle(string $content);
}
