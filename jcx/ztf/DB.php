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
        $dbConfig = Config::getAll('database');
        try {
            $this->conn = new \PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['passwd'], $options);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->conn->exec('SET CHARACTER SET ' . ($dbConfig['charset'] ?? 'utf8'));
        } catch (\PDOException $e) {
            echo '数据库连接出错：' . var_export($this->conn->errorInfo(), true);
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