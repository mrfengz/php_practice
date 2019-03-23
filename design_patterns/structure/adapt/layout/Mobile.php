<?php
include_once 'IMobileFormat.php';

class Mobile implements  IMobileFormat
{
    public function formatCss()
    {
        // TODO: Implement formatCss() method.
        return "Mobile css";
    }

    public function formatGraphics()
    {
        // TODO: Implement formatGraphics() method.
        return "Mobile Graphics";
    }

    public function verticalLayout()
    {
        // TODO: Implement verticalLayout() method.
        return $this->formatCss() . "\n" . $this->formatGraphics();
    }
}