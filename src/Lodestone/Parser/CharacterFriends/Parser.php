<?php

namespace Lodestone\Parser\CharacterFriends;

use Lodestone\{
    Entity\Character\CharacterFriends,
    Entity\Character\CharacterSimple,
    Parser\Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var CharacterFriends */
    protected $friends;

    function __construct($id)
    {
        $this->friends = new CharacterFriends();
        $this->friends->ID = $id;
    }

    public function parse(): CharacterFriends
    {
        $this->initialize();

        if ($this->getDocument()->find('.parts__zero', 0)) {
            return $this->friends;
        }

        $this->pageCount();
        $this->parseFriends();
        
        return $this->friends;
    }

    private function pageCount(): void
    {
        if (!$this->getDocument()->find('.btn__pager__current', 0)) {
            return;
        }
        
        // page count
        $data = $this->getDocument()->find('.btn__pager__current', 0)->plaintext;
        list($current, $total) = explode(' of ', $data);

        $this->friends->PageCurrent = filter_var($current, FILTER_SANITIZE_NUMBER_INT);
        $this->friends->PageTotal   = filter_var($total, FILTER_SANITIZE_NUMBER_INT);
        $this->friends->setNextPrevious();

        // friend count
        $count = $this->getDocument()->find('.parts__total', 0)->plaintext;
        $this->friends->Total = filter_var($count, FILTER_SANITIZE_NUMBER_INT);
    }

    private function parseFriends(): void
    {
        if ($this->friends->Total == 0) {
            return;
        }
        
        $rows = $this->getDocumentFromClassname('.ldst__window');

        foreach($rows->find('div.entry') as $node) {
            $obj = new CharacterSimple();
            $obj->ID      = explode('/', $node->find('a', 0)->getAttribute('href'))[3];
            $obj->Name    = trim($node->find('.entry__name')->plaintext);
            $obj->Server  = trim($node->find('.entry__world')->plaintext);
            $obj->Avatar  = explode('?', $node->find('.entry__chara__face img', 0)->src)[0];
            $this->friends->Characters[] = $obj;
        }
    }
}
