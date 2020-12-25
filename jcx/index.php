<?php

define('VERSION', '0.1');


define('APP_PATH', __DIR__. DIRECTORY_SEPARATOR . 'App');

defined('DEBUG') OR define('DEBUG', true);



// print_r(get_included_files()) ;

// (new \ztf\App($config));

include APP_PATH . '/../ztf/start.php';

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


