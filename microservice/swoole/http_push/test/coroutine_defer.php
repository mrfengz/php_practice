<?php
// setDefer作用机制
// 将响应式的接口拆分为两个步骤：先发送数据，再并发收取响应结果
// 大多数情况下，建立连接和发送数据的耗时<<<等待响应耗时的时间，所以可以简单理解为多个客户端的响应是并发的。
$serv = new \Swoole\Http\Server('127.0.0.1', 9505);

$serv->on('request', function ($request, $response) {
    var_dump(time());

    $mysql = new Swoole\Coroutine\MySQL();
    $mysql->connect([
        'host' => '127.0.0.1',
        'user' => 'august',
        'password' => 'fz123456',
        'database' => 'example_db',
    ]);

    $mysql->setDefer();
    $mysql->query('select sleep(3)');

    var_dump(time());
    $redis1 = new \Swoole\Coroutine\Redis();
    $redis1->connect('127.0.0.1', 6379);
    $redis1->auth('august');
    $redis1->setDefer();
    $redis1->set('hello', 'world');

    var_dump(time());

    $redis2 = new Swoole\Coroutine\Redis();
    $redis2->connect('127.0.0.1', 6379);
    $redis2->auth('august');
    $redis2->setDefer();
    $redis2->get('hello');

    $result1 = $mysql->recv();
    $result2 = $redis1->recv();
    $result3 = $redis2->recv();
    var_dump($result1, $result2, $result3, time());

    $response->end("request finish: " . time());
});

$serv->start();