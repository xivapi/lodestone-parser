<?php

namespace Lodestone\Api;

use Lodestone\Http\Http;
use Lodestone\Http\Request;
use Lodestone\Http\RequestConfig;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiAbstract
{
    protected $http;

    public function __construct()
    {
        $this->http = new Http();
    }

    /**
     * Handle a request
     */
    protected function handle(string $parser, array $requestOptions)
    {
        $request = new Request($requestOptions);

        /** @var ResponseInterface $response */
        $response = $this->http->request($parser, $request);

        if (RequestConfig::$isAsync) {
            return null;
        }

        return $response;
    }
}
