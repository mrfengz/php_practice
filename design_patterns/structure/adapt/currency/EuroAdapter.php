<?php
include_once 'ITarget.php';
include_once 'EuroCalc.php';

/**
 * 适配器类继承一个具体类。继承+实现接口
 * Class EuroAdapter
 */
class EuroAdapter extends EuroCalc implements ITarget
{
    /**
     * 实例化时会改变汇率
     * EuroAdapter constructor.
     */
    public function __construct()
    {
        $this->requester();
    }

    public function requester()
    {
        // TODO: Implement requester() method.
        $this->rate = .8111;
        return $this->rate;
    }
}