<?php
namespace ztf;

class Lang
{
    protected static $list;

    public static function init()
    {
        $langConfig = Config::get('lang', 'common');
        $default = $langConfig['default'] ?? 'zh-cn';
        self::load($default);
        if ($langConfig['lang_switch_on']) {
            $lang = self::getLang();
            if ($lang && $lang != $default) {
                self::load($lang);
            }
        }
    }

    public static function e($name)
    {
        print_r(self::$list);
        return self::$list[$name] ?? $name;
    }

    public static function load($lang)
    {
        // 框架、项目、模块语言包
        $langConfig = Config::get('lang', 'common');
        $supportLangList = $langConfig['lang_list'] ?: [];

        $data = [];
        if (!in_array($lang, $supportLangList)) {
            self::$list = [];
        } else {
            // 加载项目语言文件
            $langFile = BASE_PATH . Config::get('lang_path', 'common') . DS . $lang . '.lang.php';
            
            if (is_file($langFile)) {
                $data = array_merge($data, include $langFile);
            }
            
             // 加载应用语言文件
             $langFile = APP_PATH . DS . Config::get('lang_path', 'common') . DS . $lang .'.lang.php';
             if (is_file($langFile)) {
                 $data = include $langFile;
             }
             // 加载模块语言文件
             // $langFile = MODULE_PATH . 'lang.php';
             // if (is_file($langFile)) {
             //     $data = array_merge($data, include $langFile);
             // }
        }
        // dump($data);
        self::$list = $data;
    }

    public static function getLang()
    {
        $langConfig = Config::get('lang', 'common');
        if ($langConfig['auto_detect_lang']) {
            if ($_GET[$langConfig['var_lang']]) {
                return $_GET[$langConfig['var_lang']];
            }
            //  elseif (cookie('lang')) {
    
            // }
            elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && preg_match('/^([a-z\d\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches) > 0)
            {
                // set cookie todo
                return strtolower($matches[1]);
            } else {
                return false;
            }
        }
        return null;
    }
}