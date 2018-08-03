<?php

namespace Lodestone\Parser\CharacterFollowing;

use Lodestone\{
    Entity\Character\CharacterFollowing,
    Entity\Character\CharacterSimple,
    Parser\Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var CharacterFollowing */
    protected $following;

    function __construct($id)
    {
        $this->following = new CharacterFollowing();
        $this->following->ID = $id;
    }

    public function parse(): CharacterFollowing
    {
        $this->initialize();

        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->following;
        }
        
        $this->pageCount();
        $this->parseFollowing();
    
        return $this->following;
    }

    private function pageCount(): void
    {
        if (!$this->getDocument()->find('.btn__pager__current', 0)) {
            return;
        }
        
        // page count
        $data = $this->getDocument()->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);

        $this->following->PageCurrent = filter_var($current, FILTER_SANITIZE_NUMBER_INT);
        $this->following->PageTotal   = filter_var($total, FILTER_SANITIZE_NUMBER_INT);
        $this->following->setNextPrevious();

        // member count
        $this->following->Total = filter_var(
            $this->getDocument()->find('.parts__total', 0)->plaintext,
            FILTER_SANITIZE_NUMBER_INT
        );
    }

    private function parseFollowing(): void
    {
        if ($this->following->Total == 0) {
            return;
        }

        foreach ($this->getDocumentFromClassname('.ldst__window')->find('div.entry') as $node) {
            $obj = new CharacterSimple();
            $obj->ID     = explode('/', $node->find('a', 0)->getAttribute('href'))[3];
            $obj->Name   = trim($node->find('.entry__name')->plaintext);
            $obj->Server = trim($node->find('.entry__world')->plaintext);
            $obj->Avatar = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];
            $this->following->Characters[] = $obj;
        }
    }
}
