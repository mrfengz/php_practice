<?php
include __DIR__ . '/RabbitMQ.php';
// ttl+死信队列实现
// 创建延时交换机和延时队列
$delayExchangeName = 'unpaid_order_exchange';
$delayRoute = 'unpaid_order';
$queueName = 'unpaid_order_queue';

// $delayExchangeName = "dlx_exchange";
// $delayRoute = "dlx_key";
// $queueName = 'dlx_queue';

$rabbitMQ = new RabbitMQ();
$rabbitMQ->setExchange($delayExchangeName)
    ->createQueue($queueName, $delayRoute);

// $rabbitMQ->getQueue()->delete();

$rabbitMQ->setExchange($delayExchangeName)
    ->createQueue($queueName, $delayRoute);


$callback = function($msg){
    var_dump(time() . ' 接收到消息：' . $msg);
    sleep(3);   //此时断开，再次连接时会再次接收到消息
    echo "done no ack";
    exit();  //这里die掉，导致消息没有ack，会一致接收到
};
$rabbitMQ->consume($callback);
