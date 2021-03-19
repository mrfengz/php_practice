<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/18
 * Time: 10:43
 */
namespace Application\Autoload;
class Loader
{
    private static $dirs = [];
    private static $registered = 0;
    public static function init($dirs)
    {
        self::addDirs($dirs);
        // spl_autoload_register('self::autoload');
        spl_autoload_register(['self', 'autoload']);
    }

    private static function autoload($class)
    {
        $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        $isLoaded = false;
        foreach (self::$dirs as $dir) {
            if (self::loadFile($dir . DIRECTORY_SEPARATOR .$classFile)) {
                $isLoaded = true;
                break;
            }
        }
        $file = __DIR__ . DIRECTORY_SEPARATOR . $classFile;
        if (!$isLoaded && !self::loadFile($file)) {
            throw new \Exception("找不到类{$class}，无法自动加载");
        }
    }

    private static function loadFile($file)
    {
        if (file_exists($file)) {
            require_once($file);
            return true;
        }
        return false;
    }


    public static function addDirs($dirs)
    {
        if (is_array($dirs)) {
            self::$dirs = array_merge(self::$dirs, $dirs);
        } else {
            self::$dirs[$dirs] = $dirs;
        }
    }
}