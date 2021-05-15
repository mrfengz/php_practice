<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/4/9
 * Time: 15:05
 */

class MysqlPools
{
    private static $instance;



    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
        $pool = new \Swoole\Database\PDOPool();
    }
}