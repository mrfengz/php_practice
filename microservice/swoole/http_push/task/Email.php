<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/19
 * Time: 17:30
 */
namespace task;

class Email
{
    public function send($params)
    {
        echo "邮件发送中...";
        // print_r($params);
        return true;
    }
}