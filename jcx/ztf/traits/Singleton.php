<?php
namespace ztf\traits;

trait Singleton
{
    private static $instance;

    public static function getInstance($params = [])
    {
        if (is_null(self::$instance)) {
            return new self($params);
        } else {
            return self::$instance;
        }
    }
}
