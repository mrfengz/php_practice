<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
	public function index()
	{
		$this->view('home');
	}

	public function login()
    {
        $postData = $this->request->getPost();
        $username = trim($postData['username']);
        $password = trim($postData['password']);
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->returnJson('用户名或密码错误', 1);
        }
        $token = substr(md5(mb_substr($username, 0, 1) . 'CA' .  substr($password,2) . '$'), 8, 16);
        if ($userModel->update($user['id'], ['token' => $token])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_token'] = $token;
            return  $this->returnJson('登录成功', 0, ['token' => $token]);
        } else {
            return $this->returnJson('登录失败，请联系管理员', 1);
        }
    }

    public function reg()
    {
        $postData = $this->request->getPost();
        $username = trim($postData['username']);
        $password = trim($postData['password']);
        $rePassword = trim($postData['re_password']);

        if (empty($username)) {
            return $this->returnJson('用户名不能为空', 1);
        }
        if (!$password || $password !== $rePassword) {
            return $this->returnJson('两次输入密码不一致', 1);
        }

        $userModel = new UserModel();

        $data = [
            'create_time' => time(),
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status' => STATUS_ACTIVE,
        ];
        if (!($id = $userModel->insert($data, true))) {
            return $this->returnJson('用户名或密码错误', 1);
        }

        $token = substr(md5(mb_substr($username, 0, 1) . 'CA' .  substr($password,2) . '$'), 8, 16);
        if ($userModel->update($id, ['token' => $token])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $id;
            $_SESSION['user_token'] = $token;
            return  $this->returnJson('登录成功', 0, ['token' => $token]);
        } else {
            return $this->returnJson('登录失败，请联系管理员', 1);
        }
    }

    public function logout()
    {
        $this->session->remove('logged_in');
        $this->session->remove('user_id');
        $this->session->remove('user_token');
        $this->session->destroy();
        return $this->returnJson('OK', 0);
        // $this->response->redirect('home/index');
    }
}
