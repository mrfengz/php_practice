<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/5/15
 * Time: 11:37
 */

class RabbitMQ
{
    private $host = 'localhost';
    private $port = 5672;
    private $vHost = '/';
    private $user = 'guest';
    private $password = 'guest';

    /**
     * @var AMQPConnection
     */
    private $amqpConn;

    /**
     * @var AMQPEChannel
     */
    private $channel;

    private $exchanges = [];
    /**
     * @var AMQPExchange
     */
    private $currentExchange;

    private $exchangeType;

    /**
     * @var AMQPQueue
     */
    private $queue;


    private $lastError = '';

    public function __construct($config = [])
    {
        foreach ($config as $k => $v) {
            if($v) $this->$k = $v;
        }
        $this->amqpConn = new AMQPConnection([
            'host' => $this->host,
            'port' => $this->port,
            'vHost' => $this->vHost,
            'user' => $this->user,
            'password' => $this->password,
        ]);

        if (!$this->amqpConn->connect()) {
            throw new \AMQPConnectionException('连接队列失败', 1);
        }

        $this->channel = new AMQPChannel($this->amqpConn);
    }

    /**
     * author: august 2021/5/15
     * @param $exchangeName
     * @param string $type direct/直接发送 fanout/广播 topic/可以指定模式
     * @parma int $flag
     * @return $this
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     */
    public function setExchange($exchangeName, $type = AMQP_EX_TYPE_DIRECT, $flag = AMQP_DURABLE)
    {
        $key = $exchangeName . '-' . $type;
        $this->exchangeType = $type;
        if (!array_key_exists($key, $this->exchanges)) {
            $exchange = new AMQPExchange($this->channel);
            $exchange->setName($exchangeName);
            $exchange->setType($type);
            if ($flag) {
                $exchange->setFlags($flag);
            }
            $exchange->declareExchange();

            $this->exchanges[$key] = $exchange;
        }
        $this->currentExchange = $this->exchanges[$key];

        return $this;
    }

    public function switchExchange($exchangeName, $type = AMQP_EX_TYPE_DIRECT, $flag = AMQP_DURABLE)
    {
        return $this->setExchange($exchangeName, $type, $flag);
    }

    public function getCurrentExchange(): AMQPExchange
    {
        return $this->currentExchange;
    }

    public function getExchanges()
    {
        return $this->exchanges;
    }

    /**
     * 发送消息
     * author: august 2021/5/15
     * @param $message
     * @param null $routeKey
     * @param int $flags
     * @param array $attributes
     * @return bool
     */
    public function sendMessage($message, $routeKey = null, $flags = AMQP_NOPARAM, $attributes = [])
    {
        try {
            return $this->currentExchange->publish($message, $routeKey, $flags, $attributes);
        } catch(\AMQPException $e) {
            $this->lastError = $e;
            return false;
        } catch(\Exception $e) {
            $this->lastError = $e;
            return false;
        }
    }

    public function createQueue($queueName, $routeKey, $flag = AMQP_DURABLE, $arguments = [], $bindParams = [])
    {
        $queue = new AMQPQueue($this->channel);
        $queue->setName($queueName);
        if ($flag) $queue->setFlags($flag);
        if ($arguments) $queue->setArguments($arguments);
        $queue->declareQueue();
        $queue->bind($this->currentExchange->getName(), $routeKey, $bindParams);

        $this->queue = $queue;
        return $this;
    }

    public function getQueue(): AMQPQueue
    {
        return $this->queue;
    }

    /**
     * author: august 2021/5/15
     * @param $routeKey
     * @param callable $callback
     * @param array $bindArgs
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPEnvelopeException
     */
    public function consume(callable $callback)
    {
        while (true) {
            $this->queue->consume(function($envelop, $queue)use($callback){
                $callback($envelop->getBody());
                $queue->ack($envelop->getDeliveryTag());
            });
        }
    }

    public function __destruct()
    {
        $this->amqpConn->disconnect();
    }
}