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
include_once(LIB . 'Db.php');

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
$ws = new Swoole\WebSocket\Server('0.0.0.0', 8890);

$redis = new Redis();
$redis->pconnect('127.0.0.1');
$redis->select(1);


$ws->set([
    'max_request' => 2,
    'worker_num' => 2,
    'enable_coroutine' => true,
]);

$ws->on('connect', function($ws, $fd){
    echo "Client:Connect.\n";
});

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    $ws->push($request->fd, "hello, welcome\n");
});

// 计算波动百分比
function calculatePercent($currentPrice, $cost) {
    $diff = $currentPrice - $cost;
    if (empty($cost)) {
        return 0;
    }
    return 100 * ($diff / $cost);
}


$ws->on('workerStart', function($ws, $workerId)use($redis){
    if ($workerId == 0) {
        $ws->tick(5000, function()use($ws,$redis){
            foreach ($ws->connections as $fd) {
                if (!$redis->hExists('fd-token', $fd)) {
                    continue;
                }

                $db = Db::getInstance();
                $userToken = $redis->hGet('fd-token', $fd);

                // echo $userToken . "\n";

                $connectionInfo = $ws->getClientInfo($fd);
                // http请求的fd,不是实时剔除的，所以这里可能会出现http的连接，无法使用push(),会报错，不是websocket client
                if (!$connectionInfo['websocket_status']) {
                    continue;   //非socket请求，跳过
                }

                $user = $db->fetchOne("SELECT * FROM `user` WHERE token=". $db->quote($userToken));
                if (!$user) {
                    continue;
                }

                $stockCodes = $db->fetchAll("SELECT * FROM `stock` WHERE user_id={$user['id']} AND status = 1");
                $codes = [];
                foreach ($stockCodes as $row) {
                    $_code = $row['stock_type'] . $row['stock_code'];
                    if ($row['stock_type'] && $row['stock_code']) {
                        $codes[] = $_code;
                    }
                    $stockCodes[$_code] = $row;
                }

                // echo join(',', $codes) . "\n";

                $url = 'http://hq.sinajs.cn/list=' . join(',', $codes);
                $res = Curl::httpRequest($url);
                $res = formatResult($res);

                // print_r($res);

                if (!$res) continue;
                $_data = ['当前时间' . date('H:i:s')];
                foreach ($codes as $code) {
                    if (!$res[$code]) continue;
                    $tmp = $res[$code];
                    $name = $tmp[0] ?: $code;
                    $_data[] = $name . ": " . ($tmp[3] ?? 'null');
                }
                // socket 实时展示 5秒间隔
                $ws->push($fd, join("\n", $_data));

                // continue;
                // 波动条件预警
                $warningData = [];
                foreach ($stockCodes as $_code => $config) {
                    $info = $res[$_code] ?? [];
                    if (!$info) continue;

                    if ($config['is_warning'] == 1) {   //预警
                        if (!empty($config['day_warning_min']) || !empty($config['day_warning_max'])) {
                            $todayChangeRatio = calculatePercent($info[3], $info[1]);
                            if ($config['day_warning_min'] && $todayChangeRatio < $config['day_warning_min']) {
                                $warningData[] = '止损提醒(开盘价:'. $info[1] .')： '.$info[0] . '达到设置下线，当前价为' . $info[3];
                            }

                            if ($config['day_warning_max'] && $todayChangeRatio > $config['day_warning_max']) {
                                $warningData[] = '止盈提醒(开盘价:'. $info[1] .')： '.$info[0] . '达到设置上线，当前价为' . $info[3];
                            }
                        }
                        // 基于成本价预测
                        if (empty($config['cost'])) {
                            continue;
                        } elseif (!empty($config['cost_warning_min']) || !empty($config['cost_warning_max'])) {
                            $costChangeRatio = calculatePercent($info[3], $config['cost']);
                            if ($config['cost_warning_min'] && $costChangeRatio < $config['cost_warning_min']) {
                                $warningData[] = '止损提醒(成本:'.$config['cost'].')： '.$info[0] . '达到设置下线，当前价为' . $info[3];
                            }

                            if ($config['cost_warning_max'] && $costChangeRatio > $config['cost_warning_max']) {
                                $warningData[] = '止盈提醒(成本' . $config['cost'] . ')： '.$info[0] . '达到设置上线，当前价为' . $info[3];
                            }
                        }
                    }
                }


                if ($warningData) {
                    foreach ($db->fetchAll("SELECT * FROM `push_setting` WHERE user_id={$user['id']} AND status=1") as $row) {
                        try {
                            $robot = new Robot([
                                'access_token' => $row['token'],
                                'secret' => $row['secret'] ?: '',
                            ]);
                            $atMobiles = $row['at_mobiles'] ? explode(',', $row['at_mobiles']) : [];
                            $res = json_decode($robot->sendText(join("\n", $warningData), $atMobiles), true);
                            // print_r($res);
                            if ($res['errcode']) {
                                echo "api出错：" . $res['errmsg'] . "\n";
                            }
                        } catch(\Exception $e) {
                            echo '推送出错：'.$e->getMessage() . "\n";
                        }
                    }
                }


                $stockCodeList = [
                    'sz002294' => '信立泰',
                    // 'sz002430' => '杭氧股份',
                    'sh600882' => '妙可蓝多',
                    'sz002919' => '名臣健康',
                    'sz002493' => '荣盛石化',
                    // 'sh601088' => '中国神华',
                    'sz002726' => '龙大肉食',
                    // 'sz000338' => '潍柴动力',
                    'sh600009' => '上海机场',
                    // 'sh603103' => '横店影视',
                    // 'sz002402' => '和而泰',
                    // 'sz000426' => '兴业矿业',
                    // 'sz000987' => '越秀金控',
                    // 'sh600875' => '东方电气',
                    // 'sz002867' => '周大生',
                    // 'sz002695' => '煌上煌',
                    'sh600305' => '恒顺醋业',
                    // 'sz000423' => '东阿阿胶',
                    /*, 'sh600970' => '中材国际'*/]; //sh601003,sh601001

            }

        });
    }
});

$ws->on('workerExit', function($ws){
    Swoole\Timer::clearAll();
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) use($redis) {
    if (substr($frame->data, 0, 6) === 'token=') {
        $token = substr($frame->data, 6);
        // $ws->myData[$frame->fd] = $token;
        $redis->hSet('fd-token', $frame->fd, $token);
    }
    // print_r($ws->myData);
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
$ws->on('close', function ($ws, $fd)use($redis) {
    // unset($ws->myData[$fd]);
    $redis->hDel('fd-token', $fd);
    echo "client-{$fd} is closed\n";
});

$ws->start();