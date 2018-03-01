<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/28/18
 * Time: 10:26 PM
 */

namespace WsTest\ws;


class JsonRpcMessage
{
    public $method;
    public $params;
    public $id;

    public function fromArray(array $params): JsonRpcMessage
    {
        $this->method = $params["method"];
        $this->params = $params["params"];
        $this->id = $params["id"];
        return $this;
    }

    public function fromString(string $data): JsonRpcMessage
    {
        $json = json_decode($data, true);
        return $this->fromArray($json);
    }

    public function stringify(): string
    {
        return json_encode([
            "id" => $this->id,
            "method" => $this->method,
            "params" => $this->params,
            "jsonrpc" => "2.0"
        ]);
    }


}