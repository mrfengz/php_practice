<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/8
 * Time: 20:30
 */

namespace ztf;


use ztf\drives\log\File;

class Log
{
    /**
     * @var 单例
     */
    private static $instance;

    /**
     * @var File| Log Handler
     */
    private $handler;

    private function __construct()
    {
        $drive = Config::get('drive', 'cache');
        $class = '\\' . __NAMESPACE__ . '\drives\log\\' . ucfirst(strtolower($drive));
        $this->handler = new $class;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function write($message, $file = 'log')
    {
        $this->handler->log($message, $file);
    }
}