<?php

namespace Lodestone\Parser\FreeCompany;

use Lodestone\{
    Entity\FreeCompany\FreeCompany,
    Html\ParserHelper
};

class Parser extends ParserHelper
{
    /** @var FreeCompany */
    protected $fc;

    function __construct($id)
    {
        $this->fc = new FreeCompany();
    }

    public function parse(): FreeCompany
    {
        $this->initialize();
        $this->parseHeader();
        $this->parseProfile();
        $this->parseFocus();
        return $this->fc;
    }

    private function parseHeader(): void
    {
        $box = $this->getDocumentFromClassname('.ldst__window .entry', 0);

        foreach ($box->find('.entry__freecompany__crest__image img') as $img) {
            $this->fc->Crest[] = str_ireplace('64x64', '128x128', $img->getAttribute('src'));
        }

        $this->fc->GrandCompany = trim(explode('<', trim(
            $box->find('.entry__freecompany__gc')->plaintext
        ))[0]);
        $this->fc->Name   = trim($box->find('.entry__freecompany__name')->plaintext);
        $this->fc->Server = trim($box->find('.entry__freecompany__gc', 1)->plaintext);
    }

    private function parseProfile(): void
    {
        $box = $this->getDocumentFromClassname('.ldst__window', 0);

        $this->fc->Formed   = $this->getTimestamp($box->find('.freecompany__text', 2));
        $this->fc->Tag      = trim(str_ireplace(['«', '»'], null,
            $box->find('.freecompany__text__tag', 1)->plaintext
        ));
        $this->fc->Rank     = filter_var(
            $box->find('.freecompany__text', 4)->plaintext, FILTER_SANITIZE_NUMBER_INT
        );
        $this->fc->Slogan   = trim(str_ireplace("<br/>", "\n",
            $box->find('.freecompany__text__message', 0)->innertext
        ));
        $this->fc->Estate   = [
            'Name' => trim($box->find('.freecompany__estate__name')->plaintext),
            'Plot' => trim($box->find('.freecompany__estate__text')->plaintext),
            'Greeting' => trim($box->find('.freecompany__estate__greeting')->plaintext),
        ];
        $this->fc->Ranking['Weekly']  = filter_var(
            $box->find('.character__ranking__data th', 0)->plaintext, FILTER_SANITIZE_NUMBER_INT
        );
        $this->fc->Ranking['Monthly'] = filter_var(
            $box->find('.character__ranking__data th', 1)->plaintext, FILTER_SANITIZE_NUMBER_INT
        );
        $this->fc->ActiveMemberCount = filter_var(
            $box->find('.freecompany__text', 3)->plaintext, FILTER_SANITIZE_NUMBER_INT
        );

        foreach ($box->find('.freecompany__reputation') as $rep) {
            $this->fc->Reputation[] = [
                'Name' => trim($rep->find('.freecompany__reputation__gcname')->plaintext),
                'Rank' => trim($rep->find('.freecompany__reputation__rank')->plaintext),
                'Progress' => trim(filter_var(
                    $rep->find('.character__bar div', 0)->getAttribute('style'), FILTER_SANITIZE_NUMBER_INT)
                ),
            ];
        }
    }

    private function parseFocus(): void
    {
        $box = $this->getDocumentFromClassname('.ldst__window', 1);

        $this->fc->Active = trim($box->find('.freecompany__text', 0)->plaintext);
        $this->fc->Recruitment = trim($box->find('.freecompany__text', 1)->plaintext);

        if ($seekNodes = $box->find('.freecompany__focus_icon--role', 0)) {
            foreach ($seekNodes->find('li') as $node) {
                $this->fc->Seeking[] = [
                    'Status' => ($node->getAttribute('class') == 'freecompany__focus_icon--off'),
                    'Icon'   => trim($node->find('img', 0)->src),
                    'Name'   => trim($node->find('p', 0)->plaintext)
                ];
            }
        }

        if ($focusNodes = $box->find('.freecompany__focus_icon:not(.freecompany__focus_icon--role)', 0)) {
            foreach ($focusNodes->find('li') as $node) {
                $this->fc->Focus[] = [
                    'Status' => ($node->getAttribute('class') == 'freecompany__focus_icon--off'),
                    'Icon'   => trim($node->find('img', 0)->src),
                    'Name'   => trim($node->find('p', 0)->plaintext),
                ];
            }
        }
    }
}
