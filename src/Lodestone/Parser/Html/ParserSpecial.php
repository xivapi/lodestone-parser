<?php

namespace Lodestone\Parser\Html;

use Lodestone\Dom\Document;

/**
 * Bunch of custom helpers
 *
 * These will require small maintenance whenever
 * lodestone is updated as they trim html
 * based on fixed positions. This is done for
 * very explicit performance gains.
 *
 * Class ParserHelperSpecial
 * @package src\Parser
 */
trait ParserSpecial
{
    /**
     * Special HTML action for Attributes (Part 1)
     *
     * @return Document
     */
    protected function getSpecial__AttributesPart1()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'icon-c--title icon-c__attributes');
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'character__profile__detail');
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml(html_entity_decode($html));

        unset($html);
        return $dom;
    }

    /**
     * Special HTML action for Attributes (Part 3)
     *
     * @return Document
     */
    protected function getSpecial__AttributesPart3()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__param__text__hp');
        $html = substr($html, $start - 100);

        // strip finish
        $finish = strpos($html, 'character__param__text__hp');
        $finish = $finish + 500; // todo - this is a bit of a hack, could possibly use "TP"
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * Special HTML action for Class/Jobs
     *
     * @return Document
     */
    protected function getSpecial__ClassJobs()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__job');
        $start = $start - 50;
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'Fisher');
        $finish = $finish + 200;
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * Special HTML action for Collectables
     *
     * @return bool|Document
     */
    protected function getSpecial__Collectables()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__mounts');
        $html = substr($html, $start - 30);

        if (!$html) {
            return false;
        }

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * Special HTML action for Equipment
     *
     * @return Document
     */
    protected function getSpecial__EquipGear()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__profile__detail');
        $html = substr($html, $start - 30);

        // strip finish
        $finish = strpos($html, 'heading__icon parts__space--reset');
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }

    /**
     * Special HTML action for Achievements
     *
     * @return Document
     */
    protected function getSpecial__Achievements()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'ldst__achievement');
        $html = substr($html, $start + 372);

        // strip finish
        $finish = strpos($html, '/ul');
        $html = substr($html, 0, $finish + 30);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }
    
    /**
     * Special HTML action for Achievement Details
     *
     * @return Document
     */
    protected function getSpecial__AchievementDetails()
    {
        $html = $this->dom->innerHtml();
        
        $dom = $this->getDocumentFromHtml($html)->find('.ldst__achievement');
        
        unset($html);
        return $dom;
    }
    
    /**
     * Special HTML action for Achievement Details
     *
     * @return Document
     */
    protected function getSpecial__AchievementCategories()
    {
        $html = $this->dom->innerHtml();
        
        $dom = $this->getDocumentFromHtml($html)->find('.btn__category');
        
        unset($html);
        return $dom;
    }
    
    /**
     * Special HTML action for Profile Data
     *
     * @return Document
     */
    protected function getSpecial__Profile_Data_Details()
    {
        $html = $this->dom->innerHtml();

        // strip start
        $start = strpos($html, 'character__profile__data__detail');
        $html = substr($html, $start);

        // strip finish
        $finish = strpos($html, 'character__level clearfix');
        $html = substr($html, 0, $finish);

        $dom = $this->getDocumentFromHtml($html);
        unset($html);
        return $dom;
    }
}
