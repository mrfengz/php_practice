<?php
$routeKey = 'rpc_queue';

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
$channel->setPrefetchCount(1);

$serverQueue = new AMQPQueue($channel);
$serverQueue->setName($routeKey);
$serverQueue->declareQueue();

$exchange = new AMQPExchange($channel);

$serverQueue->consume(function ($envelop, $queue) use ($exchange) {
    $num = intval($envelop->getBody());
    $response = fib($num);
    $exchange->publish($response, $envelop->getReplyTo(), AMQP_NOPARAM, [
        'correlation_id' => $envelop->getCorrelationId(),
    ]);
    $queue->ack($envelop->getDeliveryTag());
});

$connection->disconnect();

function fib($num)
{
    if ($num == 0) {
        return 1;
    } elseif ($num == 1) {
        return 1;
    }

    return fib($num - 1) + fib($num - 2);
}