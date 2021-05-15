<?php
// setDefer作用机制
// 将响应式的接口拆分为两个步骤：先发送数据，再并发收取响应结果
// 大多数情况下，建立连接和发送数据的耗时<<<等待响应耗时的时间，所以可以简单理解为多个客户端的响应是并发的。
$serv = new \Swoole\Http\Server('127.0.0.1', 9505);

$serv->on('request', function ($request, $response) {
    $channel = new \Swoole\Coroutine\Channel(3);

    var_dump(time());

    go(function()use($channel){
        $mysql = new Swoole\Coroutine\MySQL();
        $mysql->connect([
            'host' => '127.0.0.1',
            'user' => 'august',
            'password' => 'fz123456',
            'database' => 'example_db',
        ]);

        // $mysql->setDefer();  //协程已经是并发调用了，去掉defer
        $result = $mysql->query('select sleep(3)');
        $channel->push($result);
    });


    var_dump(time());
    go(function ()use($channel) {
        $redis1 = new \Swoole\Coroutine\Redis();
        $redis1->connect('127.0.0.1', 6379);
        $redis1->auth('august');
        // $redis1->setDefer();
        $res = $redis1->set('hello', 'world');
        $channel->push($res);
    });


    var_dump(time());

    go(function ()use($channel){
        $redis2 = new Swoole\Coroutine\Redis();
        $redis2->connect('127.0.0.1', 6379);
        $redis2->auth('august');
        // $redis2->setDefer();
        $res = $redis2->get('hello');
        $channel->push($res);
    });

    $results = [];
    for ($i=0; $i<3; $i++) {
        $results[] = $channel->pop();
    }

    $response->end(json_encode($results));
});

$serv->start();