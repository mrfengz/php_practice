<?php

/**
 * IComplete接口
 * Class IComponent
 */
abstract class IComponent
{
    protected $site;
    abstract public function getSite();
    abstract public function getPrice();
}