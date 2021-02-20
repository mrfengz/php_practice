<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/4
 * Time: 22:17
 */

namespace App\Controllers;


use App\Models\StockModel;

class Stock extends BaseController
{
    public function index()
    {
        $list = [];
        foreach (StockModel::getList($this->userId) as $row) {
            $list[] = [
                'id' => $row['id'],
                'stock_name' => $row['stock_name'] . "({$row['stock_code']})",
                'stock_type' => StockModel::$stockTypeMaps[$row['stock_type']] ?? '-',
                'is_warning' => StockModel::$isWarningMaps[$row['is_warning']] ?? '-',
                'cost' => $row['cost'] / MONEY_RATE,
                'cost_warning' => "{$row['cost_warning_min']} - {$row['cost_warning_max']}",
                'day_warning' => "{$row['day_warning_min']} - {$row['day_warning_max']}",
            ];
        }

        $this->view("stock/list", [
            "title" => "我的股票列表",
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
            if ($msg = $this->verifyPostData($postData)) {
                return $this->returnJson($msg, 1);
            }
            $stockName = \StockApi::getStockName($postData['stock_type'] . $postData['stock_code']);
            $postData['stock_name'] = strval($stockName);
            $model = new StockModel();
            if (!$id) {
                $res = $model->insert($postData);
            } else {
                $obj = $model->find($id);
                if ($obj['user_id'] != $this->userId) {
                    return $this->returnJson("记录不存在", 1);
                }
                $res = $model->update($id, $postData);
            }
            // print_r($res);
            echo $this->formatCodeAndMsg($res, 'save');
            return ;
        }
        $data = $id ? StockModel::getModel($id) : [];
        $data['cost'] = empty($data['cost']) ? 0 : $data['cost'] / MONEY_RATE;
        $this->view('stock/save', ['data' => $data], true);
    }

    private function verifyPostData(&$data)
    {
        $data = array_map('trim', $data);
        $requireFields = [
            'stock_type' => "股票类型",
            'stock_code' => "股票代码",
        ];
        foreach ($requireFields as $f => $text) {
            if (empty($data[$f])) {
                return $text . "不能为空";
            }
        }

        $intFields = [
            'stock_code' => '股票代码',
            'cost' => '成本',
            'day_warning_min' => '预警上限%(当天)',
            'day_warning_max' => '预警上限%(当天)',
            'cost_warning_min' => '预警上限%(成本价)',
            'cost_warning_max' => '预警上限%(成本价)',
        ];
        foreach ($intFields as $f => $text) {
            if (empty($data[$f]))
                $data[$f] = 0;
            elseif (!is_numeric($data[$f])) {
                return $text . "必须为数字";
            }
        }

        $data['cost'] = $data['cost'] * MONEY_RATE; //成本价*100，具体到分

        $data['is_warning'] = empty($data['is_warning']) ? StockModel::IS_WARNING_NO :  StockModel::IS_WARNING_YES;
        $data['status'] = STATUS_ACTIVE;
        $data['user_id'] = $this->userId;
    }


    public function changeStatus()
    {

    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        if(!StockModel::getModel($id)) {
            return $this->returnJson("记录不存在", 1);
        }
        $res = StockModel::deleteOne($id);
        echo $this->formatCodeAndMsg($res);
    }
}