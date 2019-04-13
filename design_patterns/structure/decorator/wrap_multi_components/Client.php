<?php
function __autoload($class)
{
    include $class . '.php';
}

/*
 Age Groups
    18-29   Group1
    30-39   Group2
    40-49   Group3
    50+     Group4
 */

/**
 * Class Client
 */
class Client
{
    private $hotDate;

    public function __construct()
    {
        $this->hotDate = new Female();
        $this->hotDate->setAge("Age Group 4");
        echo $this->hotDate->getAge();
        //利用包装器对hotDate对象进行包装，添加了一些其它具体装饰
        $this->hotDate = $this->wrapComponent($this->hotDate);
        echo $this->hotDate->getFeature();
    }

    private function wrapComponent(IComponent $component)
    {
        $component = new ProgramLang($component);
        $component->setFeature('php');
        $component = new Hardware($component);
        $component->setFeature('lin');
        $component = new Food($component);
        $component->setFeature('veg');

        return $component;
    }
}

$worker = new Client();