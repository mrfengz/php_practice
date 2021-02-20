<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/4
 * Time: 16:04
 */

include_once('lib/Db.php');
include_once('lib/Helper.php');
include_once('lib/Robot.php');

// var_dump(Helper::getToken('fz123456'));die;
$db = Db::getInstance();
$username = 'august';
$password = 'fz123456';

print_r($db->fetchAll("show tables;"));
die;
$token = 'baba89017db5573a';

$user = $db->fetchOne("SELECT * FROM `user` WHERE token=". $db->quote($token));
// if (!$user) {
//     continue;
// }

$stockCodes = $db->fetchAll("SELECT * FROM `stock` WHERE user_id={$user['id']} AND status = 1");

$codes = [];
foreach ($stockCodes as $row) {
    $_code = $row['stock_type'] . $row['stock_code'];
    if ($row['stock_type'] && $row['stock_code']) {
        $codes[] = $_code;
    }
    $stockCodes[$_code] = $row;
}

// Array
// (
//     [0] => úҵ    //股票名称
//     [1] => 4.700 //开盘价
//     [2] => 4.690 //收盘价
//     [3] => 4.700 //当前价格
//     [4] => 4.760 //当天最高价格
//     [5] => 4.640 //当天最低价格
//     [6] => 4.690 //竞买价， “买一”


$res = [
    'sh00001' => ['上证指数', '4.7', '4.69', '4.77'],
    'sh000036' => ['上证指数233', '4.7', '4.69', '4.77'],
];

$warningData = [];
foreach ($stockCodes as $_code => $config) {
    $info = $res[$_code] ?? [];
    if (!$info) continue;

    if ($config['is_warning'] == 1) {   //预警
        if (!empty($config['day_warning_min']) || !empty($config['day_warning_max'])) {
            $todayChangeRatio = calculatePercent($info[3], $info[1]);
            if ($config['day_warning_min'] && $todayChangeRatio < $config['day_warning_min']) {
                $warningData[] = '止损提醒(当天波动)： '.$info[0] . '当天波动达到设置下线，当前价为' . $info[3];
            }

            if ($config['day_warning_max'] && $todayChangeRatio > $config['day_warning_max']) {
                $warningData[] = '止盈提醒(当天波动)： '.$info[0] . '当天波动达到设置下线，当前价为' . $info[3];
            }
        }
        // 基于成本价预测
        if (empty($config['cost'])) {
            continue;
        } elseif (!empty($config['cost_warning_min']) || !empty($config['cost_warning_max'])) {
            $costChangeRatio = calculatePercent($info[3], $config['cost']);
            if ($config['cost_warning_min'] && $costChangeRatio < $config['cost_warning_min']) {
                $warningData[] = '止损提醒(基于成本价)： '.$info[0] . '当天波动达到设置下线，当前价为' . $info[3];
            }

            if ($config['cost_warning_max'] && $costChangeRatio > $config['cost_warning_max']) {
                $warningData[] = '止盈提醒(基于成本价)： '.$info[0] . '当天波动达到设置下线，当前价为' . $info[3];
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
            $res = $robot->sendText(join("\n", $warningData), $atMobiles);
            print_r($res);
    } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }
}



print_r($warningData);

print_r($user);die;



if (!$user || !password_verify($password, $user['password'])) {
    return Helper::returnData('用户名或密码错误');
}
$token = Helper::getToken(mb_substr($username, 0, 1) . 'CA' .  substr($password,2) . '$');
$res = $db->execute("UPDATE user SET token='{$token}' WHERE id=" . intval($user['id']));
if ($res) {
    echo 11;
    return  Helper::returnData('登录成功', 0, ['token' => $token]);
} else {
    echo 33;
    return  Helper::returnData('登录失败，请联系管理员', 1);
}

print_r($res);die;
$password = password_hash('fz123456', PASSWORD_DEFAULT);
$time = time();
// $afftectedRows = $db->execute("INSERT INTO `user`(username, password, status, create_time) VALUE('august', '{$password}', 1, {$time})");
var_dump($db->getError());
// var_dump($afftectedRows);
