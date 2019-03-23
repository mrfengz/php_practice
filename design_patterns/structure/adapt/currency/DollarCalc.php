<?php

/**
 * 美元计算
 * Class DollarCalc
 */
class DollarCalc
{
    private $dollar;
    private $product;
    private $service;
    private $ratio = 1;

    public function requestCalc($productNow, $serviceNow)
    {
        $this->product = $productNow;
        $this->service = $serviceNow;
        $this->dollar = $this->product + $this->service;
        return $this->requestTotal();
    }

    public function requestTotal()
    {
        return $this->dollar * $this->ratio;
    }
}