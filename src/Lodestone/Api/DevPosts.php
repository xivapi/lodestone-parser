<?php

namespace Lodestone\Api;

use Lodestone\Parser\NoParser;

class DevPosts extends ApiAbstract
{
    public function blog()
    {
        return $this->handle(NoParser::class, [
            'endpoint' => "/pr/blog/atom.xml",
        ]);
    }

    /**
     * todo - this needs a clean up and requires multiple calls
     */
    public function forums($language = 'en')
    {
        // parse tracking url
        $devTrackerUrl = $this->handle(NoParser::class, [
            'base_uri'  => 'https://forum.square-enix.com/',
            'endpoint'  => '/ffxiv',
            'user_data' => [
                'language' => $language
            ]
        ]);

        // parse dev links
        $devLinks = $this->handle(NoParser::class, [
            'base_uri'  => $devTrackerUrl,
        ]);

        $content = [];
        foreach ($devLinks as $postUrl) {
            $postId = str_ireplace('post', null, explode('#', $postUrl)[1]);

            $content[] = $this->handle(NoParser::class, [
                'base_uri'  => $postUrl,
                'user_data' => [
                    'post_id' => $postId
                ]
            ]);
        }

        return $content;
    }
}
