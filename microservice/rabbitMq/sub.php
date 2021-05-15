<?php
/**
 * 接收消息
 */

$exchangeName = 'logs';

$user = 'admin';
// 建立TCP连接
$connection = new AMQPConnection([
    'host' => 'localhost',
    'port' => '5672',
    'vhost' => '/',
    'login' => $user,
    'password' => $user,
]);

$connection->connect() or die("连接到broker失败\n");

//订阅，可以使用匿名队列，所有队列都会收到一份

$channel = new AMQPChannel($connection);

$exchange = new AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_FANOUT);
$exchange->declareExchange();

$queue = new AMQPQueue($channel);
$queue->setFlags(AMQP_EXCLUSIVE);
$queue->declareQueue();
$queue->bind($exchangeName);

var_dump("waiting for message...");

// 消费队列消息
while(true) {
    $queue->consume('processMessage');
}

$connection->disconnect();

function processMessage($envelop, $queue) {
    $msg = $envelop->getBody();
    var_dump("received: " . $msg);
    $queue->ack($envelop->getDeliveryTag());    //手动发送ACK应答
}
