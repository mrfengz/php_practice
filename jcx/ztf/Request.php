<?php
namespace ztf;

class Request
{
    /**
     * 判断请求是否为https
     *
     * @return boolean
     */
    public static function isHttps()
    {
        if (!$_SERVER) return false;
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        } elseif(isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https'){
            return true;
        }

        return false;
    }

    /**
     * 返回当前请求的协议 http://或者https://
     *
     * @return string
     */
    public static function getProtocol(): string
    {
        return 'http' . (self::isHttps() ? 's' : '') . '://';
    }

    /**
     * 获取(或比较)请求方法
     *
     * @param null|string $compareMethod
     * @return bool|string
     */
    public static function getMethod($compareMethod = null)
    {
        if (is_null($compareMethod)) {
            return $_SERVER['REQUEST_METHOD'];  //请求方法
        } else {
            return strcasecmp($compareMethod, $_SERVER['REQUEST_METHOD']) === 0;    //判断是否为指定请求方法
        }
    }

    /**
     * 获取请求的客户端的ip地址
     *
     * @return void
     * @see https://juejin.cn/post/6844903799941758990
     */
    public static function getClientIp($default = '0.0.0.0')
    {
        if (isset($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {   //客户端直连，没有转发
            return $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown')) { //nginx转发
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim(current($ips));
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"]) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown')) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER["HTTP_X_REAL_IP"]) && strcasecmp($_SERVER['HTTP_X_REAL_IP'], 'unknown')) {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
        return $default;
    }
}