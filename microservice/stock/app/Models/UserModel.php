<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/4
 * Time: 21:13
 */

namespace App\Models;



class UserModel extends BaseModel
{
    protected $table = 'user';

    protected $allowedFields = ['username', 'password', 'create', 'status', 'token'];
}