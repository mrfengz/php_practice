<?php
include_once 'IFormat.php';
include_once 'IMobileFormat.php';

class MobileAdapter implements IFormat
{
    private $mobile;

    public function __construct(IMobileFormat $mobileNow)
    {
        $this->mobile = $mobileNow;
    }

    public function formatCss()
    {
        // TODO: Implement formatCss() method.
        return $this->mobile->formatCss();
    }

    public function formatGraphics()
    {
        // TODO: Implement formatGraphics() method.
        return $this->mobile->formatGraphics();
    }

    public function horizontalLayout()
    {
        // TODO: Implement horizontalLayout() method.
        return $this->mobile->verticalLayout();
    }
}