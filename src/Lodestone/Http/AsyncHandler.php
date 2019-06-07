<?php

namespace Lodestone\Http;

use Symfony\Contracts\HttpClient\ResponseInterface;

class AsyncHandler
{
    /** @var string */
    public static $requestId = null;
    /** @var array */
    private static $responses = [];

    /**
     * Add a response for concurrency
     */
    public static function add(ResponseInterface $response)
    {
        self::$responses[] = $response;
    }

    /**
     * Get all concurrent requests
     */
    public static function get(): array
    {
        $responses = self::$responses;

        self::reset();

        return $responses;
    }

    /**
     * Reset responses, this is done every time ::get() is called
     */
    public static function reset(): void
    {
        self::$responses = [];
        self::$requestId = null;
    }
}
