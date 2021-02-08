<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/7
 * Time: 16:32
 */

namespace App\Controllers;


class Notify extends BaseController
{
    public function index()
    {
        $this->view('notify/list', ['token' => $_SESSION['user_token']]);
    }
}