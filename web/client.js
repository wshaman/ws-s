var Settings = {
    host: 'ws://127.0.0.1:9009',
    waitForSocketTimeout: 5,
    waitForResponseTimeout: 500,
    waitForResponseMaxTries: 500,
    socket: null
};

var Client = {
    personal: {
        user_id: 0,
        task_id: 0
    },
    messages: [],
    socket: null,
    init: function (user_id, task_id) {
        var self = this;
        self.personal.user_id = user_id;
        self.personal.task_id = task_id;
        try {
            self.socket = new WebSocket(Settings.host);
            self._log.info("WebSocket - status " + self.socket.readyState);
            self.socket.onopen = self._log.info("Welcome - status " + self.socket.readyState);
            self.socket.onmessage = self.received;
            self.socket.onclose = self._log.info("Disconnected - status " + self.socket.readyState);
            self.request.send('register', [self.personal.user_id, self.personal.task_id], function (message) {
                self._log.info(message.data)
            })
        }
        catch (ex) {
            self._log.error(ex);
        }
    },
    setOnReceived: function(callback){
        this.socket.onmessage = callback;
    },
    request: {
        _waitForResponce: function(id, callback){
            return true;
            //@nb: should be simplified for this use-case. We have no need to catch this on JS side at all.
            var self = Client;
            setTimeout(
                function () {
                    if (self.messages[id].answer) {
                        callback(self.messages[id].answer);
                        // self.messages[id] = null;
                        return true;
                    } else {
                        self.request._waitForResponce(id, callback);
                    }
                }, Settings.waitForResponseTimeout);
        },
        send: function (method, params, callback) {
            var jsonId, jsonData;
            jsonId = random(4096);
            jsonData = {
                jsonrpc: "2.0",
                id: jsonId,
                method: method,
                params: params
            };
            Client.messages[jsonId] = {
                id: jsonId,
                question: jsonData,
                answer: null
            };
            Client._send(JSON.stringify(jsonData));
            this._waitForResponce(jsonId, callback)
        }
    },
    received: function (message) {
        // @nb: no need to make magic here after initial request. Should be removed?
        console.log("Received: " + message.data);
        // this. = message.data;
    },
    _log: {
        log: function (message) {
            message = "Client " + Client.personal.user_id + " says: " + message;
            console.log(message);
        },
        info: function (message) {
            this.log(message);
        },
        error: function (message) {
            this.log(message);
            console.log(Error().stack);
        }
    },
    _send: function (message) {
        var self = this;
        this.__waitForSocketConnection(function () {
            try {
                self.socket.send(message);
                self._log.log('Sent: ' + message);
            } catch (ex) {
                self._log.error(ex);
            }
        });
    },
    __waitForSocketConnection: function (callback) {
        var self = this;
        setTimeout(
            function () {
                if (self.socket.readyState === 1) {
                    self._log.info("Connection established");
                    if (callback != null) {
                        callback();
                    }
                    return true;

                } else {
                    self._log.info("Connection is pending");
                    self.__waitForSocketConnection(callback);
                }

            }, Settings.waitForSocketTimeout);
    }
};

function random(max) {
    return Math.floor(Math.random()*max);
}