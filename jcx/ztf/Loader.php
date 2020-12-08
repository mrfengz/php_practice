<?php
namespace ztf;

class Loader
{
	public static $loadedClasses = [];
	// todo 加载类
	public static function autoload($class)
	{
	    // 防止重复加载
	    if (isset(self::$loadedClasses[$class])) return false;
	    // print_r($class);die;

        // 将命名空间的 \ 替换为 /
	    if (strpos( $class, '\\') !== false) {
	        $classFile = str_replace('\\', '/', $class) . '.php';
        }

	    // p(BASE_PATH . $classFile);
	    // 判断类文件是否存在
	    if (!file_exists($file = BASE_PATH . $classFile)) {
	        exit('找不到类文件：' . $file);
        }

	    require $file;
	    if (!class_exists($class)) {
	        throw new \Exception("无法找到类{$class}, 在类文件{$file}中", 1);
        }
	    self::$loadedClasses[$class] = $file;
	}
}