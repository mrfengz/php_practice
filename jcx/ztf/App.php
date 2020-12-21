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

    /**
     * @var Route 路由
     */
    public $route;

    public function __construct(array $config = [])
    {
        foreach ($config as $attr => $val) {
            $this->$attr = $val;
        }
        self::$app = $this;
        $this->bootstrap();
    }

    /**
     * 启动前初始化操作
     *
     * @return void
     */
    protected function bootstrap()
    {
        // 注册错误、异常和关闭处理函数
        register_shutdown_function([ExceptionHandler::class, 'onShutdown']);
        set_error_handler([ExceptionHandler::class, 'onError']);
        set_exception_handler([ExceptionHandler::class, 'onException']);

        self::$app->route = new Route();
        // echo 'app bootstrap...';
        self::$app->log = Log::getInstance();
    }

    /**
     * 解析请求并执行
     *
     * @return void
     */
    public function run()
    {
        self::$app->route->parse();
        // 请求前事件触发
        Event::trigger(Event::EVENT_BEFORE_REQUEST);
        $res = self::$app->route->doRequest();
        // var_dump($res);
        // 请求后事件触发    
        Event::trigger(Event::EVENT_AFTER_REQUEST, $res);
    }

    public static function get($name)
    {
        return self::$app->$name ?? null;
    }

    /**
     * 写日志
     *
     * @param mixed $data
     * @return void
     */
    public static function writeLog($data, $config = [])
    {
        $canWrite = true;
        if ($config && empty($config['write_log'])) {
            $canWrite = false;
        }
        /** @var Log */
        $log = App::get('log');
        if ($log && $canWrite) {
            $log->write($data);
        }
    }

}