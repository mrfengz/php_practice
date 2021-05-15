var heartCheck = {
    timeout: 30,
    serverTimeoutObj: null,
    reset: function(){
        clearTimeout(this.serverTimeoutObj);
        this.start();
        return this;
    },
    start: function(){
        var self = this;
        this.serverTimeoutObj = setInterval(function(){
            if (websocket.readyState == 1) {
                console.log('连接正常，发送消息ping');
                websocket.send('ping');
            } else {
                console.log('断开连接，尝试重连');
                newWebSocket();
            }
        },  this.timeout);
    }
}