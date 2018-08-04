<?php

namespace Lodestone\Helpers;

class Logger
{
    public static function write($message)
    {
        if (!defined('LOGGER_ENABLED')) {
            return;
        }
        
        $time  = date('Y-m-d H:i:s');
        $ms    = round(microtime(true) * 1000);
        $mem   = memory_get_usage(true);
        $debug = debug_backtrace()[0];
        $class = $debug['class'];
        $line  = $debug['line'];
        echo sprintf("[%s][%s][%s][%s %s] %s\n", $time, $ms, $mem, $line, $class, $message);
    }
}
