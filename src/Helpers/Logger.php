<?php

namespace Lodestone\Helpers;

class Logger
{
    public static function write($message)
    {
        $time = date('Y-m-d H:i:s');
        $ms   = round(microtime(true) * 1000);
        $mem  = memory_get_usage(true);

        print_r(debug_backtrace()[0]);die;

        return sprintf("[%s][%s][%s] %s", $time, $ms, $mem, $message);
    }
}
