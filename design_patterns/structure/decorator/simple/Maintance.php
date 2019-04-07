<?php

/**
 * 具体装饰器
 * Class Maintance
 */
class Maintance extends Decorator
{
    public function __construct(IComponent $siteNow)
    {
        $this->site = $siteNow;
    }

    /**
     * 在原组件的基础上，增加新的内容(装饰)
     * @return string
     */
    public function getSite()
    {
        // TODO: Implement getSite() method.
        $fmat = "<br>&nbsp;&nbsp;Maintance ";
        return $this->site->getSite() . $fmat;
    }

    /**
     * 在具体组件的基础上，添加金额(装饰)
     * @return int
     */
    public function getPrice()
    {
        // TODO: Implement getPrice() method.
        return 950 + $this->site->getPrice();
    }
}