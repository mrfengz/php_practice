<?php
namespace ztf;

class App
{
    public static $app;

    public $moduleName = '';
    public $moduleDirectory = 'App';
    public $controllerNamespace = '\\App\\Controller\\';

    private $routeQueryName = 'r';

    /**
     * @var Log 日志
     */
    public $log;

    public function __construct(array $config = [])
    {
        foreach ($config as $attr => $val) {
            $this->$attr = $val;
        }
        self::$app = $this;
        $this->bootstrap();
    }

    public function bootstrap()
    {
        echo 'app bootstrap...';
        self::$app->log = Log::getInstance();
    }

    public function run()
    {
        // print_r($_SERVER);
        $this->parseRequest($_SERVER);
    }

    public function parseRequest($params)
    {
        // pd($_SERVER);
        // pathinfo ? get
        if (isset($_GET['r'])) {
            $route =  $_GET['r'];
        } elseif($params['REQUEST_URI']) {
            pd($params['REQUEST_URI']);
            if ($params['REQUEST_URI'] === '/') {
                $route = 'index/index';
            } else {
                $res = parse_url($params['REQUEST_URI']);
                parse_str($res['query'], $segments);
                if (isset($segments[$this->routeQueryName])) {
                    $route = $segments[$this->routeQueryName];
                }
            }
        } elseif ($params['argv']) {
            $route = $params['argv'][1];
        }

        if ($route) {
            // todo 支持目录 system/admin/index
            list($controller, $action) = self::parseRoute($route);
        }
        // pd($controller, $action);
        if (is_null($controller)) $controller = 'test'; //$this->defaultController;
        if (is_null($action)) $action = 'index'; //$this->defaultAction;

        $camelController = ucfirst(strtolower($controller));
// pd($controller, $action);
        $controllerFile = BASE_PATH . DS . $this->moduleDirectory . DS . 'Controller' . DS .$camelController . '.php';
        // echo $controllerFile;die;
        $c = $this->controllerNamespace . $camelController;
        // echo $c;
        // var_dump(is_file($controllerFile));die;
        if (!$controllerFile) {
            throw new \Exception("找不到对应的控制器文件" . $camelController . '.php');
        }
        $controllerObj = new $c;
        // p($controllerObj);
        if (!is_callable([$controllerObj, $action], true)) {
            throw new \Exception($controllerFile . '中不存在方法' . $action);
        }

        $res = call_user_func([$controllerObj, $action]);

        // var_dump($res);
        return $res;
    }

    public function parseRoute($route, $directory = '')
    {
        list($controller, $action) = explode( '/', $route, 2);
        $camelController = ucfirst(strtolower($controller));
        $controllerFile = BASE_PATH . DS . $this->moduleDirectory . DS . 'Controller' . DS .$camelController . '.php';
        if (file_exists($controllerFile)) {
            //todo
            return [$controller, $action];
        } else {
            return 'todo';
        }
    }
}