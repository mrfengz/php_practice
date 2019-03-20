<?php
include_once 'IPrototype.php';

class MaleProto extends  IPrototype
{
    const GENDER = "MALE";

    public $mated;//是否交配，交配后为true

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