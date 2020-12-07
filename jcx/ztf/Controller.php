<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/7
 * Time: 19:14
 */

namespace ztf;


use ztf\exceptions\NotFoundException;

class Controller
{
    public $viewData = [];

    public function assign($name, $value)
    {
        $this->viewData[$name] = $value;
    }

    public function display($file)
    {
        $directory = substr(static::class, strpos(static::class, 'Controller') + 11);
        $filePath = BASE_PATH . MODULE . DS . 'view/' . strtolower($directory) . DS . $file . '.html';
        if (!is_file($filePath)) {
            throw new NotFoundException("找不到视图文件" . $file);
        }
        echo static::class;
        // pd($this->viewData);
        extract($this->viewData);
        include($filePath);
    }

}