<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/1/28
 * Time: 17:26
 */

define('LIB', __DIR__ . '/lib/');
include_once(LIB . 'Curl.php');
include_once(LIB . 'Robot.php');
include_once('WebController.php');

// print_r((new Robot(['access_token' => 'c24ce2403084ff7c70fe13e1f70522adf760abd196b84824b39565c7c51ac1e2']))->sendText("hello, you"));
// die;
//获取股票数据
// $stockCodeList = ['sz000423' => '东阿阿胶', 'sz002294' => '信立泰', 'sz002430' => '杭氧股份', 'sh600882' => '妙可蓝多', 'sh601088' => '中国神华', 'sz002726' => '龙大肉食', 'sz002919' => '名臣健康', 'sh600970' => '中材国际']; //sh601003,sh601001
// $url = 'http://hq.sinajs.cn/list=' . join(',', array_keys($stockCodeList));
// $res = Curl::httpRequest($url);
// var_dump($res);die;
// $res = formatResult($res);
// var_dump($res);die;
// $_data = [];
// foreach ($stockCodeList as $code => $name) {
//     $_data[] = $name . " current price is: " . ($res[$code] ? $res[$code][3] : 'null');
// }



// $response = 'var hq_str_sh601003=",5.560,5.560,5.540,5.680,5.480,5.540,5.550,5879916,32733728.000,21500,5.540,12300,5.530,25900,5.520,5800,5.510,30200,5.500,2300,5.550,12720,5.560,18100,5.570,16200,5.580,11000,5.590,2021-02-01,11:30:00,00,";
// var hq_str_sh601001="úҵ,4.700,4.690,4.700,4.760,4.640,4.690,4.700,7373400,34699643.000,34600,4.690,94800,4.680,79000,4.670,99700,4.660,99100,4.650,52500,4.700,102000,4.710,19234,4.720,107700,4.730,145000,4.740,2021-02-01,11:30:00,00,"';

function formatResult(string $val) {
    $parts = [];
    foreach (explode(';', $val) as $v) {
        if (!trim($v)) continue;    //分割后，$v可能为带空格的字符串
        $tmp = explode('=', $v);
        $code = substr($tmp[0], -8);    //股票代码信息
        $info = $tmp[1];    //接口返回数据信息
        preg_match('/"(([^"]*)+)"/', $info, $matched);
        if ($matched && $matched[1]) {
            $parts[$code] = explode(',', $matched[1]);
            // $currentPrice = $parts[3];
            // Array
            // (
            //     [0] => úҵ    //股票名称
            //     [1] => 4.700 //开盘价
            //     [2] => 4.690 //收盘价
            //     [3] => 4.700 //当前价格
            //     [4] => 4.760 //当天最高价格
            //     [5] => 4.640 //当天最低价格
            //     [6] => 4.690 //竞买价， “买一”
            //     [7] => 4.700 //竞卖价，“卖一”
            //     [8] => 7373400   //成交股票数，除以100位手数（一手=100股）
            //     [9] => 34699643.000  //成交金额，单位元
            //     [10] => 34600    //买一股数
            //     [11] => 4.690    //买一价格
            //     [12] => 94800    //买二
            //     [13] => 4.680
            //     [14] => 79000    //买三
            //     [15] => 4.670
            //     [16] => 99700    //买四
            //     [17] => 4.660
            //     [18] => 99100    //买五
            //     [19] => 4.650
            //     [20] => 52500    //卖一
            //     [21] => 4.700
            //     [22] => 102000   //卖二
            //     [23] => 4.710
            //     [24] => 19234    //卖三
            //     [25] => 4.720
            //     [26] => 107700   //卖4
            //     [27] => 4.730
            //     [28] => 145000   //卖5
            //     [29] => 4.740
            //     [30] => 2021-02-01   //日期
            //     [31] => 11:30:00
            //     [32] => 00   //unknown
            //     [33] =>      //unknown
            // )
        }

    }
    return $parts;
}

//创建WebSocket Server对象，监听0.0.0.0:9502端口
$ws = new Swoole\WebSocket\Server('0.0.0.0', 8888);

$ws->mydata = [];

// 监听80端口
$ws->addlistener('127.0.0.1', 8080, SWOOLE_SOCK_TCP);

$ws->set([
    'max_request' => 2,
    'worker_num' => 2,
    // 'enable_coroutine' => true,
]);

