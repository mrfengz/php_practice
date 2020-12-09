<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/9
 * Time: 15:39
 */

include 'MyPdo.php';


$pid = getmypid();
// $res = $db->exec("update trade_message set pid=" . $pid . ', update_time=' . time() . ' where id=1');

$count = $limit = 2;
while($count == $limit) {
    $count = 0;
    foreach (getBatch($pid, $limit) as $row) {
        $count++;
        print_r($row);
        sleep(1);
        $db = MyPdo::getInstance()->exec('UPDATE trade_message SET status=3,update_time=' . time() . ' WHERE id=' . $row['id']);
    }
}

function getBatch($pid, $limit)
{
    $db = MyPdo::getInstance();
    $rows = $db->exec("UPDATE trade_message SET pid=" . $pid . ', status=2 WHERE pid=0 and status=1 limit ' . $limit);
    var_dump($rows);
    return $db->query("SELECT * FROM trade_message WHERE pid=" . $pid . ' AND status=2 limit ' . $limit);
}