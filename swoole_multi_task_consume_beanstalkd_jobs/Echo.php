<?php
include_once '../../vendor/autoload.php';
$beanstalkd = new \Pheanstalk\Pheanstalk('127.0.0.1', 11300);

class Detect
{
    public static function ping($tubeName)
    {
        global $beanstalkd;
        $beanstalkd->putInTube($tubeName, 'ping');
    }

    public static function isPing($data)
    {
        return $data == 'ping' ? true : false;
    }

}
