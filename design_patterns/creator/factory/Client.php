<?php
include_once '../../error_set.php';
include_once 'TextFactory.php';
include_once 'GraphicFactory.php';

class Client
{
    private $someGraphicObject;
    private $someTextObject;

    public function __construct()
    {
        $this->someGraphicObject = new GraphicFactory();
        echo $this->someGraphicObject->startFactory();

        $this->someTextObject = new TextFactory();
        echo $this->someTextObject->startFactory();
    }
}

$client = new Client();