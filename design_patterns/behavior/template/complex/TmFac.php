<?php

/**
 * 具体模板方法，调用工厂方法
 * Class TmFac
 */
class TmFac extends TmAb
{
    protected function addPix()
    {
        $this->pix = new GraphicFactory();
        echo $this->pix->doFactory();
    }

    protected function addCaption()
    {
        $this->cap = new TextFactory();
        echo $this->cap->doFactory();
    }
}