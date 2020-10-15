<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\Achievement;
use Lodestone\Entity\Character\Achievements;
use Lodestone\Entity\Character\Minion;
use Lodestone\Exceptions\LodestonePrivateException;
use Rct567\DomQuery\DomQuery;

class ParseCharacterMounts extends ParseAbstract implements Parser
{
    use HelpersTrait;

    /**
     * @throws LodestonePrivateException
     */
    public function handle(string $html)
    {
        // set dom
        $this->setDom($html, true);
        $mounts = [];
        foreach ($this->dom->find('.mount__list__item') as $li) {
            $mount       = new Minion();
            $mount->Name = $li->find('.mount__list__text')->text();
            $mount->Icon = $li->find('.mount__list__icon__image')->attr('data-original');
            $mounts[]    = $mount;
        }
        return $mounts;
    }
}
