<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/19
 * Time: 16:29
 */
include __DIR__ . '/BaseController.php';

class Message extends BaseController
{
    public function send()
    {
        $this->response->end("send request 234");
    }

    public function email()
    {
        $data = json_encode([
            'taskClass' => \task\Email::class,
            'taskAction' => 'send',
            'content' => "亲爱的，你好，我要去找你了！",
        ]);
        // 投递任务
        $this->server->task($data);
        $this->response->setHeader("Content-Type"," text/html;charset=utf-8");
        $this->response->end("任务处理中...");
    }
}