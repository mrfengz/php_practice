<?php
require_once 'helper.php';
require 'config.php';
ini_set('max_execution_time', 0);

$beanstalk = getBeanstalkd();
while(true){
    //阻塞等待job的到来
    $job = $beanstalk->watch(CANCEL_ORDER)->ignore('default')->reserve();
    $data = json_decode($job->getData(), true);

    if (empty($data['event'])) {
        $beanstalk->release($job);
    } else {
        p($data);
        foreach($data['event'] as $event) {
            if (!class_exists($event['class'])) {
                $beanstalk->release($job);
                exit('class '.$event['class'] . '不存在');
            }
            $class = new $event['class']();
            if (!method_exists($class, $event['action'])) {
                $beanstalk->release($job);
                exit('action ' . $event['action'] . '不存在');
            }

            $res = $class->{$event['action']}($data['order_id']);
            if ($res) {
                echo "{$data['order_id']} cancel success\n";

                $beanstalk->delete($job);
            } else {
                echo "{$data['order_id']} cancel fail\n";

                $beanstalk->release($job);
            }
        }
    }
}