<?php

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
require ROOT . 'MyPdo.php';

// $db=new \PDO('mysql:host=127.0.0.1;dbname=test', 'root', 'root');
// // $res =$db->query('show databases;')->fetchAll(PDO::FETCH_ASSOC);
// var_dump($db->query('show tables;')->fetchAll(PDO::FETCH_ASSOC));
// die;

$db = MyPdo::getInstance();
// $res = $db->query('show tables;');
function generateTradeId($time = null)
{
    date_default_timezone_set('Asia/Shanghai');
    if (empty($time)) {
        $time = time();
    }
    $id = date('ymd', $time).substr($time, -5) . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    echo $id;
    return $id;
}
for($i=0; $i<18600; $i++) {
    // status 1：待处理, 2: 处理中 3:处理成功 4:处理失败
    $res = $db->insert(
        'trade_message', [
        'trade_id' => generateTradeId(),
        'create_time' => time(),
        ]
    );
}

// var_dump($res);
