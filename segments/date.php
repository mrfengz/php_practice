<?php
// 东八区 比 UTC时间快8个小时
$time = 1599051638;

date_default_timezone_set('UTC');

echo strtotime('2020-09-02 13:00:38+0000') . PHP_EOL;

echo date('Y-m-d H:i:s O', $time);
echo "\n";

date_default_timezone_set('Asia/Shanghai');

echo strtotime('2020-09-02 13:00:38+0000') . PHP_EOL;


echo date('Y-m-d H:i:s O', $time);
