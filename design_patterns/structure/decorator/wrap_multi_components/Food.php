<?php
class Food extends Decorator
{
    private $foodNow;
    private $snacks = [
        'piz' => 'Pizza',
        'burg' => 'Burgers',
        'nach' => 'Nachos',
        'veg' => 'Veggies'
    ];

    public function __construct(IComponent $component)
    {
        $this->date = $component;
    }

    public function setFeature($yum)
    {
        $this->foodNow = $this->snacks[$yum];
    }

    public function getFeature()
    {
        $output = $this->date->getFeature();
        $fmat = "<br>&nbsp;&nbsp;";
        $output .= $fmat . " favorite food: ";
        $output .= $this->foodNow;

        return $output;
    }
}