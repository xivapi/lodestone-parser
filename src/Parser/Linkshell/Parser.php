<?php

namespace Lodestone\Parser\Linkshell;

use Lodestone\{
    Entity\Character\CharacterSimple,
    Entity\Linkshell\Linkshell,
    Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var Linkshell() */
    protected $linkshell;

    function __construct($id)
    {
        $this->linkshell = new Linkshell();
    }

    public function parse(): Linkshell
    {
        $this->initialize();

        // no members
        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->linkshell;
        }

        $box = $this->getDocumentFromClassname('.ldst__window .heading__linkshell', 0);
        $this->linkshell->Name = trim($box->find('.heading__linkshell__name')->plaintext);

        // parse
        $this->pageCount();
        $this->parseList();
        
        return $this->linkshell;
    }

    private function pageCount(): void
    {
        if (!$this->getDocument()->find('.btn__pager__current', 0)) {
            return;
        }
        
        // page count
        $data = $this->getDocument()->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);

        $this->linkshell->PageCurrent = filter_var($current, FILTER_SANITIZE_NUMBER_INT);
        $this->linkshell->PageTotal   = filter_var($total, FILTER_SANITIZE_NUMBER_INT);
        $this->linkshell->setNextPrevious();
    
        // member count
        $this->linkshell->Total = filter_var(
            $this->getDocument()->find('.parts__total', 0)->plaintext,
            FILTER_SANITIZE_NUMBER_INT
        );
    }

    private function parseList(): void
    {
        if ($this->linkshell->Total == 0) {
            return;
        }

        foreach ($this->getDocumentFromClassname('.ldst__window')->find('div.entry') as $node) {
            $character = new CharacterSimple();
            $character->ID = trim(explode('/', $node->find('a', 0)->getAttribute('href'))[3]);
            $character->Name = trim($node->find('.entry__name')->plaintext);
            $character->Server = trim($node->find('.entry__world')->plaintext);
            $character->Avatar = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];

            if ($rank = $node->find('.entry__chara_info__linkshell')->plaintext) {
                $character->Rank = $rank;
                $character->RankIcon = $this->getImageSource($node->find('.entry__chara_info__linkshell>img'));
            }

            $this->linkshell->Characters[] = $character;
            $this->linkshell->Server       = $character->Server;
        }
    }
}
