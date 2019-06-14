<?php

namespace Lodestone\Parser;

use Rct567\DomQuery\DomQuery;

/**
 * Trait HelpersTrait
 *
 * @package Lodestone\Parser
 */
trait HelpersTrait
{
    /**
     * @param DomQuery $node
     */
    public function getLodestoneId($node)
    {
        return explode('/', $node->find('a')->attr('href'))[3];
    }

    /**
     * @param DomQuery $node
     */
    public function getTimestamp($node)
    {
        $timestamp = $node->text();
        $timestamp = trim(explode('(', $timestamp)[2]);
        $timestamp = trim(explode(',', $timestamp)[0]);
        return $timestamp ? $timestamp : null;
    }
}
