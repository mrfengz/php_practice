<?php

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

define('VERSION', '0.1');

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__ . DS);
define('CORE', BASE_PATH . 'ztf' . DS);
define('IS_CLI', php_sapi_name() === 'cli');
defined('DEBUG') OR define('DEBUG', true);
define('MODULE', 'App');


require BASE_PATH . 'vendor/autoload.php';

if (DEBUG) {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL ^ E_NOTICE);

    $whoops = new Run();
    $whoops->pushHandler(new PrettyPageHandler());
    $whoops->register();
} else {
    ini_set('display_errors', 'Off');
}


// 引入配置文件
$config = require BASE_PATH . 'config/main.php';

// 引入常用文件函数
require CORE . 'funcs/functions.php';

// print_r($config);die;
require_once(BASE_PATH . 'autoload.php');

// 如果存在bootstrap.php文件，先加载
if (file_exists('bootstrap.php')) {
    require('bootstrap.php');
}

// print_r(get_included_files()) ;

// (new \ztf\App($config));

(new \ztf\App($config))->run();

//
// function test($args) {
//     p($args);
//     p(func_get_args());
// }
//
// call_user_func('test', [123, 'test']);   // 将对应的元素赋值给函数对应的参数 比如回调函数有arg1,arg2, 如果传递 call_user_func('callback', '123', 'test') 会传递给 $arg1, $arg2
//
// // 会将数组作为整体传递（一般要传递关联数组，而不是索引数组[会被按照顺序转为关联数组]），数组中的元素传递给对应的元素
// call_user_func_array('test', ['aa' => 123, 'bb' => 'test', 0 => 'aaa', '1' => ['wawa']]); // p($args) 输出 123


