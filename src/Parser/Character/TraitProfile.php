<?php

namespace Lodestone\Parser\Character;

use Lodestone\Entity\Character\{
    Town,
    GrandCompany,
    Guardian
};

trait TraitProfile
{
    protected function parseProfile()
    {
        // parse main profile info
        $rows = $this->getSpecial__Profile_Data_Details()->find('.character-block');
        foreach ($rows as $row) {
            $blocktitle = $row->find('.character-block__title')->plaintext;
            if (in_array($blocktitle, ['Race/Clan/Gender', 'Volk / Stamm / Geschlecht', 'Race / Ethnie / Sexe', '種族/部族/性別'])) {
                $this->parseProfileRaceTribeGender($row);
            } elseif (in_array($blocktitle, ['NamedayGuardian', 'NamenstagSchutzgott', 'Date de naissanceDivinité', '誕生日守護神'])) {
                $this->parseProfileNameDay($row);
            } elseif (in_array($blocktitle, ['City-state', 'Stadtstaat', 'Cité de départ', '開始都市'])) {
                $this->parseProfileTown($row);
            } elseif (in_array($blocktitle, ['Grand Company', 'Staatliche Gesellschaft', 'Grande compagnie', '所属グランドカンパニー'])) {
                $this->parseProfileGrandCompany($row);
            } else {
                if ($row->find('.character__freecompany__name')->plaintext != "") {
                    $this->parseProfileFreeCompany($row);
                } elseif ($row->find('.character__pvpteam__name')->find('h4')->plaintext != "") {
                    $this->parseProfilePvPTeam($row);
                }
            }
        }
        $this->parseProfileBasic();
        $this->parseProfileBio();
    }

    protected function parseProfileBasic()
    {
        $html = $this->getArrayFromRange('frame__chara', 'parts__connect--state');

        // name
        $name = $this->getArrayFromRange('frame__chara__name', 0, $html);
        $name = trim(strip_tags($name[0]));
        $name = html_entity_decode($name, ENT_QUOTES, "UTF-8");
        $this->profile->Name = trim($name);
        
        // server
        $server = $this->getArrayFromRange('frame__chara__world', 0, $html);
        $this->profile->Server = trim(strip_tags($server[0]));

        // title
        if ($title = $this->getArrayFromRange('frame__chara__title', 0, $html)) {
            $this->profile->Title = trim(strip_tags($title[0]));
        }
    }

    protected function parseProfileBio()
    {
        $bio = $this->getArrayFromRange('character__selfintroduction', 'btn__comment');
        $bio = trim($bio[1]);
        $bio = html_entity_decode($bio, ENT_QUOTES, "UTF-8");

        if ($bio = strip_tags($bio)) {
            $this->profile->Bio = $bio;
        }
    }

    protected function parseProfileRaceTribeGender($node)
    {
        $html = $node->find('.character-block__name', 0)->innerHTML();
        $html = str_ireplace(['<br />', '<br>', '<br/>'], ' / ', $html);

        list($race, $tribe, $gender) = explode('/', strip_tags($html));

        $this->profile->Race   = strip_tags(trim($race));
        $this->profile->Tribe  = strip_tags(trim($tribe));
        $this->profile->Gender = strip_tags(trim($gender)) == '♀' ? 'female' : 'male';

        // picture
        $avatar = $this->getImageSource($node->find('img', 0));
        $this->profile->Avatar   = $avatar;
        $this->profile->Portrait = str_ireplace('c0_96x96', 'l0_640x873', $avatar);
    }

    protected function parseProfileNameDay($node)
    {
        $this->profile->Nameday = $node->find('.character-block__birth', 0)->plaintext;

        $guardian = new Guardian();
        $guardian->Name = html_entity_decode($node->find('.character-block__name', 0)->plaintext, ENT_QUOTES, "UTF-8");
        $guardian->Icon = $this->getImageSource($node->find('img', 0));

        $this->profile->GuardianDeity = $guardian;
    }

    protected function parseProfileTown($node)
    {
        $town = new Town();
        $town->Name = html_entity_decode($node->find('.character-block__name', 0)->plaintext, ENT_QUOTES, "UTF-8");
        $town->Icon = $this->getImageSource($node->find('img', 0));

        $this->profile->Town = $town;
    }

    protected function parseProfileGrandCompany($node)
    {
        $html = $node->find('.character-block__name', 0)->innerHTML();

        // not all characters have a grand company
        list($name, $rank) = explode('/', strip_tags($html));

        $gc = new GrandCompany();
        $gc->Name = trim($name);
        $gc->Icon = $this->getImageSource($node->find('img', 0));
        $gc->Rank = trim($rank);

        $this->profile->GrandCompany = $gc;
    }

    protected function parseProfileFreeCompany($node)
    {
        $this->profile->FreeCompanyId = trim(explode('/', $node->find("a", 0)->getAttribute("href"))[3]);
    }

    protected function parseProfilePvPTeam($node)
    {
        $this->profile->PvPTeam = trim(explode('/', $node->find("a", 0)->getAttribute("href"))[3]);
    }
}
