<?php
include_once "Iformat.php";

class Desktop implements  IFormat
{
    public function formatCss()
    {
        // TODO: Implement formatCss() method.
        return "Desktop css";
    }

    public function formatGraphics()
    {
        // TODO: Implement formatGraphics() method.
        return "Desktop graphics";
    }

    public function horizontalLayout()
    {
        // TODO: Implement horizontalLayout() method.
        return $this->formatCss() . '----------' . $this->formatGraphics() . PHP_EOL;
    }
}