<?php

namespace Lodestone\Dom;

use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * Class SelectorConverter
 * @package Lodestone\Dom
 */
class SelectorConverter
{
    protected static $compiled = [];

    public static function toXPath($selector)
    {
        if (isset(self::$compiled[$selector])){
            return self::$compiled[$selector];
        }

        // Select DOMText
        if ($selector === 'text') {
            return '//text()';
        }

        // Select DOMComment
        if ($selector === 'comment') {
            return '//comment()';
        }

        if (!class_exists('Symfony\\Component\\CssSelector\\CssSelectorConverter')) {
            throw new \RuntimeException('Unable to filter with a CSS selector as the Symfony CssSelector 2.8+ is not installed (you can use filterXPath instead).');
        }

        $converter = new CssSelectorConverter(true);

        $xPathQuery = $converter->toXPath($selector);
        self::$compiled[$selector] = $xPathQuery;

        return $xPathQuery;
    }
}
