<?php

namespace Lodestone\Http;

class Request
{
    /** @var string */
    public $baseUri;
    /** @var string */
    public $method;
    /** @var string */
    public $endpoint;
    /** @var array */
    public $query;
    /** @var array */
    public $headers;
    /** @var array */
    public $json;
    /** @var array */
    public $userData;

    /**
     * Build a Lodestone Request
     */
    public function __construct(array $options)
    {
        // required
        $this->method    = $options['method'] ?? 'GET';
        $this->endpoint  = $options['endpoint'] ?? '';

        // optional
        $this->baseUri   = $options['base_uri'] ?? null;
        $this->query     = $options['query'] ?? [];
        $this->headers   = $options['headers'] ?? [];
        $this->json      = $options['json'] ?? null;
        $this->userData  = $options['user_data'] ?? [];

        // hard-coded headers
        $this->headers['User-Agent'] = $options['user-agent'] ?? 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36';
        $this->headers['Content-type'] = 'text/html';
        $this->headers['Accept-Language'] = 'en';
        $this->headers['charset'] = 'utf-8';
    }
}
