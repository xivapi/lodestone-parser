<?php

namespace Lodestone\Parser\Html;

use Lodestone\{
    Dom\Document, Exceptions\GenericException, Http\HttpRequest
};

class ParserHelper
{
    use ParserSpecial;
    use ParserDocument;
    use ParserHtml;

    /** @var Document */
    public $dom;

    /** @var string */
    public $html;
    public $htmlOriginal;

    /** @var array (depreciated) */
    public $data = [];

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get the html from a url
     */
    public function url($url)
    {
        $http = new HttpRequest();
        $this->html = $http->get($url);
        $this->htmlOriginal = $this->html;
        return $this;
    }

    /**
     * Add some data to the array
     * @param $name
     * @param $value
     */
    protected function add($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Get data from the array
     * @param $name
     * @return mixed
     */
    protected function get($name)
    {
        return $this->data[$name];
    }

    /**
     * Default setup that most parsers will use.
     */
    protected function initialize()
    {
        // setup html
        $this->ensureHtml();
        $this->html = $this->trim($this->html, 'class="ldst__main"', 'class="ldst__side"');

        // ensure that there is something left after trimming
        $this->ensureHtml();
        $this->htmlOriginal = $this->html;
        $this->setDocument($this->html);
    }

    /**
     * Ensures the HTML exists and is not empty.
     *
     * @throws \Exception
     */
    protected function ensureHtml()
    {
        if (empty(trim($this->html))) {
            throw new GenericException("Html was empty");
        }
    }

    /**
     * Set html document
     *
     * @param $html
     */
    protected function setDocument($html)
    {
        $this->dom = $this->getDocumentFromHtml($html);
    }

    /**
     * Get the current html document
     *
     * @return mixed
     */
    protected function getDocument()
    {
        return $this->dom;
    }

    /**
     * Provides a timestamp based on the html
     * that Lodestone uses for time display.
     *
     * @param $html
     * @return false|null|string
     */
    protected function getTimestamp($html)
    {
        $timestamp = $html->plaintext;
        $timestamp = trim(explode('(', $timestamp)[2]);
        $timestamp = trim(explode(',', $timestamp)[0]);
        return $timestamp ? $timestamp : null;
    }

    // Get
    protected function getImageSource($html)
    {
        // split on img incase html is prior to the img tag
        $html = explode('<img', $html)[1];
        $html = explode('"', $html)[1];
        $html = explode('?', $html)[0];
        return $html;
    }

     /**
     * Trim a bunch of html between two points
     *
     * @param $html
     * @param $startHtml
     * @param $finishHtml
     * @return array|string
     */
    protected function trim($html, $startHtml, $finishHtml)
    {
        // trim the dom
        $html = explode("\n", $html);
        $startIndex = 0;
        $finishIndex = 0;

        // truncate down to just the character
        foreach($html as $i => $line) {
            // start of code
            if (stripos($line, $startHtml) !== false) {
                $startIndex = $i;
                continue;
            }

            if (stripos($line, $finishHtml) !== false) {
                $finishIndex = ($i - $startIndex);
                break;
            }
        }

        $html = array_slice($html, $startIndex, $finishIndex);

        // remove blank lines
        foreach($html as $i => $line) {
            if (!trim($line)) {
                unset($html[$i]);
            }
        }

        $html = implode("\n", $html);

        return $html;
    }
}
