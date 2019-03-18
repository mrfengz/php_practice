<?php

class FormatHelper
{
    private $topper;
    private $bottom;

    public function addTop()
    {
        $this->topper = 'Formatter topper'.PHP_EOL;
        return $this->topper;
    }

    public function closeUp()
    {
        $this->bottom = 'Formatter bottom';
        return $this->bottom;
    }
}