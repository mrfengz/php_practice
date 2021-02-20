<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/4
 * Time: 15:54
 */

class Config
{
    public static function get($key, $default = null)
    {
        $config = require_once(__DIR__ . '/../config/main.php');
        return $config[$key] ?? $default;
    }
}