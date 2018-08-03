<?php

namespace Lodestone;

// use all the things
use Lodestone\Modules\{
    Logging\Logger, Http\Routes
};

use Lodestone\Entities\Search\{
    SearchCharacter,
    SearchFreeCompany,
    SearchLinkshell
};

use Lodestone\Parser\{
    Character\Parser as CharacterParser,
    Character\Search as CharacterSearch,
    CharacterFriends\Parser as CharacterFriendsParser,
    CharacterFollowing\Parser as CharacterFollowingParser,
    
    Achievements\Parser as AchievementsParser,
    
    FreeCompany\Parser as FreeCompanyParser,
    FreeCompany\Search as FreeCompanySearch,
    FreeCompanyMembers\Parser as FreeCompanyMembersParser,
    
    Linkshell\Parser as LinkshellParser,
    Linkshell\Search as LinkshellSearch,
    
    PvPTeam\Parser as PvPTeamParser,
    PvPTeam\Search as PvPTeamSearch,
    
    Lodestone
};

/**
 * Provides quick functions to various parsing routes
 *
 * Class Api
 * @package Lodestone
 */
class Api
{
    /**
     * @test .
     * @return Lodestone/Lodestone
     */
    private function getLodeStoneInstance()
    {
        return new Lodestone();
    }

    /**
     * Get all entries in the log (Accessible
     * even with log disabled)
     * @return array
     */
    public function getLog()
    {
        return Logger::$log;
    }

