<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/4
 * Time: 21:14
 */

namespace App\Models;


use CodeIgniter\Model;

class BaseModel extends Model
{
    /**
     * 获取数据，并验证用户，或者状态
     * author: august 2021/2/7
     * @param $id
     * @param bool $verifyUser
     * @param bool $notDeleted
     * @return array|object|null
     */
    public static function getModel($id, $verifyUser = true, $notDeleted = false)
    {
        $id = intval($id);
        $model = (new static())->find($id);
        if (!$model) {
            return [];
        }
        if ($verifyUser) {
            if ($model['user_id'] != $_SESSION['user_id']) {
                return [];
            }
        }

        if ($notDeleted) {
            if ($model['status'] == STATUS_DELETED) {
                return [];
            }
        }

        return $model;
    }

    /**
     * 删除记录
     * author: august 2021/2/7
     * @param $id
     * @return mixed
     */
    public static function deleteOne($id)
    {
        $id = intval($id);
        return (new static())->delete($id);
    }

    /**
     * 获取列表数据
     * author: august 2021/2/6
     * @param $userId
     * @param $hasStatus 是否有status字段
     * @return array
     */
    public static function getList($userId, $hasStatus = true)
    {
        if (!$userId) return [];
        $query = (new static())->where('user_id', $userId);
        if ($hasStatus) {
            $query->where('status <>', STATUS_DELETED);
        }
        return $query->findAll();
    }
}