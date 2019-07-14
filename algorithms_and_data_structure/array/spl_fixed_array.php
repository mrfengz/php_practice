<?php
/**
 * SplFixedArray 必须定义长度，必须为索引数组，且下表为0-(n-1)
 * 数组的动态扩展，都需要花费时间，所以使用固定长度的数组，就会提高效率
 */

$array = new SplFixedArray(10); //长度为10

for ($i = 0; $i < 10; $i++) {
    $array[$i] = $i;
}

for ($i = 0; $i < 10; $i++)
    echo $array[$i] . "\n";

// echo $array[10]; #报错，invalid or out of range

//声明固定长度数组后修改长度
$items = 5;
$array = new SplFixedArray($items);
for($i=0; $i<$items; $i++) {
    $array[$i] = $i * 10;
}

$array->setSize(10);
$array[7] = 70;
// $array[10] = 110;    #报错

print_r($array);

//多维数组
$array = new SplFixedArray(5);
for($i=0; $i<5; $i++) {
    $array[$i] = new SplFixedArray(6);
}

print_r($array);

