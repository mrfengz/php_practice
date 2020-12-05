<?php
// 单例
class Singleton
{
    protected $hash;
    static protected $instance;

    final protected function __construct()
    {
        $this->hash = mt_rand(1, 1000);
    }

    public static function getInstance()
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }

        self::$instance = new self();
        return self::$instance;
    }
}

// 工厂模式

class RandFactory
{
    public static function factory()
    {
        return Singleton::getInstance();
    }
}


// 注册树
class Register
{
    protected static $objects = [];

    public static function set($alias, $object)
    {
        self::$objects[$alias] = $object;
    }

    public static function get($alias)
    {
        return self::$objects[$alias];
    }

    public function __unset($alias)
    {
        unset(self::$objects[$alias]);
    }
}

$rand = RandFactory::factory();
Register::set('rand', $rand);
$object = Register::get('rand');
print_r($object);