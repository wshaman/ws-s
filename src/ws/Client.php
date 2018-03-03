<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/28/18
 * Time: 9:56 PM
 */

namespace WsTest\ws;

use Ratchet\Client\Connector;
use WsTest\tools\EchoLog;

class Client
{
    private $socket;
    private $host;
    private $port;

    public function __construct(string $host, int $port, string $path)
    {
        $this->host = $host;
        $this->port = $port;
//        $this->socket = new WebSocket('localhost', $port);
//        $socket = $this->socket;
//        $this->socket->on("receive", function($client, $data) use($socket) {
//            print_r($data);
//        });

//        $this->wsClient = new WebSocketClient($params);
    }

    private function _ask(string $method, array $params=[])
    {
        $json = (new JsonRpcMessage())->fromArray([
            'method' => $method,
            "params" => $params,
            "id" => rand(0, 8092)
        ]);
        \Ratchet\Client\connect("ws://{$this->host}:{$this->port}")->then(function ($conn) use ($json){
            $conn->on('message', function($msg) use ($conn) {
                $json = (new JsonRpcResponse(0))->fromString($msg);
                if($json->error) EchoLog::error($json->error);
                if($json->message) EchoLog::log($json->message);
                $conn->close();
            });
            $conn->send($json->stringify());
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }

    public function getUserList()
    {
        $this->_ask('user-list');
    }

    public function getUserTasks(int $uid)
    {
        $this->_ask('user-task', [$uid]);
    }

    public function sendMessage(int $uid, int $task_id=0, string $message)
    {
        $params = [$uid, $task_id, $message];
//        $params["task_id"] = ($task_id) ? $task_id : 0;
        $this->_ask('send-message', $params);
    }
}