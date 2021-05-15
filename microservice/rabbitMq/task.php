<?php
/**
 * 分发任务
 */
$exchangeName = 'task';
$queueName = 'worker';
$routeKey = 'worker';
$message = empty($argv[1]) ? 'hello world' : $argv[1];

//建立tcp连接
$connection = new AMQPConnection([
    'host' => 'localhost',
    'port' => '5672',
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest',
]);
$connection->connect() or die("Cannot connect to the broker\n");

try {
    $channel = new AMQPChannel($connection);

    $exchange = new AMQPExchange($channel);
    $exchange->setName($exchangeName);
    // $exchange->setFlags(AMQP_DURABLE);   //开启这个会报错
    $exchange->setType(AMQP_EX_TYPE_DIRECT);
    $exchange->declareExchange();

    $exchange->publish($message, $routeKey);
    echo "Message is sent: ", $message , "\n";
} catch (AMQPException $e) {
    var_dump($e);
}

$connection->disconnect();