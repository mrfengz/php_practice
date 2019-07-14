<?php
$array = [1 => 10, 2 => 102, 3 => 'age', 4 => 'following', 8 => 'nickname'];

//索引不连续的话，其值会被值为null
$fixed = SplFixedArray::fromArray($array);
print_r($fixed);

//会重新索引
$fixed2 = SplFixedArray::fromArray($array, false);
print_r($fixed2);