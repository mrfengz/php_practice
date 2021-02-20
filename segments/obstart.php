<?php
if(!ob_start("ob_gzhandler")) ob_start();

$a = "this is a test! ";

echo "hello shit";

echo $a;

// ob_end_clean();  //输出空白

// ob_end_flush(); // hello shitthis is a test!

$contents = ob_get_clean();
echo $contents;
ob_end_clean();
// ob_end_clean(); //Notice: ob_end_clean(): failed to delete buffer. No buffer to delete.删除次数与ob_start()不对应，会报错


function getView($view)
{
    $level = ob_get_level();

    ob_start();
    
    try {
        include $view;
    } catch(\Exception $e) {
        while (ob_get_level() > $level) {
            ob_end_clean();
        }

        throw $e;
    }

    return ob_get_clean();
}