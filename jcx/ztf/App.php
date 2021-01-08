<?php
namespace ztf;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class App
{
    public static $app;

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
        // define('BASE_PATH', dirname(__DIR__ . DS));

        require BASE_PATH . 'vendor/autoload.php';

        if (DEBUG) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL ^ E_NOTICE);

            $whoops = new Run();
            $whoops->pushHandler(new PrettyPageHandler());
            $whoops->register();
        } else {
            ini_set('display_errors', 'Off');
        }


        // 引入配置文件
        $config = require BASE_PATH . 'config/main.php';

        // 引入常用文件函数
        require CORE . 'funcs/functions.php';

        // print_r($config);die;
        require_once(BASE_PATH . 'autoload.php');

        // 如果存在bootstrap.php文件，先加载
        if (file_exists('bootstrap.php')) {
            require('bootstrap.php');
        }

        $commonConfig = Config::getAll('common');

        defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
        defined('MODULE_PATH') or define('MODULE_PATH', APP_PATH . $commonConfig['module_path'] . DS);
        define('CORE', BASE_PATH . 'ztf' . DS);

        // 设置时区
        if ($commonConfig['timezone']) {
            date_default_timezone_set($commonConfig['timezone']);
        }

        // 语言设置
        Lang::init();

        // 注册错误、异常和关闭处理函数
        register_shutdown_function([ExceptionHandler::class, 'onShutdown']);
        set_error_handler([ExceptionHandler::class, 'onError']);
        set_exception_handler([ExceptionHandler::class, 'onException']);

        self::$app->route = new Route();
        // echo 'app bootstrap...';
        self::$app->log = Log::getInstance();
        Session::getInstance();
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