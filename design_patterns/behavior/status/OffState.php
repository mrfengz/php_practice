<?php
class OffState implements  IState
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function turnLightOn()
    {
        // TODO: Implement turnLightOn() method.
        echo "Light on! Now I can see!<br>";
        $this->context->setState($this->context->getOnState());
    }

    public function turnLightOff()
    {
        // TODO: Implement turnLightOff() method.
        echo "Light is already off -> take no action<br>";
    }
}