<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 3/1/18
 * Time: 10:42 PM
 */

namespace WsTest\ws;


class JsonRpcResponse
{
    public $id;
    public $message;
    public $error;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function fromString(string $message)
    {
        $p = json_decode($message, true);
        if(!is_array($p)) return $this;
        $this->id = $p['id'];
        if(array_key_exists('message', $p)) $this->message = $p['message'];
        if(array_key_exists('error', $p)) $this->error = $p['error'];
        return $this;
    }

    public function fromError(string $message): JsonRpcResponse
    {
        $this->message = null;
        $this->error = $message;
        return $this;
    }

    public function fromMessage($message): JsonRpcResponse
    {
        $this->error = null;
        $this->message = $message;
        return $this;
    }

    public function stringify(): string
    {
        $data = [
            "id" => $this->id,
            "jsonrpc" => "2.0"
        ];
        if ($this->message) $data['message'] = $this->message;
        if ($this->error) $data['error'] = $this->error;
        return json_encode($data);
    }
}