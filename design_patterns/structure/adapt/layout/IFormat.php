<?php
interface IFormat
{
    public function formatCss();
    public function formatGraphics();
    //水平布局，适用于宽屏
    public function horizontalLayout();
}