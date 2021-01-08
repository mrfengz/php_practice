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
use ztf\Cookie;
use ztf\Lang;
use ztf\Session;

class Test extends Controller
{
    public function index()
    {
        // var_dump( Lang::e('order list'));die;

        // trigger_error('方法不存在', E_USER_ERROR);

        // die;

        // throw new \Exception('test', 111);
        
        // return 'hello test';
        $this->assign('title', 'PHP View test');
        // echo $this->display('twig_index');
        echo $this->display('test/index');
    }

    public function setCookie()
    {
       $data = [
           'username' => 'august',
           'password' => 'admh34',
       ];
       Cookie::set($data);
    //    Cookie::set('find', 'you', 300);
    }

    public function getCookie()
    {
        dump($_COOKIE);die;
        dump(Cookie::get('user'));
        dump(Cookie::get('passwd'));
    }


    public function testSet()
    {
        // if (empty($_SESSION['login'])) {
        if (empty(Session::get('login3'))) {
            // $_SESSION['login'] = true;
            Session::set('login3', 'zhangsna3');
            echo '登录成功';
        } else {
            echo '跳转成功！';die;
        }
    }

    public function testGet()
    {
        // session_start();
        // var_dump($_SESSION['login']);
        var_dump(Session::get('login2'));
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