<?php

use Swoole\Table;

$table = new Table(1024);
$table->column('name', Table::TYPE_STRING, 10);
$table->column('age', Table::TYPE_INT, 3);
$table->column('desc', Table::TYPE_STRING, 30);
$table->column('avg_score', Table::TYPE_FLOAT, 10);
$table->create();


$serv = new \Swoole\Server('127.0.0.1', 9505);
$serv->set([
    'worker_num' => function_exists('swoole_cpu_num') ? swoole_cpu_num() * 2 : 1,
]);

$serv->on('connect', function($serv, $fd){
    echo 'open connection: ' . $fd, "\n";
    global $table;
    $table->set('student:'.$fd, ['name' => 'a:'.$fd, 'age' => mt_rand(0, 16), 'avg_score' => round(mt_rand(180, 390) / 60, 2), 'desc' => str_pad('', 30, $fd)]);
    foreach ($table as $key => $v) {
        print_r($v);
    }
});

$serv->on('receive',function($serv, $fd, $fromId, $data){

});

$serv->start();