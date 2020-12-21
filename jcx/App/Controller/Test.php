<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/11/30
 * Time: 19:51
 */

namespace App\Controller;

use ztf\App;
use ztf\Controller;
use App\Model\TestModel;

class Test extends Controller
{
    public function index()
    {
        // return 'hello test';
        $this->assign('title', 'PHP View test');
        // echo $this->display('twig_index');
        echo $this->display('test/index');
    }

    public function index2()
    {
        // return 'hello test';
        $this->assign('title', 'PHP View test');
        echo $this->display('twig_index');
    }

    public function getTables()
    {
        // model增删改查
        $model = new TestModel();
        $newId =  $model->insertOne(['name' => 'lele', 'age' => 18, 'friends' => 1560, 'salary' => 15687, 'geo_id' => 15]);

        echo 'new: ' . $newId;

        dump($model->updateOne(['salary' => 16000], ['id' => $newId]));

        dump($model->getOne(['id' => $newId]));

        dump($model->deleteOne(['id' => $newId]));

        die;


        // App::$app->log->write('hello world');
        $res = $model->query('SHOW TABLES;')->fetchAll(\PDO::FETCH_ASSOC);
        // p($res);
        return $res;

    }
}