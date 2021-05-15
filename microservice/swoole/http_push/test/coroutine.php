<?php
use Swoole\Coroutine;

// 协程相当于yield操作，所以end最后输出。
// 按照顺序，遇到子协程的话，会执行并等待子协程执行完毕，如果子协程中有yield操作，则会发生协程调度，执行外层的协程。

\Co\run(function () {
    go(function () {
        go(function () {
            // echo Co::getPcid() . " 1\n";
            Co::sleep(0.001);
            var_dump(Co::exists(Co::getPcid())); // 1: true
        });
        go(function () {
            // echo Co::getPcid() . " 2\n";
            Co::sleep(0.003);
            var_dump(Co::exists(Co::getPcid())); // 3: false
        });
        // echo Co::getPcid() . " 3\n";
        Co::sleep(0.002);
        var_dump(Co::exists(Co::getPcid())); // 2: false
    });
});
die;

echo "main start\n";

Coroutine\run(function(){
    echo "（父）coro: " . Coroutine::getCid() . "start \n";
    Coroutine::create(function(){
        echo "（子）coro1: " . Coroutine::getCid() . "start \n";
        Coroutine::sleep(.2);
        echo "（子）coro1: " . Coroutine::getCid() . "end \n";
    });
    echo "（父）coro: " . Coroutine::getCid() . "不等待子协程了 \n";
    Coroutine::sleep(.5);
    echo "（父）coro: " . Coroutine::getCid() . "end \n";
});

echo "end\n";
// output
// main start
// （父）coro: 1start
// （子）coro1: 2start
// （父）coro: 1不等待子协程了
// （父）coro: 1end
// （子）coro1: 2end
// end