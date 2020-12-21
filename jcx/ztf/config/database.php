<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/8
 * Time: 20:05
 */

//  medoo library配置
return [
    // required
	'database_type' => 'mysql',
	'database_name' => 'test',
	'server' => '127.0.0.1',
	'username' => 'root',
	'password' => 'root',
 
	// [optional]
	'charset' => 'utf8',
	'port' => 3306,
];

return [
    'dsn' => 'mysql:host=127.0.0.1;dbname=test',
    'username' => 'root',
    'passwd' => 'root',
    'charset' => 'utf8mb4',
];