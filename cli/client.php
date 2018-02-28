<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/28/18
 * Time: 8:59 PM
 */
require "../vendor/autoload.php";

use WsTest\ws\WebSocketClient;

$config = require("../config/ws.php");

$ws = new WebSocketClient(array
(
    'host' => $config['host'],
    'port' => $config['port'],
    'path' => $config['path']
));
$result = $ws->send('message');
$ws->close();
echo $result;