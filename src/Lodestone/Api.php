<?php

namespace Lodestone;

use Lodestone\Entity\Character\Achievements;
use Lodestone\Entity\Character\CharacterFollowingFull;
use Lodestone\Entity\Character\CharacterFriendsFull;
use Lodestone\Entity\Character\CharacterProfile;
use Lodestone\Entity\Character\AchievementsFull;
use Lodestone\Entity\Database\Item;
use Lodestone\Entity\Linkshell\LinkshellFull;
use Lodestone\Entity\FreeCompany\FreeCompany;
use Lodestone\Entity\FreeCompany\FreeCompanyFull;
use Lodestone\Entity\ListView\ListView;
use Lodestone\Exceptions\AchievementsPrivateException;
use Lodestone\Game\AchievementsCategory;
use Lodestone\Http\Routes;

use Lodestone\Parser\Character\Parser as CharacterParser;
use Lodestone\Parser\Character\Search as CharacterSearch;
use Lodestone\Parser\CharacterFriends\Parser as CharacterFriendsParser;
use Lodestone\Parser\CharacterFollowing\Parser as CharacterFollowingParser;
use Lodestone\Parser\Achievements\Parser as AchievementsParser;
use Lodestone\Parser\FreeCompany\Parser as FreeCompanyParser;
use Lodestone\Parser\FreeCompany\Search as FreeCompanySearch;
use Lodestone\Parser\FreeCompanyMembers\Parser as FreeCompanyMembersParser;
use Lodestone\Parser\Linkshell\Parser as LinkshellParser;
use Lodestone\Parser\Linkshell\Search as LinkshellSearch;
use Lodestone\Parser\PvPTeam\Parser as PvPTeamParser;
use Lodestone\Parser\PvPTeam\Search as PvPTeamSearch;
use Lodestone\Parser\Lodestone as Lodestone;
use Lodestone\Parser\Database\ItemParser;

class Api
{
    const NAME_REPLACEMENT = [
        [' ', '’'],
        ['+', "'"],
    ];
    
    public function getLodeStoneInstance(): Lodestone
    {
        return new Lodestone();
    }

