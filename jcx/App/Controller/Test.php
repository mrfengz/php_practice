<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/11/30
 * Time: 19:51
 */

namespace App\Controller;

class Test
{
    public function index()
    {
        p($_GET);
        echo __METHOD__;
        return 'hello test';
    }
}