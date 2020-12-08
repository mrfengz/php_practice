<?php
namespace ztf\drives\log;

use ztf\Config;

class File
{
    private $path; // 日志存储位置
    public function log($message, $file = 'log')
    {
        $this->path = Config::get('options.path', 'cache');

        $dir = $this->path . date('Ymd') . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($dir . $file . '.php', date('Y-m-d H:i:s') . ' ' . json_encode($message, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
    }
}