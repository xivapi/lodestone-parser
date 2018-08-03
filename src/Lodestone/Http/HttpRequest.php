<?php

namespace Lodestone\Http;

use Lodestone\Exceptions\{MaintenanceException,NotFoundException};
use Lodestone\Helpers\Logger;

class HttpRequest
{
    const CURL_OPTIONS = [
        CURLOPT_POST            => false,
        CURLOPT_BINARYTRANSFER  => false,
        CURLOPT_HEADER          => true,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_CONNECTTIMEOUT  => 10,
        CURLOPT_TIMEOUT         => 10,
        CURLOPT_FOLLOWLOCATION  => true,
        CURLOPT_MAXREDIRS       => 3,
        CURLOPT_ENCODING        => '',
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_HTTPHEADER      => ['Content-type: text/html; charset=utf-8', 'Accept-Language: en'],
        CURLOPT_USERAGENT       => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
    ];

    public function get($url)
    {
        $url = str_ireplace(' ', '+', $url);
        Logger::write('GET: '. $url);

        // build handle
        $handle = curl_init();
        curl_setopt_array($handle, self::CURL_OPTIONS);
        curl_setopt($handle, CURLOPT_URL, $url);

        // handle response
        $response = curl_exec($handle);
        $hlength  = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $data     = substr($response, $hlength);

        curl_close($handle);
        unset($handle);

        Logger::write('RESPONSE: '. $httpCode);
        
        if ($httpCode == 503) {
            throw new MaintenanceException();
        }
        
        if ($httpCode == 404) {
            throw new NotFoundException();
        }
        
        return $data;
    }
}
