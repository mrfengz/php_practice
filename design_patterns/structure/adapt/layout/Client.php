<?php
include_once 'Desktop.php';
include_once 'Mobile.php';
include_once 'MobileAdapter.php';

class Client
{
    private $mobile;
    private $mobileAdapter;
    private $desktop;

    public function __construct()
    {
        $this->mobile = new Mobile;
        $this->mobileAdapter = new MobileAdapter($this->mobile);
        echo $this->mobileAdapter->horizontalLayout();

        $this->desktop = new Desktop();
        echo "\n\n";
        echo $this->desktop->horizontalLayout();
    }
}

new Client();