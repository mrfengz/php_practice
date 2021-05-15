<?php
$exchangeName = 'demo';
$queueName = 'hello';
$routeKey = 'hello';

//建立tcp连接
$connection = new AMQPConnection([
    // 'host' => '192.168.1.247',
    // 'port' => '25672',
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
    $exchange->setType(AMQP_EX_TYPE_DIRECT);
    $exchange->declareExchange();

    echo "Exchange Status: " , $exchange->declareExchange(), "\n";
    $queue = new AMQPQueue($channel);
    $queue->setName($queueName);

    echo "Message Total: " , $queue->declareQueue(), "\n";

    echo "Queue Bind: ", $queue->bind($exchangeName, $routeKey);
    var_dump("waiting for message...\n");

    while(true) {
        $queue->consume('processMessage');
    }
} catch (AMQPException $e) {
    var_dump($e);
}

// print_r($connection);

// 断开连接
$connection->disconnect();

function processMessage($envelope, $queue) {
    $msg = $envelope->getBody();
    var_dump("Received: ", $msg);
    $queue->ack($envelope->getDeliveryTag());   //手动发送ack应答
}