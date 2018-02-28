<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/28/18
 * Time: 9:56 PM
 */

namespace WsTest\ws;


class Client
{
    private $wsClient;

    public function __construct(string $host, int $port, string $path)
    {
        $params = [
            'host' => $host,
            'port' => $port,
            'path' => $path
        ];
        $this->wsClient = new WebSocketClient($params);
    }

    public function getUserList() : string
    {
        return $this->wsClient->send('user-list');
    }
}