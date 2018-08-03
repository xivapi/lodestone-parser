<?php

namespace Lodestone\Http;

/**
 * URL's for Lodestone content
 *
 * The API is currently built based on the NA lodestone,
 * changing the lodestone to de/fr may work but this has
 * not been tested and can't be guaranteed.
 */
class Routes
{
    // characters
    const LODESTONE_URL = 'https://na.finalfantasyxiv.com/';
    const LODESTONE_CHARACTERS_URL = self::LODESTONE_URL . 'lodestone/character/%s/';
    const LODESTONE_CHARACTERS_FRIENDS_URL = self::LODESTONE_URL . 'lodestone/character/%s/friend';
    const LODESTONE_CHARACTERS_FOLLOWING_URL = self::LODESTONE_URL . 'lodestone/character/%s/following';
    const LODESTONE_CHARACTERS_SEARCH_URL = self::LODESTONE_URL .'lodestone/character';
    const LODESTONE_ACHIEVEMENTS_URL = self::LODESTONE_URL . 'lodestone/character/%s/achievement/kind/%s/';
    const LODESTONE_ACHIEVEMENTS_CAT_URL = self::LODESTONE_URL . 'lodestone/character/%s/achievement/category/%s/';
    const LODESTONE_ACHIEVEMENTS_DET_URL = self::LODESTONE_URL . 'lodestone/character/%s/achievement/detail/%s/';

    // free company
    const LODESTONE_FREECOMPANY_URL = self::LODESTONE_URL . 'lodestone/freecompany/%s/';
    const LODESTONE_FREECOMPANY_SEARCH_URL = self::LODESTONE_URL . 'lodestone/freecompany';
    const LODESTONE_FREECOMPANY_MEMBERS_URL = self::LODESTONE_URL .'lodestone/freecompany/%s/member/';

    // linkshell
    const LODESTONE_LINKSHELL_SEARCH_URL = self::LODESTONE_URL . 'lodestone/linkshell';
    const LODESTONE_LINKSHELL_MEMBERS_URL = self::LODESTONE_URL .'lodestone/linkshell/%s/';
    
    // linkshell
    const LODESTONE_PVPTEAM_SEARCH_URL = self::LODESTONE_URL . 'lodestone/pvpteam';
    const LODESTONE_PVPTEAM_MEMBERS_URL = self::LODESTONE_URL .'lodestone/pvpteam/%s/';

    // homepage
    const LODESTONE_BANNERS = self::LODESTONE_URL .'lodestone/';
    const LODESTONE_NEWS = self::LODESTONE_URL .'lodestone/news/';
    const LODESTONE_TOPICS = self::LODESTONE_URL .'lodestone/topics/';
    const LODESTONE_NOTICES = self::LODESTONE_URL .'lodestone/news/category/1';
    const LODESTONE_MAINTENANCE = self::LODESTONE_URL .'lodestone/news/category/2';
    const LODESTONE_UPDATES = self::LODESTONE_URL .'lodestone/news/category/3';
    const LODESTONE_STATUS = self::LODESTONE_URL .'lodestone/news/category/4';

    // feast
    const LODESTONE_FEAST_CURRENT = self::LODESTONE_URL .'lodestone/ranking/thefeast/';
    const LODESTONE_FEAST_SEASON = self::LODESTONE_FEAST_CURRENT . 'result/%s/';

    // deep dungeon
    const LODESTONE_DEEP_DUNGEON = self::LODESTONE_URL .'lodestone/ranking/deepdungeon/';

    // world status
    const LODESTONE_WORLD_STATUS = self::LODESTONE_URL .'lodestone/worldstatus/';

    // devblog
    const LODESTONE_DEV_BLOG = self::LODESTONE_URL .'/pr/blog/atom.xml';

    // forums
    const LODESTONE_FORUMS = 'https://forum.square-enix.com/ffxiv/';
    const LODESTONE_FORUMS_HOMEPAGE = self::LODESTONE_FORUMS .'forum.php';
}
