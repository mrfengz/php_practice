<?php
namespace ztf;

use ztf\traits\Singleton;

class Session
{
    use Singleton;

    public $handler;


    private function __construct()
    {
        $sessConfig = Config::get('session', 'common');
        if ($sessConfig['enable_session']) {
            if ($sessConfig['session_name']) {
                self::sessionName($sessConfig['session_name']);
            }
            // if ($sessConfig['save_path']) {
            //     session_save_path($sessConfig['save_path']);
            // }
            session_start();
            echo 'start';
        }
    } 

    public static function set($key, $val)
    {
        $_SESSION[$key] = $val;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function remove($key)
    {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function clear()
    {
        //重置会话变量
        $_SESSION = [];
        //重置会话id
        // if (ini_get('session.use_cookies')) {
        //     $cookieParmas = session_get_cookie_params();
        //     setcookie(self::sessionName(), '', time() - 1, $cookieParmas['path'], $cookieParmas['domain'], $cookieParmas['secure'], $cookieParmas['httponly']);
        // }
        session_destroy();
    }

    // public function __destruct()
    // {
    //     session_write_close();
    // }

    public static function sessionName($name = null)
    {
        return null === $name ? session_name() : session_name($name);
    }

    public static function sessionId($id = null)
    {
        return null === $id ? session_id() : session_id($id);
    }
}