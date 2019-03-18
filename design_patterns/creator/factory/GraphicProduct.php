<?php
include_once 'IProduct.php';

class GraphicProduct implements IProduct
{
    private $mfgProduct;
    public function getProperities()
    {
        // TODO: Implement getProperities() method.
        $this->mfgProduct = "This is a graphic .<3\n";
        return $this->mfgProduct;
    }
}