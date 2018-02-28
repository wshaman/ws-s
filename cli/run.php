<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/27/18
 * Time: 1:06 PM
 */

require "../vendor/autoload.php";

use WsTest\ws\Server;

$config = require("../config/ws.php");


$s = new Server($config['host'], $config['port']);

try {
    $s->run();
}
catch (Exception $e) {
    $s->stdout($e->getMessage());
}