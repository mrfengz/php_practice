<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/7
 * Time: 19:56
 */
namespace App\Model;

use ztf\Model;

class TestModel extends Model
{
    public $table = 'test';

    public function getList()
    {
        return $this->select($this->table, '*');
    }

    public function getOne($where, $columns = '*')
    {
        return $this->get($this->table, $columns, $where);
    }

    public function updateOne($data, $where)
    {
        return $this->update($this->table, $data, $where);
    }

    public function insertOne($data)
    {
        $this->insert($this->table, $data);
        return $this->id();
    }

    public function deleteOne($where)
    {
        $this->delete($this->table, $where);
    }
}