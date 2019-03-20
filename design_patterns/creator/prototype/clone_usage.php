<?php
abstract class CloneMe
{
    public $name;
    public $picture;
    abstract function __clone();
}

class Person extends CloneMe
{
    public function __construct()
    {
        echo "constructor\n";
        $this->picture = 'CloneMan.png';
        $this->name = 'original';
    }

    public function display()
    {
        echo "My picture is: $this->picture\n";
        echo "My name is: $this->name;\n";
    }

    public function __clone(){}
}

$worker = new Person();
$worker->display();

$slacker = clone $worker;
$slacker->name = 'Cloned';
$slacker->display();