<?php
include_once 'TextProduct.php';
include 'CountryFactory.php';

class Client
{
    private $countryFactory;

    public function __construct()
    {
        $this->countryFactory = new CountryFactory();
        //doFactory()方法需要传入具体的产品
        echo $this->countryFactory->doFactory(new TextProduct());
    }
}

new Client();