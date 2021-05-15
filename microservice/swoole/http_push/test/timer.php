<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/5/6
 * Time: 16:10
 */

// echo microtime(true), "\n";
//
// \Swoole\Event::defer(function () {
//     echo microtime(true), "\n";
// });

// $counter = 1;
// $timerId = \Swoole\Timer::tick(500, function($nowId/*, $counter*/){
//     global $counter;
//     var_dump($counter);
//     echo microtime(true), "\n";
//     ++$counter;
//     if ($counter > 101) {
//         \Swoole\Timer::clear($nowId);
//     }
// }/*, $counter*/);
//
// var_dump($timerId);


\Swoole\Timer::after(1000, function(){
   echo "会来这里，但是不执行\n";
});

echo "来得早不如来得巧\n";