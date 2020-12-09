<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/9
 * Time: 15:45
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
function getBatch($pid, $limit)
{

}
$dir = __DIR__;
echo 'manager: ' . getmypid();

$cmd = "php {$dir}\consume.php";

execInBackground($cmd);die;

// echo $cmd;
$handler = popen("start /B " . $cmd, 'r');
// $r =fread($handler, 4096);
// var_dump($r);
pclose($handler);
// execInBackground($cmd);
die;

// var_dump($res);

// execInBackground($cmd);
//
// die;

// 也是同步
// $handler = popen($cmd, 'r');
// $res = fread($handler, 4096);
// pclose($handler);
// var_dump($res);
// die;

// 同步
// $res = shell_exec($cmd);
// var_dump($res);die;


// var_dump(__DIR__) ;die;

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        echo $cmd;
        pclose(popen("start /B ". $cmd, "r"));
    }
    else {
        exec($cmd . " > /dev/null &");
    }
}
// file_put_contents('log.txt', 'manager: ' . getmypid().PHP_EOL, FILE_APPEND);


execInBackground($cmd);die;


execInBackground('php D:\phpStudy\PHPTutorial\WWW\php_practice\queue\mysql\consume.php');die;

for ($i=0; $i<10;$i++) {
    execInBackground('php D:\phpStudy\PHPTutorial\WWW\php_practice\queue\mysql\consume.php');
}
