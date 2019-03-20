<?php
include_once 'IPrototype.php';

class FemaleProto extends IPrototype
{
    const GENDER = 'FEMALE';
    public $fecundity; //产卵数量

    public function __construct()
    {
        $this->eyeColor = 'red';
        $this->wingBeat = '220';
        $this->unitEyes = '760';
    }

    public function __clone()
    {
        // TODO: Implement __clone() method.
    }
}