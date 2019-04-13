<?php

/*
 * 包装基本类型。
 * 比如php的file_get_contents()包装器，将一个指定资源（比如一个文件、或者一个文件名的url）绑定到一个流。
 * 我的理解是：将一个或者多个不同形式的内容，处理成相同格式的结果。
 *
 * 包装器类似于'接口'，一般是为了处理接口的不兼容，或者希望增加组件功能，用来减少不兼容性的策略
 */


/**
 * Class PrimitiveWrap
 */
class PrimitiveWrap
{
    private $wrapMe;

    public function __construct($prim)
    {
        $this->wrapMe = $prim;
    }

    public function showWrap()
    {
        return $this->wrapMe;
    }
}

$myPrim = 521;
$wrappedUp = new PrimitiveWrap($myPrim);
echo $wrappedUp->showWrap();
