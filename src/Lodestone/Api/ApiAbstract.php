<?php

namespace Lodestone\Api;

use Lodestone\Http\Http;

class ApiAbstract
{
    protected $http;

    public function __construct()
    {
        $this->http = new Http();
    }
}
