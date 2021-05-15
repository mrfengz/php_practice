<?php
/* 原理 */
// 所有的编码和之前编写同步代码时并没有任何不同，但是 Swoole 底层会在 IO 事件发生时，保存当前状态，将程序控制权交出，以便 CPU 处理其它事件，
// 当 IO 事件完成时恢复并继续执行后续逻辑，从而实现异步 IO 的功能，
// 这正是协程的强大之处，它可以让服务器同时可以处理更多请求，而不会阻塞在这里等待 IO 事件处理完成，从而极大提高系统的并发性。

/* 使用场景 */
// 高并发服务，如秒杀系统、高性能 API 接口、RPC 服务器，使用协程模式，服务的容错率会大大增加，某些接口出现故障时，不会导致整个服务崩溃；
// 爬虫，可实现非常强大的并发能力，即使是非常慢速的网络环境，也可以高效地利用带宽；
// 即时通信服务，如 IM 聊天、游戏服务器、物联网、消息服务器等等，可以确保消息通信完全无阻塞，每个消息包均可即时地被处理。


/* 注意事项 */
// 1. 每次只有一个协程可以运行
// 2.协程之间通讯，不要使用全局变量或者引入外部变量，应该使用channel
// 3.不要使用全局变量、类静态变量保存协程上下文，可能多个协程运行，导致变量被污染，使用context管理上下文。
// 4.多个协程共用一个客户端连接的话，也可能导致数据错乱
// 5.异常处理， try{}catch()捕捉，不能跨协程。使用ExitException
ini_set('assert.exception', 1);
assert('1>6');
var_dump(ini_get('assert.exception'));
var_dump(234);
die;

// go函数创建一个协程
go(function(){
    // 协程内的代码仍然是按照顺序执行的，遇到io,会自动让出cpu的使用权，执行其他代码。
    $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
    // 尝试与指定的tcp服务端建立连接，触发io事件切换协程，交出控制权，让cpu去处理其他事情
    if ($client->connect('127.0.0.1', 9505)) {
        $client->send("hello world\n");
        // 恢复协程，继续处理后续代码
        echo $client->recv(3);
        $client->close();
    } else {
        echo "Connection failed\n";
    }

    echo "I'm here\n";
});


echo "last but execute first\n";
