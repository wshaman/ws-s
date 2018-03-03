<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/28/18
 * Time: 8:59 PM
 */
require __DIR__ . DIRECTORY_SEPARATOR . "../vendor/autoload.php";

use WsTest\ws\Client;


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
         $_ = explode('=', $i);
         if(!isset($_[1])) $_[1] = true;
         return $_;
    }, $arguments);
}

function hasParamValue(array $params, string $key) {
    $p = array_values(array_filter($params, function ($i) use ($key) {return $i[0] == $key;}));
    return (!$p[1]) ? null : $p[1];
}

if ($argc < 2) {
    help($argv[0]);
}

$params = parseParams($argv);
$config = new \WsTest\tools\Config();
$wsClient = new Client($config->read('host'), $config->read('port'), $config->read('path'));

switch ($params[0][0]) {
    case 'get-all-users':
        $wsClient->getUserList();
        break;
    case 'get-all-user-task':
        $user_id = $params[0][1];
        if(!is_numeric($user_id)) help($argv[0]);
        $wsClient->getUserTasks($user_id);
        break;
    case 'send-message':
        $user_id = $params[0][1];
        $message = hasParamValue($params, "message");
        $task_id = hasParamValue($params, "task");
        if($user_id == "all") $user_id = 0;
        if(!is_numeric($user_id)) help($argv[0]);
        if(!is_string($message)) help($argv[0]);
        if($task_id && !is_numeric($task_id)) help($argv[0]);
        $wsClient->sendMessage($user_id, $task_id, $message);
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