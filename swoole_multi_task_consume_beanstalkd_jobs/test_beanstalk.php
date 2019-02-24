<?php
include_once '../../vendor/autoload.php';
function getBeanstalkd()
{
    return new \Pheanstalk\Pheanstalk('127.0.0.1', 11300);
}

$pheanstalkd = getBeanstalkd();
$tubeName = 'tester';


// print_r($pheanstalkd->statsTube($tubeName));
// die;
ini_set('default_socket_timeout', 3);
ini_set('max_execution_time', 0);
$i = 0;
while(true) {
    try {
        if (!empty($flag)) {
            sleep(2);
            echo time() . PHP_EOL;
            var_dump($pheanstalkd->statsTube($tubeName));
            break;
        }
        $job = $pheanstalkd->useTube($tubeName)->reserve();
        echo $job->getData() . PHP_EOL;
        $pheanstalkd->delete($job);
    } catch(\Exception $e) {
        echo time() . $e->getMessage() . PHP_EOL;
        $flag = true;

        $beanstalkd = getBeanstalkd();
        print_r($pheanstalkd->statsTube($tubeName));
    }
}

// while (true) {
//     try {
//         if (!empty($print)) {
//             sleep(10);
//             echo time() . PHP_EOL;
//             print_r($pheanstalkd->statsTube($tubeName));
//             $print = false;
//             break;
//         }
//         $job = $pheanstalkd->watch($tubeName)->reserve();
//         echo $job->getData() . PHP_EOL;
//         $pheanstalkd->delete($job);
//     } catch(\Exception $e) {
//         echo time() . '---' .$e->getMessage();
//         $pheanstalkd = getBeanstalkd();
//         $pheanstalkd->statsTube($tubeName);
//         $print = true;
//     }
// }
