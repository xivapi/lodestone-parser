<?php

namespace Lodestone\Parser\Database;

use Lodestone\Entity\Database\Item;
use Lodestone\Parser\Html\ParserHelper;
use Lodestone\Parser\ListView\ListPagingTrait;

class ItemParser extends ParserHelper
{
    use ListPagingTrait;
    
    /** @var Item */
    protected $item;
    
    function __construct($id)
    {
        $this->item = new Item();
        $this->item->ID = $id;
    }
    
    public function parse(): Item
    {
        $this->initializeDatabase();
        $this->parseBasicInfo();

        return $this->item;
    }
    
    /**
     * todo - flesh this out so it captures more.
     */
    private function parseBasicInfo()
    {
        $dom = $this->getDocument();
    
        // get item icon
        $this->item->Icon = $this->getImageSource($dom->find('.db-view__item__icon__item_image', 0));
    }
}
