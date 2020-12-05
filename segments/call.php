<?php

class Customer
{
    private $firstName;
    private $lastName;

    public function __construct($firstName, $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}

$customer = new Customer("主子", "你");

$greeting = function($message) {
    return "$message $this->firstName $this->lastName\n";
};

echo $greeting->call($customer, "Hello");