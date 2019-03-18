<?php
include_once 'IProduct.php';
//抽象类无法直接实例化，只能被子类继承。可以用在基类控制器和模型等类中
abstract class Creator
{
    protected abstract function factoryMethod(IProduct $product);

    public function doFactory($productNow) {
        $countryProduct = $productNow;
        $mfg = $this->factoryMethod($countryProduct);
        return $mfg;
    }
}
