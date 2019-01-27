<?php
require_once 'helper.php';
$config = require_once 'config.php';

$conf = $config['cancel_order'];
$orderStatus = [
    '待支付', '已支付', 9 => '已取消',
];

$conn = getDbConnection();
$stat = $conn->query("select * from `order` order by `id` desc;");
$orders = $stat->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>订单列表</title>
</head>
<body>
<a href="order.php?action=create">创建订单</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>用户</th>
            <th>下单时间</th>
            <th>订单状态</th>
            <th>支付</th>
        </tr>
        <?php foreach ($orders as $order){ ?>
            <tr>
                <td><?= $order['id'];?></td>
                <td><?= $order['user_id'];?></td>
                <td><?= date('Y-m-d H:i:s', $order['created_at']);?></td>
                <td><?= $orderStatus[$order['status']] ?? '未知状态';?></td>
                <td>
                    <?= time() - $order['created_at'];?>
                    <?php if((time() - $conf['pay_in_seconds'] <= $order['created_at']) && $order['status'] == 0){;?>
                        <a href="order.php?action=pay&id=<?=$order['id']?>">支付</a>
                    <?php }?>
                </td>
            </tr>
        <?php }?>
    </table>
</body>
</html>
