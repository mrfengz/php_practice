<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/7
 * Time: 19:47
 */
namespace ztf;

class DB
{
    private static $instance = null;
    /**
     * @var \PDO
     */
    protected $conn;

    private function __construct(array $options = [])
    {
        $dsn = 'mysql:host=127.0.0.1;dbname=test';
        $username = 'root';
        $passwd = 'root';

        try {
            $this->conn = new \PDO($dsn, $username, $passwd, $options);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo '数据库连接出错：' . $this->conn->errorInfo();
            throw $e;
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql)
    {
        return $this->conn->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function execute($sql)
    {
        return $this->conn->exec($sql);
    }
}