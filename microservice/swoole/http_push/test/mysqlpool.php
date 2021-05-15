<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/4/7
 * Time: 10:19
 */

// Swoole\Coroutine::set(['hook_flags' => SWOOLE_HOOK_ALL]);
\Swoole\Runtime::enableCoroutine();
//数据库连接池要常驻内存，才有用，怎么办呢，每一次请求之后，连接就断开了，貌似没啥好办法。
// include __DIR__ . '/../common/MysqlDb.php';

class MysqlPool
{
    // 最大连接数
    // 最小连接数
    // 配置
    // 连接池

    private static $instance;
    private $pool;
    private $config;
    private $conn;

    public static function getInstance($config = null)
    {
        if (!$config) {
            $config = [
                'master' => [
                    'pool_size' => 10,
                    'pool_get_timeout' => 2,
                ],
                'driver' => 'mysql',
                'user' => 'august',
                'password' => 'fz123456',
                'host' => '127.0.0.1',
                'port' => '3306',
                'charset' => 'utf8',
                'dbname' => 'example_db'
            ];
        }
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
            $this->config = $config;
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

    public function get():MysqlDB
    {
        if ($this->pool->length() > 0) {
            go(function(){
                $mysql = $this->pool->pop($this->config['master']['pool_get_timeout']);
                if (false === $mysql) {
                    throw new \RuntimeException('弹出mysql超时');
                }
                Swoole\Coroutine::defer(function()use($mysql){
                    $this->pool->push($mysql);
                });
                $this->conn = $mysql;
            });

            return $this->conn;
        } else {
            throw new \RuntimeException("连接池中暂时没有连接了");
        }
    }
}

// php-fpm无法利用连接池
// $conn = MysqlPool::getInstance()->get();
for ($i=0; $i<1000; $i++) {
    // go(function(){
        $conn = new \PDO('mysql:host=127.0.0.1;dbname=example_db;charset=utf8', 'august', 'fz123456');
        $res=$conn->query("select * from test1 where id = " . mt_rand(1, 10000));
        print_r($res->fetchAll(PDO::FETCH_ASSOC));

    // });

    // $res=$conn->select("test", ['id' => mt_rand(1, 10000)]);
    // print_r($res->fetchAll(PDO::FETCH_ASSOC));
}

var_dump('hello');
sleep(30);