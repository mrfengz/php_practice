<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/7
 * Time: 14:52
 */

namespace App\Models;


class PushSettingModel extends BaseModel
{
    protected $table = 'push_setting';

    protected $allowedFields = ['token', 'secret', 'name', 'at_mobiles', 'status', 'user_id', 'create_time', 'update_time'];


}