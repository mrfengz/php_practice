<?php
define('VERSION', '0.1');

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__ . DS);
define('CORE', BASE_PATH . 'ztf' . DS);
define('IS_CLI', php_sapi_name() === 'cli');
defined('IS_DEBUG') OR define('DEBUG', true);


// 引入配置文件
$config = require BASE_PATH . 'config/main.php';

// 引入常用文件函数
require CORE . 'funcs/functions.php';

// print_r($config);die;
require_once(BASE_PATH . 'autoload.php');



// print_r(get_included_files()) ;

// (new \ztf\App($config));

// (new \ztf\App($config))->run();


function test($args) {
    p($args);
    p(func_get_args());
}

call_user_func('test', [123, 'test']);
call_user_func_array('test', ['aa' => 123, 'bb' => 'test']);


