<?php

use ztf\ExceptionHandler;

return [
    'defaultController' => 'Index',
    'defaultAction' => 'index',
    'routeParamName' => 'r', // get['r'] => index/index, the name of $_GET to get route

    'timezone' => 'Asia/Shanghai',  //时区

    'lang_path' => 'langs', // 语言文件存放目录

    // 语言
    'lang' => [
        'lang_switch_on' => true,
        'auto_detect_lang' => true,
        'lang_list' => ['zh-cn', 'en'],
        'var_lang' => 'l'
    ],

    // 错误、异常处理配置
    'exception_handler' => [
        'class' => ExceptionHandler::class,
        'write_log' => true,
    ],

    //cookie
    'cookie' => [
        'cookie_domain' => '',
        'cookie_path' => '/',
        'cookie_httponly' => true,
        'cookie_secure' => false,
        'cookie_expire_days' => 2,
    ]
];