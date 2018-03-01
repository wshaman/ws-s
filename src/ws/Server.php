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


    private function _readJson($data){

    }

    private function _register(int $user_id, int $task_id, object $user)
    {

    }

    protected function process($user, $message)
    {
        $data = (new JsonRpcObject())->fromString($message);
        switch ($data->method){
            case 'register':

        }
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