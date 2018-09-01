<?php

namespace Lodestone;

use Lodestone\{
    Entity\Character\Achievements,
    Entity\Character\CharacterProfile,
    Entity\FreeCompany\FreeCompany,
    Entity\FreeCompany\FreeCompanyMembers,
    Entity\Linkshell\Linkshell,
    Entity\ListView\ListView,
    Entity\PvPTeam\PvPTeam,
    Http\Routes
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

class Api
{
    public function getLodeStoneInstance(): Lodestone
    {
        return new Lodestone();
    }

    /**
     * @param $name
     * @param bool $server
     * @param bool $page
     */
    public function searchCharacter($name, $server = false, $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_CHARACTERS_SEARCH_URL;
        return (new CharacterSearch())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param $name
     * @param bool $server
     * @param bool $page
     */
    public function searchFreeCompany($name, $server = false, $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_FREECOMPANY_SEARCH_URL;
        return (new FreeCompanySearch())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param $name
     * @param $server
     * @param $page
     */
    public function searchLinkshell($name, $server = false, $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_LINKSHELL_SEARCH_URL;
        return (new LinkshellSearch())->url($url . $urlBuilder->get())->parse();
    }
    
    /**
     * @param $name
     * @param $server
     * @param $page
     */
    public function searchPvPTeam($name, $server = false, $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', str_ireplace(' ', '+', $name));
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_PVPTEAM_SEARCH_URL;
        return (new PvPTeamSearch())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param $id
     */
    public function getCharacter($id): CharacterProfile
    {
        $url = sprintf(Routes::LODESTONE_CHARACTERS_URL, $id);
        return (new CharacterParser($id))->url($url)->parse();
    }

    /**
     * @param $id
     * @param $page
     */
    public function getCharacterFriends($id, $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_CHARACTERS_FRIENDS_URL, $id);
        return (new CharacterFriendsParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param $id
     * @param $page
     */
    public function getCharacterFollowing($id, $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_CHARACTERS_FOLLOWING_URL, $id);
        return (new CharacterFollowingParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param $id
     * @param int $type = 1
     * @param bool $includeUnobtained = false
     * @param int $category = false
     */
    public function getCharacterAchievements($id, $kindId = 1, bool $nonObtained = false): Achievements
    {
        $url = sprintf(Routes::LODESTONE_ACHIEVEMENTS_URL, $id, $kindId);
        return (new AchievementsParser($kindId))->url($url)->parse($nonObtained);
    }

    /**
     * @param $id
     */
    public function getFreeCompany($id): FreeCompany
    {
        $url = sprintf(Routes::LODESTONE_FREECOMPANY_URL, $id);
        return (new FreeCompanyParser($id))->url($url)->parse();
    }

    /**
     * @param $id
     * @param bool $page
     */
    public function getFreeCompanyMembers($id, $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_FREECOMPANY_MEMBERS_URL, $id);
        return (new FreeCompanyMembersParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param $id
     * @param bool $page
     */
    public function getLinkshellMembers($id, $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_LINKSHELL_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new LinkshellParser())->url($url)->parse();
    }
    
    /**
     * @param $id
     */
    public function getPvPTeamMembers($id): ListView
    {
        $urlBuilder = new UrlBuilder();

        $url = sprintf(Routes::LODESTONE_PVPTEAM_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new PvPTeamParser())->url($url)->parse();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneBanners(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_BANNERS)->parseBanners();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneNews(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_NEWS)->parseTopics();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneTopics(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_TOPICS)->parseTopics();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneNotices(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_NOTICES)->parseNotices();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneMaintenance(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_MAINTENANCE)->parseMaintenance();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneUpdates(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_UPDATES)->parseUpdates();
    }

    /**
     * @return array|bool
     */
    public function getLodestoneStatus(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_STATUS)->parseStatus();
    }

    /**
     * @return array
     */
    public function getWorldStatus(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_WORLD_STATUS)->parseWorldStatus();
    }

    /**
     * @return mixed
     */
    public function getDevBlog(): array
    {
        return $this->getLodeStoneInstance()->url(Routes::LODESTONE_DEV_BLOG)->parseDevBlog();
    }

    /**
     * @return array|bool
     */
    public function getDevPosts(): array
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
     * @param bool $season
     * @param array $params
     * @return array
     */
    public function getFeast($season = false, $params = []): array
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
     * @param array $params
     * @return array
     */
    public function getDeepDungeon($params = []): array
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->addMulti($params);

        return (new Lodestone())->url(Routes::LODESTONE_DEEP_DUNGEON . $urlBuilder->get())->parseDeepDungeon();
    }
}
