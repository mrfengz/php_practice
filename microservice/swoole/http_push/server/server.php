<?php
require_once __DIR__ . '/../../Application/Autoload/Loader.php';

// kill -USR1 主进程PID    # 平稳启动所有worker进程
// kill -USR2 主进程PID    # 平稳启动所有task进程
// $serv = new Swoole\Server('0.0.0.0', 9501, SWOOLE_PROCESS);
$serv = new Swoole\WebSocket\Server('0.0.0.0', 9501);   //继承了swoole\Server和swoole\Http\Server()

$serv->set(array(
    'worker_num' => 1,  //worker进程数
    'task_worker_num' => 2, // task worker 进程
    'max_wait_time' => 60,  //进程reload时，最大等待时间
    'reload_async' => true, //异步重启，新启动一个worker进程，旧的等到事件处理完毕后才会退出，更安全
));

// websocket 可以设置onHandshake回调，设置后不会调用onOpen()
$serv->on('open', function (Swoole\WebSocket\Server $server, \Swoole\Http\Request $request) {
    // print_r($request);
    echo "server: handshake success with fd{$request->fd}\n";
});

$serv->on('message', function (Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

$serv->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});


// http onRequest
$serv->on('request', function(\Swoole\Http\Request $request, \Swoole\Http\Response $response){
    if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
        $response->end();
        return;
    }

    global $serv;
    (new \controller\Controller())->runAction($request, $response, $serv);
    // (new $controller($request, $response, $serv))->$action();
    return ;
    global $serv;//调用外部的server
    // $server->connections 遍历所有websocket连接用户的fd，给所有用户推送
    foreach ($serv->connections as $fd) {
        // 需要先判断是否是正确的websocket连接，否则有可能会push失败
        if ($serv->isEstablished($fd)) {
            echo 'websocket';
            $serv->push($fd, $request->get['message']);
        } else {
            print_r($request);
            $response->end("hello world");
        }
    }
});

// 启动worker时，引入类文件
$serv->on('workerStart', function(\Swoole\Server $serv, $workerId){
    // 通过 $serv->taskWorker 判断为 worker进程还是task进程
    global $argv;
    if ($workerId > $serv->setting['worker_num']) {
        swoole_set_process_name("php {$argv[0]} task worker");
    } else {
        swoole_set_process_name("php {$argv[0]} event worker");
    }
    // 每个worker都会载入一份
    if ($serv->taskworker) {
        // 载入task相关文件
        require_once __DIR__ . '/../task/Task.php';
    } else {
        // 载入
        require_once __DIR__ . '/../controller/Controller.php';
    }


    if ($workerId == 0) {
        // 定时器等
    }
});

// task
$serv->on('task', function(Swoole\Server $serv, $taskId, $srcWorkerId, $data){
    $data = json_decode($data, true);
    return (new \task\Task($data))->run();
});

// task finish
$serv->on('finish', function(\Swoole\Server $serv, $taskId, $data){
    var_dump($data);
});


// 接受消息
$serv->on('receive', function (Swoole\Server $serv, $fd, $reactor_id, $data) {
    echo "[#" . $serv->worker_id . "]\tClient[$fd] receive data: $data\n";
    Swoole\Timer::tick(5000, function () {
        echo 'tick';
    });
});

// 启动一个master进程（会有refactor线程，用于接收tcp连接，转发给worker进程处理），一个manager进程（用于管理worker进程的重启、启动等）
// + 指定数量的worker进程
// + 指定数量的task worker进程
$serv->start();