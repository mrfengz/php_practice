<?php
namespace ztf;

class Route
{
    public $controller;
    public $action;
    protected $routeParamName;

    public function __construct()
    {
        $conf = Config::getAll('common');
        $this->routeParamName = $conf['routeParamName'] ?? 'r';
        $this->controller = $conf['defaultController'];
        $this->action = $conf['defaultAction'];
    }

    /**
     * 解析请求路由
     *
     * @return void
     */
    public function parse()
    {
        if (isset($_GET[$this->routeParamName])) {
            $route = $_GET[$this->routeParamName];
        } elseif (strstr($_SERVER['REQUEST_URI'], 'index.php')) {
            $route = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'index.php')+10);
        } elseif ($_SERVER['REQUEST_URI']) {
            if ($_SERVER['REQEUST_URI'] === '/') {
                $route = $this->controller . '/' . $this->action;
            } else {
                $route = $$_SERVER['REQUEST_URI'];
            }
        }
        if (empty($route)) {
            throw new \UnexpectedValueException("Cann't get route!", 100);
        }
        list($this->controller, $this->action) = explode('/', $route, 2);
    }

    /**
     * 执行请求
     *
     * @return void
     */
    public function doRequest()
    {
        $camelController = ucfirst(strtolower($this->controller));
        // /path/to/App/Controller/Index.php
        $controllerFile = BASE_PATH . MODULE . DS . 'Controller' . DS .$camelController . '.php';
        // var_dump(is_file($controllerFile));die;
        if (!$controllerFile) {
            throw new \Exception("找不到对应的控制器文件" . $camelController . '.php');
        }

        //  App\Controller\Index
        $c = MODULE . '\Controller\\' . $camelController;
        $controllerObj = new $c;
        // p($controllerObj);
        if (!is_callable([$controllerObj, $this->action], true)) {
            throw new \Exception($controllerFile . '中不存在方法' . $this->action);
        }

        $res = call_user_func([$controllerObj, $this->action]);

        // var_dump($res);
        return $res;
    }
}