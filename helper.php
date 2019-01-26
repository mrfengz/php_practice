<?php
if (!function_exists('p')) {
    function p($arg)
    {
        echo '<pre>';
        if(empty($arg)) {
            var_dump($arg);
        } else {
            print_r($arg);
        }
    }
}

if (!function_exists('pd')) {
    function pd($arg)
    {
        echo '<pre>';
        if(empty($arg)) {
            var_dump($arg);
        } else {
            print_r($arg);
        }
        die;
    }
}

if (!function_exists('getBeanstalkd')) {
    function getBeanstalkd()
    {
        require_once __DIR__.'/vendor/autoload.php';
        return new Pheanstalk\Pheanstalk('127.0.0.1', 11300);
    }
}

spl_autoload_register(function($class) {
   $classFile = $class . '.php';
   if (!file_exists($classFile)) {
       exit($classFile . '文件不存在');
   }
   require_once $classFile;
});