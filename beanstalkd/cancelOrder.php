<?php
require_once 'helper.php';

class CancelOrder
{
    public function exec($orderId)
    {
        $conn = getDbConnection();
        $stat = $conn->prepare('update `order` set `status` = 9 where `id`= :id;');
        $stat->bindParam(':id', $orderId);
        return $stat->execute();
    }
}