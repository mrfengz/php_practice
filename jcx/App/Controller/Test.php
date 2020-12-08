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
use App\Model\Test as TestModel;

class Test extends Controller
{
    public function index()
    {
        p($_GET);
        echo __METHOD__;
        // return 'hello test';
        $this->assign('title', 'PHP View test');
        $this->display('index');
    }

    public function getTables()
    {
        App::$app->log->write('hello world');

        $model = new TestModel();
        $res = $model->query('SHOW TABLES;');
        p($res);

    }
}