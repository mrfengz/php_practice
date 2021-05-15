<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/5/6
 * Time: 18:58
 */

$poolNum = 5;

$pool = new \Swoole\Process\Pool($poolNum);

$pool->on('workerStart', function($pool, $workerId){
    echo "worker#{$workerId} is started\n";
    $redis = new Redis();
    $redis->pconnect('127.0.0.1', 6379);
    $redis->auth('august');
    $key = 'key1';
    while(1) {
        $msg = $redis->brPop($key, 2);
        if ($msg == null) continue;

        var_dump($msg);
        echo "process by worker#{$workerId}\n";
    }
});

$pool->on('workerStop', function($pool, $workerId){
    echo "worker#{$workerId} is stopped\n";

});

$pool->start();