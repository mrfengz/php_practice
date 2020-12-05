<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/4
 * Time: 17:06
 */

namespace ztf;


class Event
{
    private static $events = [];

    /**
     * 注册事件
     * author: august 2020/12/4
     * @param $eventName
     * @param $callback
     * @param bool $prepend
     */
    public static function register($eventName, $callback, $prepend = false)
    {
        if (!isset(self::$events[$eventName])) {
            self::$events[$eventName] = [];
        }

        if ($prepend) {
            array_unshift(self::$events[$eventName], $callback);
        } else {
            array_push(self::$events[$eventName]);
        }
    }

    /**
     * 触发执行事件
     * author: august 2020/12/4
     * @param $eventName
     * @param array $params
     * @return bool
     */
    public static function trigger($eventName, $params = [])
    {
        foreach (self::$events[$eventName] ?? [] as $eventCallback) {
            if (true === call_user_func($eventCallback, $params)) {
                // 如果事件回调结果返回为true，不再继续执行
                return true;
            }
        }
        return true;
    }
}