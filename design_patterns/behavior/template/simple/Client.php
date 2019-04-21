<?php
function __autoload($className)
{
    include $className . '.php';
}

class Client
{
    function __construct()
    {
        $caption = "Modigliani painted elongated faces.";
        $mo = new ContreteClass();
        $mo->templateMethod("modig.png", $caption);
    }
}

new Client();