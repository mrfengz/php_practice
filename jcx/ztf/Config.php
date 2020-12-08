<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/8
 * Time: 19:57
 */

namespace ztf;


use ztf\exceptions\NotFoundException;

class Config
{
    private static $loadedConfig = [];

    /**
     * author: august 2020/12/8
     * @param $name 配置名称, 支持.语法 db.username 代表文件中 'db' => ['username' => 'xxx']
     * @param $file 配置文件名称
     * @return array|mixed|null
     * @throws NotFoundException
     */
    public static function get($name, $file)
    {
        $config = self::getAll($file);
        if (strpos($name, '.') !== false) {
            foreach (explode('.', $name) as $key) {
                $config = $config[$key];
            }
            return $config;
        }
        return $config[$name] ?? null;
    }

    public static function getAll($file = null)
    {
        if (is_null($file))
            return self::$loadedConfig;

        if (isset(self::$loadedConfig[$file]))
            return self::$loadedConfig[$file];

        $filePath = CORE . 'config/' . $file . '.php';
        if (is_file($filePath)) {
            self::$loadedConfig[$file] = require $filePath;
            return self::$loadedConfig[$file] ?? [];
        } else {
            throw new NotFoundException("找不到配置文件" . $file);
        }
    }
}