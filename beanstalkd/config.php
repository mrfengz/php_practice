<?php
defined('CANCEL_ORDER') or define('CANCEL_ORDER', 'order_to_pay');
$config =  [
    'beanstalkd' => [
        'host' => '127.0.0.1',
        'port' => 11300,
    ],
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'beanstalk',
        'username' => 'root',
        'password' => 'root'
    ],
    'cancel_order' => [
        'pay_in_seconds' => 20, #等待支付时间 秒
        'ttl' => 60,            #取消订单业务处理时间限制，超过后job任务重新变为ready状态
    ],
];