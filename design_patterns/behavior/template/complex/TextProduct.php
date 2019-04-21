<?php
include_once 'IProduct.php';

class TextProduct implements IProduct
{
    private $mfgProduct;

    public function getProperties()
    {
        // TODO: Implement getProperties() method.
        $this->mfgProduct = "<em>Modigliani painted elongated faces.</em>";
        return $this->mfgProduct;
    }
}