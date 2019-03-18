<?php
include_once 'IProduct.php';

class TextProduct implements IProduct
{
    private $mfgProduct;

    public function getProperities()
    {
        $this->mfgProduct = 'This is a text.' . PHP_EOL;
        return $this->mfgProduct;
    }
}