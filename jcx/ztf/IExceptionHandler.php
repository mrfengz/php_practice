<?php
namespace ztf;

interface IExceptionHandler
{
    public static function onShutdown();
    public static function onException(\Exception $e);

    public static function onError($errno, $errstr, $errfile, $errline);
}