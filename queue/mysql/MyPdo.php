<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/5
 * Time: 14:04
 */

class MyPdo
{
    /**
     * @var mypdo实例
     */
    private static $instance;

    /**
     * @var pdo连接
     */
    private static $conn;

    private function __construct()
    {
        self::$conn = new PDO("mysql:host=127.0.0.1;dbname=test", 'root', 'root');
        if (self::$conn->errorCode()) {
            throw new \Exception('数据库连接出错：' . self::$conn->errorInfo());
        }
        self::$conn->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        self::$conn->exec('SET CHARACTER SET UTF8');
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            return self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql, $mode = PDO::FETCH_ASSOC)
    {
        return self::$conn->query($sql, $mode)->fetchAll();
    }

    public function exec($sql)
    {
        return self::$conn->exec($sql);
    }

    public function insert($table, $data)
    {
        if (empty($data)) throw new \InvalidArgumentException("插入数据不能为空", 1);
        $sql = "INSERT INTO {$table}";
        $placeholders = $vals = [];
        foreach ($data as $field => $val) {
            $key = ":$field";
            $placeholders[$field] = $key;
            $vals[$key] = $val;
        }
        $sql .= '(' . join(',', array_keys($placeholders)) . ') VALUES(' . join(',', array_values($placeholders)) . ')';
        $stat = self::$conn->prepare($sql);
        foreach ($vals as $k => $v) {
            // bindParam() 这个地方有坑，是引用传递。 如果传递$v,第一次循环时，会把这个值改为第一个的值，后边再改的话，把这个地址传过来，放入第二个值，最终的结果是，所有的值都是最后一个元素的值。
            $stat->bindParam($k, $vals[$k]);
        }
        $stat->execute();
        return $stat->rowCount();
    }
}