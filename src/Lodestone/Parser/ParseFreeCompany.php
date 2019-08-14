<?php

namespace Lodestone\Parser;

use Lodestone\Entity\FreeCompany\FreeCompany;
use Rct567\DomQuery\DomQuery;

class ParseFreeCompany extends ParseAbstract implements Parser
{
    use HelpersTrait;

    public function handle(string $html)
    {
        // set dom
        $this->setDom($html);

        $fc = new FreeCompany();

        /** @var DomQuery $this->html */
        $this->dom = $this->dom->find('.ldst__window');

        /** @var DomQuery $img */
        foreach ($this->dom->find('.entry__freecompany__crest__image img') as $img) {
            $fc->Crest[] = str_ireplace('64x64', '128x128', $img->attr('src'));
        }
        
        [$server, $dc] = $this->getServerAndDc(
            $this->dom->find('.entry__freecompany__gc')->eq(1)->text()
        );

        $fc->GrandCompany       = trim(explode('<', trim($this->dom->find('.entry__freecompany__gc')->text()))[0]);
        $fc->Name               = $this->dom->find('.entry__freecompany__name')->text();
        $fc->Server             = $server;
        $fc->DC                 = $dc;

        // all use: freecompany__text
        $fc->Active             = trim($this->dom->find('.freecompany__text')->eq(5)->text());
        $fc->Recruitment        = trim($this->dom->find('.freecompany__text')->eq(1)->text());
        $fc->Formed             = $this->getTimestamp($this->dom->find('.freecompany__text')->eq(2));
        $fc->ActiveMemberCount  = filter_var($this->dom->find('.freecompany__text')->eq(3)->text(), FILTER_SANITIZE_NUMBER_INT);
        $fc->Rank               = filter_var($this->dom->find('.freecompany__text')->eq(4)->text(), FILTER_SANITIZE_NUMBER_INT);

        $fc->Tag                = str_ireplace(['«', '»'], null, $this->dom->find('.freecompany__text__tag')->eq(1)->text());
        $fc->Slogan             = str_ireplace(["<br>", "<br/>"], "\n", $this->dom->find('p.freecompany__text__message')->text());
        $fc->Ranking['Weekly']  = filter_var($this->dom->find('.character__ranking__data th')->eq(0)->text(), FILTER_SANITIZE_NUMBER_INT);
        $fc->Ranking['Monthly'] = filter_var($this->dom->find('.character__ranking__data th')->eq(1)->text(), FILTER_SANITIZE_NUMBER_INT);

        $fc->Estate             = [
            'Name'     => $this->dom->find('.freecompany__estate__name')->text(),
            'Plot'     => $this->dom->find('.freecompany__estate__text')->text(),
            'Greeting' => $this->dom->find('.freecompany__estate__greeting')->text(),
        ];

        /** @var DomQuery $rep */
        foreach ($this->dom->find('.freecompany__reputation') as $rep) {
            $fc->Reputation[]  = [
                'Name'     => $rep->find('.freecompany__reputation__gcname')->text(),
                'Rank'     => $rep->find('.freecompany__reputation__rank')->text(),
                'Progress' => filter_var($rep->find('.character__bar div')->attr('style'), FILTER_SANITIZE_NUMBER_INT),
            ];
        }

        // Process "Seeking"
        if ($this->dom->find('.freecompany__focus_icon--role')->html()) {
            /** @var DomQuery $node */
            foreach ($this->dom->find('.freecompany__focus_icon--role li') as $node) {
                $fc->Seeking[]  = [
                    'Status' => !$node->hasClass('freecompany__focus_icon--off'),
                    'Icon'   => $node->find('img')->attr('src'),
                    'Name'   => $node->find('p')->text()
                ];
            }
        }

        // Process "Focus"
        if ($this->dom->find('.freecompany__focus_icon:not(.freecompany__focus_icon--role)')->html()) {
            /** @var DomQuery $node */
            foreach ($this->dom->find('.freecompany__focus_icon:not(.freecompany__focus_icon--role) li') as $node) {
                $fc->Focus[]  = [
                    'Status' => !$node->hasClass('freecompany__focus_icon--off'),
                    'Icon'   => $node->find('img')->attr('src'),
                    'Name'   => $node->find('p')->text()
                ];
            }
        }

        return $fc;
    }
}
