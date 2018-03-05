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

    private function _userList()
    {
        $this->_setStatus(Constants::STATUS_OK);
        $list = array_values(array_map(function ($i) {
            return ['user_id' => $i->user_id, 'task_id' => $i->task_id];
        }, $this->users));
        $res = [];
        foreach ($list as $item){
            if($item['user_id'] && $item['task_id'])
                $res[] = "{$item['user_id']}:{$item['task_id']}";
        }
        return implode("\n", $res);
    }

    private function _userTasks($uid)
    {
        $this->_setStatus(Constants::STATUS_OK);
        $my =
            array_values(array_map(function ($i) {
                return $i->task_id;
            },
                array_filter($this->users, function ($i) use ($uid) {return $i->user_id == $uid;})
            ));
        return implode(",", $my);
    }

    private function _sendMessage(int $user_id, int $task_id, string $message)
    {
//        var_dump($user_id);
//        var_dump($task_id);
//        var_dump($message);
        if($user_id == 0){
            //@nb: send to all
            $users = $this->users;
        } else {
            $users = array_filter($this->users, function ($i) use ($user_id, $task_id) {
            return $i->user_id == $user_id && ($task_id == 0 || $i->task_id == $task_id);
            });
        }
        if (count($users) == 0){
            $this->_setStatus(Constants::STATUS_FAIL);
            return "No users matching criteria found";
        }
        foreach ($users as $user) {
            $json = new JsonRpcResponse(0);
            $json->fromMessage( $message);
            $this->send($user, $json->stringify());
        }
        $this->_setStatus(Constants::STATUS_OK);
        return "Messages sent: " . count($users);
    }

    private function _answer(int $id, object $user, $message)
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
                $response = $this->_register($data->params[0], $data->params[1], $user);
                break;
            case 'user-list':
                $response = $this->_userList();
                break;
            case 'user-task':
                $response = $this->_userTasks($data->params[0]);
                break;
            case "send-message":
                $response = $this->_sendMessage(...$data->params);
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
//        unset($this->users[$user->id]);
        // TODO: Implement closed() method.
    }


}