<?php

use Swoole\Process;

/* 同步通信 */
// $process = new Process(function(Process $worker){
//     //子进程逻辑
//     //通过管道从主进程读取数据
//     $cmd = $worker->read();
//     ob_start();
//     //执行外部程序，并显示未经处理的原始输出，会直接打印输出
//     passthru($cmd);
//     $ret = ob_get_clean() ?: '';
//     $ret = trim($ret) . ' worker pid: ' . $worker->pid . "\n";
//     //将数据写入管道
//     $worker->write($ret);
//     $worker->exit(0);   //退出子进程
// }); //如果第二个参数设置为true,可以通过echo写入管道。第三个参数默认为true，表示开启管道
//
// $process->start();
//
// //将主进程数据通过管道发送给子进程
// $process->write('php --version');
//
// //从子进程读取数据并写入
// $msg = $process->read();
// echo "result from worker: {$msg}\n";


/* 异步通信，使用 swoole_event_add */
// $process = new Process(function(Process $worker){
//     swoole_event_add($worker->pipe, function($pipe)use($worker){
//         // 从主进程读取数据
//         $cmd = $worker->read();
//         ob_start();
//         passthru($cmd);
//         $ret = ob_get_clean() ?: '';
//         $ret = trim($ret) . ' worker pid: ' . $worker->pid . "\n";
//         $worker->write($ret);
//         $worker->exit(0);
//     });
//
// //     其他子进程逻辑,这里不会被阻塞，会先执行
//     echo "我要去玩。。。\n";
// });
//
// $process->start();
// $process->write('php --version');
// $msg = $process->read();
// echo "result from worker: {$msg}\n";

/* 使用消息队列 */
$process = new Process(function(Process $worker){
    // 从主进程读取数据
    $cmd = $worker->pop();
    ob_start();
    passthru($cmd);
    $ret = ob_get_clean() ?: '';
    $ret = trim($ret) . ' worker pid: ' . $worker->pid . "\n";
    $worker->push($ret);
    $worker->exit(0);
}, false, false); //关闭管道

// 使用队列通信。
// 队列与管道不能同时使用
// 使用争抢模式时，无法确定那个那个进程抢到，不能指定进程消费
// 消息队列不支持事件循环，使用   IPC_NOWAIT表示非阻塞模式进行通信
$process->useQueue(1, 2 | Process::IPC_NOWAIT);
$process->push('php --version');
$msg = $process->pop();
echo 'message from worker: ' . $msg, "\n";

$process->start();


Process::wait();