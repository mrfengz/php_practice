<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/5/15
 * Time: 12:32
 */

include __DIR__ . '/RabbitMQ.php';
// ttl+死信队列实现
// 创建延时交换机和延时队列
$toDelayExchangeName = 'unpaid_order_exchange';
$toDelayRoute = 'unpaid_order';

$rabbitMQ = new RabbitMQ();
// $rabbitMQ->setExchange($delayExchangeName)
//     ->createQueue('unpaid_order_queue', $delayExchangeRoute);

// 创建一个死信队列
$bindArgs = [
    'x-message-ttl' => 20000, //消息TTL   10秒
    'x-dead-letter-exchange' => $toDelayExchangeName, //死信发送的交换机->延时交换机
    'x-dead-letter-routing-key' => $toDelayRoute, //死信routeKey->到延时队列的routeKey
];
$newOrderExchangeName = 'new_order_exchange';
$newOrderRoute = 'new_order';

// 删除queue
// $res =$rabbitMQ->setExchange($newOrderExchangeName)->createQueue('new_order_queue2', $newOrderRoute, AMQP_DURABLE, $bindArgs)->getQueue()->delete(AMQP_DURABLE);

$rabbitMQ->switchExchange($newOrderExchangeName)
    ->createQueue('new_order_queue2', $newOrderRoute, AMQP_DURABLE, $bindArgs);

// print_r($rabbitMQ->getExchanges());
// print_r($rabbitMQ->getCurrentExchange());

echo time() . ' 发送消息...';
$rabbitMQ->sendMessage('10秒后将会发送到delay队列 ' . time(), $newOrderRoute, AMQP_MANDATORY, ['delivery_mode' => 2]);


// $config = [
//     'host'      => 'localhost',
//     'vhost'     => '/',
//     'port'      => 5672,
//     'login'     => 'admin',
//     'password'  => 'admin'
// ];
//
// //链接broker
// $conn = new AMQPConnection($config);
// if(!$conn->connect()) {
//     echo "cannot connect to broker.";
//     exit();
// }
//
// //创建一个通道
// $ch = new AMQPChannel($conn);

//创建死信交换机以及队列
// $dlxName = "dlx_exchange";
// $dlxKey = "dlx_key";
// $dlx = new AMQPExchange($ch);
// $dlx->setName($dlxName);
// $dlx->setType(AMQP_EX_TYPE_DIRECT);
// $dlx->setFlags(AMQP_DURABLE);
// $dlx->declareExchange();
// $dlxQ = new AMQPQueue($ch);
// $dlxQ->setName('dlx_queue');
// $dlxQ->setFlags(AMQP_DURABLE);
// $dlxQ->declareQueue();
// $dlxQ->bind($dlx->getName(), $dlxKey);

//需要被delay的交换机
// $ex = new AMQPExchange($ch);
// $routeKey = "key_2";
// $exchangeName = "exchange_delayed";
// $ex->setName($exchangeName);
// $ex->setType(AMQP_EX_TYPE_DIRECT);
// $ex->setFlags(AMQP_DURABLE);
// $ex->declareExchange();
//
// //被delayed的队列
// $q = new AMQPQueue($ch);
// $q->setName('queue_delayed');
// $q->setFlags(AMQP_DURABLE);
// $arguments = [
//     'x-message-ttl' => 10000, //消息TTL
//     'x-dead-letter-exchange' => $dlxName, //死信发送的交换机
//     'x-dead-letter-routing-key' => $dlxKey, //死信routeKey
// ];
// //设置属性
// $q->setArguments($arguments);
// $q->declareQueue();
// $q->bind($ex->getName(), $routeKey);
//
// //发送消息
// $ex->publish("消息10秒后将被delay", $routeKey, AMQP_NOPARAM, array('delivery_mode' => 2));
