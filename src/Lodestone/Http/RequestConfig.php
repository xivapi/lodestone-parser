<?php

namespace Lodestone\Http;

class RequestConfig
{
    public static $isAsync = false;

    /**
     * Use asynchronous requests
     */
    public function useAsync()
    {
        RequestConfig::$isAsync = true;
    }

    /**
     * Use synchronous requests
     */
    public function useSync()
    {
        RequestConfig::$isAsync = false;
    }

    public function setRequestId($requestId)
    {
        AsyncHandler::$requestId = $requestId;
    }

    /**
     * Reset the async handler
     */
    public function resetAsyncHandler()
    {
        AsyncHandler::reset();
    }
}
