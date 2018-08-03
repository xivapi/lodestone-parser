<?php

namespace Lodestone\Parser\Character;

use Lodestone\Dom\NodeList,
    Lodestone\Entity\Character\Item,
    Lodestone\Entity\Character\ItemSimple;

trait TraitGear
{
    /**
     * Parse gear for the current role
     */
    protected function parseEquipGear(): void
    {
        foreach ($this->getSpecial__EquipGear()->find('.item_detail_box') as $i => $node)
        {
            $item = new Item();

            /** @var NodeList $node */
            $html = $this->getArrayFromHtml($node->innerHtml());

            // get name
            $name = $this->getArrayFromRange('db-tooltip__item__name', 1, $html);

            // If this slot has no item name html
            // it's safe to assume empty slot
            if (!$name) {
                continue;
            }

            $item->Name = strip_tags($name[1]);

            // get lodestone id
            $lodestoneId = $this->getArrayFromRange('db-tooltip__bt_item_detail', 1, $html);
            $item->ID = trim(explode('/', $lodestoneId[1])[5]);

            // get category
            $category = $this->getArrayFromRange('db-tooltip__item__category', 1, $html);
            $category = trim(strip_tags($category[1]));
            $category = explode("'", $category)[0];
            $item->Category = trim(str_ireplace(['Two-handed', 'One-handed'], null, $category));

            // get slot from category
            $slot = ($i == 0) ? 'MainHand' : ucwords(strtolower($category));

            // if item is secondary tool or shield, its off-hand
            $slot = (stripos($slot, 'secondary tool') !== false) ? 'OffHand' : $slot;
            $slot = ($slot == 'Shield') ? 'OffHand' : $slot;

            // if item is a ring, check if its ring 1 or 2
            if ($slot == 'Ring') {
                $slot = isset($this->profile->Gear['Ring1']) ? 'Ring2' : 'Ring1';
            }

            // save slot
            $item->Slot = str_ireplace(' ', '', $slot);

            // add mirage
            $mirage = $this->getArrayFromRange('db-tooltip__item__mirage', 8, $html);
            if ($mirage) {
                $mirage = stripos($mirage[6], '/lodestone/playguide') === false ? explode('/', $mirage[8]) : explode("/", $mirage[6]);

                // grab mirage name and id
                $mirageName = trim(str_ireplace('<a href="', null, $mirage[0]));
                $mirageId = trim($mirage[5]);
                
                $mirageItem = new ItemSimple();
                $mirageItem->ID   = $mirageId;
                $mirageItem->Name = $mirageName;
            }


            // add creator
            $creator = $this->getArrayFromRange('db-tooltip__signature-character', 4, $html);
            if ($creator) {
                $creator = explode("/", $creator[1]);
                $item->Creator = trim($creator[3]);
            }

            // add dye
            $dye = $this->getArrayFromRange('class="stain"', 4, $html);
            if ($dye) {
                // grab mirage name and id
                $dyeName = trim(strip_tags($dye[2]));
                $dyeId = trim(explode("/", $dye[1])[5]);
                
                $dyeObject = new ItemSimple();
                $dyeObject->ID   = $dyeId;
                $dyeObject->Name = $dyeName;
                $item->Dye = $dyeObject;
            }

            // add materia
            $materiaNodes = $node->find('.db-tooltip__materia',0);
            if ($materiaNodes) {
               if ($materiaNodes = $materiaNodes->find('li')) {
                   foreach ($materiaNodes as $mnode) {
                       $mhtml = $mnode->find('.db-tooltip__materia__txt')->innerHtml();
                       if (!$mhtml) {
                           continue;
                       }

                       $mdetails = explode('<br>', html_entity_decode($mhtml));
                       if (empty($mdetails[1])) {$mdetails[1] = null;}

                       $materiaObject = new ItemSimple();
                       $materiaObject->Name  = trim(strip_tags($mdetails[0]));
                       $materiaObject->Value = trim(strip_tags($mdetails[1]));
                       $item->Materia = $materiaObject;
                   }
               }
            }

            $this->profile->Gear[$slot] = $item;
        }
    }
}
