<?php
/*
 * templateMethod()是抽象类的一个具体方法，对抽象方法序列排序，具体实现留给具体类来完成。
 */

/**
 * Class AbstractClass
 */
abstract class AbstractClass
{
    protected $pix;
    protected $cap;

    public function templateMethod($pixNow, $capNow)
    {
        $this->pix = $pixNow;
        $this->cap = $capNow;
        $this->addPix($this->pix);
        $this->addCaption($this->cap);
    }

    abstract protected function addPix($pix);
    abstract protected function addCaption($cap);
}