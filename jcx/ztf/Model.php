<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/7
 * Time: 19:57
 */

namespace ztf;


class Model
{
    /**
     * @var DB|null
     */
    static $db;

    /**
     * @var 表名
     */
    public $table;



    public function __construct()
    {
        self::$db = self::getDb();
    }

    public static function getDb()
    {
        return DB::getInstance();
    }

    public function query($sql)
    {
        return self::$db->query($sql);
    }
}