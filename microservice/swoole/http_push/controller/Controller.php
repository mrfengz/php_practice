<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/20
 * Time: 14:03
 */

namespace controller;


use Application\Autoload\Loader;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

class Controller
{
    public function runAction(Request $request, Response $response, Server $serv)
    {
        Loader::init(__DIR__ . '/..');
        list($controller, $action) = array_map('strtolower', explode('/', trim($request->server['request_uri'], '/')));
        $controller = ucfirst($controller);
        $response->setHeader("Content-Type"," text/html;charset=utf-8");
        $ctrlWithNamespace = '\\controller\\' . $controller;    //加上命名空间

        try {
            return (new $ctrlWithNamespace($request, $response, $serv))->$action();
        } catch(\Exception $e) {
            $response->end(strval($e));
        }
    }
}