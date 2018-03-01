<?php
/**
 * Created by PhpStorm.
 * User: wshaman
 * Date: 2/28/18
 * Time: 10:26 PM
 */

namespace WsTest\ws;


class JsonRpcObject
{
    public $method;
    public $params;
    public $id;

    public function fromArray(array $params) : JsonRpcObject
    {
        $this->method = $params['method'];
        $this->params = $params['params'];
        $this->id = $params['id'];
        return $this;
    }

    public function fromString(string $data) : JsonRpcObject
    {
        $json = json_decode($data, true);
        return $this->fromArray($json);
    }


}