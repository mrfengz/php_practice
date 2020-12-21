<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/7
 * Time: 19:14
 */

namespace ztf;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use ztf\exceptions\NotFoundException;

class Controller
{
    public $viewData = [];

    public function assign($name, $value)
    {
        $this->viewData[$name] = $value;
    }

    public function displayV1($file)
    {
        $directory = substr(static::class, strpos(static::class, 'Controller') + 11);
        $filePath = BASE_PATH . MODULE . DS . 'View/' . strtolower($directory) . DS . $file . '.html';
        if (!is_file($filePath)) {
            throw new NotFoundException("找不到视图文件" . $file);
        }
        echo static::class;
        // pd($this->viewData);
        extract($this->viewData);
        include($filePath);
    }

    /**
     * 使用twig模板
     *
     * @param string $file view文件名，不需要带后缀
     * @return void
     */
    public function display($file, array $data = [])
    {
        $directory = substr(static::class, strpos(static::class, 'Controller') + 11);
        // $viewPath = BASE_PATH . MODULE . DS . 'View' . DS . strtolower($directory);
        $viewPath = BASE_PATH . MODULE . DS . 'View';

        $file = $file . '.html';

        if (!file_exists($viewFile = $viewPath . DS . $file)) {
            exit('找不到视图文件' . $viewFile);
        }
        $loader = new FilesystemLoader($viewPath);
        $twig = new Environment($loader, [
            'cache' => BASE_PATH . 'log/twig',
            'debug' => DEBUG,
        ]);
        
        $viewData = array_merge($this->viewData, $data);
        return $twig->display($file, $viewData);
    }

}