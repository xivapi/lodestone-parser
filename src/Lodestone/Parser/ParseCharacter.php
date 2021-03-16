<?php

namespace Lodestone\Parser;

use Lodestone\Entity\Character\Attribute;
use Lodestone\Entity\Character\CharacterProfile;
use Lodestone\Entity\Character\GrandCompany;
use Lodestone\Entity\Character\Guardian;
use Lodestone\Entity\Character\Item;
use Lodestone\Entity\Character\ItemSimple;
use Lodestone\Entity\Character\Town;
use Rct567\DomQuery\DomQuery;

class ParseCharacter extends ParseAbstract implements Parser
{
    use HelpersTrait;

    const TX_RACECLANGENDER = ['Race/Clan/Gender', 'Volk / Stamm / Geschlecht', 'Race / Ethnie / Sexe', '種族/部族/性別'];
    const TX_NAMEDAY        = ['Nameday', 'Guardian', 'Namenstag', 'Schutzgott', 'Date de naissance', 'Divinité', '誕生日', '守護神'];
    const TX_TOWN           = ['City-state', 'Stadtstaat', 'Cité de départ', '開始都市'];
    const TX_GRANDCOMPANY   = ['Grand Company', 'Staatliche Gesellschaft', 'Grande compagnie', '所属グランドカンパニー'];
    
    /** @var CharacterProfile */
    private $profile;
    
    /**
     * Handle Character parsing
     */
    public function handle(string $html)
    {
        // set dom
        $this->setDom($html);
        
        // set profile object
        $this->profile = new CharacterProfile();
        
        // parse main profile
        $this->parseProfile();
        $this->parseAttributes();
        $this->parseEquipGear();

        return $this->profile;
    }
    
    /**
     * Parse the "Profile" tab
     */
    private function parseProfile()
    {
        $blocks = $this->dom->find('.character__profile__data__detail .character-block');
        
        /** @var DomQuery $block */
        foreach ($blocks as $block) {
            $blocktitle = $block->find('.character-block__title')->text();
    
            if (in_array($blocktitle, self::TX_RACECLANGENDER)) {
                $this->parseProfileRaceTribeGender($block);
            } elseif (in_array($blocktitle, self::TX_NAMEDAY)) {
                $this->parseProfileNameDay($block);
            } elseif (in_array($blocktitle, self::TX_TOWN)) {
                $this->parseProfileTown($block);
            } elseif (in_array($blocktitle, self::TX_GRANDCOMPANY)) {
                $this->parseProfileGrandCompany($block);
            } else {
                if ($block->find('.character__freecompany__name')->text() != "") {
                    $this->parseProfileFreeCompany($block);
                } elseif ($block->find('.character__pvpteam__name')->find('h4')->text() != "") {
                    $this->parseProfilePvPTeam($block);
                }
            }
        }
    
        $this->parseProfileBasic();
        $this->parseProfileBio();
    }
    
    /**
     * Parse the "Attributes" tab
     */
    private function parseAttributes()
    {
        //
        // Base Param
        //
        
        /** @var DomQuery $tr */
        foreach ($this->dom->find('.character__param__list tr') as $tr) {
            $attr        = new Attribute();
            $attr->Name  = $tr->find('th')->text();
            $attr->Value = $tr->find('td')->text();

            $this->profile->GearSet['Attributes'][] = $attr;
        }
        
        //
        // hp, mp, etc
        //
        /** @var DomQuery $li */
        foreach ($this->dom->find('.character__param ul li') as $li) {
            $attr        = new Attribute();
            $attr->Name  = $li->find('p')->text();
            $attr->Value = $li->find('span')->text();

            $this->profile->GearSet['Attributes'][] = $attr;
        }
    }
    
