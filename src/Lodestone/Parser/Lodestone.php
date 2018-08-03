<?php

namespace Lodestone\Parser;

use Lodestone\Http\Routes;
use Lodestone\Parser\Html\ParserHelper;

class Lodestone extends ParserHelper
{
    /**
     * @return array
     */
    public function parseBanners()
    {
        $this->initialize();
        
        $entries = $this->getDocument()->find('#slider_bnr_area li');
        $results = [];
        
        foreach($entries as $entry) {
            $results[] = [
                'Url'    => $entry->find('a',0)->href,
                'Banner' => explode('?', $entry->find('img', 0)->src)[0],
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseTopics()
    {
        $this->initialize();
        
        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list--topics');
        $results = [];
        
        foreach($entries as $entry) {
            $results[] = [
                'Time'   => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'Title'  => $entry->find('.news__list--title')->plaintext,
                'Url'    => $entry->find('.news__list--title a', 0)->href,
                'Banner' => $entry->find('.news__list--img img', 0)->getAttribute('src'),
                'Html'   => $entry->find('.news__list--banner p')->innerHtml(),
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseNotices()
    {
        $this->initialize();
        
        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];
        
        foreach($entries as $entry) {
            $results[] = [
                'Time'  => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'Title' => $entry->find('.news__list--title')->plaintext,
                'Url'   => Routes::LODESTONE_URL . $entry->find('.news__list--link', 0)->href,
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseMaintenance()
    {
        $this->initialize();
        
        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];
        
        foreach($entries as $entry) {
            $tag = $entry->find('.news__list--tag')->plaintext;
            $title = $entry->find('.news__list--title')->plaintext;
            $title = str_ireplace($tag, null, $title);
            
            $results[] = [
                'Time'  => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'Title' => $title,
                'Url'   => Routes::LODESTONE_URL . $entry->find('.news__list--link', 0)->href,
                'Tag'   => $tag,
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseUpdates()
    {
        $this->initialize();
        
        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];
        
        foreach($entries as $entry) {
            $results[] = [
                'Time'  => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'Title' => $entry->find('.news__list--title')->plaintext,
                'Url'   => Routes::LODESTONE_URL . $entry->find('.news__list--link', 0)->href,
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseStatus()
    {
        $this->initialize();
        
        $entries = $this->getDocumentFromClassname('.news__content')->find('li.news__list');
        $results = [];
        
        foreach($entries as $entry) {
            $tag = $entry->find('.news__list--tag')->plaintext;
            $title = $entry->find('.news__list--title')->plaintext;
            $title = str_ireplace($tag, null, $title);
            
            $results[] = [
                'Time'  => $this->getTimestamp($entry->find('.news__list--time', 0)),
                'Title' => $title,
                'Url'   => Routes::LODESTONE_URL . $entry->find('.news__list--link', 0)->href,
                'Tag'   => $tag,
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseWorldStatus()
    {
        $this->initialize();
        
        $entries = $this->getDocumentFromClassname('.parts__space--pb16')->find('div.item-list__worldstatus');
        $results = [];
        
        foreach($entries as $entry) {
            $results[] = [
                'Title'  => trim($entry->find('h3')->plaintext),
                'Status' => trim($entry->find('p')->plaintext),
            ];
        }
        
        return $results;
    }
    
    /**
     * @return array
     */
    public function parseFeast()
    {
        $this->ensureHtml();
        
        $this->setDocument($this->html);
        
        $entries = $this->getDocument()->find('.wolvesden__ranking__table tr');
        $results = [];
        
        foreach($entries as $node) {
            $results[] = [
                'Character' => [
                    'ID'        => explode('/', $node->getAttribute('data-href'))[3],
                    'Name'      => trim($node->find('.wolvesden__ranking__result__name h3', 0)->plaintext),
                    'Server'    =>trim( $node->find('.wolvesden__ranking__result__world', 0)->plaintext),
                    'Avatar'    => explode('?', $node->find('.wolvesden__ranking__result__face img', 0)->src)[0],
                ],
                'Leaderboard' => [
                    'Rank'         => $node->find('.wolvesden__ranking__result__order', 0)->plaintext,
                    'RankPrevious' => trim($node->find('.wolvesden__ranking__td__prev_order', 0)->plaintext),
                    'WinCount'     => trim($node->find('.wolvesden__ranking__result__win_count', 0)->plaintext),
                    'WinRate'      => str_ireplace('%', null, trim($node->find('.wolvesden__ranking__result__winning_rate', 0)->plaintext)),
                    'Matches'      => trim($node->find('.wolvesden__ranking__result__match_count', 0)->plaintext),
                    'Rating'       => trim($node->find('.wolvesden__ranking__result__match_rate', 0)->plaintext),
                    'RankImage'    => @trim($node->find('.wolvesden__ranking__td__rank img', 0)->src)
                ],
            ];
        }
        
        $this->add('Results', $results);
        return $this->data;
    }
    
    /**
     * @return array
     */
    public function parseDeepDungeon()
    {
        $this->ensureHtml();
        $this->setDocument($this->html);
        
        $entries = $this->getDocument()->find('.deepdungeon__ranking__wrapper__inner li');
        $results = [];
        
        foreach($entries as $node) {
            if ($node->find('.deepdungeon__ranking__job', 0)) {
                $classjob = $node->find('.deepdungeon__ranking__job img', 0)->getAttribute('title');
            } else {
                $classjob = $this->getDocument()->find('.deepdungeon__ranking__select_job', 0)->find('a.selected', 0)->find('img', 0)->getAttribute('title');
            }
            
            $results[] = [
                'Character' => [
                    'ID'        => explode('/', $node->getAttribute('data-href'))[3],
                    'Name'      => trim($node->find('.deepdungeon__ranking__result__name h3', 0)->plaintext),
                    'Server'    =>trim( $node->find('.deepdungeon__ranking__result__world', 0)->plaintext),
                    'Avatar'    => explode('?', $node->find('.deepdungeon__ranking__face__inner img', 0)->src)[0],
                ],
                'ClassJob' => [
                    'Name' => $classjob,
                ],
                'Leaderboard' => [
                    'Rank'  => $node->find('.deepdungeon__ranking__result__order', 0)->plaintext,
                    'Score' => trim($node->find('.deepdungeon__ranking__data--score', 0)->plaintext),
                    'Time'  => $this->getTimestamp($node->find('.deepdungeon__ranking__data--time')),
                    'Floor' => filter_var($node->find('.deepdungeon__ranking__data--reaching', 0)->plaintext, FILTER_SANITIZE_NUMBER_INT),
                ],
            ];
        }
        
        $this->add('results', $results);
        return $this->data;
    }
    
    /**
     * @return mixed
     */
    public function parseDevBlog()
    {
        $html = $this->html;
        $xml = simplexml_load_string($html, null, LIBXML_NOCDATA);
        $json = json_decode(json_encode($xml), true)['entry'];
        return $json;
    }
    
    /**
     * @param $lang
     * @return mixed
     */
    public function parseDevTrackingUrl($lang = 'en')
    {
        $this->ensureHtml();
        $this->setDocument($this->html);
        
        $trackerNumber = [
             'ja' => 0,
             'en' => 1,
             'fr' => 2,
             'de' => 3,
        ][$lang];
        
        return $this->getDocument()->find('.devtrack_btn', $trackerNumber)->href;
    }
    
    /**
     * @return array
     */
    public function parseDevPostLinks()
    {
        $this->ensureHtml();
        
        $this->setDocument($this->html);
        $posts = $this->getDocument()->find('.blockbody li');
        
        $links = [];
        foreach($posts as $node) {
            $links[] = Routes::LODESTONE_FORUMS . $node->find('.posttitle a', 0)->href;
        }
        
        return $links;
    }
    
    /**
     * @param $postId
     * @return array|bool
     */
    public function parseDevPost($postId)
    {
        $this->ensureHtml();
        $this->setDocument($this->html);
        
        $post = $this->getDocument();
        
        // get postcount
        $postcount = $post->find('#postpagestats_above', 0)->plaintext;
        $postcount = explode(' of ', $postcount)[1];
        $postcount = filter_var($postcount, FILTER_SANITIZE_NUMBER_INT);
        
        $data = [
            'Title' => $post->find('.threadtitle a', 0)->plaintext,
            'Url' => Routes::LODESTONE_FORUMS . $post->find('.threadtitle a', 0)->href . sprintf('?p=%s#post%s', $postId, $postId),
            'PostCount' => $postcount,
        ];
        
        // get post
        $post = $post->find('#post_'. $postId);
        
        // todo : translate ...
        $timestamp = $post->find('.posthead .date', 0)->plaintext;
        
        // remove invisible characters
        $timestamp = preg_replace('/[[:^print:]]/', ' ', $timestamp);
        $timestamp = str_ireplace('-', '/', $timestamp);
        
        // fix time from Tokyo to Europe
        $date = new \DateTime($timestamp, new \DateTimeZone('Asia/Tokyo'));
        $date->setTimezone(new \DateTimeZone('UTC'));
        $timestamp = $date->format('U');
        
        // get colour
        $color = str_ireplace(['color: ', ';'], null, $post->find('.username span', 0)->getAttribute('style'));
        
        // fix some post stuff
        $message = trim($post->find('.postcontent', 0)->innerHtml());
        
        // get signature
        $signature = false;
        if ($post->find('.signaturecontainer', 0)) {
            $signature = trim($post->find('.signaturecontainer', 0)->plaintext);
        }
        
        // create data
         $data['UserName'] = trim($post->find('.username span', 0)->plaintext);
         $data['UserColour'] = $color;
         $data['UserTitle'] = trim($post->find('.usertitle', 0)->plaintext);
         $data['UserAvatar'] = Routes::LODESTONE_FORUMS . $post->find('.postuseravatar img', 0)->src;
         $data['UserSignature'] = $signature;
        
        // clean up the message
        $replace = [
            "\t" => null,
            "\n" => null,
            '&#13;' => null,
            'â€™' => "'",
            'images/' => Routes::LODESTONE_FORUMS .'images/',
            'members/' => Routes::LODESTONE_FORUMS .'members/',
            'showthread.php' => Routes::LODESTONE_FORUMS .'showthread.php',
        ];
        
        $message = str_ireplace(array_keys($replace), $replace, $message);
        $message = trim($message);
        
        $dom = new \DOMDocument;
        $dom->loadHTML($message);
        $message = $dom->saveXML();
        
        $remove = [
            '<?xml version="1.0" standalone="yes"?>',
            '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
            '<html>', '</html>', '<head>', '</head>',
        ];
        
        $message = str_ireplace($remove, null, $message);
        $message = str_ireplace([
            '<body>', '</body>', '&#xE2;&#x80;&#x99;',
        ], [
            '<article>', '</article>', "'",
        ], $message);
        
        // dirty fix for iframes
        // https://github.com/viion/lodestone-php/issues/22
        $message = str_ireplace(['allowfullscreen=""/>'], ['allowfullscreen=""></iframe>'], $message);
        
        $data['Time'] = $timestamp;
        $data['Content'] = trim($message);
        
        return $data;
    }
}
