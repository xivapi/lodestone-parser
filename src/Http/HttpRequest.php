<?php

namespace Lodestone\Http;

use Lodestone\Modules\Logging\Logger;
use Lodestone\Validator\Exceptions\ValidationException,
    Lodestone\Validator\HttpRequestValidator;

/**
 * Class HttpRequest
 * @package src\Modules
 */
class HttpRequest
{
    /**
     * curl options
     */
    const CURL_OPTIONS = [
        CURLOPT_POST => false,
        CURLOPT_BINARYTRANSFER => false,
        CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_HTTPHEADER => ['Content-type: text/html; charset=utf-8', 'Accept-Language: en'],
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        CURLOPT_ENCODING => '',
        CURLOPT_SSL_VERIFYPEER => false,
    ];

  /**
   * @param $url
   * @return bool|string
   * @throws ValidationException
   */
    public function get($url)
    {
        $url = str_ireplace(' ', '+', $url);
        Logger::write(__CLASS__, __LINE__, 'GET: '. $url);

        // build handle
        $handle = curl_init();
        curl_setopt_array($handle, self::CURL_OPTIONS);
        curl_setopt($handle, CURLOPT_URL, $url);

        // handle response
        $response = curl_exec($handle);
        $hlength = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $data = substr($response, $hlength);

        curl_close($handle);
        unset($handle);

        Logger::write(__CLASS__, __LINE__, 'RESPONSE: '. $httpCode);

        // specific conditions to return code on
        HttpRequestValidator::getInstance()
            ->check($httpCode, 'HTTP Response Code', $url)
            ->isFound()
            ->isNotMaintenance()
            ->isNotHttpError()
            ->validate();

        return $data;
    }
}
