<?php
include 'App.php';


define('IS_CLI', php_sapi_name() === 'cli');
define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', dirname(__DIR__) . DS);
define('CORE', BASE_PATH . 'ztf' . DS);
// var_dump(BASE_PATH, APP_PATH, CORE);die;

(new \ztf\App($config = []))->run();