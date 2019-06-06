<?php

namespace Lodestone\Http;

use Symfony\Component\HttpClient\CurlHttpClient;

class Http
{
    const BASE_URI = 'https://xivapi.com';
    const TIMEOUT  = 5;

    /**
     * Get Symfony Client
     */
    private function getClient()
    {
        return new CurlHttpClient([
            'base_uri' => self::BASE_URI,
            'timeout'  => self::TIMEOUT
        ]);
    }

    /**
     * Perform a request
     * @throws
     */
    public function request(string $class, Request $request)
    {
        // get client
        $client = $this->getClient();

        // perform request
        $response = $client->request($request->method, $request->endpoint, [
            'query'   => $request->query,
            'headers' => $request->headers,
            'json'    => $request->json
        ]);

        // Asynchronous: Pop the response into the async handler, this returns the number
        // it was assigned to
        if (RequestConfig::$isAsync) {
            return AsyncHandler::add($class, $response);
        }

        // Synchronous: Get the content
        return $response->getContent();
    }

    /**
     * Settle any async requests
     * @throws
     */
    public function settle()
    {
        if (RequestConfig::$isAsync === false) {
            throw new \Exception("Request API is not in async mode. There will be no async requests to settle.");
        }

        $content   = [];
        $client    = $this->getClient();
        $responses = AsyncHandler::get();

        foreach ($client->stream($responses) as $response => $chunk) {
            if ($chunk->isLast()) {
                $content[] = $response->getContent();
            }
        }

        return $content;
    }
}
