<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 3/2/18
 * Time: 1:10 PM
 */

namespace WsTest\tools;


class Config
{
    private $config;

    public function __construct()
    {
        $this->config = require(__DIR__ . DIRECTORY_SEPARATOR . "../../config/ws.php");
    }

    public function read($part)
    {
        return $this->config[$part];
    }
}