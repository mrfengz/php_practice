<?php

use ztf\ExceptionHandler;

return [
    'defaultController' => 'Index',
    'defaultAction' => 'index',
    'routeParamName' => 'r', // get['r'] => index/index, the name of $_GET to get route

    // 错误、异常处理配置
    'exception_handler' => [
        'class' => ExceptionHandler::class,
        'write_log' => true,
    ],
];