<?php
class GraphicFactory extends Creator
{
    protected function factoryMethod()
    {
        $product = new GraphaicProduct();
        return $product->getProperties();
    }
}