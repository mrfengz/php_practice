<?php

/*
 * 装饰器包含了部分组件接口方法的实现。
 */

/**
 * Class Decorator
 */
abstract class Decorator extends IComponent
{
    public function getAge()
    {
        return $this->ageGroup;
    }

    public function setAge($age)
    {
        return $this->ageGroup = $age;
    }
}