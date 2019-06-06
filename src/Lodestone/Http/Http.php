<?php

namespace Lodestone\Http;

use Lodestone\Parser\Parser;
use Ramsey\Uuid\Uuid;
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
    public function request(string $parser, Request $request)
    {
        $requestId = Uuid::uuid4()->toString();

        // get client
        $client = $this->getClient();

        // perform request
        $response = $client->request($request->method, $request->endpoint, [
            'query'     => $request->query,
            'headers'   => $request->headers,
            'json'      => $request->json,
            'user_data' => [
                'request_id' => $requestId,
                'parser'     => $parser
            ]
        ]);

        // Asynchronous: Pop the response into the async handler, this returns the number
        // it was assigned to
        if (RequestConfig::$isAsync) {
            AsyncHandler::add($response);
            return null;
        }

        /** @var Parser $parser */
        $parser = new $parser();

        // Synchronous: Get the content
        return $parser->handle($response->getContent());
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
                // grab the user data
                $userdata  = $response->getInfo('user_data');

                // grab the parser class name
                /** @var Parser $parser */
                $parser = new $userdata['parser']();

                // handle response
                $content[] = $parser->handle(
                    $response->getContent()
                );
            }
        }

        return $content;
    }
}