    /**
     * @param $name
     * @param bool $server
     * @param bool $page
     */
    public function searchCharacter(string $name, string $server = null, int $page = 1): ListView
    {
        $name = str_ireplace(self::NAME_REPLACEMENT[0], self::NAME_REPLACEMENT[1], $name);
        
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', '"'. $name .'"');
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
    public function searchFreeCompany(string $name, string $server = null, int $page = 1): ListView
    {
        $name = str_ireplace(self::NAME_REPLACEMENT[0], self::NAME_REPLACEMENT[1], $name);
        
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', '"'. $name .'"');
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
    public function searchLinkshell(string $name, string $server = null, int $page = 1): ListView
    {
        $name = str_ireplace(self::NAME_REPLACEMENT[0], self::NAME_REPLACEMENT[1], $name);
        
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', '"'. $name .'"');
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
    public function searchPvPTeam(string $name, string $server = null, int $page = 1): ListView
    {
        $name = str_ireplace(self::NAME_REPLACEMENT[0], self::NAME_REPLACEMENT[1], $name);
        
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('q', '"'. $name .'"');
        $urlBuilder->add('worldname', $server);
        $urlBuilder->add('page', $page);

        $url = Routes::LODESTONE_PVPTEAM_SEARCH_URL;
        return (new PvPTeamSearch())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param $id
     */
    public function getCharacter(int $id): CharacterProfile
    {
        $url = sprintf(Routes::LODESTONE_CHARACTERS_URL, $id);
        return (new CharacterParser($id))->url($url)->parse();
    }

    /**
     * @param $id
     * @param $page
     */
    public function getCharacterFriends(int $id, int $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_CHARACTERS_FRIENDS_URL, $id);
        return (new CharacterFriendsParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param int $id
     * @return CharacterFriendsFull
     */
    public function getCharacterFriendsFull(int $id): CharacterFriendsFull
    {
        $obj = new CharacterFriendsFull();
        $obj->ID = $id;

        // grab first page and add those members
        $first = $this->getCharacterFriends($id, 1);
        $obj->addMembers($first);

        // if there is more than 1 page, add all other members
        if ($first->Pagination->PageTotal > 1) {
            foreach (range(2, $first->Pagination->PageTotal) as $page) {
                $members = $this->getCharacterFriends($id, $page);
                $obj->addMembers($members);
            }
        }

        return $obj;
    }

    /**
     * @param $id
     * @param $page
     */
    public function getCharacterFollowing(int $id, int $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_CHARACTERS_FOLLOWING_URL, $id);
        return (new CharacterFollowingParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param int $id
     * @return CharacterFollowingFull
     */
    public function getCharacterFollowingFull(int $id): CharacterFollowingFull
    {
        $obj = new CharacterFollowingFull();
        $obj->ID = $id;

        // grab first page and add those members
        $first = $this->getCharacterFollowing($id, 1);
        $obj->addMembers($first);

        // if there is more than 1 page, add all other members
        if ($first->Pagination->PageTotal > 1) {
            foreach (range(2, $first->Pagination->PageTotal) as $page) {
                $members = $this->getCharacterFollowing($id, $page);
                $obj->addMembers($members);
            }
        }

        return $obj;
    }

    /**
     * @param $id
     * @param int $type = 1
     * @param bool $includeUnobtained = false
     * @param int $category = false
     * @throws AchievementsPrivateException
     */
    public function getCharacterAchievements(int $id, int $kindId = 1, bool $nonObtained = false): Achievements
    {
        $url = sprintf(Routes::LODESTONE_ACHIEVEMENTS_URL, $id, $kindId);
        return (new AchievementsParser($kindId))->url($url)->parse($nonObtained);
    }

    /**
     * @param $id
     * @return array
     * @throws AchievementsPrivateException
     */
    public function getCharacterAchievementsFull(int $id): AchievementsFull
    {
        $obj = new AchievementsFull();
        foreach(AchievementsCategory::LIST as $kindId => $kindName) {
            try {
                $obj->addAchievements(
                    $this->getCharacterAchievements($id, $kindId)
                );
            } catch (\Exception $ex) {
                // if the first kind threw an exception, achievements are private
                // otherwise it is likely category 13
                if ($kindId === 1) {
                    throw $ex;
                }
            }
        }

        return $obj;
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
    public function getFreeCompanyMembers($id, int $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_FREECOMPANY_MEMBERS_URL, $id);
        return (new FreeCompanyMembersParser())->url($url . $urlBuilder->get())->parse();
    }

    /**
     * @param $id
     * @return array
     */
    public function getFreeCompanyFull($id): FreeCompanyFull
    {
        $obj = new FreeCompanyFull();
        $obj->ID = $id;

        // grab FC Profile
        $obj->Profile = $this->getFreeCompany($id);

        // grab first page and add those members
        $first = $this->getFreeCompanyMembers($id, 1);
        $obj->addMembers($first);

        // if there is more than 1 page, add all other members
        if ($first->Pagination->PageTotal > 1) {
            foreach (range(2, $first->Pagination->PageTotal) as $page) {
                $members = $this->getFreeCompanyMembers($id, $page);
                $obj->addMembers($members);
            }
        }

        return $obj;
    }

    /**
     * @param $id
     * @param bool $page
     */
    public function getLinkshellMembers($id, int $page = 1): ListView
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->add('page', $page);

        $url = sprintf(Routes::LODESTONE_LINKSHELL_MEMBERS_URL, $id) . $urlBuilder->get();
        return (new LinkshellParser())->url($url)->parse();
    }

    /**
     * @param $id
     * @return LinkshellFull
     */
    public function getLinkshellMembersFull($id): LinkshellFull
    {
        $obj = new LinkshellFull();
        $obj->ID = $id;

        // grab first page and add those members
        $first = $this->getLinkshellMembers($id, 1);
        $obj->addMembers($first);

        // if there is more than 1 page, add all other members
        if ($first->Pagination->PageTotal > 1) {
            foreach (range(2, $first->Pagination->PageTotal) as $page) {
                $members = $this->getLinkshellMembers($id, $page);
                $obj->addMembers($members);
            }
        }

        return $obj;
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
     * @return array
     */
    public function getLodestoneMaintenanceTimes(): array
    {
        $response = [];
        
        // go back a maximum of 14 days
        $dateDeadline = time() - (60*60*24*14);
        
        /** @var array $entry */
        foreach ($this->getLodestoneMaintenance() as $entry) {
            // finish if out dated or the tag is not a "Maintenance" tag.
            if ($entry['Time'] < $dateDeadline || $entry['Tag'] != '[Maintenance]') {
                break;
            }

            // detect entry type
            $type = 'unknown';
            if (stripos($entry['Title'], 'Companion App Maintenance') !== false) {
                $type = 'companion';
            } elseif (stripos($entry['Title'], 'The Lodestone Maintenance') !== false) {
                $type = 'lodestone';
            } elseif (stripos($entry['Title'], 'All Worlds Maintenance') !== false) {
                $type = 'all_worlds';
            } elseif (stripos($entry['Title'], 'Mog Station Maintenance') !== false) {
                $type = 'mog_station';
            } elseif (stripos($entry['Title'], 'Support System Maintenance') !== false) {
                $type = 'support_system';
            } elseif (stripos($entry['Title'], 'EU Data Center Emergency Maintenance') !== false) {
                $type = 'datacenter_eu';
            } elseif (stripos($entry['Title'], 'All JP Worlds Emergency Maintenance') !== false) {
                $type = 'datacenter_jp';
            } elseif (stripos($entry['Title'], 'Square Enix Account Management System Maintenance') !== false) {
                $type = 'account_management';
            } elseif (stripos($entry['Title'], '"PSN" Maintenance') !== false) {
                $type = 'psn';
            } elseif (stripos($entry['Title'], 'FINAL FANTASY XIV Forums Maintenance') !== false) {
                $type = 'forums';
            }
            
            $response[] = [
                'type'          => $type,
                'time'          => $this->getLodeStoneInstance()->url($entry['Url'])->parseMaintenanceTime(),
                'emergency'     => stripos($entry['Title'], 'Emergency') !== false,
                'entry'         => $entry,
            ];
        }

        return $response;
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
    public function getFeast($season = false, array $params = []): array
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
    public function getDeepDungeon(array $params = []): array
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->addMulti($params);

        return (new Lodestone())->url(Routes::LODESTONE_DEEP_DUNGEON . $urlBuilder->get())->parseDeepDungeon();
    }
    
    /**
     * @param string $id
     * @return Item
     */
    public function getDatabaseItem(string $id): Item
    {
        $url = sprintf(Routes::LODESTONE_DATABASE_ITEM, $id);
        return (new ItemParser($id))->url($url)->parse();
    }
}
