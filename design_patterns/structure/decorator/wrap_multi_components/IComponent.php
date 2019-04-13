<?php

/*
 * 本实例功能：
 * 为软件开发人员建立一个约会服务
 * 有两个组件，分别为Male和Female
 * 可以分别为这两个组件装饰不同的约会关注点
 * 可以使用相同或者不同的具体装饰采用任意组合来装饰这些组件
 */

/**
 * Class IComponent
 */
abstract class IComponent
{
    protected $date;
    protected $ageGroup;
    protected $feature;

    abstract public function setAge($ageNow);
    abstract public function getAge();
    abstract public function getFeature();
    abstract public function setFeature($fea);
}