    /**
     * Parse the characters currently equipped gear
     */
    private function parseEquipGear()
    {
        /**
         * @var int $i
         * @var DomQuery $node
         */
        foreach ($this->dom->find('.character__view')->eq(0)->find('.item_detail_box') as $i => $node) {
            $item = new Item();
    
            // get name
            $name = $node->find('.db-tooltip__item__name')->text();
    
            // If this slot has no item name html
            // it's safe to assume empty slot
            if (!$name) {
                continue;
            }
    
            $item->Name = strip_tags($name);
    
            // get lodestone id
            $lodestoneId = $node->find('.db-tooltip__bt_item_detail a')->attr('href');
            $explodedLodestoneId = explode('/', $lodestoneId);
            $item->ID = trim($explodedLodestoneId[count($explodedLodestoneId) - 2]);
    
            // get category
            // this is a bit buggy for crafters, eg: https://eu.finalfantasyxiv.com/lodestone/character/17650647
            // as it's just looking for "Two-handed" and ignoring things like "Carpenters Secondary"
            $category   = $node->find('.db-tooltip__item__category')->text();
            $category   = trim(strip_tags($category));
            $catData    = explode("'", $category);
            $catName    = $catData[0];
            $catSecond  = $catData[1] ?? null;
            $catName    = trim(str_ireplace(['Two-handed', 'One-handed'], null, $catName));
            $catName    = ucwords(strtolower($catName));
            $item->Category = $catName;           
    
            // get slot from category
            $slot = ($i == 0) ? 'MainHand' : $catName;
    
            // if item is secondary tool or shield, its off-hand
            $slot = (stripos($catSecond, 'secondary tool') !== false) ? 'OffHand' : $slot;
            $slot = ($slot == 'Shield') ? 'OffHand' : $slot;
    
            // if item is a ring, check if its ring 1 or 2
            if ($slot == 'Ring') {
                $slot = isset($this->profile->GearSet['Gear']['Ring1']) ? 'Ring2' : 'Ring1';
            }
    
            // save slot
            $slot = str_ireplace(' ', '', $slot);
            $item->Slot = $slot;
    
            // add mirage
            $mirage = $node->find('.db-tooltip__item__mirage');
            if (trim($mirage->html())) {
                $lodestoneId = $mirage->find('a')->attr('href');
                $lodestoneId = trim(explode('/', $lodestoneId)[5]);

                // setup mirage item
                $mirageItem = new ItemSimple();
                $mirageItem->ID   = $lodestoneId;
                $mirageItem->Name = $mirage->find('p')->text();;
        
                $item->Mirage = $mirageItem;
            }
    
            // add creator
            $creator = $node->find('.db-tooltip__signature-character');
            if (trim($creator->html())) {
                $creator = explode("/", $creator->find('a')->attr('href'));
                $item->Creator = trim($creator[3]);
            }
    
            // add dye
            $dye = $node->find('.eorzeadb_tooltip_mb10 .stain');
            if (trim($dye->html())) {
                $dyeUrl  = $dye->find('a')->attr('href');
                $dyeName = $dye->find('a')->text();
                $dyeId   = trim(explode("/", $dyeUrl)[5]);
                
                $dyeObject = new ItemSimple();
                $dyeObject->ID   = $dyeId;
                $dyeObject->Name = $dyeName;
                $item->Dye = $dyeObject;
            }
    
            // add materia
            $materiaNodes = $node->find('.db-tooltip__materia');
            if (trim($materiaNodes->html())) {
                if ($materiaNodes = $materiaNodes->find('li')) {
                    /** @var DomQuery $mnode */
                    foreach ($materiaNodes as $mnode) {
                        $mhtml = $mnode->find('.db-tooltip__materia__txt')->html();
                        if (!$mhtml) {
                            continue;
                        }
                
                        $mdetails = explode('<br>', html_entity_decode($mhtml));
                        if (empty($mdetails[1])) {$mdetails[1] = null;}
                
                        $materiaObject = new ItemSimple();
                        $materiaObject->Name  = trim(strip_tags($mdetails[0]));
                        $materiaObject->Value = trim(strip_tags($mdetails[1]));
                        $item->Materia[] = $materiaObject;
                    }
                }
            }
            
            $this->profile->GearSet['Gear'][$slot] = $item;
        }
    }
    
