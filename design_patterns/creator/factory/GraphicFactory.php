<?php
include_once 'Creator.php';
include_once 'GraphicProduct.php';

class GraphicFactory extends Creator
{
    protected function factoryMethod()
    {
        // TODO: Implement factoryMethod() method.
        $product = new GraphicProduct();
        return $product->getProperities();
    }
}