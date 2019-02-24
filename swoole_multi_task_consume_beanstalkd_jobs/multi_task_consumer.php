<?php
error_reporting(-1);
ini_set('display_errors', 1);

include "../../vendor/autoload.php";
include './Echo.php';
function getBeanstalkd()
{
    return new \Pheanstalk\Pheanstalk('127.0.0.1', 11300);
}
$pheanstalkd = getBeanstalkd();
$tubeName = 'tester';

$server = new swoole_server('127.0.0.1', 9501);
$server->set([
    'worker_num' => 1,
    'task_worker_num' => 1,
]);
$server->on('workerStart', function(swoole_server $worker, $worker_id){
    if (!$worker->taskworker) {
        global $tubeName, $pheanstalkd;
        if (PHP_OS != 'Darwin') {
            swoole_set_process_name('php #{$worker_id}: worker');
        }
        // print_r([
        //     'worker_id' => $worker->worker_id,
        //     'worker_pid' => $worker->worker_pid,
        //     'manager_pid' => $worker->manager_pid,
        //     'master_pid' => $worker->master_pid,
        // ]);

        /*$i = 0;
        while($i < 10000) {
            $worker->task('Num' . $i);
            $i++;
        }*/

        // return ;
        //任务投递到task处理，异步处理。然后调用delete()删除任务。task进程执行时，如果失败了，重新添加或者buried

        while(true) {
            $job = $pheanstalkd->watch($tubeName)->reserve();
            if (!Detect::isPing($data = $job->getData())) {
                $worker->task();
            }
            $pheanstalkd->delete($job);
        }

        /*while(true) {
            try {
                $job = $pheanstalkd->watch($tubeName)->reserve();
                $worker->task($job->getData());
                $pheanstalkd->delete($job);
            } catch(\Pheanstalk\Exception\SocketException $e) {
                echo time() . $e->getMessage() . PHP_EOL;
                $GLOBALS['pheanstalkd'] = $pheanstalkd = getBeanstalkd();
            } catch(\Exception $e) {
                $worker->stop(true);
                break;
            }
        }*/

        echo "fucked\n";
    } else {
        if (PHP_OS != 'Darwin') {
            swoole_set_process_name('php #{$worker_id}: task worker');
        }
    }

});

$server->on('receive', function($server, $fd, $refactor_id, $data){
    echo "receive data from client:{$fd}\n";
});

$server->on('task', function($server, $task_id, $src_worker_id, $data) {
    //task进程无法调用异步io操作
    file_put_contents('./multi_task_log.txt', "[task] task called, from: {$src_worker_id}, task_id: {$task_id}, data: {$data}\n", FILE_APPEND);

    //无法触发onFinish事件
    $server->finish(['result' => 'fail', 'data' => $data]);
    if(mt_rand(1, 100) > 10) {
        $server->finish(['result' => 'success', 'data' => $data]) ;
    } else {
        global $beanstalkd, $tubeName;
        $beanstalkd->putInTube($tubeName, $data);
        $server->finish(['result' => 'fail', 'data' => $data]) ;
    }
});

$server->on('finish', function($server, $task_id, $data){
    file_put_contents('./multi_task_log.txt', "[finish]task#{$task_id} finish. result is: {$data['result']}\n\n", FILE_APPEND);
});

$server->start();

