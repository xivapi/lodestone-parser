<?php

namespace Lodestone\Http;

class Request
{
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

    /**
     * Build a Lodestone Request
     */
    public function __construct(array $options)
    {
        // required
        $this->method    = (string)$options['method'];
        $this->endpoint  = (string)$options['endpoint'];

        // optional
        $this->query     = $options['query'] ?? [];
        $this->headers   = $options['headers'] ?? [];
        $this->json      = $options['json'] ?? [];

        // hard-coded headers
        $this->headers['User-Agent'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36';
        $this->headers['Content-type'] = 'text/html';
        $this->headers['Accept-Language'] = 'en';
        $this->headers['charset'] = 'utf-8';
    }
}
