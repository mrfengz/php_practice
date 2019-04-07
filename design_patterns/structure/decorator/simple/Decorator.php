<?php

/**
 * Class Decorator
 * 不需要实现任何一个方法，目的是维护component的引用
 * 如果有需要，也可以添加具体的属性和方法
 */
abstract class Decorator extends IComponent
{
    /*
     //具体装饰器最重要的是构造函数，为其提供一个组件类型
    public function __construct(IComponent $component)
    {
    }
    */
}