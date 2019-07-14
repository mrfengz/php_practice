<?php
/**
 * PHP数组本质
 * 同时使用了 double-list 和 hash table. 所以 一个range(0, 10000)的数组，所占用的内存不止 10000 * 8bytes
 *
 * splFixedArray 无法使用php的array函数
 */

$m1 = memory_get_usage();
$array = range(0, 100000);
$m2 = memory_get_usage();

echo 'normal array memory usage: ' . ($m2 - $m1) / 1024 / 1024 . 'M' . PHP_EOL;
unset($array);

$items = 100000;
$m1 = memory_get_usage();
$array = new SplFixedArray($items);
for($i = 0; $i < $items; $i++) {
    $array[$i] = $i;
}
$m2 = memory_get_usage();

echo 'normal array memory usage: ' . ($m2 - $m1) / 1024 / 1024 . 'M' . PHP_EOL;