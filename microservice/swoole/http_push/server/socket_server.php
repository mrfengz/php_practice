<?php

use Swoole\WebSocket\Server;

class SocketServer
{
    private $ws;

    public function __construct()
    {
    }

    public function run()
    {
        $this->ws = new Server('127.0.0.1', 9100);
        $this->ws->set([
            'heartbeat_idle_time'      => 7, // 表示一个连接如果600秒内未向服务器发送任何数据，此连接将被强制关闭
            'heartbeat_check_interval' => 3,  // 表示每60秒遍历一次
            'open_eof_check' => true,   //打开EOF检测
            'package_eof'    => "\r\n", //设置EOF
        ]);
        $this->ws->on('open', function (Server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $this->ws->on('message', function (Server $server, $frame) {
            echo "new receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish} ", time() ,"\n";
            foreach ($server->connections as $fd) {
                if (!$server->isEstablished($fd)) continue;
                // 判断是否为websocket链接
                $server->push($frame->fd, "this is server");
            }
        });

        $this->ws->on('close', function ($serv, $fd) {
            echo "new client {$fd} closed ", time(), "\n";
        });

        $this->ws->start();
    }
}

(new SocketServer())->run();