    /**
     * Parse basic profile information (name, server, avatar, etc)
     */
    private function parseProfileBasic()
    {
        // name
        $name = $this->dom->find('.frame__chara__name')->eq(0)->html();
        $name = trim(strip_tags($name));
        $name = html_entity_decode($name, ENT_QUOTES, "UTF-8");
        $this->profile->Name = trim($name);
        
        // server
        [$server, $dc] = $this->getServerAndDc(
            $this->dom->find('.frame__chara__world')->eq(0)->text()
        );
    
        $this->profile->Server = $server;
        $this->profile->DC = $dc;
        
        // title
        if ($title = $this->dom->find('.frame__chara__title')) {
            $this->profile->Title = html_entity_decode(trim(strip_tags($title[0])), ENT_QUOTES, "UTF-8");
            $this->profile->TitleTop = $this->dom->find('.frame__chara .frame__chara__box p')->eq(0)->hasClass('frame__chara__title');
        }
    }
    
    /**
     * Parse a players bio field
     */
    private function parseProfileBio()
    {
        $bio = $this->dom->find('.character__selfintroduction')->text();
        $bio = html_entity_decode($bio, ENT_QUOTES, "UTF-8");
        $bio = str_ireplace('Character Profile', null, $bio);
        
        if ($bio = strip_tags($bio)) {
            $this->profile->Bio = $bio;
        }
    
        $this->profile->Bio = mb_convert_encoding($this->profile->Bio, 'UTF-8', 'UTF-8');
    }
    
    /**
     * @param DomQuery $node
     */
    private function parseProfileRaceTribeGender($node)
    {
        $html = $node->find('.character-block__name')->html();
        $html = str_ireplace(['<br />', '<br>', '<br/>'], ' / ', $html);
        
        [$race, $tribe, $gender] = explode('/', strip_tags($html));
        
        $this->profile->Race   = strip_tags(trim($race));
        $this->profile->Tribe  = strip_tags(trim($tribe));
        $this->profile->Gender = strip_tags(trim($gender)) == '♀' ? 'female' : 'male';
        
        // picture
        $avatar = $node->find('img')->attr('src');
        $this->profile->Avatar   = $avatar;
        $this->profile->Portrait = str_ireplace('c0_96x96', 'l0_640x873', $avatar);
    }
    
    /**
     * @param DomQuery $node
     */
    private function parseProfileNameDay($node)
    {
        $this->profile->Nameday = $node->find('.character-block__birth')->text();
        
        $obj = new Guardian();
        $obj->Name = html_entity_decode($node->find('.character-block__name')->text(), ENT_QUOTES, "UTF-8");
        $obj->Icon = $node->find('img')->attr('src');
        
        $this->profile->GuardianDeity = $obj;
    }
    
    /**
     * @param DomQuery $node
     */
    private function parseProfileTown($node)
    {
        $obj = new Town();
        $obj->Name = html_entity_decode($node->find('.character-block__name')->text(), ENT_QUOTES, "UTF-8");
        $obj->Icon = $node->find('img')->attr('src');
        $this->profile->Town = $obj;
    }
    
    /**
     * @param DomQuery $node
     */
    private function parseProfileGrandCompany($node)
    {
        $html = $node->find('.character-block__name')->html();
        
        // not all characters have a grand company
        [$name, $rank] = explode('/', strip_tags($html));
        
        $gc = new GrandCompany();
        $gc->Name = trim($name);
        $gc->Icon = $node->find('img')->attr('src');
        $gc->Rank = trim($rank);
        $this->profile->GrandCompany = $gc;
    }
    
    /**
     * @param DomQuery $node
     */
    private function parseProfileFreeCompany($node)
    {
        $this->profile->FreeCompanyId = $this->getLodestoneId($node);
        $this->profile->FreeCompanyName = trim($node->find('a')->text());
    }
    
    /**
     * @param DomQuery $node
     */
    private function parseProfilePvPTeam($node)
    {
        $this->profile->PvPTeamId = $this->getLodestoneId($node);
    }
}
