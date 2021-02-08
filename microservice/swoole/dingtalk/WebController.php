<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/2
 * Time: 16:32
 */

require_once(__DIR__ . '/lib/Db.php');
require_once(__DIR__ . '/lib/Helper.php');

class WebController
{
    private $request;
    private $requestUri;

    private $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
        $this->requestUri = $_GET['m'] ?? 'index';

    }

    public function execute()
    {
        if ($this->requestUri == 'login') {
            return $this->login();
        }
    }

    public function login()
    {
        Helper::setReturnType('json');

        $postData = $_POST;
        $username = trim($postData['username']);
        $password = trim($postData['password']);
        $user = $this->db->fetchOne("SELECT * FROM `user` WHERE username=". $this->db->quote($username));
        if (!$user || !password_verify($password, $user['password'])) {
            return Helper::returnData('用户名或密码错误', 1);
        }
        $token = Helper::getToken(mb_substr($username, 0, 1) . 'CA' .  substr($password,2) . '$');
        $res = $this->db->execute("UPDATE user SET token='{$token}' WHERE id=" . intval($user['id']));
        if ($res) {
            return  Helper::returnData('登录成功', 0, ['token' => $token]);
        } else {
            return  Helper::returnData('登录失败，请联系管理员', 1);
        }
    }

    // public function __call($method)
    // {
    //     return $this->method($this->request);
    // }


}