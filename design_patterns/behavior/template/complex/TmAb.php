<?php
//抽象模板方法类
abstract class TmAb
{
    protected $pix;
    protected $cap;

    public function templateMethod()
    {
        $this->addPix();
        $this->addCaption();
    }

    protected abstract function addPix();
    protected abstract function addCaption();
}