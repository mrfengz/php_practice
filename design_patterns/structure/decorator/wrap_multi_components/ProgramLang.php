<?php
class ProgramLang extends Decorator
{
    private $languageNow;
    private $language = [
        'php' => 'PHP',
        'cs' => 'C#',
        'js' => "Javascript",
        'as3' => "ActionScript 3.0"
    ];


    public function __construct(IComponent $dateNow)
    {
        $this->date = $dateNow;
    }

    public function setFeature($lan)
    {
        $this->languageNow = $this->language[$lan];
    }

    public function getFeature()
    {
        $output = $this->date->getFeature();
        $fmat = "<br>&nbsp;&nbsp;";
        $output .= "$fmat Prefered Programing Language: ";
        $output .= $this->languageNow;

        return $output;
    }
}