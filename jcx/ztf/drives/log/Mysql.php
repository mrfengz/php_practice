<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/8
 * Time: 20:43
 */

namespace ztf\drives\log;

use Medoo\Medoo;
use ztf\Model;

class Mysql
{
    /**
     * 数据库链接
     *
     * @var Model|Medoo
     */
    private static $db;
    
    public function __construct()
    {
        if (empty(self::$db)) {
            self::$db = new Model;
        }    
    }
    
    public function log($data, $type = 'error')
    {
        self::$db->insert('log', [
            'type' => $type,
            'message' => is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : strval($data),
            'create_time' => time(),
            'params' => json_encode(array_merge($_POST ?? [], $_GET ?? [], $_SERVER ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ]);
    }
}