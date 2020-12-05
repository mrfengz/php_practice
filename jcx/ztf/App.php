<?php
namespace ztf;

class App
{
    public static $app;

    public $moduleName = '';
    public $moduleDirectory = 'App';
    public $controllerNamespace = '\\App\\Controller\\';

    private $routeQueryName = 'r';

    public function __construct(array $config = [])
    {
        foreach ($config as $attr => $val) {
            $this->$attr = $val;
        }
        $this->bootstrap();
        self::$app = $this;
    }

    public function bootstrap()
    {
        echo 'app bootstrap...';
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
        if($params['REQUEST_URI']) {
            $res = parse_url($params['REQUEST_URI']);
            parse_str($res['query'], $segments);
            if (isset($segments[$this->routeQueryName])) {
                $route = $segments[$this->routeQueryName];
            }
        } elseif ($params['argv']) {
            $route = $params['argv'][1];
        }

        if ($route) {
            list($controller, $action) = explode( '/', $route, 2);
        }

        if (is_null($controller)) $controller = $this->defaultController;
        if (is_null($action)) $action = $this->defaultAction;

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

        var_dump($res);
        return $res;
    }
}