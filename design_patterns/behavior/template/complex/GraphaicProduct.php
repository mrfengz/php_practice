<?php
include_once 'IProduct.php';
class GraphaicProduct implements IProduct
{
    private $mfgProduct;

    public function getProperties()
    {
        // TODO: Implement getProperties() method.
        $this->mfgProduct =  "<img src='pix/modig.png'>";
        return $this->mfgProduct;
    }
}