$ws->on('connect', function($ws, $fd){
    echo "Client:Connect.\n";
});

// 用户登录，然后返回用户登录的session或者token，后续请求根据token判断用户，查询数据库，获取设置，进行通知
$ws->on('request', function(\Swoole\Http\Request $request, \Swoole\Http\Response $response){
    $pathInfo = $request->server['path_info'];
    //如果请求 favicon.ico，直接返回
    if ($pathInfo == '/favicon.ico' || $pathInfo == '/favicon.ico') {
        $response->end();
        return;
    }
    // if ($pathInfo)
    // print_r($request);
    // $requestManager = new WebController($request);
    // $res = $requestManager->execute();
    // $response->end(json_encode($res));
});

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    // var_dump($request->fd, $request->server);
    $ws->push($request->fd, "hello, welcome2\n");
});

// \Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL | SWOOLE_HOOK_CURL);
// go(function()use($ws){
//     \Swoole\Coroutine::sleep(1);
//     echo time() . "\n";
// });

$ws->on('workerStart', function($ws, $workerId){
    if ($workerId == 0) {
        $ws->tick(5000, function()use($ws){
            foreach ($ws->connections as $fd) {
                $connectionInfo = $ws->getClientInfo($fd);
                // http请求的fd,不是实时剔除的，所以这里可能会出现http的连接，无法使用push(),会报错，不是websocket client
                if (!$connectionInfo['websocket_status']) {
                    continue;   //非socket请求，跳过
                }
                $stockCodeList = [
                    'sz002013' => '中航机电',
                    'sz000426' => '兴业矿业',
                    'sh600588' => '用友网络',
                    'sz002781' => '奇信股份',
                    'sz000423' => '东阿阿胶',
                    'sz000903' => '云内动力',
                    // 'sz002294' => '信立泰',
                    'sz002230' => '科大讯飞',
                    'sh600009' => '上海机场',
                    // 'sz002430' => '杭氧股份',
                    // 'sh600882' => '妙可蓝多',
                    // 'sz002919' => '名臣健康',
                    // 'sz002493' => '荣盛石化',
                    // 'sz000703' => '恒逸石化',
                    'sz000063' => '中兴通讯',
                    // 'sh601088' => '中国神华',
                    // 'sz002726' => '龙大肉食',
                    // 'sh603103' => '横店影视',
                    // 'sz002402' => '和而泰',
                    // 'sz002555' => '三七互娱',
                    // 'sz002867' => '周大生',
                    // 'sh600305' => '恒顺醋业',
                    // 'sh603298' => '杭叉集团',
                    // 'sz000338' => '潍柴动力',
                    // 'sz002884' => '凌霄泵业',
                    // 'sh603890' => '春秋电子',
                    // 'sh601919' => '中远海控',
                    // 'sh600970' => '中材国际',
                    // 'sh603708' => '家家悦',
                    // 'sz002695' => '煌上煌',
                    // 'sh600593' => '大连亚圣',
                    // 'sh600875' => '东方电气',
                    // 'sz000725' => '京东方A',
                    // 'sh600674' => '川投能源',
                    // 'sh600379' => '宝光股份',
                    // 'sh600337' => '美克家居',
                    // 'sz000987' => '越秀金控',
                    /*, 'sh600970' => '中材国际'*/]; //sh601003,sh601001
                $url = 'http://hq.sinajs.cn/list=' . join(',', array_keys($stockCodeList));
                $res = Curl::httpRequest($url);
                $res = formatResult($res);
                if (!$res) continue;
                $_data = [];
                foreach ($stockCodeList as $code => $name) {
                    $_data[] = $name . ": " . ($res[$code] ? $res[$code][3] : 'null');
                }
                $data = json_encode(['time' => date('H:i:s'), 'price' => $_data], JSON_UNESCAPED_UNICODE);
                // todo 根据登录用户，查询对应的配置通知条件，然后获取对应的数据，通知对应的用户
                $ws->push($fd, $data);
            }

        });
    }
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    if (substr($frame->data, 0, 6) === 'token=') {
        $token = substr($frame->data, 6);
        $ws->mydata[$frame->fd] = $token;
    }
    print_r($ws->mydata);
    echo "Message: {$frame->data}\n";
    // print_r($ws);
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

    // print_r($frame);
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
    unset($ws->myData[$fd]);
    echo "client-{$fd} is closed\n";
});

$ws->start();