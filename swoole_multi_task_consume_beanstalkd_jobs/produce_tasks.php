<?php
//向tester管道中添加job
include '../../vendor/autoload.php';

$tube = (new \Pheanstalk\Pheanstalk('127.0.0.1', 11300))->useTube('tester');

$i = 0;
while($i < 10) {
    $tube->put('tester-data-' . $i);
    $i++;
}
