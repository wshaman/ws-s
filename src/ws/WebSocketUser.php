<?php
/**
 * Based on:
 * Created by ghedipunk
 * https://github.com/ghedipunk/PHP-Websockets
 */

namespace WsTest\ws;


class WebSocketUser {
    public $socket;
    public $id;
    public $task_id;
    public $user_id;
    public $headers = array();
    public $handshake = false;
    public $handlingPartialPacket = false;
    public $partialBuffer = "";
    public $sendingContinuous = false;
    public $partialMessage = "";

    public $hasSentClose = false;

    function __construct($id, $socket) {
        $this->id = $id;
        $this->socket = $socket;
    }
}