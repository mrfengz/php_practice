<?php

/**
 * 上下文类
 * 1、包括所有状态的对象  $onState / $offState
 * 2、包括获取所有状态对象的方法  getOnState() / getOffState()
 * 3、包括所有状态变迁的方法 turnOnLight() / turnOffLight()
 * 4、包括一个设置当前状态的方法
 * Class Context
 */
class Context
{
    private $offState;  //关灯状态
    private $onState;   //开灯状态
    private $currentState; //当前状态

    public function __construct()
    {
        //状态对象实例化时会传递一个$this,自引用
        $this->offState = new OffState($this);
        $this->onState = new OnState($this);
        //开始状态为off
        $this->currentState = $this->offState;
    }

    //调用状态方法触发器
    public function turnOnLight()
    {
        $this->currentState->turnLightOn();
    }

    public function turnOffLight()
    {
        $this->currentState->turnLightOff();
    }

    //设置当前状态
    public function setState(IState $state)
    {
        $this->currentState = $state;
    }

    //获取状态
    public function getOnState()
    {
        return $this->onState;
    }

    public function getOffState()
    {
        return $this->offState;
    }

}