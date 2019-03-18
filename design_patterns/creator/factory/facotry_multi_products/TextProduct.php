<?php
include_once 'IProduct.php';
include_once 'FormatHelper.php';

class TextProduct implements IProduct
{
    private $mfgProduct;
    private $formatHelper;

    public function getProperties()
    {
        // TODO: Implement getProperties() method.
        $this->formatHelper = new FormatHelper();
        $this->mfgProduct = $this->formatHelper->addTop();

        $this->mfgProduct .= <<<COUNTRY
        this is something from Country\n
COUNTRY;

        $this->mfgProduct .= $this->formatHelper->closeUp();

        return $this->mfgProduct;
    }
}