<?php
/**
 * 分发任务
 */
$exchangeName = 'task';
$queueName = 'worker';
$routeKey = 'worker';

//建立tcp连接
$connection = new AMQPConnection([
    'host' => 'localhost',
    'port' => '5672',
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest',
]);
$connection->connect() or die("Cannot connect to the broker\n");

$channel = new AMQPChannel($connection);
// fair dispatch 公平分发
$channel->setPrefetchCount(1);  //同一时刻，不要发送超过1条消息给一个worker


$exchange = new AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_DIRECT);
// $exchange->setFlags(AMQP_DURABLE);
$exchange->declareExchange();

echo 'Exchange status: ', $exchange->declareExchange(), "\n";

$queue = new AMQPQueue($channel);
$queue->setName($queueName);
$queue->setFlags(AMQP_DURABLE); //queue消息持久化， 同时设置exchange和queue
echo "Message total:", $queue->declareQueue(), "\n";
echo "Queue bind: ", $queue->bind($exchangeName, $routeKey);

while(1) {
    $queue->consume('processMessage');
}

$connection->disconnect();


function processMessage($envelop, $queue) {
    $msg = $envelop->getBody();
    var_dump("Received: " . $msg);
    sleep(substr_count($msg, '.')); //每一个点号，1秒钟睡眠
    $queue->ack($envelop->getDeliveryTag());    //手动发送ack应答
}