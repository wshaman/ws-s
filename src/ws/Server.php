<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/27/18
 * Time: 1:42 PM
 */

namespace WsTest\ws;


class Server extends WebSocketServer
{
    protected function process($user, $message)
    {
        $this->send($user, $message);
        // TODO: Implement process() method.
    }

    protected function connected($user)
    {
        // TODO: Implement connected() method.
    }

    protected function closed($user)
    {
        // TODO: Implement closed() method.
    }

}