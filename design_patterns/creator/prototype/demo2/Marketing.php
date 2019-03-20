<?php
include_once 'IAcmePrototype.php';

class Marketing extends IAcmePrototype
{
    const UNIT = 'Marketing';
    private $sales = 'sales';
    private $promotion = "promotion";
    private $strategic = 'strtegic planning';

    public function setDept($orgCode)
    {
        // TODO: Implement setDept() method.
        switch($orgCode) {
            case 101:
                $this->dept = $this->sales;
                break;
            case 102:
                $this->dept = $this->promotion;
                break;
            case 103:
                $this->dept = $this->strategic;
                break;
            default:
                $this->dept = "unrecognized Marketing";
        }
    }

    public function getDept()
    {
        // TODO: Implement getDept() method.
        return $this->dept;
    }

    function __clone()
    {
        // TODO: Implement __clone() method.
    }
}