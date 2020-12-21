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
// 将回调函数中的$this对象，绑定为$customer
$customer = new Customer("主子", "你");

$greeting = function($message) {
    // this->firstName 和 this->lastName为 绑定对象的属性
    return "$message $this->firstName $this->lastName\n";
};
// Closure::call($bindToObject, $params)
echo $greeting->call($customer, "Hello");