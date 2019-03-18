<?php
//抽象类，定义了子类要实现的工厂方法和一个共用的具体方法：创建工厂
abstract class Creator
{
    //希望由子类的factoryMethod（）返回一个具体的产品对象
    protected abstract function factoryMethod();

    public function startFactory()
    {
        $mfg = $this->factoryMethod();
        return $mfg;
    }
}