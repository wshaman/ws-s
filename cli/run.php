<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/27/18
 * Time: 1:06 PM
 */

require __DIR__ . DIRECTORY_SEPARATOR . "../vendor/autoload.php";

use WsTest\ws\Server;

$config = new \WsTest\tools\Config();

$s = new Server($config->read('host'), $config->read('port'));

try {
    $s->run();
} catch (Exception $e) {
    $s->stdout($e->getMessage());
}