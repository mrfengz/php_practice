<?php
namespace ztf;

use ztf\exceptions\ClientException;
use ztf\IExceptionHandler;

class ExceptionHandler implements IExceptionHandler
{
    /**
     *  异常相关配置
     *
     * @var array
     */
    private static $config = [];

    private static function __init()
    {
        self::$config = Config::get('exception_handler', 'common');
    }

    public static function onShutdown()
    {
        Event::trigger('EVENT_ON_SHUTDOWN');
        $e = error_get_last();
        $hasError = false;
        // 有严重错误
        if ($e && in_array($e['type'], self::getLogErrorTypes())) {
            $hasError = true;
            App::writeLog($e);
        }

        // session save todo

        if ($hasError) {
            @ob_end_clean();
            self::printError($e);
        }
    }

    public static function onError($errno, $errstr, $errfile, $errline)
    {
        $errArr = [
            'errno' => $errno,
            'errstr' => $errstr,
            'errfile' => $errfile,
            'errline' => $errline,
        ];
        Event::trigger('EVENT_ON_ERROR', $errArr);
        // $errorInfo = error_get_last();
        // if (in_array($errorInfo['type'], static::getLogErrorTypes())) {

        // }
        if (in_array($errno, self::getLogErrorTypes())) {
            @ob_end_clean();

            $message = "错误： {$errstr}, 错误文件: {$errfile}, 第{$errline}行";
            App::writeLog($message);
            self::printError($errArr);
        }
    }

    public static function onException( $e)
    {
        Event::trigger('EVENT_ON_EXCEPTION', $e);
        
        $debugInfo = '';
        if (DEBUG) {
            $debugInfo = strval($e);
        }

        // 客户端错误
        if ($e instanceof ClientException) {
            http_response_code(400);
            exit('客户端请求错误' . $debugInfo);
        } else {
            http_response_code(500);
            // 记录日志
            App::writeLog(strval($e), self::$config);
            self::printError($e);
            // exit('服务器错误，请稍后重试' . $debugInfo);
        }
    }

    public static function printError($err)
    {
        if (is_array($err)) {
            $errArr = $err;
            ob_start();
            debug_print_backtrace();
            $errArr['trace'] = ob_get_clean();
        } else {
            $errArr = [
                'message'	=>	$err->getMessage(),
				'file'		=>	$err->getFile(),
				'line'		=>	$err->getLine(),
				'trace'		=>	$err->getTraceAsString()
            ];
        }
        @ob_end_clean();

        if (IS_CLI) {
            print_r($errArr);
            exit();
        } else {
            echo var_export($errArr, true);
        }
    }

    public static function getLogErrorTypes()
    {
        return [E_ERROR, E_PARSE, E_USER_ERROR, E_CORE_ERROR, E_COMPILE_ERROR];
    }
}