<?php
require_once 'helper.php';
require 'config.php';
ini_set('max_execution_time', 0);

$action = $_GET['action'];
//$action = 'pay';
switch($action) {
    case 'pay':
        $conn = getDbConnection();
        $id = (int)$_GET['id'];
//        $id = 12;
        $stat = $conn->query("select * from `order` where `id`=$id");
        if (!$order = $stat->fetch(PDO::FETCH_ASSOC)) {
            exit('找不到订单');
        }
        if ($order['status'] != 0) {
            exit('订单状态非法');
        }

        $stat2 = $conn->prepare('update `order` set `status` = 1 where `id`= :id;');
        $stat2->bindParam(':id', $id);
        if (!$res =$stat2->execute()) {
            exit('更新订单为已支付失败');
        }
        //从队列中删除该订单对应任务
        $extra_data = json_decode($order['extra_data'], true);
        $beanstalk = getBeanstalkd();
        $job = $beanstalk->watch(CANCEL_ORDER)->ignore('default')->peek($extra_data['job_id']);
        $beanstalk->delete($job);
        header('Location: order_list.php');
        break;
    case 'create':
        //创建订单
        $orderId = order_create();
        $data = [
            'order_id' => $orderId,
            'event' => [
                //取消订单
                'cancel_order' => [
                    'class' => 'CancelOrder',
                    'action' => 'exec'
                ]
                //可以加入其它处理，比如推送通知消息等
            ]
        ];
        //插入tube中
        $beanstalk = getBeanstalkd();
        $jobId = $beanstalk->putInTube(CANCEL_ORDER, json_encode($data), 1024, $config['cancel_order']['pay_in_seconds']);
        //将jobid保存到订单信息中，支付后从待支付队列中取消此订单
        if (update_job_id($orderId, $jobId)) {
            header('Location: order_list.php');
        }
        break;
}