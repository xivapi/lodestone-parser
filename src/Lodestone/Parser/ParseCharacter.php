<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\CharacterProfile;

class ParseCharacter extends ParseAbstract implements Parser
{
    /** @var CharacterProfile */
    private $profile;
    /** @var string */
    private $html;

    public function handle(string $html)
    {
        $this->profile = new CharacterProfile();
        $this->html    = $html;




        return $this->profile;
    }
}
