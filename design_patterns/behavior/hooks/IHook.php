<?php

/**
 * Class IHook
 * 子类可以该表钩子的行为，但仍然要遵循模板方法中定义的顺序
 */
abstract class IHook
{
    protected $purchased;
    protected $hookSpecial;
    protected $shippingHook;
    protected $fullCost;

    //模板方法定义好了执行顺序.total表示消费总额，special是否免运费
    public function templateMethod($total, $special)
    {
        $this->purchased = $total;
        $this->hookSpecial = $special;
        $this->addTax();
        $this->addShippingHook();
        $this->displayCost();
    }

    protected abstract function addTax();
    protected abstract function addShippingHook();
    protected abstract function displayCost();
}