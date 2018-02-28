<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/27/18
 * Time: 1:06 PM
 */

require "../vendor/autoload.php";

use WsTest\ws\Server;

$s = new Server("127.0.0.1", 9009);

try {
    $s->run();
}
catch (Exception $e) {
    $s->stdout($e->getMessage());
}