<?php

namespace Lodestone\Parser\Html;

trait ParserHtml
{
    /**
     * Provides an array of html, each line is bit
     * of html code.
     *
     * @param $html
     * @return array|mixed
     */
    protected function getArrayFromHtml($html)
    {
        $html = str_ireplace(">", ">\n", $html);
        $html = explode("\n", $html);
        return $html;
    }

    /**
     * Gets a section of html from a start/finish point, this is considerably faster
     * than using $this->getDocumentFromClassname()
     *
     * Returns an array of html
     *
     * @param string $start
     * @param number|string $finish
     * @param null|array $html
     * @return array
     */
    protected function getArrayFromRange($start, $finish, $html = null)
    {
        $started = false;
        $results = [];

        // handle html
        $html = $html ?? $this->htmlOriginal;
        $html = is_array($html) ? $html : explode("\n", $html);

        // loop through html to find stuff
        foreach($html as $i => $line) {
            // if text found, started is true
            if (stripos($line, trim($start)) > -1) {
                $started = true;
            }

            // if started, append html into results
            if ($started) {
                $results[] = trim($line);
            }

            // Break if:
            //  $finish is numeric and results = value
            //  $finish is string and line matches that string
            $break = is_numeric($finish)
                ? (count($results) > $finish)
                : (stripos($line, trim($finish)) > -1);

            if ($started && $break) {
                break;
            }
        }

        return array_values(array_filter($results));
    }
}
