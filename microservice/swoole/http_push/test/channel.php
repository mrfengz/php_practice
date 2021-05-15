<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/4/9
 * Time: 15:31
 */
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;
use function Swoole\Coroutine\run;

run(function(){
    $channel = new Channel(2);
    Coroutine::create(function () use ($channel) {
        for($i = 0; $i < 2; $i++) {
            Coroutine::sleep(1.0);
            $channel->push(['rand' => rand(1000, 9999), 'index' => $i]);
            echo "{$i}\n";
        }
    });
    // 协程挂起后，会继续执行，而不是停留下来（跟yield还不太一样）
    var_dump('middle');
    Coroutine::create(function () use ($channel) {
        while(1) {
            $data = $channel->pop(20.0);
            if ($data) {
                var_dump($data);
            } else {
                assert($channel->errCode === SWOOLE_CHANNEL_TIMEOUT);
                break;
            }
        }
    });
    var_dump('end');
});