<?php
$exchangeName = 'demo';
$routeKey = 'hello';
$message = 'hello world';

//建立tcp连接
$connection = new AMQPConnection([
    'host' => '127.0.0.1',
    'port' => '5672',
    'vhost' => '/',
    'login' => 'admin',
    'password' => 'admin',
]);

$connection->connect() or die("Cannot connect to the broker\n");

try {
    $channel = new AMQPChannel($connection);

    $exchange = new AMQPExchange($channel);
    $exchange->setName($exchangeName);
    $exchange->setType(AMQP_EX_TYPE_DIRECT);
    $exchange->declareExchange();

    echo "send Message: " . $exchange->publish($message, $routeKey) . "\n";
    echo "Message is sent: " , $message, "\n";
} catch (AMQPException $e) {
    var_dump($e);
}

// 断开连接
$connection->disconnect();