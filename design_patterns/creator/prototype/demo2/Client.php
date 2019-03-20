<?php
include_once '../../../error_set.php';

function __autoload($class_name)
{
    include_once $class_name . '.php';
}

class Client
{
    private $market;
    private $manage;
    private $engineer;

    public function __construct()
    {
        //初始化不同的部分
        $this->makeConProto();
        //market克隆
        $tess = clone $this->market;
        $this->setEmpolyee($tess, "Tess Smith", 101, "ts101-1234", "tess.png");
        $this->showEmpolyee($tess);

        $jacob = clone $this->market;
        $this->setEmpolyee($jacob, "Jacob Jones", 102, "jj101-2234", "jacob.png");
        $this->showEmpolyee($jacob);
        //manage克隆
        $ricky = clone $this->manage;
        $this->setEmpolyee($ricky, "Ricky Rodriguze", 203, "rr203-5634", 'ricky.png');
        $this->showEmpolyee($ricky);
        //engineer克隆
        $olivia = clone $this->engineer;
        $this->setEmpolyee($olivia, "Olivia Perez", 302, 'op301-1278', 'olivia.png');
        $this->showEmpolyee($olivia);

        $john = clone $this->engineer;
        $this->setEmpolyee($john, "John Jackson", 301, 'jj302-1454', 'john.png');
        $this->showEmpolyee($john);

    }

    private function makeConProto()
    {
        $this->market = new Marketing();
        $this->manage = new Management();
        $this->engineer = new Engineering();
    }

    private function showEmpolyee(IAcmePrototype $employeeNow)
    {
        $px = $employeeNow->getPic();
        echo "Pic: " . $px . PHP_EOL;
        echo "Name: " . $employeeNow->getName() . PHP_EOL;
        echo "Depolyment: " . $employeeNow->getDept() . " unit: " . $employeeNow::UNIT . PHP_EOL;
        echo "ID: " . $employeeNow->getId() . PHP_EOL;
        echo PHP_EOL;
    }

    private function setEmpolyee(IAcmePrototype $empolyeeNow, $name, $dp, $id, $px)
    {
        $empolyeeNow->setName($name);
        $empolyeeNow->setId($id);
        $empolyeeNow->setDept($dp);
        $empolyeeNow->setPic($px);
    }
}

new Client();