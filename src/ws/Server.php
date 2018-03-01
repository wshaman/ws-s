<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/27/18
 * Time: 1:42 PM
 */

namespace WsTest\ws;


use WsTest\tools\Constants;

class Server extends WebSocketServer
{
    private $_last_status = 0;

    public function __construct($addr, $port, $bufferLength = 2048)
    {
        $this->_setStatus(Constants::STATUS_NO_DATA);
        parent::__construct($addr, $port, $bufferLength);
    }

    private function _setStatus(int $status)
    {
        $this->_last_status = $status;
    }

    private function _flushStatus(): int
    {
        $a = $this->_last_status;
        $this->_last_status = Constants::STATUS_NO_DATA;
        return $a;
    }

    /**
     * @param int $user_id This one is user_id got from client, not internal one
     * @param int $task_id
     * @param object $user
     * @return string
     */
    private function _register(int $user_id, int $task_id, object $user): string
    {
        if ($this->users[$user->id]) {
            $this->users[$user->id]->user_id = $user_id;
            $this->users[$user->id]->task_id = $task_id;
            $this->_setStatus(Constants::STATUS_OK);
            return "user registration was successful";
        }
        $this->_setStatus(Constants::STATUS_FAIL);
        return "user registration failed";
    }

    private function _answer(int $id, object $user, mixed $message)
    {
        $st = $this->_flushStatus();
        $answer = (new JsonRpcResponse($id));
        if ($st == Constants::STATUS_OK) $answer->fromMessage($message);
        else $answer->fromError($message);
        $this->send($user, $answer->stringify());
    }

    protected function process($user, $message)
    {
        $data = (new JsonRpcMessage())->fromString($message);
        $response = null;
        switch ($data->method) {
            case 'register':
                $response = $this->_register($data->params['user_id'], $data->params['task_id'], $user);
                break;
            default :
                $this->_setStatus(Constants::STATUS_FAIL);
                $response = "{$data->method} not implemented (yet)";
        }
        $this->_answer($data->id, $user, $response);
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