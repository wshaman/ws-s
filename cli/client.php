<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/28/18
 * Time: 8:59 PM
 */
require __DIR__ . DIRECTORY_SEPARATOR . "../vendor/autoload.php";

use WsTest\ws\Client;

$config = require(__DIR__ . DIRECTORY_SEPARATOR . "../config/ws.php");


function help(string $myName)
{
    $message = <<<EOT
Usage:
$myName get-all-users -> вывести на экран ID всех зарегистрированных на WebSocket сервере пользователей.
$myName get-all-user-task=userId -> вывести на эран ID всех зарегистрированных на WebSocket сервере задач одного пользователя.
$myName send-message=all message="Текст сообщения" -> отправить сообщение всем зарегистрированным на WebSocket сервере пользователям, во все задачи.
$myName send-message=userId message="Текст сообщения" -> отправить сообщение одному зарегистрированному на WebSocket сервере пользователю, во все задачи.
$myName send-message=userId task=taskId message="Текст сообщения" -> отправить сообщение одному зарегистрированному на WebSocket сервере пользователю, в одну задачу.
EOT;
    echo $message;
    exit(0);
}

function parseParams(array $arguments): array
{
    array_shift($arguments);
    return array_map(function ($i) {
        return explode('=', $i);
    }, $arguments);
}
$knownCommands = ['get-all-users', 'get-all-user-task', 'send-message'];

if ($argc < 2) {
    help($argv[0]);
}

$params = parseParams($argv);
$wsClient = new Client($config['host'], $config['port'], $config['path']);

switch ($params[0][0]) {
    case 'get-all-users':
        echo $wsClient->getUserList();
        break;
    case 'get-all-user-task':
        break;
    case 'send-message':
        break;
    default:
        help($argv[0]);
}

//$ws = new WebSocketClient(array
//(
//    'host' => $config['host'],
//    'port' => $config['port'],
//    'path' => $config['path']
//));
//$result = $ws->send('message');
//$ws->close();
//echo $result;