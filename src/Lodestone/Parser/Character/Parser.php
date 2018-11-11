<?php

namespace Lodestone\Parser\Character;

use Lodestone\{
    Entity\Character\CharacterProfile,
    Parser\Html\ParserHelper
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

    function __construct($id)
    {
        $this->profile = new CharacterProfile();
        $this->profile->ID = $id;
    }

    public function parse(): CharacterProfile
    {
        $this->initialize();
        $this->validatePage();

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
