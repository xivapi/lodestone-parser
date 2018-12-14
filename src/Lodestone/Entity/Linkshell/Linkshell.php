<?php

namespace Lodestone\Entity\Linkshell;

use Lodestone\Entity\AbstractEntity;

class Linkshell extends AbstractEntity
{
    public $ID;
    public $Name;
    public $Server;
    public $ParseDate;

    public function __construct()
    {
        $this->ParseDate = time();
    }
}
