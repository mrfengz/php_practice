<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/19
 * Time: 16:29
 */
namespace controller;
// include __DIR__ . '/BaseController.php';
use controller\BaseController;
use model\AdminUserModel;

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

        $this->response->end("任务处理中...");
    }

    public function db()
    {
        $model = new AdminUserModel();
        $res = $model->getOne("SELECT * FROM admin_user WHERE id=2");
        print_r($res);
    }
}