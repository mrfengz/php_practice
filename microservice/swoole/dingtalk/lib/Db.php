<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/4
 * Time: 15:52
 */
require_once('Config.php');
class Db
{
    private static $instance;
    private $conn;

    private function __construct()
    {
        $conf = Config::get('db', []);
        if (!$conf) throw new \InvalidArgumentException("数据库配置有误，请检查", 1);
        $conn = new PDO("mysql:host={$conf['host']};dbname={$conf['dbname']}", $conf['user'], $conf['password']);
        if (!$conn) exit("无法连接数据库，错误" . $conn->errorInfo());
        $this->conn = $conn;
    }

    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    public function query($sql, $mode = PDO::FETCH_ASSOC)
    {
        return $this->conn->query($sql, $mode)->fetchAll();
    }

    public function fetchOne($sql)
    {
        return $this->conn->query($sql, PDO::FETCH_ASSOC)->fetch();
    }

    public function fetchAll($sql)
    {
        return $this->conn->query($sql, PDO::FETCH_ASSOC)->fetchAll();
    }

    public function getError()
    {
        return $this->conn->errorInfo();
    }

    public function execute($sql)
    {
        return $this->conn->exec($sql);
    }

    public function quote($str)
    {
        return $this->conn->quote($str);
    }
}