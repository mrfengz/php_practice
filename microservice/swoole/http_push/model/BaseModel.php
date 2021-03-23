<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/20
 * Time: 15:51
 */
namespace model;

use Application\Database\Connection;
use PDO;

class BaseModel
{
    protected $dbConn;
    protected $lastSql = '';
    protected $lastError = '';

    public function __construct()
    {
        $this->dbConn = new Connection();
    }

    public function getOne($sql)
    {
        $this->lastSql = $sql;
        return $this->dbConn->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($sql)
    {
        $this->lastSql = $sql;
        $query = $this->dbConn->pdo->query($sql);
        $res = [];
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }
        return $res;
    }

    public function create($data)
    {
        $table = static::$table;
        $sql = 'INSERT INTO ' . $table . '(' . explode(',', array_keys($data)) . ') VALUE('
            . explode('?', array_fill(0, count($data), '?')) . ')';
        $statement = $this->dbConn->pdo->prepare($sql);
        try {
            $this->lastSql = 'INSERT INTO ' . $table . '(' . explode(',', array_keys($data)) . ') VALUE('
                . explode('?', array_values($data)) . ')';
            return $statement->execute(array_values($data));
        } catch(\Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function update($data, $where = [])
    {
        $table = static::$table;
        $sql[] = 'UPDATE ' . $table;
        $kvs = [];
        $values = [];
        foreach ($data as $field => $val) {
            $kvs[] = "SET {$field} => ?";
            $values[] = $val;
        }

        $sql[] = explode(',', $kvs);

        $wh = [];
        foreach($where as $f => $v) {
            if (is_numeric($f)) {
                $wh[] = $v;
            } else {
                $wh[$f] = '?';
                $values[] = $v;
            }
        }

        if ($wh) {
            $sql[] = 'WHERE';
            $sql[] = explode(' ', $wh);
        }

        $sql = explode(' ', $sql);
        echo $sql;
        print_r($values);die;
        $statement = $this->dbConn->pdo->prepare($sql);
        try {
            $this->lastSql = 'INSERT INTO ' . $table . '(' . explode(',', array_keys($data)) . ') VALUE('
                . explode('?', array_values($data)) . ')';
            return $statement->execute(array_values($data));
        } catch(\Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function getLastSql()
    {
        return $this->lastSql;
    }

    public function getLastError()
    {
        return $this->lastError;
    }
}