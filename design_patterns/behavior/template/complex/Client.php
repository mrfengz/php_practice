<?php
function __autoload($className)
{
    include $className . '.php';
}

class Client
{
    function __construct()
    {
        $mo = new TmFac();
        $mo->templateMethod();
    }
}

$worker = new Client();