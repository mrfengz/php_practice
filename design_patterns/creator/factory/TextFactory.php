<?php
include_once 'Creator.php';
include_once 'TextProduct.php';

class TextFactory extends Creator
{
    protected function factoryMethod()
    {
        // TODO: Implement factoryMethod() method.
        $product = new TextProduct();
        return $product->getProperities();
    }
}