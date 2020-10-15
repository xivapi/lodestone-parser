<?php

namespace Lodestone\Parser;

use Rct567\DomQuery\DomQuery;

class ParseLodestoneWorldStatus extends ParseAbstract implements Parser
{
    use HelpersTrait;

    public function handle(string $html)
    {
        // set dom
        $this->setDom($html);

        /** @var DomQuery $node */
        $arr = [];
        foreach ($this->dom->find('.item-list') as $node) {
            $status = trim($node->find('.world-list__status_icon i')->attr('data-tooltip'));
            $isOnline = strtolower($status) == 'online';
            
            $category = trim($node->find('.world-list__world_category')->text());
            $isCongested = strtolower($category) == 'congested';
            
            $arr[] = [
                'Name'        => trim($node->find('.world-list__world_name')->text()),
                'Status'      => $status,
                'Category'    => $category,
                'Tooltip'     => trim($node->find('.world-list__create_character i')->attr('data-tooltip')),
                'IsOnline'    => $isOnline,
                'IsCongested' => $isCongested
            ];
        }

        return $arr;
    }
}
