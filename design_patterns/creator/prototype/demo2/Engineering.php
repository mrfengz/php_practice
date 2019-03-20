<?php
include_once 'IAcmePrototype.php';

class Engineering extends IAcmePrototype
{
    const UNIT = 'Engineering';
    private $development = 'programming';
    private $design = 'digital design';
    private $sysAd = 'system administation';

    public function setDept($orgCode)
    {
        // TODO: Implement setDept() method.
        switch($orgCode) {
            case 301:
                $this->dept = $this->development;
                break;
            case 302:
                $this->dept = $this->design;
                break;
            case 303:
                $this->dept = $this->sysAd;
                break;
            default:
                $this->dept = "unrecognized Engineering";
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