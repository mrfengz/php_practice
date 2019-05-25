<?php
class OnState implements IState
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function turnLightOn()
    {
        // TODO: Implement turnLightOn() method.
        echo "Light is already on -> take no action<br>";
    }

    public function turnLightOff()
    {
        echo "Light off<br>";
        $this->context->setState($this->context->getOffState());
    }
}