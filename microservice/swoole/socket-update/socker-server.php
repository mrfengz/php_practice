<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/1/28
 * Time: 17:26
 */

//创建WebSocket Server对象，监听0.0.0.0:9502端口
$ws = new Swoole\WebSocket\Server('0.0.0.0', 8888);

$ws->set([
    'max_request' => 2,
    'worker_num' => 2
]);

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    var_dump($request->fd, $request->server);
    $ws->push($request->fd, "hello, welcome\n");
});

$ws->on('workerStart', function($ws, $workerId){
    if ($workerId == 0) {
        $ws->tick(5000, function()use($ws){
            foreach ($ws->connections as $fd) {
                $ws->push($fd, json_encode(['time' => time(), 'total' => mt_rand(1, 100000)]));
            }
        });
    }
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    echo "Message: {$frame->data}\n";
    print_r($ws);
//     Swoole\WebSocket\Server Object
//     (
//         [onStart:Swoole\Server:private] =>
    //     [onShutdown:Swoole\Server:private] =>
    //     [onWorkerStart:Swoole\Server:private] =>
    //     [onWorkerStop:Swoole\Server:private] =>
    //     [onBeforeReload:Swoole\Server:private] =>
    //     [onAfterReload:Swoole\Server:private] =>
    //     [onWorkerExit:Swoole\Server:private] =>
    //     [onWorkerError:Swoole\Server:private] =>
    //     [onTask:Swoole\Server:private] =>
    //     [onFinish:Swoole\Server:private] =>
    //     [onManagerStart:Swoole\Server:private] =>
    //     [onManagerStop:Swoole\Server:private] =>
    //     [onPipeMessage:Swoole\Server:private] =>
    //     [setting] => Array
    //     (
    //         [open_http_protocol] => 1
    //             [open_mqtt_protocol] =>
    //             [open_eof_check] =>
    //             [open_length_check] =>
    //             [open_websocket_protocol] => 1
    //             [worker_num] => 1
    //             [task_worker_num] => 0
    //             [output_buffer_size] => 2097152
    //             [max_connection] => 1024
    //         )
    //
    //     [connections] => Swoole\Connection\Iterator Object
    //     (
    //     )
    //
    //     [host] => 0.0.0.0
    //     [port] => 8888
    //     [type] => 1
    //     [mode] => 2
    //     [ports] => Array
    //     (
    //         [0] => Swoole\Server\Port Object
    //     (
    //         [onConnect:Swoole\Server\Port:private] =>
    //                     [onReceive:Swoole\Server\Port:private] =>
    //                     [onClose:Swoole\Server\Port:private] => Closure Object
    //     (
    //         [parameter] => Array
    //     (
    //         [$ws] => <required>
    //                                     [$fd] => <required>
    //                                 )
    //
    //                         )
    //
    //                     [onPacket:Swoole\Server\Port:private] =>
    //                     [onBufferFull:Swoole\Server\Port:private] =>
    //                     [onBufferEmpty:Swoole\Server\Port:private] =>
    //                     [onRequest:Swoole\Server\Port:private] =>
    //                     [onHandShake:Swoole\Server\Port:private] =>
    //                     [onOpen:Swoole\Server\Port:private] => Closure Object
    //     (
    //         [parameter] => Array
    //     (
    //         [$ws] => <required>
    //                                     [$request] => <required>
    //                                 )
    //
    //                         )
    //
    //                     [onMessage:Swoole\Server\Port:private] => Closure Object
    //     (
    //         [parameter] => Array
    //     (
    //         [$ws] => <required>
    //                                     [$frame] => <required>
    //                                 )
    //
    //                         )
    //
    //                     [host] => 0.0.0.0
    //                     [port] => 8888
    //                     [type] => 1
    //                     [sock] => 3
    //                     [setting] =>
    //                     [connections] => Swoole\Connection\Iterator Object
    //     (
    //     )
    //
    //                 )
    //
    //         )
    //
    //     [master_pid] => 93683
    //     [manager_pid] => 93684
    //     [worker_id] => 0
    //     [taskworker] =>
    //     [worker_pid] => 93686
    //     [stats_timer] =>
    // )

    print_r($frame);
//     Swoole\WebSocket\Frame Object
//     (
//         [fd] => 3
//     [data] => hello test
//     [opcode] => 1
//     [flags] => 33
//     [finish] => 1
// )

    // //定时器 这样写有问题，因为$frame会断开连接
    // \Swoole\Timer::tick(1000, function()use($ws, $frame){
    //     if (!$frame->fd) {
    //         return false;
    //     }
    //     $ws->push($frame->fd, "server: {$frame->data} " . time());
    // });
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();