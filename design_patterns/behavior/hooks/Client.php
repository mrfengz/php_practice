<?php
function __autoload($class_name)
{
    include $class_name . '.php';
}

class Client
{
    private $buyTotal;
    private $gpsNow;
    private $mapNow;
    private $boatNow;
    private $special;
    private $zamCalc;

    function __construct()
    {
        $this->setHtml();
        $this->setCost();
        $this->zamCalc = new ZambeziCalc();
        $this->zamCalc->templateMethod($this->buyTotal, $this->special);
    }

    private function setHtml()
    {
        $this->gpsNow = $_POST['gps'];
        $this->mapNow = $_POST['map'];
        $this->boatNow = $_POST['boat'];
    }

    private function setCost()
    {
        //计算gpc和地图费用
        $this->buyTotal = $this->gpsNow;
        foreach($this->mapNow as $value) {
            $this->buyTotal += $value;
        }

        //boolean.
        $this->special = ($this->buyTotal >= 200);  //是否免运费
        $this->buyTotal += $this->boatNow;  //运费不计入费用
    }
}

$worker = new Client();