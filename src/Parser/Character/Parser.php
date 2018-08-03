<?php

namespace Lodestone\Parser\Character;

use Lodestone\{
    Entity\Character\CharacterProfile,
    Html\ParserHelper
};

class Parser extends ParserHelper
{
    use TraitProfile;
    use TraitClassJob;
    use TraitAttributes;
    use TraitMinionsMounts;
    use TraitGear;
    use TraitClassJobActive;

    /** @var CharacterProfile */
    protected $profile;

    function __construct(int $id)
    {
        $this->profile = new CharacterProfile();
    }

    /**
     * @return CharacterProfile
     */
    public function parse()
    {
        $this->initialize();

        // parse stuff (order is important)
        $this->parseProfile();
        $this->parseClassJob();
        $this->parseAttributes();
        $this->parseMinionAndMounts();
        $this->parseEquipGear();
        $this->parseActiveClass();

        return $this->profile;
    }
}