    /**
     * @test Premium Virtue,Phoenix
     * @param $name
     * @param bool $server
     * @param bool $page
     * @return SearchCharacter
     */
    public function searchCharacter($name, $server = false, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_CHARACTERS_SEARCH_URL;
        return (new CharacterSearch())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test Equilibrium,Pheonix
     * @param $name
     * @param bool $server
     * @param bool $page
     * @return SearchFreeCompany
     */
    public function searchFreeCompany($name, $server = false, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_FREECOMPANY_SEARCH_URL;
        return (new FreeCompanySearch())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test Monster Hunt
     * @param $name
     * @param $server
     * @param $page
     * @return SearchLinkshell
     */
    public function searchLinkshell($name, $server = false, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_LINKSHELL_SEARCH_URL;
        return (new LinkshellSearch())->url($url . $urlBuilder->get())->parse();
    }
    
    /**
     * @test Ankora
     * @param $name
     * @param $server
     * @param $page
     * @return SearchPvPTeam
     */
    public function searchPvPTeam($name, $server = false, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_PVPTEAM_SEARCH_URL;
        return (new PvPTeamSearch())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test 730968
     * @param $id
     * @return Entities\Character\CharacterProfile
     */
    public function getCharacter($id)
    {
        $url = sprintf(Routes::LODESTONE_CHARACTERS_URL, $id);
        return (new CharacterParser($id))->url($url)->parse();
    }

    /**
     * @test 730968
     * @softfail true
     * @param $id
     * @param $page
     * @return Entities\Character\CharacterFriends
     */
    public function getCharacterFriends($id, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_CHARACTERS_FRIENDS_URL, $id);
        return (new CharacterFriendsParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test 730968
     * @softfail true
     * @param $id
     * @param $page
     * @return Entities\Character\CharacterFollowing
     */
    public function getCharacterFollowing($id, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_CHARACTERS_FOLLOWING_URL, $id);
        return (new CharacterFollowingParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test 730968
     * @param $id
     * @param int $type = 1
     * @param bool $includeUnobtained = false
     * @param int $category = false
     * @return Entities\Character\Achievements
     */
    public function getCharacterAchievements($id, $type = 1, bool $includeUnobtained = false, $category = false, bool $details = false, $detailsAchievementId = false)
    {
        if ($details === true && $detailsAchievementId !== false) {
            return (new AchievementsParser($type, $id))->parse($includeUnobtained, $details, $detailsAchievementId);
        } else {
            $url = $category === false
                ? sprintf(Routes::LODESTONE_ACHIEVEMENTS_URL, $id, $type)
                : sprintf(Routes::LODESTONE_ACHIEVEMENTS_CAT_URL, $id, $type);
            
            return (new AchievementsParser($type, $id))->url($url)->parse($includeUnobtained, $details);
        }
    }

    /**
     * @test 9231253336202687179
     * @param $id
     * @return Entities\FreeCompany\FreeCompany
     */
    public function getFreeCompany($id)
    {
        $url = sprintf(Routes::LODESTONE_FREECOMPANY_URL, $id);
        return (new FreeCompanyParser($id))->url($url)->parse();
    }

    /**
     * @test 9231253336202687179
     * @param $id
     * @param bool $page
     * @return Entities\FreeCompany\FreeCompanyMembers
     */
    public function getFreeCompanyMembers($id, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_FREECOMPANY_MEMBERS_URL, $id);
        return (new FreeCompanyMembersParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @test 19984723346535274
     * @param $id
     * @param bool $page
     * @return Entities\Linkshell\Linkshell
     */
    public function getLinkshellMembers($id, $page = 1)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_LINKSHELL_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new LinkshellParser($id))->url($url)->parse();
    }
    
    /**
     * @test c7a8e4e6fbb5aa2a9488015ed46a3ec3d97d7d0d
     * @param $id
     * @return Entities\PvPTeam\PvPTeam
     */
    public function getPvPTeamMembers($id)
    {
        $urlBuilder = new UrlBuilder();

        $url = sprintf(Routes::LODESTONE_PVPTEAM_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new PvPTeamParser($id))->url($url)->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneBanners()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_BANNERS)->parseBanners();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNews()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_NEWS)->parseTopics();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneTopics()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_TOPICS)->parseTopics();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNotices()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_NOTICES)->parseNotices();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneMaintenance()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_MAINTENANCE)->parseMaintenance();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneUpdates()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_UPDATES)->parseUpdates();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneStatus()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_STATUS)->parseStatus();
    }

    /**
     * @test .
     * @return array
     */
    public function getWorldStatus()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_WORLD_STATUS)->parseWorldStatus();
    }

    /**
     * @test .
     * @return mixed
     */
    public function getDevBlog()
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_DEV_BLOG)->parseDevBlog();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getDevPosts()
    {
        $lodestone = new Lodestone();
        $lodestone->url(Routes::LODESTONE_FORUMS);

        // todo : support multiple languages
        $lang = 'en';
        $devTrackerUrl = $lodestone->parseDevTrackingUrl($lang);
        if (!$devTrackerUrl) {
            return false;
        }

        // get dev tracking search results
        $lodestone->url($devTrackerUrl);

        // get dev posts
        $devLinks = $lodestone->parseDevPostLinks();
        if (!$devLinks) {
            return false;
        }

        // get all dev posts
        $data = [];
        foreach($devLinks as $url) {
            $lodestone->url($url);
            $postId = str_ireplace('post', null, explode('#', $url)[1]);
            $post = $lodestone->parseDevPost($postId);
            $post['id'] = $postId;
            $data[] = $post;
        }

        return $data;
    }

    /**
     * Get params from: http://eu.finalfantasyxiv.com/lodestone/ranking/thefeast/
     *
     * @test .
     * @param bool $season
     * @param array $params
     * @return array
     */
    public function getFeast($season = false, $params = [])
    {
        if ($season !== false && is_numeric($season)) {
            $url = sprintf(Routes::LODESTONE_FEAST_SEASON, $season);
        } else {
            $url = Routes::LODESTONE_FEAST_CURRENT;
        }

        $urlBuilder = new UrlBuilder();
        $urlBuilder->addMulti($params);

        return (new Lodestone())->url($url . $urlBuilder->get())->parseFeast();
    }

    /**
     * Get params from: http://eu.finalfantasyxiv.com/lodestone/ranking/deepdungeon/
     *
     * @test .
     * @param array $params
     * @return array
     */
    public function getDeepDungeon($params = [])
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->addMulti($params);

        return (new Lodestone())->url(Routes::LODESTONE_DEEP_DUNGEON . $urlBuilder->get())->parseDeepDungeon();
    }
}
