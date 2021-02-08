<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/7
 * Time: 14:51
 */

namespace App\Controllers;


use App\Models\PushSettingModel;

class PushSetting extends BaseController
{
    public function index()
    {
        $list = [];
        foreach (PushSettingModel::getList($this->userId) as $row) {
            $list[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'token' => $row['token'],
                'status' => STATUS_MAPS[$row['status']],
                // 'secret' => $row['secret'],
                'atMobiles' => str_replace(',', "\n", $row['at_mobiles']),
                'create_time' => date('Y-m-d H:i:s', $row['create_time']),
                'update_time' => date('Y-m-d H:i:s', $row['update_time']),
            ];
        }

        $this->view("push-setting/list", [
            "title" => "推送配置",
            'list' => $list
        ]);
    }

    public function add()
    {
        $this->_save(0);
    }

    public function edit($id)
    {
        $this->_save($id);
    }

    private function _save($id)
    {
        if ($this->request->isAjax()) {
            $postData = $this->request->getPost();
            try {
                if ($msg = $this->verifyPostData($postData)) {
                    throw new \Exception($msg, 1);
                }

                $model = new PushSettingModel();
                $postData['update_time'] = time();

                if (!$id) {
                    $postData['create_time'] = time();
                    $res = $model->insert($postData);
                } else {
                    if (!PushSettingModel::getModel($this->userId)) {
                        throw new \Exception("记录不存在", 1);
                    }
                    $res = $model->update($id, $postData);
                }
                // print_r($res);
                echo $this->formatCodeAndMsg($res, 'save');
            } catch(\Exception $e) {
                echo $this->returnJson($e->getMessage(), $e->getCode());
            }

            return ;
        }
        $data = $id ? PushSettingModel::getModel($id) : [];
        $data['at_mobiles'] = empty($data['at_mobiles']) ? '' : str_replace(',', "\n", $data['at_mobiles']);
        // print_r($data);die;
        $this->view('push-setting/save', ['data' => $data], true);
    }

    private function verifyPostData(&$data)
    {
        $data = array_map('trim', $data);
        $requireFields = [
            'name' => "群名称",
            'token' => "群通知Token",
        ];
        foreach ($requireFields as $f => $text) {
            if (empty($data[$f])) {
                return $text . "不能为空";
            }
        }

        $data['at_mobiles'] = empty($data['at_mobiles']) ? '' : join(',', explode("\n", $data['at_mobiles']));

        $data['status'] = empty($data['status']) ? STATUS_PAUSED : STATUS_ACTIVE;
        $data['user_id'] = $this->userId;
    }


    public function changeStatus()
    {

    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        if(!PushSettingModel::getModel($id)) {
            return $this->returnJson("记录不存在", 1);
        }
        $res = PushSettingModel::deleteOne($id);
        echo $this->formatCodeAndMsg($res);
    }
}