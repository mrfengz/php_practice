<?php
namespace ztf;

class Cookie
{
    /**
     * cookie有效域名，默认为当前域名
     *
     * @var string
     */
    public static $domain;

    /**
     * cookie在域名下边的哪个路径有效
     *
     * @var string
     */
    public static $path;

    
    /**
     * cookie是否仅允许https传输
     *
     * @var string
     */
    public static $secure;

    
    /**
     * cookie是否仅可以通过http协议进行传输
     *
     * @var bool
     */
    public static $httponly;

    public static $expire;

    /**
     * 是否已经初始化
     *
     * @var boolean
     */
    public static $isInit = false;


    public static function init()
    {
        if (self::$isInit) return false;
        $cookieConfig = Config::get('cookie', 'common', []);
        self::$path = $cookieConfig['cookie_path'] ?? '/';
        self::$secure = $cookieConfig['cookie_secure'] ?? false;
        self::$domain = $cookieConfig['cookie_domain'] ?? '';
        self::$httponly = $cookieConfig['cookie_httponly'] ?? false;
        $expiresDays = (int)$cookieConfig['cookie_expire_days'] ?? 0;
        self::$expire = $expiresDays * 86400;


        self::$isInit = true;
        return true;
    }

    /**
     * 设置cookie
     *
     * @param string $name cookie名字
     * @param string $value 值
     * @param integer $expire 过期时间戳
     * @param string $path / 对整个域名有效 /path 对域名下的/path目录有效
     * @param string $domain 域名
     * @param string $secure 仅通过https传递给客户端
     * @param string $httponly 仅可通过http协议传递
     * @return boolean
     */
    public static function set($name, string $value = '', int $expire = 0, string $path = '/', string $domain = '', $secure = '', $httponly = ''): bool
    {
        self::init();

        $path = '' === $path ? self::$path : $path;
        $domain = '' === $domain ? self::$domain : $domain;
        $secure = '' === $secure ? self::$secure : $secure;
        $httponly = ''=== $httponly ?: self::$httponly;
       
        $expireTime = time() + intval($expire ?: self::$expire);

        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $arr[] = setcookie($key, $value, $expireTime, $path, $domain, $secure, $httponly);
            }
            return true;
        }
        // 设置值为false，会导致被删除
        if (is_bool($value)) $value = intval($value);

        return setcookie($name, $value, $expireTime, $path, $domain, $secure, $httponly);
    }

    public static function delete($name, string $path = '/', string $domain = '', $secure = '', $httponly = '')
    {
        self::init();
        return self::set($name, '', -1 , $path, $domain, $secure, $httponly);
    }

    public static function clear(string $path = '/', string $domain = '', $secure = '', $httponly = '')
    {
        foreach ($_COOKIE as $key => $val)
        {
            self::delete($key, $path, $domain, $secure, $httponly);
        }
    }

    public static function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public static function get(string $name)
    {
        return $_COOKIE[$name] ?? null;
    }
}