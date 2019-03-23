<?php
interface IMobileFormat
{
    public function formatCss();
    public function formatGraphics();
    //垂直布局，移动端
    public function verticalLayout();
}