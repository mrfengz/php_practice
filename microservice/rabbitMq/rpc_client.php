<?php
$routeKey = 'rpc_queue';
$num = empty($argv[1]) ? 0 : intval($argv[1]);

// 建立TCP连接
$connection = new AMQPConnection([
    'host' => 'localhost',
    'port' => '5672',
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest',
]);

$connection->connect() or die("连接到broker失败\n");

$channel = new AMQPChannel($connection);

$clientQueue = new AMQPQueue($channel);
$clientQueue->setFlags(AMQP_EXCLUSIVE);
$clientQueue->declareQueue();
$callbackQueueName = $clientQueue->getName();

$corrId = uniqid();
$properties = [
    'correlation_id' => $corrId,
    'reply_to' => $callbackQueueName,
];

$exchange = new AMQPExchange($channel);
$exchange->publish($num, $routeKey, AMQP_NOPARAM, $properties);


$clientQueue->consume(function($envelop, $queue)use($corrId){
    if ($envelop->getCorrelationId() == $corrId) {
        $msg = $envelop->getBody();
        var_dump('Received Data: ' . $msg);
        $queue->nack($envelop->getDeliveryTag());
        return false;
    }
});

$connection->disconnect();