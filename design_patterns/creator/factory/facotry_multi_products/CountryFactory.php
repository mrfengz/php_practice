<?php
include_once 'Creator.php';
include_once 'IProduct.php';

class CountryFactory extends Creator
{
    private $country;

    public function factoryMethod(IProduct $product)
    {
        $this->country = $product;
        return $this->country->getProperties();
    }
}