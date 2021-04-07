<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/4/7
 * Time: 10:19
 */

class MysqlPool
{
    // 最大连接数
    // 最小连接数
    // 配置
    // 连接池

    private static $instance;
    private $pool;
    private $config;

    public static function getInstance($config = null)
    {
        if (empty(self::$instance)) {
            if (empty($config)) {
                throw new \RuntimeException("缺少配置信息");
            }
            self::$instance = new static($config);
        }
        return self::$instance;
    }

    public function __construct($config)
    {
        if (empty($this->pool)) {
            Swoole\Coroutine::set(['hook_flags' => SWOOLE_HOOK_ALL]);   //不包括curl
            $this->config = $config;
            // $this->pool = new \Co\Channel($config['master']['pool_size']);
            $this->pool = new \Swoole\Coroutine\Channel($config['master']['pool_size']);
            for($i=0; $i < $config['master']['pool_size']; $i++) {
                go(function()use($config) {
                   $mysql = new MysqlDb();
                   $res = $mysql->connect($config);
                   if ($res === false) {
                       throw new \RuntimeException('连接数据库失败');
                   } else {
                       $this->pool->push($mysql);
                   }
                });
            }
        }
    }

    public function get()
    {
        if ($this->pool->length() > 0) {
            $mysql = $this->pool->pop($this->config['master']['pool_get_timeout']);
            if (false === $mysql) {
                throw new \RuntimeException('弹出mysql超时');
            }
            Swoole\Coroutine::defer(function()use($mysql){
                $this->pool->push($mysql);
            });
            return $mysql;
        } else {
            throw new \RuntimeException("连接池中暂时没有连接了");
        }
    }
}