<?php

namespace Lodestone\Parser;

use Rct567\DomQuery\DomQuery;

class ParseAbstract
{
    /** @var DomQuery */
    public $dom;
    
    public function setDom(string $html)
    {
        $dom = new DomQuery($html);
        $this->dom = $dom->find('.ldst__contents');
    }
}
