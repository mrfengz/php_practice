<?php
/**
 * 调试打印
 * author: august 2020/12/4
 */
function p()
{
    foreach (func_get_args() as $arg) {
        if (is_scalar($arg)) {
            var_dump($arg);
        } else {
            echo '<pre>';
            print_r($arg);
        }
    }
}

/**
 * 调试打印并终止执行
 * author: august 2020/12/4
 */
function pd()
{
    p(func_get_args());
    die;
}