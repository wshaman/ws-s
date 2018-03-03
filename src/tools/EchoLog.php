<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 3/2/18
 * Time: 1:48 PM
 */

namespace WsTest\tools;


class EchoLog
{

    public static function log(string $msg, string $type='LOG')
    {
        $is_error = (in_array($type, ['ERR', 'ERROR']));
        $dt = date('c');
        switch ($type){
            case 'ERR' : $clr_start = "\033[01;31m"; $type = 'ERR '; break;
            case 'WARN' : $clr_start = "\033[01;33m"; break;
            case 'OK' : $clr_start = "\033[01;32m"; $type = ' OK '; break;
            default : $clr_start = "\033[01;37m";
        }
        $clr_end = "\033[0m";
        echo "[{$dt}][{$clr_start}{$type}{$clr_end}] : $msg " . PHP_EOL;
//        if ($is_error) {
//            debug_print_backtrace();
//        }
    }

    public static function warn(string $message)
    {
        self::log($message, 'WARN');
    }

    public static function error(string $message)
    {
        self::log($message, 'ERR');
    }

    public static function ok(string $message)
    {
        self::log($message, 'OK');
    }


}