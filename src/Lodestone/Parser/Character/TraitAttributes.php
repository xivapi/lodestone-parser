<?php

namespace Lodestone\Parser\Character;

use Lodestone\Dom\NodeList;
use Lodestone\Entity\Character\Attribute;

trait TraitAttributes
{
    protected function parseAttributes(): void
    {
        /** @var NodeList $box */
        $box = $this->getSpecial__AttributesPart1();

        // fetches:
        // * attributes
        // * offensive, defensive, physical and mental properties
        for ($i = 0; $i < 6; $i++) {
            /** @var NodeList $trs */
            $trs = $box->find('.character__param__list', $i);
            if (empty($trs)) {
                continue;
            }
            
            foreach($trs->find('tr') as $node) {
                $this->profile->Attributes[] = $this->parseAttributeCommon($node);
            }
        }

        $box = $this->getSpecial__AttributesPart3();

        // hp, mp, tp, cp, gp etc
        foreach($box->find('li') as $node) {
            $attr = new Attribute();
            $attr->Name  = trim($node->find('.character__param__text')->plaintext);
            $attr->Value = intval($node->find('span')->plaintext);
            $this->profile->Attributes[] = $attr;
        }

        unset($box);
    }

    protected function parseAttributeCommon($node): Attribute
    {
        $obj = new Attribute();
        $obj->Name  = trim($node->find('th')->plaintext);
        $obj->Value = intval($node->find('td')->plaintext);

        unset($node);
        return $obj;
    }
}
