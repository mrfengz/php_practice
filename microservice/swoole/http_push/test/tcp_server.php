<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/5/7
 * Time: 10:37
 */

// $fds = [];
//
// $serv = new \Swoole\Server('127.0.0.1', 9505);
// $serv->set([
//     'worker_num' => function_exists('swoole_cpu_num') ? swoole_cpu_num() * 2 : 1,
// ]);
//
// $serv->on('connect', function($serv, $fd){
//     echo 'open connection: ' . $fd, "\n";
//     global $fds;
//     $fds[] = $fd;
//     var_dump($fds);
// });
//
// $serv->on('receive',function($serv, $fd, $data){
//
// });
//
// $serv->start();

class TcpServer
{
    private $serv = [];
    private $fds = [];

    public function __construct()
    {
        $this->start();
    }

    private function start()
    {
        $this->serv = new \Swoole\Server('127.0.0.1', 9505);
        $this->serv->on('connect', function($serv, $fd){
           echo "connection open: {$fd}\n";

           $this->fds[] = $fd;
           var_dump($this->fds);
        });

        $this->serv->on('receive', function($serv, $fd, $fromId, $data){
            // 这里会自动创建一个协程，可以使用协程api
            Swoole\Coroutine::sleep(2);
           echo "{$fd}发送消息 {$data}\n";
           $serv->send($fd, 'server response: ' . $fd . '--' . $data . "\n");
        });

        $this->serv->on('close', function($serv, $fd){
            echo "{$fd}关闭\n";
        });

        $this->serv->start();
    }
}

new TcpServer();