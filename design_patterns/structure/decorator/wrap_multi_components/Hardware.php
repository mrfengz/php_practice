<?php
class Hardware extends Decorator
{
    private $hardwareNow;
    private $box = [
        'dell' => "Dell",
        'hp' => "Hewlett-Packard",
        'lin' => 'Linux'
    ];

    public function __construct(IComponent $dateNow)
    {
        $this->date = $dateNow;
    }

    public function setFeature($hdw)
    {
        $this->hardwareNow = $this->box[$hdw];
    }

    public function getFeature()
    {
        $output = $this->date->getFeature();
        $fmat = "<br>&nbsp;&nbsp;";
        $output .= $fmat . " Current Hardware: ";
        $output .= $this->hardwareNow;

        return $output;
    }

}